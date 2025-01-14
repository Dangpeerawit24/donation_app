<script>
    const modalBody = document.getElementById('modal-body');
    const notificationCountSpan = document.querySelector('#open-modal-button span');
    const notificationCountSpan2 = document.querySelector('#open-modal-button2 span');

    function fetchPendingTransactions() {
        fetch('/pending-transactions')
            .then(response => response.json())
            .then(data => {
                // อัปเดตตัวเลขแจ้งเตือน (Badge) ที่ไอคอน
                // สมมติว่าเราต้องการได้ยอดรวม total_transactions ใน data ทั้งหมด
                const totalPending = data.reduce((sum, item) => sum + item.total_transactions, 0);
                notificationCountSpan.innerText = totalPending;
                notificationCountSpan2.innerText = totalPending;

                // ล้างเนื้อหาเก่าใน modalBody
                modalBody.innerHTML = '';

                if (data.length === 0) {
                    modalBody.innerHTML = '<p class="text-gray-500">ไม่มีรายการค้าง</p>';
                } else {
                    // สร้าง list หรือ table
                    const ul = document.createElement('ul');

                    data.forEach(item => {
                        // สร้าง <li>
                        const li = document.createElement('li');
                        li.classList.add(
                            'px-4',
                            'py-3',
                            'flex',
                            'items-center',
                            'hover:bg-gray-50',
                            'border-b'
                        );

                        // สร้าง <a> สำหรับลิงก์ไปยังหน้า
                        const a = document.createElement('a');
                        a.href = `/admin/campaigns_transaction?campaign_id=${item.campaign_id}&name=${item.campaign_name}`;
                        a.classList.add('flex', 'items-start', 'w-full');

                        // สร้าง div สำหรับเก็บข้อความ
                        const textContainer = document.createElement('div');
                        textContainer.innerHTML = `
                            <p class="text-sm font-semibold text-gray-900">
                                กองบุญ: ${item.campaign_name}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                จำนวน: ${item.total_transactions}
                            </p>
                        `;

                        a.appendChild(textContainer);
                        li.appendChild(a);
                        ul.appendChild(li);
                    });

                    modalBody.appendChild(ul);
                }
            })
            .catch(err => {
                console.error('Error fetching pending transactions:', err);
            });
    }

    // เรียกครั้งแรกเมื่อหน้าโหลด
    fetchPendingTransactions();

    // ตั้ง interval เรียกทุก 5 วินาที
    setInterval(fetchPendingTransactions, 5000);
</script>

<script>
    const toggle = document.querySelector('#menu-toggle');
    const menu = document.querySelector('#menu');
    const closeMenu = document.querySelector('#close-menu');

    // Open menu
    toggle.addEventListener('change', () => {
        if (toggle.checked) {
            menu.classList.remove('-translate-x-full');
        } else {
            menu.classList.add('-translate-x-full');
        }
    });

    // Close menu
    closeMenu.addEventListener('click', () => {
        menu.classList.add('-translate-x-full');
        toggle.checked = false;
    });
</script>
<script>
    document.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            if (link.id !== 'pos' && link.id !== 'open-modal-button' && link.id !==
                'open-modal-button2') {
                document.getElementById('loader').classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            document.getElementById('loader').classList.remove('hidden');
        });
    });

    window.addEventListener('pageshow', function(event) {

        document.getElementById('loader').classList.add('hidden');
    });

    window.addEventListener('load', function() {
        document.getElementById('loader').classList.add('hidden');
    });
</script>
<script>
    document.querySelector('#logout-btn').addEventListener('click', function(e) {
        e.preventDefault(); // ป้องกันการส่งฟอร์มทันที

        Swal.fire({
            text: "ต้องการออกจากระบบหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ออกจากระบบ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#logout-form').submit(); // ส่งฟอร์มเมื่อกด Confirm
            } else {
                document.getElementById('loader').classList.add('hidden');
            }
        });
    });
    document.querySelector('#logout-btn2').addEventListener('click', function(e) {
        e.preventDefault(); // ป้องกันการส่งฟอร์มทันที

        Swal.fire({
            text: "ต้องการออกจากระบบหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ออกจากระบบ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#logout-form').submit(); // ส่งฟอร์มเมื่อกด Confirm
            } else {
                document.getElementById('loader').classList.add('hidden');
            }
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
{{-- <script>
    let logoutTimer;

    // ฟังก์ชันรีเซ็ต Timer
    function resetLogoutTimer() {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(() => {
            document.getElementById('logout-form').submit();
        }, 600000); // 600000 ms = 10 นาที
    }

    // เหตุการณ์ที่รีเซ็ต Timer
    window.onload = resetLogoutTimer;
    document.onmousemove = resetLogoutTimer;
    document.onkeypress = resetLogoutTimer;
    document.ontouchstart = resetLogoutTimer;
    document.onscroll = resetLogoutTimer;

    // ตรวจสอบก่อนออกจากระบบ
    document.getElementById('logout-btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('logout-form').submit();
    });
</script> --}}
