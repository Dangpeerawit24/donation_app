@if (Auth::check())
    <script>
        window.location.href = "{{ route(Auth::user()->type . '.dashboard') }}";
    </script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบกองบุญออนไลน์</title>
    <link rel="icon" href="{{asset('img/AdminLogo.png')}}" />
    <script src="{{ asset('style.js') }}"></script>
</head>

<body class="p-0 m-0 box-border bg-gradient-to-r from-cyan-500 to-blue-500 h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col items-center mb-6">
            <img src="{{asset('img/AdminLogo.png')}}" alt="Logo" class="h-28 mb-3">
            <h1 class="text-3xl font-bold text-gray-700">ระบบกองบุญออนไลน์</h1>
            <h2 class="text-xl font-bold mt-3 text-gray-700">โปรดลงชื่อเข้าสู่ระบบ</h2>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                    class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 @error('email') border-red-500 @enderror"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••"
                    class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 @error('password') border-red-500 @enderror"
                    required>
                @error('password')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-base font-semibold shadow-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                Login
            </button>
        </form>
    </div>
</body>

</html>
