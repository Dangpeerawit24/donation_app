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
            if (link.id !== 'pos') {
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
