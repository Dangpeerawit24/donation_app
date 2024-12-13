<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>อัปโหลดหลักฐานการทำบุญ</title>
    <link rel="icon" type="" href="{{ asset('img/AdminLogo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

<body>
    <div class="col text-center d-flex justify-content-center align-items-center"
        style="background: #a30000;height: 60.875px;">
        <img width="40" height="40" src="{{ asset('img/AdminLogo.png') }}" />
        <h1 style="font-size: 21.4px;color: rgb(255,255,255);">ศาลพระโพธิสัตว์กวนอิมทุ่งพิชัย</h1>
    </div>
    <div class="d-flex justify-content-center align-items-center mt-2">
        <h1 style="font-weight: bold;font-size: 23.4px; margin-top: 20px;">อัพโหลดหลักฐานส่งลูกบุญ</h1>
    </div>
    <div class="card" style="margin-top: 10px;">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <h6 class="text-muted mb-2">กองบุญ</h6>
                    <h6 class="text-muted mb-2">จำนวนกองบุญ</h6>
                    <h6 class="text-muted mb-2">ชื่อไลน์</h6>
                </div>
                <div class="col-6 text-end">
                    @foreach ($names as $name)
                        <h6 class="text-muted mb-2">{{ $name->campaignsname }}</h6>
                        <h6 class="text-muted mb-2">{{ $name->value }}</h6>
                        <h6 class="text-muted mb-2">{{ $name->lineName }}</h6>
                    @endforeach
                </div>
            </div>
            <form id="uploadForm" action="{{ Route('pushevidencetouser2') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="mt-3">
                    <input type="file" name="url_img" id="url_img" required />
                </div>
                <input type="hidden" id="transactionID" name="transactionID" value="{{ $transactionID }}">
                @foreach ($names as $name)
                    <input type="hidden" name="userid" value="{{ $name->lineId }}">
                    <input type="hidden" id="campaignname" name="campaignname" value="{{ $name->campaignsname }}">
                @endforeach
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <button class="btn btn-success" type="button" onclick="submitForm()">ยืนยันหลักฐาน</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        function submitForm() {
            var fileInput = document.getElementById('url_img');
            if (fileInput.files.length === 0) {
                swal({
                    title: "กรุณาเลือกหรือถ่ายภาพใหม่",
                    text: "คุณยังไม่ได้เลือกไฟล์  กรุณาตรวจสอบอีกครั้ง",
                    icon: "warning",
                    button: "ตกลง"
                });
            } else {
                swal({
                    title: "กำลังประมวลผล",
                    text: "กรุณารอสักครู่...",
                    icon: "info",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });

                document.getElementById("uploadForm").submit();
            }
        }
    </script>
    @if (session('success'))
        <script>
            swal({
                icon: 'success',
                title: "{{ session('success') }}",
                timer: 5000,
                buttons: "ตกลง"
            }).then(() => {
                window.location.href = "https://donation.kuanimtungpichai.com/super-admin/campaigns_transaction?campaign_id={{ $name->campaignsid }}&name={{ $name->campaignsname }}"; // ใส่ URL ที่ต้องการรีไดเรก
            });
        </script>
    @endif
</body>

</html>
