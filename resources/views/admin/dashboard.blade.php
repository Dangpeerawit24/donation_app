@extends('layouts.main')
@php
    $manu = 'แดชบอร์ด';
@endphp
@section('content')
    <div class="flex flex-col">
        <div class="flex flex-col md:flex-row gap-x-5 mb-1">
            <h3 class="text-3xl m-0 md:mb-2">Dashboard</h3>
        </div>
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Value (This Month) -->
                <div class="bg-blue-500 text-white rounded-lg shadow p-4">
                    <div>
                        <div class="flex justify-between px-4">
                            <div>
                                <h2 id="total-value-month" class="text-3xl font-bold">{{ $total_value_month }}</h2>
                                <h5 class="text-lg">ยอดรวมรายรับ</h5>
                                <h5 class="text-lg">(เดือนนี้)</h5>
                            </div>
                            <div>
                                <img src="{{ asset('img/money-bag-dollar-svgrepo-com.svg') }}" width="100px"
                                    alt="">
                            </div>
                        </div>
                        <div>
                            <a href="/admin/dashboardmonth" class="pl-4">กดเพื่อดูรายละเอียด</a>
                        </div>
                    </div>
                </div>
                <!-- Total Value (This Year) -->
                <div class="bg-green-500 text-white rounded-lg shadow p-4">
                    <div>
                        <div class="flex justify-between px-4">
                            <div>
                                <h2 id="total-value-year" class="text-3xl font-bold">{{ $total_value_year }}</h2>
                                <h5 class="text-lg">ยอดรวมรายรับ</h5>
                                <h5 class="text-lg">(ปีนี้)</h5>
                            </div>
                            <div>
                                <img src="{{ asset('img/money-bag-dollar-svgrepo-com.svg') }}" width="100px"
                                    alt="">
                            </div>
                        </div>
                        <div>
                            <a href="/admin/dashboardyear" class="pl-4">กดเพื่อดูรายละเอียด</a>
                        </div>
                    </div>
                </div>
                <!-- Total Campaigns (This Month) -->
                <div class="bg-yellow-500 text-white rounded-lg shadow p-4">
                    <div class="flex justify-between px-4">
                        <div>
                            <h2 id="total-campaign-month" class="text-3xl font-bold">{{ $total_campaign_month }}</h2>
                            <h5 class="text-lg">จำนวนกองบุญ</h5>
                            <h5 class="text-lg">(เดือนนี้)</h5>
                        </div>
                        <div>
                            <img src="{{ asset('img/graph-svgrepo-com.svg') }}" width="100px" alt="">
                        </div>
                    </div>
                    <div>
                        <a href="/admin/campaignsmonth" class="pl-4">กดเพื่อดูรายละเอียด</a>
                    </div>
                </div>
                <!-- Total Campaigns (This Year) -->
                <div class="bg-red-500 text-white rounded-lg shadow p-4">
                    <div class="flex justify-between px-4">
                        <div>
                            <h2 id="total-campaign-year" class="text-3xl font-bold">{{ $total_campaign_year }}</h2>
                            <h5 class="text-lg">จำนวนกองบุญ</h5>
                            <h5 class="text-lg">(ปีนี้)</h5>
                        </div>
                        <div>
                            <img src="{{ asset('img/graph-svgrepo-com.svg') }}" width="100px" alt="">
                        </div>
                    </div>
                    <div>
                        <a href="/admin/campaignsyear" class="pl-4">กดเพื่อดูรายละเอียด</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row mt-5">
            <div class=" lg:w-3/4">
                <h3 class="text-xl font-bold">กองบุญที่ยังเปิดให้ร่วมบุญ</h3>
                <div class=" lg:h-[600px] rounded-lg  overflow-x-auto p-2 scrollbar-hide">
                    <table class="min-w-full border-collapse bg-white" id="campaignsTable">
                        <thead>
                            <tr class="bg-gradient-to-r h-12 from-sky-600 to-sky-500">
                                <th class="px-6 py-3 text-center text-nowrap md:text-wrap text-md font-semibold text-white">
                                    ชื่อกองบุญ</th>
                                <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">
                                    จำนวนที่เปิดรับ</th>
                                <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ร่วมบุญแล้ว
                                </th>
                                <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">
                                    คงเหลือร่วมบุญได้</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class=" lg:w-1/4">
                <h3 class="text-xl font-bold">ผู้ร่วมบุญสูงสุด</h3>
                <div class="bg-white rounded-lg  lg:h-[600px] overflow-x-auto p-2 scrollbar-hide ">
                    <form method="GET" class="mb-2">
                        <select id="filterSelect" name="filter" onchange="handleFilterChange(this.value)"
                            class="block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
                            <option value="month" selected>เดือนนี้</option>
                            <option value="year">ปีนี้</option>
                            <option value="all">ทั้งหมด</option>
                        </select>
                    </form>
                    <div class="">
                        <table class="min-w-full border-collapse bg-white" id="usersTable">
                            <thead>
                                <tr class="bg-gradient-to-r h-12 from-sky-600 to-sky-500">
                                    <th class="px-6 py-3 text-center text-wrap text-md font-semibold text-white">ชื่อไลน์
                                    </th>
                                    <th class="px-6 py-3 text-center text-nowrap text-md font-semibold text-white">ยอดรวม
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function fetchDashboardData() {
                $.ajax({
                    url: "{{ route('dashboard.data') }}",
                    method: "GET",
                    success: function(data) {

                        $('#total-value-month').text(data.total_value_month.toLocaleString('en-US', {
                            minimumFractionDigits: 2
                        }));


                        $('#total-value-year').text(data.total_value_year.toLocaleString('en-US', {
                            minimumFractionDigits: 2
                        }));


                        $('#total-campaign-month').text(data.total_campaign_month);


                        $('#total-campaign-year').text(data.total_campaign_year);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching dashboard data:', error);
                    }
                });
            }


            setInterval(fetchDashboardData, 3000);


            fetchDashboardData();

            function fetchCampaigns() {
                fetch('/api/campaigns')
                    .then(response => response.json())
                    .then(data => {
                        let tableBody = '';
                        data.forEach(campaign => {
                            tableBody += `
                        <tr class="h-16">
                            <td class="px-6 py-2 text-nowrap md:text-wrap text-xl text-start text-md text-blue-700">
                                <a href="/admin/campaigns_transaction?campaign_id=${campaign.id}&name=${campaign.name}">
                                    ${campaign.name}
                                </a>
                            </td>
                            <td class="px-6 py-2 text-nowrap text-lg  text-center text-md text-gray-700">${campaign.stock >= 999999 ? "ตามกำลังศรัทธา": campaign.stock}</td>
                            <td class="px-6 py-2 text-nowrap text-lg  text-center text-md text-gray-700">${campaign.total_donated >= 999999 ? "ตามกำลังศรัทธา": campaign.total_donated.toLocaleString()}</td>
                            <td class="px-6 py-2 text-nowrap text-lg  text-center text-md text-gray-700">${campaign.remaining_stock >= 999999 ? "ตามกำลังศรัทธา": campaign.remaining_stock}</td>
                        </tr>
                    `;
                        });
                        document.querySelector('#campaignsTable tbody').innerHTML = tableBody;
                    })
                    .catch(error => console.error('Error fetching campaigns:', error));
            }

            // เรียกฟังก์ชันเมื่อโหลดหน้าเสร็จ
            document.addEventListener('DOMContentLoaded', fetchCampaigns);

            // อัปเดตข้อมูลทุก 10 วินาที
            setInterval(fetchCampaigns, 5000);

            function handleFilterChange(filter) {
                fetchUsers(filter);
            }

            function handleFilterChange(filter) {
                fetchUsers(filter); // เรียกฟังก์ชัน fetchUsers พร้อมส่งค่าที่เลือก
            }

            function fetchUsers(filter = 'month') { // ค่าเริ่มต้นเป็น "month"
                fetch(`/api/users?filter=${filter}`) // ดึงข้อมูลจาก API
                    .then(response => response.json())
                    .then(data => {
                        let tableBody = '';
                        data.forEach(user => {
                            tableBody += `
                    <tr>
                        <td class="px-6 py-2 text-wrap  text-start text-md text-gray-700 line-clamp-2">${user.name}</td>
                        <td class="px-6 py-2 text-nowrap  text-center text-md text-gray-700">${user.total_amount.toLocaleString()}</td>
                    </tr>
                `;
                        });
                        // เติมข้อมูลลงในตาราง
                        document.querySelector('#usersTable tbody').innerHTML = tableBody;
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }

            // เรียกฟังก์ชัน fetchUsers ด้วยค่าเริ่มต้น "month" เมื่อโหลดหน้า
            document.addEventListener('DOMContentLoaded', () => fetchUsers('month'));
        </script>
    @endsection
