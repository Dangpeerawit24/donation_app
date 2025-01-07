<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ระบบกองบุญออนไลน์</title>
    <link rel="icon" type="" href="{{ asset('img/AdminLogo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <style>
        .spinner {
            width: 200px; 
            height: 200px;
            animation: spin 2s linear infinite; 
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-300">
    {{-- Loading --}}
    <div id="loader" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
        <img src="{{ asset('img/loading.png') }}" alt="Loading..." class="spinner">
    </div>

    {{-- Header --}}
    <div class="row w-full h-20 fixed top-0 bg-red-950 content-center justify-items-center">
        <nav class="flex items-center">
            <img src="{{ asset('img/AdminLogo.png') }}" width="50px" height="50px" alt="">
            <h3 class="mx-2 text-white text-2xl">ศาลพระโพธิสัตว์กวนอิมทุ่งพิชัย</h3>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="grid row w-full h-full my-20 content-center justify-center">
        <h2 class="text-3xl text-center mb-4 mt-4">ติดตามสถานะกองบุญ</h2>
        <div class="max-w-sm p-2 bg-white border border-gray-200 rounded-xl shadow dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-center text-2xl">ภาพจากกองบุญ</h1>
            <h2 class="text-center text-xl">{{ $campaignsname }}</h2>

            @php
                // แปลง string เป็น array (ถ้าเป็นค่าว่างจะได้ [""] แต่เราจะเช็คด้านล่างอีกที)
                $images = explode(',', $url_img);
                // ลบค่าว่างที่อาจติดมาจากท้าย string หรือกรณีไม่มีรูป
                $images = array_filter($images);
                // จัด array ให้เป็น index ใหม่
                $images = array_values($images);
            @endphp

            {{-- ถ้ามีมากกว่า 1 รูป ให้โชว์เป็นสไลด์, ถ้ามี 1 รูป ก็โชว์รูปเดียว --}}
            @if (count($images) > 1)
                <!-- สไลด์ (Next/Prev) -->
                <div id="slider" class="flex items-center justify-center flex-col mt-5">
                    <img id="slider-img" class="rounded img-fluid my-5" width="100%" alt="Campaign Image" />
                    <div class="flex justify-between w-full max-w-sm">
                        <button id="prev-btn" class="bg-gray-300 text-gray-700 px-2 py-1 rounded-md">
                            <i class="fa-solid fa-chevron-left"></i> Prev
                        </button>
                        <button id="next-btn" class="bg-gray-300 text-gray-700 px-2 py-1 rounded-md">
                            Next <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @elseif (count($images) === 1)
                <!-- มีรูปเดียว -->
                <img 
                    class="rounded img-fluid my-5" 
                    src="{{ asset('img/pushimg/' . $images[0]) }}" 
                    width="100%" 
                    alt="Campaign Image" 
                />
            @else
                <!-- ไม่มีรูป -->
                <p class="text-center text-gray-500 my-5">ไม่พบภาพในระบบ</p>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <div
        class="row h-20 flex fixed inset-x-0 bottom-0 px-20 rounded-t-xl bg-red-950 items-center justify-between text-white">
        <div class="items-center justify-items-center text-center">
            <a href="/line"><i class="fa-solid fa-house fa-xl"></i></a>
            <a href="/line">
                <h3 class="mt-1">หน้าหลัก</h3>
            </a>
        </div>
        <div class="items-center justify-items-center text-center">
            <a href="{{ url('campaignstatus?userId=' . $profile['userId']) }}">
                <i class="fa-solid fa-clipboard-list fa-xl"></i>
            </a>
            <a href="{{ url('campaignstatus?userId=' . $profile['userId']) }}">
                <h3 class="mt-1">สถานะกองบุญ</h3>
            </a>
        </div>
    </div>

    {{-- Script ส่วนการแสดง/ซ่อน Loader --}}
    <script>
        document.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                document.getElementById('loader').classList.remove('hidden');
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

    {{-- Script ส่วนสไลด์ (เฉพาะกรณีมีหลายภาพ) --}}
    @if (count($images) > 1)
        <script>
            // รับค่า images จาก Blade (แปลงเป็น JSON)
            const images = @json($images);

            let currentIndex = 0;
            const sliderImg = document.getElementById('slider-img');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');

            // ฟังก์ชันสำหรับเปลี่ยนรูป
            function showImage(index) {
                // ปรับ path ตามตำแหน่งไฟล์ของคุณ
                sliderImg.src = `{{ asset('img/pushimg') }}/${images[index]}`;
            }

            // คลิกปุ่ม Prev
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                showImage(currentIndex);
            });

            // คลิกปุ่ม Next
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            });

            // แสดงรูปแรกตอนโหลดหน้า
            showImage(currentIndex);
        </script>
    @endif

</body>
</html>
