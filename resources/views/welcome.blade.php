<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบกองบุญออนไลน์</title>
    <link rel="icon" type="" href="{{ asset('img/AdminLogo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('asset/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/sweetalert2.min.css')}}">
    <script src="{{asset('asset/sweetalert2@11.js')}}"></script>
    <style>
        .spinner {
            width: 200px;
            height: 200px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .rounded-image {
            border-radius: 15px;
            /* หรือ 50% หากต้องการรูปภาพเป็นวงกลม */
        }
    </style>
</head>

<body class="bg-gray-300	">
    <div id="loader" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
        <img src="{{ asset('img/loading.png') }}" alt="Loading..." class="spinner">
    </div>


    <div class="row w-full h-20 fixed top-0 bg-red-950 content-center justify-items-center">
        <nav class="flex items-center	">
            <img src="{{ asset('img/AdminLogo.png') }}" width="50px" height="50px" alt="">
            <h3 class=" mx-2 text-white text-2xl">ศาลพระโพธิสัตว์กวนอิมทุ่งพิชัย</h3>
        </nav>
    </div>
    <div class=" grid row w-full h-full my-20 content-center justify-center">
        @if ($campaigns->isEmpty())
            <div class="text-center w-full mt-36 items-center text-gray-500">
                <p class="text-xl">ขออภัย ไม่มีกองบุญที่เปิดให้ร่วมบุญในขณะนี้</p>
            </div>
        @endif

        @foreach ($campaigns as $campaign)
            <div
                class="max-w-sm p-6 mb-4 mt-5 bg-white border border-gray-200 rounded-3xl shadow dark:bg-gray-800 dark:border-gray-700">
                <img src="{{ asset('img/campaign/' . $campaign->campaign_img) }}" class="rounded-xl" width="100%"
                    alt="">
                <h2 class="text-2xl mt-2">กองบุญ{{ $campaign->name }}</h2>
                <p class="mt-1 text-wrap">{!! str_replace('/n', '<br>', e($campaign->description)) !!}</p>
                @php
                    $details = $campaign->details;
                @endphp
                @if ($details == 'ชื่อสกุล')
                    <a href="{{ url('formcampaigh?campaign_id=' . $campaign->id) }}"><button
                            class=" w-full mt-3 py-2 px-5 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-blue-800 focus:outline-none focus:ring focus:ring-violet-400 focus:ring-opacity-75">
                            กดเพื่อร่วมบุญ
                        </button></a>
                @elseif ($details == 'ชื่อวันเดือนปีเกิด')
                    <a href="{{ url('formcampaighbirthday?campaign_id=' . $campaign->id) }}"><button
                            class=" w-full mt-3 py-2 px-5 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-blue-800 focus:outline-none focus:ring focus:ring-violet-400 focus:ring-opacity-75">
                            กดเพื่อร่วมบุญ
                        </button></a>
                @elseif ($details == 'ตามศรัทธา')
                    <a href="{{ url('formcampaighall?campaign_id=' . $campaign->id) }}"><button
                            class=" w-full mt-3 py-2 px-5 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-blue-800 focus:outline-none focus:ring focus:ring-violet-400 focus:ring-opacity-75">
                            กดเพื่อร่วมบุญ
                        </button></a>
                @elseif ($details == 'กิจกรรม')
                    <a href="{{ url('formcampaighgive?campaign_id=' . $campaign->id) }}"><button
                            class=" w-full mt-3 py-2 px-5 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-blue-800 focus:outline-none focus:ring focus:ring-violet-400 focus:ring-opacity-75">
                            กดเพื่อร่วมบุญ
                        </button></a>
                @else
                    <a href="{{ url('formcampaightext?campaign_id=' . $campaign->id) }}"><button
                            class=" w-full mt-3 py-2 px-5 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-blue-800 focus:outline-none focus:ring focus:ring-violet-400 focus:ring-opacity-75">
                            กดเพื่อร่วมบุญ
                        </button></a>
                @endif
            </div>
        @endforeach
    </div>
    <div
        class="row h-20 flex fixed inset-x-0 bottom-0 px-20 rounded-t-xl  bg-red-950 items-center justify-between text-white">
        <div class=" items-center justify-items-center text-center">
            <a href="/"><i class="fa-solid fa-house fa-xl"></i></a>
            <a href="/">
                <h3 class="mt-1">หน้าหลัก</h3>
            </a>
        </div>
        <div class=" items-center justify-items-center text-center">
            <a href="{{ url('campaignstatus?userId=' . $profile['userId']) }}"><i
                    class="fa-solid fa-clipboard-list fa-xl"></i></a>
            <a href="{{ url('campaignstatus?userId=' . $profile['userId']) }}">
                <h3 class="mt-1">สถานะกองบุญ</h3>
            </a>
        </div>
    </div>
    <script>
        @if (session('success'))
            Swal.fire({
                imageUrl: '{{ asset('img/ขออนุโมทนาบุญ.png') }}',
                customClass: {
                    image: 'rounded-image' // ชื่อ class CSS
                },
                imageWidth: 300,
                imageHeight: 300,
                title: 'ขออนุโมทนาบุญกับ<br>คุณ{{ session('lineName') }}',
                html: 'ที่ได้ร่วมกองบุญ{{ session('campaignname') }}',
                timer: 5000,
                showConfirmButton: false
            });
        @endif
    </script>
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

</body>

</html>
