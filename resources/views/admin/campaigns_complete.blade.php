@extends('layouts.main')
@php
    $manu = 'campaigns';
@endphp
@Section('content')
<style>
    /* ควบคุมความสูงของ Modal และทำให้เลื่อนได้ */
.modal-content {
    max-height: 100vh; /* ความสูงไม่เกิน 90% ของ viewport */
    overflow-y: auto; /* เพิ่ม Scroll bar เมื่อเนื้อหาเกิน */
}

.modal-body {
    max-height: 80vh; /* ความสูงสูงสุดของ body */
    overflow-y: auto; /* เพิ่ม Scroll bar เฉพาะ body */
}

</style>
    <div class="flex flex-col md:flex-row gap-x-5">
        <h3 class="text-3xl m-0 md:mb-10">จัดการข้อมูลกองบุญที่ปิดแล้ว</h3>
        <div>
            <button id="openModal" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                เพิ่มกองบุญ
            </button>
            <a href="{{ url('admin/campaigns') }}"
                class="px-2 py-2 mr-2 bg-green-500 text-center text-white rounded hover:bg-green-800">
                กองบุญที่เปิดอยู่
            </a>
            <a href="{{ url('admin/campaignswaitingopen') }}"
                class="px-2 py-2 bg-yellow-500 text-center text-white rounded hover:bg-yellow-800">
                กองบุญที่รอเปิด
            </a>
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
                placeholder="Search...">
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full border-collapse bg-white">
                <thead>
                    <tr class="bg-gradient-to-r h-12 from-sky-600 to-sky-500">
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">#</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">รูปกองบุญ</th>
                        <th class="px-6 py-3 text-center text-nowrap md:text-wrap text-md font-semibold text-white">ชื่อกองบุญ</th>
                        <th class="px-6 py-3 text-center text-wrap md:text-wrap text-md font-semibold text-white">รายละเอียด</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ราคา</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">จำนวนที่เปิดรับ</th>
                        <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ยอดร่วมบุญ</th>
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

    <div id="modal" class="fixed px-2 inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="modal-content bg-white rounded-xl shadow-xl w-full max-w-2xl">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h2 class="text-xl font-semibold">เพิ่มกองบุญใหม่</h2>
                <button id="closeModal" class="text-white hover:text-gray-300 text-2xl">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-6 py-4">
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
                                <option value="รอเปิด">รอเปิด</option>
                                <option value="ปิดกองบุญแล้ว">ปิดกองบุญแล้ว</option>
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="broadcastOption" class="block text-sm font-medium text-gray-700 mb-1">ส่งให้</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="broadcastOption" id="broadcastOption" required>
                                <option value="Broadcast">Broadcast ทั้งหมด</option>
                                <option value="3months">ลูกบุญย้อนหลัง 3 เดือน</option>
                                <option value="year">ลูกบุญย้อนหลัง 1 ปี</option>
                                <option value="NOBroadcast">ไม่ส่งข้อความ</option>
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="categoriesID"
                                class="block text-sm font-medium text-gray-700 mb-1">เลือกงาน</label>
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
                                <option value="คำขอพร">คำขอพร</option>
                                <option value="คำขอพรตามศรัทธา">คำขอพรตามศรัทธา</option>
                                <option value="กิจกรรม">กิจกรรม</option>
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <label for="respond"
                                class="block text-sm font-medium text-gray-700 mb-1">ข้อความตอบกลับ</label>
                            <select
                                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                name="respond" id="respond" required>
                                <option value="แอดมินจะส่งภาพกองบุญให้ท่านได้อนุโมทนาอีกครั้ง">
                                    แอดมินจะส่งภาพกองบุญให้ท่านได้อนุโมทนาอีกครั้ง</option>
                                <option value="ข้อมูลของท่านเข้าระบบเรียบร้อยแล้ว">ข้อมูลของท่านเข้าระบบเรียบร้อยแล้ว
                                </option>
                                <option value="ไม่ส่งข้อความ">ไม่ส่งข้อความ</option>
                            </select>
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อกองบุญ</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" id="name"
                                name="name" rows="2" maxlength="255" required></textarea>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด
                            </label>
                            {{-- (ต้องการขึ้นบรรทัดใหม่ให้พิมพ์ /n ) --}}
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" id="description2"
                                name="description" rows="4" maxlength="500" required></textarea>
                            <div id="charCount" class="form-text">เหลือ 500 ตัวอักษร</div>
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">ราคา</label>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center text-nowrap">
                                    <input type="checkbox" id="faithPrice" class="mr-2" onclick="toggleFaithPrice()">
                                    <label for="faithPrice"
                                        class="text-sm font-medium text-gray-700">ตามกำลังศรัทธา</label>
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
            <div class="px-6 py-4 bg-gray-100 flex justify-end space-x-3">
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

    <!-- Modal -->
    <div id="pushmessage" class="fixed inset-0 bg-gray-900 bg-opacity-50 px-2 flex items-center justify-center hidden z-50">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md overflow-hidden">
            <!-- Header -->
            <div class="px-4 py-2 bg-blue-500 text-white flex justify-between items-center">
                <h2 class="text-lg font-semibold">Push Message</h2>
                <button id="closeModalpushmessage" class="text-white text-2xl hover:text-gray-300">&times;</button>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-4">
                <form action="" id="pushmessageform" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-sm-10">
                        <label for="broadcastOption" class="block text-sm font-medium text-gray-700 mb-1">ส่งให้</label>
                        <select
                            class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            name="broadcastOption" id="" required>
                            <option value="Broadcast">Broadcast ทั้งหมด</option>
                            <option value="3months">ลูกบุญย้อนหลัง 3 เดือน</option>
                            <option value="year">ลูกบุญย้อนหลัง 1 ปี</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="campaign_imgpush" class="block text-sm font-medium text-gray-700">อัปโหลดไฟล์</label>
                        <input type="file" id="campaign_imgpush" name="campaign_imgpush"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Textarea -->
                    <div class="mt-3">
                        <label for="textareaInput" class="block text-sm font-medium text-gray-700">ข้อความ</label>
                        <textarea id="textareaInput" name="textareaInput" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                            placeholder="พิมพ์ข้อความของคุณที่นี่"></textarea>
                    </div>
                    <input type="hidden" name="pushmessage" value="pushmessage">
                </form>
            </div>

            <!-- Footer -->
            <div class="px-4 py-2 bg-gray-100 flex justify-end space-x-2">
                <button id="closeModalFooterpushmessage"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    ปิด
                </button>
                <button type="submit" form="pushmessageform"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    ยืนยัน
                </button>
            </div>
        </div>
    </div>
    <script>
        // เปิด Modal
        function openModalpushmessage(url) {
            const modal = document.getElementById('pushmessage');
            const form = document.getElementById('pushmessageform');

            // ตั้งค่า URL action ใน form
            form.action = url;

            // เปิด Modal
            modal.classList.remove('hidden');
        }

        // ปิด Modal
        function closeModalpushmessage() {
            const modal = document.getElementById('pushmessage');
            modal.classList.add('hidden');
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            const closeModalButton = document.getElementById('closeModalpushmessage');
            const closeModalFooterButton = document.getElementById('closeModalFooterpushmessage');
            const modal = document.getElementById('pushmessage');

            // ปิด Modal (จาก Header ปุ่ม X)
            closeModalButton.addEventListener('click', closeModalpushmessage);

            // ปิด Modal (จาก Footer ปุ่มปิด)
            closeModalFooterButton.addEventListener('click', closeModalpushmessage);

            // ปิด Modal เมื่อคลิกพื้นที่นอก Modal
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModalpushmessage();
                }
            });
        });
    </script>


    <script>
        function toggleFaithPrice() {
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock');
            const faithCheckbox = document.getElementById('faithPrice');
            const hiddenPrice = document.getElementById('hiddenPrice');
            const hiddenStock = document.getElementById('hiddenStock');

            if (faithCheckbox.checked) {
                // ตั้งค่า price และ stock เป็น "ตามศรัทธา" และทำให้แก้ไขไม่ได้
                priceInput.value = "ตามศรัทธา";
                stockInput.value = "ตามศรัทธา";
                priceInput.disabled = true;
                stockInput.disabled = true;
                hiddenPrice.value = 1; // อัปเดตค่า hidden input
                hiddenStock.value = 999999; // อัปเดตค่า hidden input
            } else {
                // เปิดให้แก้ไขได้
                priceInput.disabled = false;
                stockInput.disabled = false;

                // ตั้งค่าของ hiddenPrice และ hiddenStock ให้เท่ากับค่าที่กรอกลงไป
                hiddenPrice.value = priceInput.value || '';
                hiddenStock.value = stockInput.value || '';
            }
        }

        // อัปเดต hidden fields เมื่อเปลี่ยนค่าใน priceInput และ stockInput
        document.getElementById('price').addEventListener('input', (e) => {
            const hiddenPrice = document.getElementById('hiddenPrice');
            hiddenPrice.value = e.target.value;
        });

        document.getElementById('stock').addEventListener('input', (e) => {
            const hiddenStock = document.getElementById('hiddenStock');
            hiddenStock.value = e.target.value;
        });
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
        const csrfToken = "{{ csrf_token() }}";
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
            const baseUrl = "{{ asset('img/campaign/') }}";

            tableBody.innerHTML = '';
            currentData.forEach((Results, index) => {
                const row = `
                   <tr>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${startIndex + index + 1}</td>
                       <td class="px-6 py-2 text-nowrap text-center text-md text-gray-700">
                            <img src="${baseUrl}/${Results.campaign_img}" alt="Campaign Image" width="100px" height="100px">
                        </td>
                       <td class="px-6 py-2 text-nowrap md:text-wrap  text-center text-md text-gray-700">${Results.name}</td>
                       <td class="px-6 py-2 text-nowrap md:text-wrap  text-center text-md text-gray-700">${Results.description}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${Results.price <= 1 ? "ตามกำลังศรัทธา": Results.price.toLocaleString()}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${Results.stock >= 999999 ? "ตามกำลังศรัทธา": Results.stock}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${Results.total_value}</td>
                       <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">
                           <div class="flex flex-row justify-center gap-2">
                                <a href="/admin/campaigns_transaction?campaign_id=${Results.id}&name=${encodeURIComponent(Results.name)}" 
                                    class="px-4 py-2 bg-sky-300 text-black rounded hover:bg-sky-600">
                                        ดูรายการกองบุญ
                                </a>
                                        ${
                                Results.status !== "ปิดกองบุญแล้ว"
                                    ? `<button
                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                                    onclick="openModalpushmessage('/admin/pushmessage?campaign_id=${Results.id}')">
                                    pushmessage
                                </button>
                                    <button 
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
