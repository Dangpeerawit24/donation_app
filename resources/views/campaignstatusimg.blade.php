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
<body class="bg-gray-300	">
    <div id="loader" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
        <img src="{{asset('img/loading.png')}}" alt="Loading..." class="spinner">
    </div>
    <div class="row w-full h-20 fixed top-0 bg-red-950 content-center justify-items-center">
        <nav class="flex items-center	">
            <img src="{{ asset('img/AdminLogo.png') }}" width="50px" height="50px" alt="">
            <h3 class=" mx-2 text-white text-2xl">ศาลพระโพธิสัตว์กวนอิมทุ่งพิชัย</h3>
        </nav>
    </div>
    <div class=" grid row w-full h-full my-20 content-center justify-center">
        <h2 class=" text-3xl text-center mb-4 mt-4">ติดตามสถานะกองบุญ</h2>
        <div
            class="max-w-sm p-2 bg-white border border-gray-200 rounded-xl shadow dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-center text-2xl">ภาพจากกองบุญ</h1>
            <h2 class="text-center text-xl">{{ $campaignsname }}</h2>
            <img class="rounded img-fluid my-5" src="{{ $url_img }}" width="100%" alt="Campaign Image" />
        </div>
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
