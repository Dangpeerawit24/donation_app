<div class="w-full mx-0 px-4 sm:px-4 lg:px-8">
    <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <a href="/{{ Auth::user()->type }}/dashboard" class="text-lg flex flex-row gap-2 items-center font-semibold">
            <img src="{{ asset('img/AdminLogo.png') }}" width="50px" alt="">
            ระบบกองบุญออนไลน์
        </a>

        <!-- Menu Button (เฉพาะมือถือ) -->
        <div class="xl:hidden flex flex-row items-center">
            <a href="#" id="open-modal-button"
                class="relative flex items-center gap-2 p-2 mr-3 rounded-full bg-green-500 text-white hover:bg-green-600">
                <!-- ไอคอน Notification -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14V11a6 6 0 0 0-5-5.917V4a1 1 0 0 0-2 0v1.083A6 6 0 0 0 6 11v3c0 .53-.21 1.04-.586 1.414L4 17h5m6 0v1a3 3 0 0 1-6 0v-1m6 0H9">
                    </path>
                </svg>
                <!-- ตัวเลขแจ้งเตือน -->
                <span
                    class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full">
                    
                </span>
            </a>

            <label for="menu-toggle" class="cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </label>
            <input type="checkbox" id="menu-toggle" class="hidden">
        </div>

        <!-- Desktop Links -->
        <div class="hidden xl:flex items-center justify-between gap-4 border-sky-100">
            <!-- แสดงชื่อ/สิทธิ์ผู้ใช้ -->
            <div class="items-center gap-1">
                <h2 class="text-xl">{{ Auth::user()->name }}</h2>
                @if (Auth::user()->type === 'admin')
                    <h2 class="text-md">สิทธิ์การใช้งาน : {{ Auth::user()->type }}</h2>
                @elseif (Auth::user()->type === 'manager')
                    <h2 class="text-md">สิทธิ์การใช้งาน : ผู้จัดการ</h2>
                @else
                    <h2 class="text-md">สิทธิ์การใช้งาน : พนักงาน</h2>
                @endif
            </div>

            <a href="#" id="open-modal-button2"
                class="relative flex items-center gap-2 p-2 mr-3 rounded-full bg-green-500 text-white hover:bg-green-600">
                <!-- ไอคอน Notification -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14V11a6 6 0 0 0-5-5.917V4a1 1 0 0 0-2 0v1.083A6 6 0 0 0 6 11v3c0 .53-.21 1.04-.586 1.414L4 17h5m6 0v1a3 3 0 0 1-6 0v-1m6 0H9">
                    </path>
                </svg>
                <!-- ตัวเลขแจ้งเตือน -->
                <span
                    class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full">
                    
                </span>
            </a>

            <!-- ปุ่ม Logout -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" id="logout-btn"
                    class="flex items-center gap-2 p-2 rounded bg-sky-600 text-white hover:bg-sky-700">
                    Logout
                </a>
            </form>
        </div>
    </div>
</div>
<!-- Modal (เริ่มต้นซ่อนด้วย hidden) -->
<div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden" id="notification-modal">
    <div class=" bg-blue-200 text-black p-2 rounded shadow-md w-11/12 sm:w-1/2 lg:w-1/3">
        <div class="flex flex-row justify-between items-center">
            <!-- เอา mb-4 ออก หรือเปลี่ยนเป็น mb-0 -->
            <h2 class="text-lg font-bold mb-0">
                กองบุญที่ยังไม่ได้ส่งภาพ
            </h2>
            <button class="px-2 py-2 bg-red-500 text-white rounded hover:bg-red-600" id="close-modal-button">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>


        <!-- ใส่ div เปล่าสำหรับแสดงข้อมูล -->
        <div id="modal-body" class="my-4 text-xl bg-gray-100 overflow-y-auto max-h-[calc(100vh-8rem)]">
            <!-- จะถูก JS อัปเดตเนื้อหาให้โดย fetchPendingTransactions() -->
        </div>

    </div>
</div>

<script>
    const openBtn = document.getElementById('open-modal-button');
    const openBtn2 = document.getElementById('open-modal-button2');
    const modal = document.getElementById('notification-modal');
    const closeBtn = document.getElementById('close-modal-button');

    // กดปุ่ม Notification แล้วเปิด Modal
    openBtn.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.remove('hidden'); // เอา hidden ออกเพื่อให้ Modal แสดง
    });
    openBtn2.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.remove('hidden'); // เอา hidden ออกเพื่อให้ Modal แสดง
    });

    // กดปุ่ม Close ใน Modal แล้วปิด Modal
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden'); // ใส่ hidden กลับเพื่อปิด Modal
    });

    // ถ้าต้องการให้คลิกพื้นที่นอก Modal แล้วปิด
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>

