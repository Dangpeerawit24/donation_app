<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
</head>

<body class="p-0 m-0 box-border">
    <div id="loader" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="flex flex-col items-center">
            <!-- Spinner -->
            <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent border-dashed rounded-full animate-spin"></div>
            <!-- Text -->
            <p class="mt-4 text-white text-lg font-semibold">Loading...</p>
        </div>
    </div>
    <div class="row w-full h-20 fixed top-0 z-40 bg-sky-900 shadow-lg text-white content-center justify-items-center">
        @include('layouts.navbar')
        <div class="xl:ml-64 mt-20 p-2 lg:p-5 w-full  overflow-y-auto">
            @yield('content')
        </div>
    </div>
    @include('layouts.script')
</body>

</html>
