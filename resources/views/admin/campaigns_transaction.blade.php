@extends('layouts.main')
@php
    $manu = 'campaigns';
@endphp
@section('content')
    <div class="flex flex-col mb-4">
        <div class="flex flex-col md:flex-row">
            <h3 class="text-xl md:text-3xl m-0 md:mb-4">รายการผู้ร่วมบุญกองบุญ</h3>
            <h3 class="text-xl md:text-3xl m-0 md:mb-4">#{{ $name }}</h3>
        </div>
        <div class="flex flex-row mb-0 md:mb-5 gap-2 ml-3">
            <button class="px-2 py-2 bg-blue-600 text-center text-white rounded hover:bg-blue-800"
                id="openModal">เพิ่มกองบุญจากดึงมือ</button>
            <a href="{{ url('admin/campaign_transaction_complete?campaign_id=' . $campaignId . '&name=' . $name) }}"
                class="px-2 py-2 bg-red-500 text-center text-white rounded hover:bg-red-800">
                รายการที่ดำเนินการแล้ว
            </a>
            <a href="#"
                onclick="confirmAction('{{ url('admin/campaigns_transaction_success?campaign_id=' . $campaignId) }}')"
                class="px-2 py-2 bg-green-600 text-center text-white rounded hover:bg-green-800">
                เคลียร์รายการที่เสร็จแล้ว
            </a>
            <a href="#"
                onclick="confirmActionnoti('{{ url('admin/campaigns_transaction_noti?campaign_id=' . $campaignId) }}')"
                class="px-2 py-2 bg-yellow-600 text-center text-white rounded hover:bg-yellow-800">
                ส่งแจ้งเตือนเข้ากลุ่ม
            </a>
        </div>
    </div>
    <div class=" mx-auto px-4">
        <!-- Search Box -->
        <div class="flex flex-col mt-2 md:mt-0 md:flex-row justify-between items-center mb-4">
            <div>
                <button id="copy-table" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Copy
                    Table</button>
                <button id="export-excel" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Export to
                    Excel</button>
            </div>
            <input type="text" id="search" class="mt-5 md:mt-0 px-4 py-2 border rounded" placeholder="Search...">
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg shadow-lg overflow-x-auto scrollbar-custom">
            <table class="table-fixed w-full border-collapse bg-white">
                <thead>
                    <tr class="bg-gradient-to-r h-12 from-sky-600 to-sky-500">
                        <th class="px-6 py-3  text-center text-nowrap text-md font-semibold text-white">#</th>
                        <th class="px-6 py-3  text-center text-nowrap text-md font-semibold text-white">สลิป</th>
                        <th class="w-[30%] px-6 py-3  text-center text-nowrap text-md font-semibold text-white">ข้อมูลผู้ร่วมบุญ</th>
                        <th class="w-[30%] px-6 py-3 text-center text-nowrap text-md font-semibold text-white">คำขอพร</th>
                        <th class="px-6 py-3  text-center text-nowrap text-md font-semibold text-white">จำนวน</th>
                        <th class="px-6 py-3  text-center text-nowrap text-md font-semibold text-white">ชื่อไลน์</th>
                        <th class="px-6 py-3  text-center text-nowrap text-md font-semibold text-white">QR Url</th>
                        <th class="px-6 py-3  text-center text-nowrap text-md font-semibold text-white">ที่มา</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="divide-y divide-gray-200">

                </tbody>
            </table>
        </div>
        <div class="flex justify-center gap-5 items-center my-4">
            <button id="prev" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300" disabled>Previous</button>
            <span id="page-info">Page 1 of 1</span>
            <button id="next" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Next</button>
        </div>
    </div>

    <div id="modal"
        class="fixed inset-0 p-2 overflow-x-auto bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h2 class="text-xl font-semibold">เพิ่มกองบุญจากดึงมือ</h2>
                <button id="closeModal" class="text-white hover:text-gray-300 text-2xl">
                    &times;
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 overflow-x-auto">
                <form action="{{ route('campaigns_transaction.store') }}" id="usersForm" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="grid grid-cols-1 overflow-x-auto  gap-4 ">
                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1">จำนวน</label>
                            <input type="number" min="1" name="value" id="value" value="1"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                placeholder="กรอก จำนวน" required>
                        </div>
                        <div>
                            <div>
                                <label for="details"
                                    class="block text-sm font-medium text-gray-700 mb-1">ข้อมูลผู้ร่วมบุญ</label>
                                <textarea name="details" id="details" rows="5"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                    placeholder="กรอกรายละเอียด..." required></textarea>
                            </div>
                            <button class="p-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                                onclick="addCommas()">เพิ่มเครื่องหมาย ','</button>
                        </div>
                        <div>
                            <div>
                                <label for="wish"
                                    class="block text-sm font-medium text-gray-700 mb-1">คำขอพร</label>
                                <textarea name="wish" id="wish" rows="5"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                    placeholder="กรอกรายละเอียด..."></textarea>
                            </div>
                            <button class="p-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                                onclick="addCommaswish()">เพิ่มเครื่องหมาย ','</button>
                        </div>
                        <div>
                            <label for="lineName" class="block text-sm font-medium text-gray-700 mb-1">ชื่อที่แสดง</label>
                            <input type="text" name="lineName" id="lineName"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                placeholder="ระบุชื่อที่แสดง" required>
                        </div>
                        <div class="col-sm-10">
                            <label for="form" class="block text-sm font-medium text-gray-700 mb-1">ที่มา:</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="form" id="form" required>
                                <option value="L">L</option>
                                <option value="IB">IB</option>
                                <option value="P">P</option>
                            </select>
                        </div>
                        <!-- Hidden Fields -->
                        <input type="hidden" name="transactionID" value="TX-{{ now()->timestamp }}-{{ rand(1000, 9999) }}">
                        <input type="hidden" class="form-control" id="status" name="status" value="รอดำเนินการ"
                            required>
                        <input type="hidden" class="form-control" id="campaignsid" name="campaignsid"
                            value="{{ $campaignId }}" required>
                        <input type="hidden" class="form-control" id="campaignsname" name="campaignsname"
                            value="{{ $name }}" required>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-100 flex justify-end items-center space-x-3">
                <button id="closeModalFooter" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Cancel
                </button>
                <button type="submit" form="usersForm"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Save
                </button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        const closeModalFooter = document.getElementById('closeModalFooter');

        // เปิด Modal
        openModal.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // ปิด Modal
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        closeModalFooter.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // ปิด Modal เมื่อคลิกนอก Modal
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>

    {{-- Modal รายชื่อ --}}
    <div id="detailsModal"
        class="hidden px-2 fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-semibold mb-4">รายละเอียดข้อมูลผู้ร่วมบุญ</h2>
            <div id="modalContent" class="mb-4">
                <!-- แสดงข้อมูลจากแถว -->
            </div>
            <div class="flex justify-end gap-3">
                <a id="transactionLink" href="#" target="_blank"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    ส่งภาพกองบุญ
                </a>
                <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModaldetails()">
                    ปิด
                </button>
            </div>
        </div>
    </div>
    <script>
        function openDetailsModal(transaction) {
            // ดึง Modal และ Content
            const modal = document.getElementById('detailsModal');
            const modalContent = document.getElementById('modalContent');
            const transactionLink = document.getElementById('transactionLink');

            // แสดงข้อมูลใน modal
            modalContent.innerHTML = `
        <ul class="list-decimal text-left ml-4 text-xl">
            ${transaction.details ? transaction.details.split(',').map(detail => `<li class="whitespace-pre-wrap">${detail}</li>`).join('') : ''}
            ${transaction.details2 ? transaction.details2.split(',').map(detail => `<li class="whitespace-pre-wrap">${detail}</li>`).join('') : ''}
            ${transaction.detailsbirthday ? transaction.detailsbirthday.split(',').map(detail => `<li class="whitespace-pre-wrap">${detail}</li>`).join('') : ''}
            ${transaction.detailstext ? transaction.detailstext.split(',').map(detail => `<li class="whitespace-pre-wrap">${detail}</li>`).join('') : ''}
        </ul>
    `;

            // ตั้งค่า HREF สำหรับปุ่ม
            transactionLink.href =
                `https://donation.kuanimtungpichai.com/pushevidence2?transactionID=${transaction.transactionID}`;

            // แสดง Modal
            modal.classList.remove('hidden');
            document.getElementById('loader').classList.add('hidden');

        }

        function closeModaldetails() {
            const modal = document.getElementById('detailsModal');
            modal.classList.add('hidden');
            document.getElementById('loader').classList.add('hidden');

        }
    </script>


    <div id="imageModal"
        class="fixed inset-0 text-center bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white text-center rounded-xl shadow-lg max-w-4xl w-auto">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h5 class="text-lg font-semibold">หลักฐานการโอน</h5>
                <button id="closeImageModal" class="text-white hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="px-6 py-4 text-center">
                <img id="modalImage" src="" class=" max-w-80 md:max-w-md h-auto rounded-lg" alt="หลักฐานการโอน">
            </div>
        </div>
    </div>
    <script>
        // เปิด Modal
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    document.getElementById('loader').classList.add('hidden');
                }
            });
        }

        // ปิด Modal
        document.getElementById('closeImageModal').addEventListener('click', () => {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.getElementById('loader').classList.add('hidden');
        });
    </script>

    <script>
        function addCommas() {
            const textarea = document.getElementById('details');
            // แยกข้อความแต่ละบรรทัดออกมาเป็น array
            const lines = textarea.value.split('\n');

            // แปลงแต่ละบรรทัดให้มีเครื่องหมาย ',' ต่อท้าย (ยกเว้นบรรทัดสุดท้าย หรือบรรทัดว่าง)
            const updatedLines = lines.map((line, index) => {
                // trim() เอา white space ออกก่อนเช็คว่าบรรทัดว่างหรือไม่
                return (line.trim() !== '' && index < lines.length - 1) ?
                    `${line},` :
                    line;
            });

            // รวมข้อความกลับเป็น string ด้วย \n
            textarea.value = updatedLines.join('\n');

            // นับจำนวนบรรทัด (รวมบรรทัดว่างด้วย ถ้าไม่ต้องการนับบรรทัดว่างต้องกรองก่อน)
            const lineCount = lines.length;

            // เปลี่ยนค่า value ของ input id="value" เป็นจำนวนบรรทัด
            document.getElementById('value').value = lineCount;
        }
    </script>
    <script>
        function addCommaswish() {
            const textarea = document.getElementById('wish');
            // แยกข้อความแต่ละบรรทัดออกมาเป็น array
            const lines = textarea.value.split('\n');

            // แปลงแต่ละบรรทัดให้มีเครื่องหมาย ',' ต่อท้าย (ยกเว้นบรรทัดสุดท้าย หรือบรรทัดว่าง)
            const updatedLines = lines.map((line, index) => {
                // trim() เอา white space ออกก่อนเช็คว่าบรรทัดว่างหรือไม่
                return (line.trim() !== '' && index < lines.length - 1) ?
                    `${line},` :
                    line;
            });

            // รวมข้อความกลับเป็น string ด้วย \n
            textarea.value = updatedLines.join('\n');

            // นับจำนวนบรรทัด (รวมบรรทัดว่างด้วย ถ้าไม่ต้องการนับบรรทัดว่างต้องกรองก่อน)
            const lineCount = lines.length;

            // เปลี่ยนค่า value ของ input id="value" เป็นจำนวนบรรทัด
            document.getElementById('value').value = lineCount;
        }
    </script>

    <script>
        const transactions = @json($transactions); // ดึงข้อมูลจาก Controller
        const rowsPerPage = 300;
        let currentPage = 1;
        // let filteredData = transactions;

        // อ้างอิง DOM
        const tableBody = document.getElementById('table-body');
        const pageInfo = document.getElementById('page-info');
        const prevButton = document.getElementById('prev');
        const nextButton = document.getElementById('next');
        const searchInput = document.getElementById('search');
        const urlParams = new URLSearchParams(window.location.search);
        const campaign_id = urlParams.get('campaign_id');

        async function fetchData() {
            try {
                const response = await fetch(`/api/transactions?campaign_id=${campaign_id}`);
                const data = await response.json();
                filteredData = data;
                currentPage = 1;
                renderTable();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // ฟังก์ชันแสดงข้อมูลในตาราง
        function renderTable() {
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const currentData = filteredData.slice(startIndex, endIndex);
            const baseUrl = "{{ asset('img/evidence/') }}";

            tableBody.innerHTML = '';
            currentData.forEach((transactions, index) => {
                const row = `
                <tr>
                    <td class="px-6 py-2 text-center text-md text-gray-700">${startIndex + index + 1}</td>
                    <td class="px-6 py-2 text-center text-md text-gray-700">
                        <a href="#" data-toggle="modal" data-target="#imageModal"
                         onclick="openImageModal('${baseUrl}/${transactions.evidence}')">
                            <img src="${baseUrl}/${transactions.evidence}" alt="หลักฐานการโอน" width="100px" class="inline-block">
                        </a>
                    </td>
                   <td class="px-6 py-2  text-center text-md text-gray-700">
                        <a href="#" onclick="openDetailsModal(${JSON.stringify(transactions).replace(/"/g, '&quot;')})">
                            ${transactions.details || transactions.details2 || transactions.detailsbirthday || transactions.detailstext || 'ไม่มีข้อมูล'}
                        </a>
                    </td>
                   <td class="px-6 py-2  text-center text-md text-gray-700">${transactions.wish}</td>
                   <td class="px-6 py-2  text-center text-md text-gray-700">${transactions.value}</td>
                   <td class="px-6 py-2  text-center text-md text-gray-700">${transactions.lineName}</td>
                   <td class="px-6 py-2  text-center text-md text-gray-700">${transactions.qr_url}</td>
                   <td class="px-6 py-2  text-center text-md text-gray-700">${transactions.form}</td>
               </tr>
           `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            // อัปเดต Pagination Info
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);
            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
        }

        // ฟังก์ชันเปลี่ยนหน้า
        function changePage(increment) {
            currentPage += increment;
            renderTable();
        }

        // ฟังก์ชันการค้นหา
        function searchTable(query) {
            filteredData = transactions.filter((transactions) =>
                (transactions.lineName.toLowerCase().includes(query.toLowerCase()) || false) ||
                (transactions.details?.toLowerCase().includes(query.toLowerCase()) || false) ||
                (transactions.details2?.toLowerCase().includes(query.toLowerCase()) || false) ||
                (transactions.detailsbirthday?.toLowerCase().includes(query.toLowerCase()) || false) ||
                (transactions.detailstext?.toLowerCase().includes(query.toLowerCase()) || false)
            );
            currentPage = 1;
            renderTable();
        }



        // Event Listeners
        prevButton.addEventListener('click', () => changePage(-1));
        nextButton.addEventListener('click', () => changePage(1));
        searchInput.addEventListener('input', (e) => searchTable(e.target.value));

        // เริ่มแสดงข้อมูล
        fetchData(); // เรียกครั้งแรกเพื่อแสดงข้อมูลเดือนนี้

        setInterval(() => {
            fetchData();
        }, 10000);

        document.getElementById('export-excel').addEventListener('click', () => {
            const table = document.querySelector('table');
            const rows = Array.from(table.rows);

            const columnsToCopy = [0, 1, 2, 3, 4, 5, 6, 7]; // ระบุคอลัมน์ที่ต้องการ
            const mergeColumnIndex = 2; // คอลัมน์ "ข้อมูลผู้ร่วมบุญ" ที่ต้องรวมข้อความ

            // สร้างข้อมูลใหม่จากตาราง
            const data = rows.map((row, rowIndex) => {
                return Array.from(row.cells)
                    .filter((_, index) => columnsToCopy.includes(index))
                    .map((cell, index) => {
                        if (index === mergeColumnIndex) {
                            // แปลงข้อมูลในคอลัมน์ "ข้อมูลผู้ร่วมบุญ"
                            return cell.innerText
                                .split('\n') // แยกข้อความเป็นบรรทัด
                                .map(line => line.replace(/^\d+\.\s*/, '').trim()) // ลบลำดับ เช่น "1. "
                                .join(', '); // รวมข้อความคั่นด้วย ,
                        } else {
                            return cell.innerText.trim(); // คัดลอกคอลัมน์อื่นตามปกติ
                        }
                    });
            });

            // สร้างเวิร์กบุ๊ก Excel
            const worksheet = XLSX.utils.aoa_to_sheet(data); // แปลงข้อมูลเป็น worksheet
            const workbook = XLSX.utils.book_new(); // สร้าง workbook ใหม่
            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet 1"); // เพิ่ม worksheet เข้า workbook

            // ดาวน์โหลดไฟล์ Excel
            XLSX.writeFile(workbook, "table_data.xlsx");
        });


        document.getElementById('copy-table').addEventListener('click', () => {
            const table = document.querySelector('table');
            const rows = Array.from(table.rows);

            // ระบุคอลัมน์ที่ต้องการ
            const columnsToCopy = [0, 1, 2, 3, 4, 5, 6, 7]; // เปลี่ยนตามคอลัมน์ใน HTML
            const mergeColumnIndex = 2; // คอลัมน์ "ข้อมูลผู้ร่วมบุญ" ที่ต้องรวมข้อความ

            // สร้างข้อมูล
            const text = rows.map((row, rowIndex) => {
                return Array.from(row.cells)
                    .filter((_, index) => columnsToCopy.includes(index))
                    .map((cell, index) => {
                        if (index === mergeColumnIndex) {
                            // ลบลำดับ (ตัวเลขนำหน้า) และรวมข้อความในคอลัมน์ "ข้อมูลผู้ร่วมบุญ"
                            return cell.innerText
                                .split('\n') // แยกข้อความเป็นบรรทัด
                                .map(line => line.replace(/^\d+\.\s*/, '').trim()) // ลบลำดับ เช่น "1. "
                                .join(', '); // รวมข้อความคั่นด้วย ,
                        } else {
                            // คัดลอกคอลัมน์อื่นตามปกติ
                            return cell.innerText.trim();
                        }
                    })
                    .join('\t'); // ใช้ Tab คั่นระหว่างคอลัมน์
            }).join('\n'); // ใช้ Newline คั่นระหว่างแถว

            // คัดลอกข้อมูลไปยังคลิปบอร์ด
            navigator.clipboard.writeText(text).then(() => {

                Swal.fire({
                    title: 'สำเร็จ!',
                    text: 'ข้อมูลในตารางถูกคัดลอกไปยังคลิปบอร์ดแล้ว!',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    timer: 3000,
                    timerProgressBar: true
                });
            }).catch(err => {

                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถคัดลอกข้อมูลในตารางได้!',
                    icon: 'error',
                    confirmButtonText: 'ลองใหม่'
                });
                console.error('ไม่สามารถคัดลอกข้อมูลในตาราง:', err);
            });
        });
    </script>
    <script>
        function confirmAction(url) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณกำลังจะดำเนินการรายการนี้",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ดำเนินการ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // เมื่อผู้ใช้กดยืนยัน ให้เปลี่ยนเส้นทางไปยัง URL
                    window.location.href = url;
                } else {
                    document.getElementById('loader').classList.add('hidden');
                }
            });
        }
    </script>
    <script>
        function confirmActionnoti(url) {
            Swal.fire({
                title: 'ส่งแจ้งเตือนหรือไม่?',
                text: "คุณกำลังจะดำเนินการรายการนี้",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ดำเนินการ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // เมื่อผู้ใช้กดยืนยัน ให้เปลี่ยนเส้นทางไปยัง URL
                    window.location.href = url;
                } else {
                    document.getElementById('loader').classList.add('hidden');
                }
            });
        }
    </script>
@endsection
