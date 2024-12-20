@extends('layouts.main')
@php
    $manu = 'รายการลูกบุญย้อนหลัง';
@endphp
@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">รายการลูกบุญย้อนหลัง</h1>
        <form method="GET" class="mb-2">
            <select id="filterSelect" name="filter" onchange="handleFilterChange(this.value)"
                class="block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
                <option value="month" selected>เดือนนี้</option>
                <option value="3months">ย้อนหลัง 3 เดือน</option>
                <option value="year">ปีนี้</option>
                <option value="all">ทั้งหมด</option>
            </select>
        </form>

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
            <input type="text" id="search" class="mt-5 md:mt-0 px-4 py-2 border rounded"
                placeholder="Search categories...">
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full border-collapse bg-white">
                <thead>
                    <tr class="bg-gradient-to-r h-12 from-sky-600 to-sky-500">
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">#</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">UID</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">display_name</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">picture</th>
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
        // ประกาศฟังก์ชัน fetchData ใน global scope
        async function fetchData(filter = 'month') {
            try {
                const response = await fetch(`/api/lineusers?filter=${filter}`);
                const data = await response.json();
                originalData = data; // เก็บข้อมูลต้นฉบับ
                filteredData = data; // ใช้ข้อมูลต้นฉบับเริ่มต้น
                currentPage = 1;
                renderTable();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // ประกาศฟังก์ชัน handleFilterChange ใน global scope
        function handleFilterChange(filter) {
            fetchData(filter);
        }

        // รัน DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            const rowsPerPage = 300;
            let currentPage = 1;
            let originalData = [];
            let filteredData = [];
            const tableBody = document.getElementById('table-body');
            const pageInfo = document.getElementById('page-info');
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');
            const searchInput = document.getElementById('search');

            function renderTable() {
                const startIndex = (currentPage - 1) * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;
                const currentData = filteredData.slice(startIndex, endIndex);

                tableBody.innerHTML = '';
                currentData.forEach((user, index) => {
                    const row = `
            <tr>
                <td class="px-6 py-2 text-center text-nowrap text-md text-gray-700">${startIndex + index + 1}</td>
                <td class="px-6 py-2 text-center text-nowrap text-md text-gray-700">${user.user_id}</td>
                <td class="px-6 py-2 text-center text-nowrap text-md text-gray-700">${user.display_name}</td>
                <td class="px-6 py-2 text-center text-nowrap text-md text-gray-700">
                    ${user.picture_url ? `<img src="${user.picture_url}" alt="User Picture" class="w-10 h-10 rounded-full mx-auto">` : 'N/A'}
                </td>
            </tr>
        `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });

                const totalPages = Math.ceil(filteredData.length / rowsPerPage);
                pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === totalPages;
            }

            function changePage(increment) {
                currentPage += increment;
                renderTable();
            }

            function searchTable(query) {
                if (!query) {
                    filteredData = originalData;
                } else {
                    const lowerCaseQuery = query.toLowerCase();
                    filteredData = originalData.filter(user =>
                        user.display_name.toLowerCase().includes(lowerCaseQuery)
                    );
                }
                currentPage = 1;
                renderTable();
            }

            prevButton.addEventListener('click', () => changePage(-1));
            nextButton.addEventListener('click', () => changePage(1));
            searchInput.addEventListener('input', (e) => searchTable(e.target.value));

            // ดึงข้อมูลเริ่มต้น
            fetchData();
        });



        document.getElementById('export-excel').addEventListener('click', () => {
            const table = document.querySelector('table'); // ดึงข้อมูลจากตาราง
            const workbook = XLSX.utils.table_to_book(table, {
                sheet: "Sheet 1"
            }); // แปลงตารางเป็น workbook
            XLSX.writeFile(workbook, "table_lineusers.xlsx");
        });

        document.getElementById('copy-table').addEventListener('click', () => {
            const table = document.querySelector('table');
            const rows = Array.from(table.rows);

            const columnsToCopy = [0, 1, 2];

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
@endsection
