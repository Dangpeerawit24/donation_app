<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบกองบุญออนไลน์</title>
    <link rel="icon" type="" href="https://donation.kuanimtungpichai.com/img/AdminLogo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body style="background: rgb(219,219,219);">
    <div class="text-nowrap text-center d-flex justify-content-center align-items-center"
        style="font-size: 11px;background: #8d0000;">
        <div class="container d-flex justify-content-center align-items-center" style="height: 60px;">
            <h1 class="d-flex justify-content-center align-items-center"
                style="color: var(--bs-body-bg);font-size: 20.88px;margin: 8px;"><img width="40" height="40"
                    src="{{ asset('img/AdminLogo.png') }}">ศาลพระโพธิสัตว์กวนอิมทุ่งพิชัย</h1>
        </div>
    </div>
    <div class="text-center">
        <div style="margin: 6px;">
            <h3 class="d-flex justify-content-center align-items-end">คุณกำลังร่วมบุญใน</h3>
        </div>
        <div class="d-flex justify-content-center align-items-start">
            @foreach ($campaignData as $data)
                <h4>กองบุญ{{ $data['campaign']->name }}</h4>
        </div>
    </div>
    <div>
        <div class="card" style="height: auto;">
            <div class="card-body">
                <div>
                    <form action="{{ Route('formcampaigh.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 style="color: var(--bs-body-color);font-weight: bold;">กรอกข้อมูลผู้ร่วมบุญ</h4>
                        </div>
                        <div>
                            <h5 style="color: var(--bs-emphasis-color);">จำนวนกองบุญที่ต้องการร่วมบุญ</h5>
                        </div>
                        <div>
                            <input type="number" id="donationCount" name="value" required
                                style="width: 100%; text-align: center; height: 45.4286px;" placeholder="0"
                                min="0" max="120" onchange="updateDonationInputs()">
                        </div>
                        <div id="donationInputs" class="input-container"></div>
                        <div class="d-flex justify-content-start" style="margin-top: 9px;">
                            <h5 style="color: var(--bs-emphasis-color);font-weight: bold;">แนบหลักฐานการโอนเงิน</h5>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" style="margin-top: 2px;"><input
                                class="form-control" type="file" id="evidence" name="evidence" accept="image/*"
                                required></div>
                        <input type="hidden" id="campaignsid" name="campaignsid" value="{{ $data['campaign']->id }}">
                        <input type="hidden" id="respond" name="respond" value="{{ $data['campaign']->respond }}">
                        <input type="hidden" id="campaignsname" name="campaignsname"
                            value="{{ $data['campaign']->name }}">
                        <input type="hidden" name="lineId" value="{{ $data['profile']['userId'] }}">
                        <input type="hidden" name="lineName" value="{{ $data['profile']['displayName'] }}">
                        <input type="hidden" name="transactionID"
                            value="TX-{{ now()->timestamp }}-{{ rand(1000, 9999) }}">
                        <div class="d-flex justify-content-center align-items-center"
                            style="margin-top: 12px;margin-bottom: px;"><button class="btn btn-primary" type="bottom"
                                onclick="submitForm()">ยืนยันส่งข้อมูล</button></div>
                    </form>
                    @endforeach
                </div>
                <div style="margin-top: 8px;">
                    <div class="row">
                        <div class="col">
                            <div style="margin-top: 9px;">
                                <h2 style="color: var(--bs-emphasis-color);font-weight: bold;">สรุปยอดกองบุญ</h2>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-7">
                                <h3 style="color: var(--bs-emphasis-color);">ยอดรวม</h3>
                            </div>
                            <div class="col-5">
                                <div style="text-align: right;">
                                    <h3 style="color: var(--bs-emphasis-color);" id="totalAmountDisplay">0.00 บาท</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 8px;">
                    <h4 style="color: var(--bs-body-color);font-weight: bold;">รายละเอียดการโอนเงิน</h4>
                </div>
                <div style="text-align: center; text-align: -webkit-center; margin-top: 10px;">
                    <img id="qr" src="https://promptpay.io/0993000067720.png" width="150px" height="150px"
                        alt="" style="display: none;">
                </div>
                <div style="text-align: center;">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">💰มูลนิธิเมตตาธรรมรัศมี</h5>
                </div>
                <div style="text-align: center;">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">ธนาคารกสิการไทย</h5>
                </div>
                <div>
                    <div class="row d-flex justify-content-center align-items-center"
                        style="margin-right: -12px;margin-top: 5px;">
                        <div class="col-8 d-flex justify-content-end justify-content-xl-center align-items-xl-center">
                            <input class="form-control" type="text" id="accountNumber1"
                                placeholder="171-1-75423-3" value="171-1-75423-3" style="text-align: center;"
                                readonly>
                            <button class="btn btn-secondary" onclick="copyToClipboard('accountNumber1')"
                                style="margin-left: 10px;">คัดลอก</button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center ; margin-top: 5px;">----- หรือ -----
                    </h5>
                </div>
                <div style="text-align: center;">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">💰มูลนิธิเมตตาธรรมรัศมี</h5>
                </div>
                <div style="text-align: center;">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">ธนาคารไทยพาณิชย์</h5>
                </div>
                <div>
                    <div class="row d-flex justify-content-center align-items-center"
                        style="margin-right: -12px;margin-top: 5px;">
                        <div class="col-8 d-flex justify-content-end justify-content-xl-center align-items-xl-center">
                            <input class="form-control" type="text" id="accountNumber2"
                                placeholder="649-242269-4" value="649-242269-4" style="text-align: center;" readonly>
                            <button class="btn btn-secondary" onclick="copyToClipboard('accountNumber2')"
                                style="margin-left: 10px;">คัดลอก</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    let cachedDetails = null;
    const pricePerUnit = {{ $campaignData[0]['campaign']->price ?? 0 }};

    window.onload = function() {
        fetch('/fetch_formcampaigh_details')
            .then(response => response.json())
            .then(details => {
                cachedDetails = details;
                console.log('ข้อมูลที่ดึงมา:', cachedDetails);
            })
            .catch(error => console.error('Error fetching details:', error));
    };

    function createInputFields(index) {
        const inputDiv = document.createElement('div');
        inputDiv.className = 'input-container';

        let options = `<select name="name[]" id="donorName${index}" onchange="checkNewEntry(this, ${index})" style="width: 100%; text-align: center; height: 45.4286px;" required>
                        <option value="">--กดเลือกรายนามที่เคยร่วมบุญ--</option>`;

        cachedDetails.forEach(detail => {
            options += `<option value="${detail}">${detail}</option>`;
        });

        options += `<option value="new">เพิ่มรายการใหม่</option></select>`;

        const newInput =
            `<input type="text" name="newName[]" id="newDonorName${index}" style="width: 100%; text-align: center; height: 45.4286px; display: none;" placeholder="ชื่อ-นามสกุล ${index + 1}" required>`;

        inputDiv.innerHTML = `<label for="donorName${index}">กรอกข้อมูล ชุดที่ ${index + 1}</label>` + options +
            newInput;

        document.getElementById('donationInputs').appendChild(inputDiv);
    }

    function updateDonationInputs() {
        const countInput = document.getElementById('donationCount');
        let count = parseInt(countInput.value, 10);

        if (isNaN(count) || count < 0) {
            count = 0;
            countInput.value = count;
        }

        if (count > 120) {
            Swal.fire({
                title: "ข้อจำกัด!",
                text: "คุณไม่สามารถกรอกจำนวนกองบุญเกิน 120 ได้",
                icon: "warning",
                confirmButtonText: "ตกลง"
            });
            count = 120;
            countInput.value = count;
        }


        const donationInputsContainer = document.getElementById('donationInputs');
        const qrImage = document.getElementById('qr');
        donationInputsContainer.innerHTML = '';

        if (count > 0) {
            const totalAmount = count * pricePerUnit;
            document.getElementById('totalAmountDisplay').innerText = totalAmount.toFixed(2) + " บาท";

            qrImage.src = `https://promptpay.io/0993000067720/${totalAmount}`;
            qrImage.style.display = 'block';

            if (cachedDetails) {
                for (let i = 0; i < count; i++) {
                    createInputFields(i);
                }
            } else {
                console.error('ยังไม่มีข้อมูลใน cachedDetails');
            }
        } else {
            document.getElementById('totalAmountDisplay').innerText = "0.00 บาท";
            qrImage.style.display = 'none';
        }
    }


    function checkNewEntry(select, index) {
        const newInput = document.getElementById(`newDonorName${index}`);
        if (select.value === "new") {
            newInput.style.display = 'block';
            newInput.required = true;
            newInput.value = '';
        } else {
            newInput.style.display = 'none';
            newInput.required = false;
        }
    }

    function validateForm() {
        let isValid = true;

        document.querySelectorAll('input[name="newName[]"]').forEach(input => {
            if (input.style.display === 'block' && !input.value.trim()) {
                isValid = false;
                input.setCustomValidity("กรุณากรอกชื่อ-นามสกุล");
            } else {
                input.setCustomValidity("");
            }
        });

        return isValid;
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            swal("กรุณากรอกข้อมูลให้ครบถ้วน", "", "error");
        }
    });

    function copyToClipboard(id) {
        const inputField = document.getElementById(id);

        if (!inputField) {
            swal("เกิดข้อผิดพลาด!", "ไม่พบช่องที่ต้องการคัดลอก", "error");
            return;
        }

        navigator.clipboard.writeText(inputField.value)
            .then(() => {
                Swal.fire({
                    title: "คัดลอกสำเร็จ!",
                    text: "คัดลอกหมายเลขบัญชี: " + inputField.value,
                    icon: "success",
                    confirmButtonText: "ตกลง"
                });
            })
            .catch(err => {
                Swal.fire({
                    title: "เกิดข้อผิดพลาด!",
                    text: "ไม่สามารถคัดลอกข้อความได้",
                    icon: "error",
                    confirmButtonText: "ตกลง"
                });
                console.error("Error copying text: ", err);
            });

    }

    function submitForm() {
        const fileInput = document.getElementById('evidence');
        const donorInputs = document.querySelectorAll('[id^="donorName"], [id^="newDonorName"]');

        const allInputsFilled = Array.from(donorInputs).every(input => {
            if (input.style.display !== 'none') {
                return input.value.trim() !== "";
            }
            return true;
        });

        if (fileInput.files.length === 0 || !allInputsFilled) {
            Swal.fire({
                title: "กรุณากรอกข้อมูลให้ครบ",
                text: "คุณยังไม่ได้เลือกไฟล์ หรือกรอกข้อมูลในทุกช่อง กรุณาตรวจสอบอีกครั้ง",
                icon: "warning",
                confirmButtonText: "ตกลง"
            });
            return;
        }

        Swal.fire({
            title: "กำลังประมวลผล",
            text: "กรุณารอสักครู่...",
            icon: "info",
            buttons: false,
            showConfirmButton: false
        });

        setTimeout(() => {
            document.getElementById("uploadForm").submit();
        }, 1000);

    }
</script>


</html>
