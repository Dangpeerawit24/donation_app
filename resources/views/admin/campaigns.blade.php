@extends('layouts.main')
@php
    $manu = 'campaigns';
@endphp
@Section('content')
    <div class="flex flex-col md:flex-row gap-x-5">
        <h3 class="text-3xl m-0 md:mb-10">จัดการข้อมูลกองบุญ</h3>
        <div>
            <button id="openModal" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                เพิ่มกองบุญ
            </button>
        </div>
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
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">รูปกองบุญ</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ชื่อกองบุญ</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ราคา</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">จำนวนที่เปิดรับ</th>
                        <th class="px-6 py-3 text-center text-nowrap  w-10 text-md font-semibold text-white">การเปลื่ยนแปลง
                        </th>
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
                <h2 class="text-xl font-semibold">เพิ่มกองบุญใหม่</h2>
                <button id="closeModal" class="text-white hover:text-gray-300 text-2xl">
                    &times;
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 overflow-x-auto">
                <form action="{{ route('campaigns.store') }}" id="usersForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="grid grid-cols-1 overflow-x-auto  gap-4 ">
                        <div class="col-sm-10">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">สถานะกองบุญ</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="status" id="status" required>
                                <option value="เปิดกองบุญ">เปิดกองบุญ</option>
                                <option value="ปิดกองบุญแล้ว">ปิดกองบุญแล้ว</option>
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="categoriesID"
                                class="block text-sm font-medium text-gray-700 mb-1">สถานะกองบุญ</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="categoriesID" id="categoriesID" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="details"
                                class="block text-sm font-medium text-gray-700 mb-1">ต้องการให้กรอก</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="details" id="details" required>
                                <option value="ชื่อสกุล">ชื่อสกุล</option>
                                <option value="กล่องข้อความใหญ่">กล่องข้อความใหญ่</option>
                                <option value="ชื่อวันเดือนปีเกิด">ชื่อวันเดือนปีเกิด</option>
                                <option value="ตามศรัทธา">ตามศรัทธา</option>
                                <option value="กิจกรรม">กิจกรรม</option>
                            </select>
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อกองบุญ</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                placeholder="กรอก ชื่อกองบุญ" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด
                                (ต้องการขึ้นบรรทัดใหม่ให้พิมพ์ /n )</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" id="description2"
                                name="description" rows="4" maxlength="500" required></textarea>
                            <div id="charCount" class="form-text">เหลือ 500 ตัวอักษร</div>
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">ราคา</label>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center text-nowrap">
                                    <input type="checkbox" id="faithPrice" class="mr-2" onclick="toggleFaithPrice()">
                                    <label for="faithPrice" class="text-sm font-medium text-gray-700">ตามกำลังศรัทธา</label>
                                </div>
                                <input type="text" name="price" id="price" value="{{ old('price') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                    placeholder="กรอก ราคา" required>
                            </div>
                        </div>
                        <div>
                            <label for="stock"
                                class="block text-sm font-medium text-gray-700 mb-1">จำนวนที่เปิดรับ</label>
                            <input type="text" name="stock" id="stock" value="{{ old('stock') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                placeholder="จำนวนที่เปิดรับ" required>
                        </div>
                        <div class="">
                            <label for="campaign_img"
                                class="block text-sm font-medium text-gray-700 mb-1">รูปกองบุญ</label>
                            <input type="file" name="campaign_img" id="campaign_img"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        </div>
                        <input type="hidden" name="price" id="hiddenPrice">
                        <input type="hidden" name="stock" id="hiddenStock">
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

    <div id="editModal" class="fixed inset-0 p-2 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h2 class="text-xl font-semibold">แก้ไขข้อมูลสินค้า</h2>
                <button id="closeModal2" class="text-white hover:text-gray-300 text-2xl">
                    &times;
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
                <form action="" id="usersForm2" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 overflow-x-auto  gap-4 ">
                        <div class="col-sm-10">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">สถานะกองบุญ</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="status" id="status" required>
                                <option value="เปิดกองบุญ">เปิดกองบุญ</option>
                                <option value="ปิดกองบุญแล้ว">ปิดกองบุญแล้ว</option>
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="categoriesID"
                                class="block text-sm font-medium text-gray-700 mb-1">สถานะกองบุญ</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="categoriesID" id="categoriesID" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="details"
                                class="block text-sm font-medium text-gray-700 mb-1">ต้องการให้กรอก</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="details" id="details" required>
                                <option value="ชื่อสกุล">ชื่อสกุล</option>
                                <option value="กล่องข้อความใหญ่">กล่องข้อความใหญ่</option>
                                <option value="ชื่อวันเดือนปีเกิด">ชื่อวันเดือนปีเกิด</option>
                                <option value="ตามศรัทธา">ตามศรัทธา</option>
                                <option value="กิจกรรม">กิจกรรม</option>
                            </select>
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อกองบุญ</label>
                            <input type="text" name="name" id="name2" value="{{ old('name') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                placeholder="กรอก ชื่อกองบุญ" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด
                                (ต้องการขึ้นบรรทัดใหม่ให้พิมพ์ /n )</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" id="description3"
                                name="description" rows="4" maxlength="500" value="{{ old('description') }}" required></textarea>
                            <div id="charCount" class="form-text">เหลือ 500 ตัวอักษร</div>
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">ราคา</label>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center text-nowrap">
                                    <input type="checkbox" id="faithPrice2" class="mr-2"
                                        onclick="toggleFaithPrice2()">
                                    <label for="faithPrice"
                                        class="text-sm font-medium text-gray-700">ตามกำลังศรัทธา</label>
                                </div>
                                <input type="text" name="price" id="price2" value="{{ old('price') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                    placeholder="กรอก ราคา" required>
                            </div>
                        </div>
                        <div>
                            <label for="stock"
                                class="block text-sm font-medium text-gray-700 mb-1">จำนวนที่เปิดรับ</label>
                            <input type="text" name="stock" id="stock2" value="{{ old('stock') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                placeholder="จำนวนที่เปิดรับ" required>
                        </div>
                        <div class="">
                            <label for="campaign_img"
                                class="block text-sm font-medium text-gray-700 mb-1">รูปกองบุญ</label>
                            <input type="file" name="campaign_img" id="campaign_img"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        </div>
                        <input type="hidden" name="price" id="hiddenPrice2">
                        <input type="hidden" name="stock" id="hiddenStock2">
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-100 flex justify-end items-center space-x-3">
                <button id="closeModalFooter2" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Cancel
                </button>
                <button type="submit" form="usersForm2"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Save
                </button>
            </div>
        </div>
    </div>
    <script>
        function toggleFaithPrice() {
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock');
            const faithCheckbox = document.getElementById('faithPrice');
            const hiddenPrice = document.getElementById('hiddenPrice');
            const hiddenStock = document.getElementById('hiddenStock');

            if (faithCheckbox.checked) {
                // ตั้งค่า price เป็น 999999 และทำให้แก้ไขไม่ได้
                priceInput.value = "ตามศรัทธา";
                stockInput.value = "ตามศรัทธา";
                priceInput.disabled = true; // ใช้ disabled ได้
                stockInput.disabled = true; // ใช้ disabled ได้
                hiddenPrice.value = 1; // อัปเดตค่า hidden input
                hiddenStock.value = 999999; // อัปเดตค่า hidden input
            } else {
                // เปิดให้แก้ไขและเคลียร์ค่า
                priceInput.value = '';
                priceInput.disabled = false;
                stockInput.value = '';
                stockInput.disabled = false;
            }
        }

        function toggleFaithPrice2() {
            const priceInput2 = document.getElementById('price2');
            const stockInput2 = document.getElementById('stock2');
            const faithCheckbox2 = document.getElementById('faithPrice2');
            const hiddenPrice2 = document.getElementById('hiddenPrice2');
            const hiddenStock2 = document.getElementById('hiddenStock2');

            if (faithCheckbox.checked) {
                // ตั้งค่า price เป็น 999999 และทำให้แก้ไขไม่ได้
                priceInput2.value = "ตามศรัทธา";
                stockInput2.value = "ตามศรัทธา";
                priceInput2.disabled = true; // ใช้ disabled ได้
                stockInput2.disabled = true; // ใช้ disabled ได้
                hiddenPrice2.value = 1; // อัปเดตค่า hidden input
                hiddenStock2.value = 999999; // อัปเดตค่า hidden input
            } else {
                // เปิดให้แก้ไขและเคลียร์ค่า
                priceInput2.value = '';
                priceInput2.disabled = false;
                stockInput2.value = '';
                stockInput2.disabled = false;
            }
        }
    </script>
    <script>
        const textarea = document.getElementById('description2');
        const charCount = document.getElementById('charCount');
        const maxLength = textarea.getAttribute('maxlength');

        textarea.addEventListener('input', () => {
            const remaining = maxLength - textarea.value.length;
            charCount.textContent = `เหลือ ${remaining} ตัวอักษร`;
            if (remaining <= 20) {
                charCount.style.color = 'red';
            } else {
                charCount.style.color = '#6c757d';
            }
        });
    </script>

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
    <script>
        const csrfToken = "{{ csrf_token() }}";
    </script>
    <script>
        function openEditModal(id, name, description, price, stock) {
            // เปิด Modal
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            const decodedDescription = decodeURIComponent(description);


            // เติมข้อมูลในฟอร์ม
            const form = document.getElementById('usersForm2');
            form.action = `/admin/campaigns/update/${id}`; // เปลี่ยน action ของฟอร์ม

            document.getElementById('name2').value = name;
            document.getElementById('description3').value = decodedDescription;
            document.getElementById('price2').value = price;
            document.getElementById('stock2').value = stock;

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }

        // ฟังก์ชันสำหรับปิด Modal
        document.getElementById('closeModal2').addEventListener('click', () => {
            document.getElementById('editModal').classList.add('hidden');
        });

        document.getElementById('closeModalFooter2').addEventListener('click', () => {
            document.getElementById('editModal').classList.add('hidden');
        });
    </script>
    <script>
        function confirmCloseCampaign(campaignId) {
            Swal.fire({
                title: 'ยืนยันการปิดกองบุญ?',
                text: 'คุณต้องการปิดกองบุญนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ปิดเลย!',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/campaigns/close/${campaignId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                        })
                        .then((response) => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Failed to close campaign');
                            }
                        })
                        .then((data) => {
                            Swal.fire('สำเร็จ!', data.message, 'success').then(() => location.reload());
                        })
                        .catch((error) => {
                            Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถปิดกองบุญได้ กรุณาลองอีกครั้ง.', 'error');
                            console.error(error);
                        });
                }
            });
        }
    </script>
    <script>
        function confirmDelete(Resultsid) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'ข้อมูลนี้จะถูกลบและไม่สามารถกู้คืนได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งฟอร์มหลังจากได้รับการยืนยัน
                    document.getElementById(`deleteForm-${Resultsid}`).submit();
                }
            });
        }
    </script>
    <script>
        const Results = @json($Results); // ดึงข้อมูลจาก Controller
        const rowsPerPage = 10;
        let currentPage = 1;
        let filteredData = Results;

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
            const baseUrl = "{{ asset('img/evidence/') }}";

            tableBody.innerHTML = '';
            currentData.forEach((Results, index) => {
                const row = `
                   <tr>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${startIndex + index + 1}</td>
                       <td class="px-6 py-2 text-nowrap text-center text-md text-gray-700">
                            <img src="${baseUrl}/${Results.campaign_img}" alt="Campaign Image" width="100px" height="100px">
                        </td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${Results.name}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${Results.price <= 1 ? "ตามกำลังศรัทธา": Results.price.toLocaleString()}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${Results.stock >= 999999 ? "ตามกำลังศรัทธา": Results.stock}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">
                           <div class="flex justify-center gap-2">
                                <a href="/admin/campaigns_transaction?campaign_id=${Results.id}&name=${encodeURIComponent(Results.name)}" 
                                    class="px-4 py-2 bg-sky-300 text-black rounded hover:bg-sky-600">
                                        ดูรายการกองบุญ
                                </a>
                                            ${
                                    Results.status !== "ปิดกองบุญแล้ว"
                                        ? `<button 
                                            class="px-4 py-2 bg-yellow-300 text-black rounded hover:bg-yellow-700"
                                            onclick="confirmCloseCampaign(${Results.id})">
                                            ปิดกองบุญ
                                        </button>`
                                        : ''
                                }
                               
                           </div>
                       </td>
                   </tr>
               `;
                tableBody.insertAdjacentHTML('beforeend', row);
                // <form id="deleteForm-${Results.id}" action="/admin/campaigns/destroy/${Results.id}" method="POST">
                //                    <input type="hidden" name="_method" value="DELETE">
                //                    <input type="hidden" name="_token" value="${csrfToken}">
                //                    <button type="button" onclick="confirmDelete(${Results.id})" 
                //                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-900">
                //                        Delete
                //                    </button>
                //                </form>
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
            filteredData = Results.filter((Results) =>
                Results.name.toLowerCase().includes(query.toLowerCase())
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
