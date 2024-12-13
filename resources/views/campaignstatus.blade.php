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
            class=" w-11/12 p-2 bg-white border border-gray-200 rounded-xl shadow dark:bg-gray-800 dark:border-gray-700" style="justify-self: center;">
            <table class="min-w-full text-left text-sm font-light text-surface dark:text-white">
                <thead class="border-b border-neutral-200 font-medium dark:border-white/10">
                    <tr>
                        <th scope="col" class=" py-3 w-3/4 text-center">กองบุญ</th>
                        <th scope="col" class=" py-3 w-1/4 text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; @endphp
                    @foreach ($Datas as $Data)
                        <tr class="border-b border-neutral-200 dark:border-white/10">
                            <td class="whitespace-wrap text-lg  py-3 font-medium">กองบุญ{{ $Data->campaignsname }}<br>รายนาม:
                                {{ $Data->details }}{{ $Data->details2 }}{{ $Data->detailsbirthday }}{{ $Data->detailstext }}</td>
                            <td class="whitespace-nowrap  py-3 text-center">
                                @if ($Data->status == 'ส่งภาพกองบุญแล้ว')
                                    <a href="campaignstatusimg?url_img={{ $Data->url_img }}&campaignsname={{ $Data->campaignsname }}"
                                        class="btn btn-link" target=""><span
                                            class="inline-flex text-center items-center rounded-md bg-green-700 px-2 py-1 text-sm font-medium text-white ring-1 ring-inset ring-green-600/20">ส่งภาพ<br>กองบุญแล้ว</span></a>
                                @elseif ($Data->status == 'รายนามเข้าระบบเรียบร้อยแล้ว')
                                    <span
                                        class="inline-flex text-center items-center rounded-md bg-green-700 px-2 py-1 text-sm font-medium text-white ring-1 ring-inset ring-green-600/20">รายนาม<br>เข้าระบบแล้ว</span>
                                @else
                                    <span
                                        class="inline-flex text-center items-center rounded-md bg-yellow-500 px-2 py-1 text-sm font-medium text-black ring-1 ring-inset ring-green-600/20">รอ<br>ดำเนินการ</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div
        class="row h-20 flex fixed inset-x-0 bottom-0 px-20 rounded-t-xl  bg-red-950 items-center justify-center text-white">
        <div class=" items-center justify-items-center text-center">
            <a href="/"><i class="fa-solid fa-house fa-xl"></i></a>
            <a href="/">
                <h3 class="mt-1">หน้าหลัก</h3>
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
