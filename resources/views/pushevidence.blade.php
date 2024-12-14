<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>อัปโหลดหลักฐานการทำบุญ</title>
    <link rel="icon" type="" href="{{ asset('img/AdminLogo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <form id="uploadForm" action="{{ Route('pushevidencetouser') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="px-2 d-flex flex-column align-items-center"  id="fileUploadContainer">

                </div>
                <div class="px-2 d-flex flex-column align-items-center">
                    <button type="button" class="btn btn-primary" id="addFileButton">เพิ่มรูป</button>
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
        const fileUploadContainer = document.getElementById('fileUploadContainer');
        const addFileButton = document.getElementById('addFileButton');

        // กำหนดจำนวนช่องอัปโหลดไฟล์สูงสุด
        const maxFileInputs = 4;

        addFileButton.addEventListener('click', () => {
            // ตรวจสอบว่ามีช่องอัปโหลดไฟล์กี่ช่องแล้ว
            const fileInputs = fileUploadContainer.querySelectorAll('.file-container');
            if (fileInputs.length < maxFileInputs) {
                // สร้างช่องอัปโหลดไฟล์ใหม่
                const newFileContainer = document.createElement('div');
                newFileContainer.className = 'file-container';
                newFileContainer.innerHTML = `
                    <input type="file" name="url_img[]" id="url_img[]" class="file-input mt-2">
                    <button type="button" class="removeFileButton btn btn-danger" multiple required >ลบ</button>
                `;
                fileUploadContainer.appendChild(newFileContainer);

                // เพิ่มฟังก์ชันลบช่องอัปโหลด
                const removeFileButton = newFileContainer.querySelector('.removeFileButton');
                removeFileButton.addEventListener('click', () => {
                    newFileContainer.remove();
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'ข้อจำกัดในการเพิ่มไฟล์',
                    text: 'คุณสามารถเพิ่มช่องอัปโหลดไฟล์ได้สูงสุด 4 ช่องเท่านั้น!',
                    confirmButtonText: 'ตกลง',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    </script>
    <script>
        function submitForm() {
            var fileInput = document.getElementById('url_img[]');
            if (fileInput.files.length === 0) {
                Swal.fire({
                    title: "กรุณาเลือกหรือถ่ายภาพใหม่",
                    text: "คุณยังไม่ได้เลือกไฟล์  กรุณาตรวจสอบอีกครั้ง",
                    icon: "warning",
                    button: "ตกลง"
                });
            } else {
                Swal.fire({
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
                window.location.href = "/";
            });
        </script>
    @endif
</body>

</html>