<!-- Slide-in Menu -->
<div id="menu"
    class="fixed inset-0 bg-sky-900 text-white z-20 transform -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Close Button -->
        <div class="flex justify-between p-4">
            <h1 class="text-3xl">
                เมนูจัดการระบบ
            </h1>
            <button id="close-menu" class="text-white hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <!-- Menu Links -->
        <div class="flex flex-col h-full space-y-6">
            <ul class="flex-1 m-4 p-2 space-y-4">
                @if (Auth::user()->type === 'admin')
                    <li class="">
                        <a href="/admin/dashboard" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_dashboard.svg') }}" width="30px" height="30px"
                                alt="">
                            แดชบอร์ด
                        </a>
                    </li>
                    <li>
                        <a href="/admin/campaigns" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_campaigns.svg') }}" width="30px" height="30px"
                                alt="">
                            จัดการข้อมูลกองบุญ
                        </a>
                    </li>
                    <li>
                        <a href="/admin/categories" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_categories.svg') }}" width="30px"
                                height="30px" alt="">
                            จัดการหัวข้อกองบุญ
                        </a>
                    </li>
                    <li>
                        <a href="/admin/qrcode" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_qrcode.svg') }}" width="30px" height="30px"
                                alt="">
                            สร้าง QR CODE
                        </a>
                    </li>
                    <li>
                        <a href="/admin/lineusers" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_lineusers.svg') }}" width="30px" height="30px"
                                alt="">
                            รายการลูกบุญย้อนหลัง
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_user.svg') }}" width="30px" height="30px"
                                alt="">
                            จัดการพนักงาน
                        </a>
                    </li>
                @elseif (Auth::user()->type === 'manager')
                @else
                    <li class="">
                        <a href="/staff/pos" class="flex items-center gap-2 p-2 rounded hover:bg-sky-800">
                            <img src="{{ asset('img/submenu/ic_menu_store_normal.svg') }}" width="30px"
                                height="30px" alt="">
                            หน้าขาย
                        </a>
                    </li>
                @endif
                <div class="flex items-center justify-between border-t p-2	border-sky-100	">
                    <div class=" items-center gap-1">
                        <h2 class="text-xl">{{ Auth::user()->name }}</h2>
                        @if (Auth::user()->type === 'admin')
                            <h2 class="text-md">สิทธิ์การใช้งาน : {{ Auth::user()->type }}</h2>
                        @elseif (Auth::user()->type === 'manager')
                            <h2 class="text-md">สิทธิ์การใช้งาน : ผู้จัดการ</h2>
                        @else
                            <h2 class="text-md">สิทธิ์การใช้งาน : พนักงาน</h2>
                        @endif
                    </div>
                    <form id="logout-form2" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" id="logout-btn2"
                            class="flex items-center gap-2 p-2 rounded bg-sky-600 text-white hover:bg-sky-700">
                            Logout
                        </a>
                    </form>
                </div>
            </ul>
        </div>
    </div>
</div>
</div>
<div class=" row flex flex-row ">
    <div class="w-64 hidden mt-20 xl:flex fixed bg-sky-500 overflow-y-auto ">
        <div class="w-64 h-screen bg-sky-900 text-white flex flex-col ">
            {{-- <div class="p-4 text-center font-bold text-2xl border-b border-sky-100">
            เมนูจัดการระบบ
        </div> --}}
            @if (Auth::user()->type === 'admin')
                <ul class="flex-1 m-4 p-2 space-y-4">
                    <li class="">
                        <a href="/admin/dashboard"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'แดชบอร์ด' ? ' bg-sky-600 scale-125' : '' }} hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_dashboard.svg') }}" width="30px"
                                height="30px" alt="">
                            แดชบอร์ด
                        </a>
                    </li>
                    <li>
                        <a href="/admin/campaigns"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'campaigns' ? ' bg-sky-600 scale-125' : '' }}  hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_campaigns.svg') }}" width="30px"
                                height="30px" alt="">
                            จัดการข้อมูลกองบุญ
                        </a>
                    </li>
                    <li>
                        <a href="/admin/categories"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'categories' ? ' bg-sky-600 scale-125' : '' }} hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_categories.svg') }}" width="30px"
                                height="30px" alt="">
                            จัดการหัวข้อกองบุญ
                        </a>
                    </li>
                    <li>
                        <a href="/admin/qrcode"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'qrcode' ? ' bg-sky-600 scale-125' : '' }} hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_qrcode.svg') }}" width="30px" height="30px"
                                alt="">
                            สร้าง QR CODE
                        </a>
                    </li>
                    <li>
                        <a href="/admin/lineusers"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'รายการลูกบุญย้อนหลัง' ? ' bg-sky-600 scale-125' : '' }} hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_lineusers.svg') }}" width="30px"
                                height="30px" alt="">
                            รายการลูกบุญย้อนหลัง
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'พนักงาน' ? ' bg-sky-600 scale-125' : '' }} hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_user.svg') }}" width="30px" height="30px"
                                alt="">
                            จัดการพนักงาน
                        </a>
                    </li>
                </ul>
            @elseif (Auth::user()->type === 'manager')
            @else
                <ul class="flex-1 m-4 p-2 space-y-4">
                    <li class="">
                        <a href="/staff/pos"
                            class="flex items-center gap-2 p-2 rounded {{ $manu == 'หน้าขาย' ? ' bg-sky-600 scale-125' : '' }} hover:bg-sky-800 hover:scale-110	">
                            <img src="{{ asset('img/submenu/ic_menu_store_normal.svg') }}" width="30px"
                                height="30px" alt="">
                            หน้าขาย
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
