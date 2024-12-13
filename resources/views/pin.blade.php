<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกรหัส PIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">กรุณากรอกรหัส PIN</h1>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('pin.verify') }}" method="POST">
            @csrf
        
            <div class="mb-3">
                <label for="pin" class="form-label">รหัส PIN</label>
                <input type="password" name="pin" id="pin" class="form-control" maxlength="4" required>
            </div>
        
            @if (!empty($queryParams) && is_iterable($queryParams))
                @foreach ($queryParams as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            @else
                <p class="text-danger">ไม่มีข้อมูลสำหรับส่งต่อ</p>
            @endif
        
            <button type="submit" class="btn btn-primary w-100">ยืนยัน</button>
        </form>
        
        
    </div>
</body>
</html>
