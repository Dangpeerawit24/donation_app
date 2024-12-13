@extends('layouts.main')
@php
    $manu = 'categories';
@endphp
@Section('content')
    <div class="flex flex-col md:flex-row gap-x-5">
        <h3 class="text-3xl m-0 md:mb-10">รายละเอียดหัวข้อกองบุญ</h3>
    </div>
    <div class=" mx-auto px-4">
        <!-- Search Box -->
        <div class="flex flex-col mt-2 md:mt-0 md:flex-row justify-between items-center mb-4">
            <div>
                <button id="copy-table" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Copy Table</button>
                <button id="export-excel" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Export to
                    Excel</button>
            </div>
            <input type="text" id="search" class="mt-5 md:mt-0 px-4 py-2 border rounded"
                placeholder="Search categories...">
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full border-collapse bg-white">
                <thead>
                    <tr class="bg-gradient-to-r h-12 from-sky-600 to-sky-500">
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">#</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ชื่อกองบุญ</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ยอดรวมกองบุญ</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ยอดรวมรายได้</th>
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
    <script>
        const categoriesdetails = @json($categoriesdetails); // ดึงข้อมูลจาก Controller
        const rowsPerPage = 12;
        let currentPage = 1;
        let filteredData = categoriesdetails;

        // อ้างอิง DOM
        const tableBody = document.getElementById('table-body');
        const pageInfo = document.getElementById('page-info');
        const prevButton = document.getElementById('prev');
        const nextButton = document.getElementById('next');
        const searchInput = document.getElementById('search');

        // ฟังก์ชันแสดงข้อมูลในตาราง
        function renderTable() {
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const currentData = filteredData.slice(startIndex, endIndex);

            tableBody.innerHTML = '';
            currentData.forEach((categoriesdetails, index) => {
                const row = `
                   <tr>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${startIndex + index + 1}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${categoriesdetails.name}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${categoriesdetails.total_value}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${categoriesdetails.total_value_price.toLocaleString()}</td>
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
            filteredData = categoriesdetails.filter((categoriesdetails) =>
                categoriesdetails.name.toLowerCase().includes(query.toLowerCase())
            );
            currentPage = 1;
            renderTable();
        }

        // Event Listeners
        prevButton.addEventListener('click', () => changePage(-1));
        nextButton.addEventListener('click', () => changePage(1));
        searchInput.addEventListener('input', (e) => searchTable(e.target.value));

        // เริ่มแสดงข้อมูล
        renderTable();

        document.getElementById('export-excel').addEventListener('click', () => {
            const table = document.querySelector('table'); // ดึงข้อมูลจากตาราง
            const workbook = XLSX.utils.table_to_book(table, {
                sheet: "Sheet 1"
            }); // แปลงตารางเป็น workbook
            XLSX.writeFile(workbook, "table_data.xlsx"); // ดาวน์โหลดไฟล์ Excel ชื่อ "table_data.xlsx"
        });

        document.getElementById('copy-table').addEventListener('click', () => {
            const table = document.querySelector('table');
            const rows = Array.from(table.rows);

            const columnsToCopy = [0, 1];

            const text = rows.map(row => {
                return Array.from(row.cells)
                    .filter((_, index) => columnsToCopy.includes(index)) // เลือกเฉพาะคอลัมน์ที่ต้องการ
                    .map(cell => cell.innerText)
                    .join('\t');
            }).join('\n');

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
@endSection
