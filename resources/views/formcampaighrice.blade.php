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
                style="color: var(--bs-body-bg);font-size: 20.88px;margin: 8px;">
                <img width="40" height="40" src="{{ asset('img/AdminLogo.png') }}">
                ศาลพระโพธิสัตว์กวนอิมทุ่งพิชัย
            </h1>
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
                    <!-- เพิ่ม id="uploadForm" เพื่อใช้ submit ภายใน JS ได้สะดวก -->
                    <form id="uploadForm" action="{{ Route('formcampaighrice.store') }}" method="POST" enctype="multipart/form-data">
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

                        <!-- ส่วนแสดง Input -->
                        <div id="donationInputs" class="input-container"></div>

                        <!-- อัพโหลดหลักฐานการโอนเงิน -->
                        <div class="d-flex justify-content-start" style="margin-top: 9px;">
                            <h5 style="color: var(--bs-emphasis-color);font-weight: bold;">แนบหลักฐานการโอนเงิน</h5>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" style="margin-top: 2px;">
                            <input class="form-control" type="file" id="evidence" name="evidence" accept="image/*" required>
                        </div>

                        <!-- Hidden fields ที่จำเป็นต่อหลังบ้าน -->
                        <input type="hidden" id="campaignsid" name="campaignsid" value="{{ $data['campaign']->id }}">
                        <input type="hidden" id="respond" name="respond" value="{{ $data['campaign']->respond }}">
                        <input type="hidden" id="campaignsname" name="campaignsname" value="{{ $data['campaign']->name }}">
                        <input type="hidden" name="lineId" value="{{ $data['profile']['userId'] }}">
                        <input type="hidden" name="lineName" value="{{ $data['profile']['displayName'] }}">
                        <input type="hidden" name="transactionID" value="TX-{{ now()->timestamp }}-{{ rand(1000, 9999) }}">

                        <div class="d-flex justify-content-center align-items-center" style="margin-top: 12px;">
                            <button class="btn btn-primary" type="button" onclick="submitForm()">ยืนยันส่งข้อมูล</button>
                        </div>
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

                <!-- ข้อมูลการชำระ/QR -->
                <div style="margin-top: 8px;">
                    <h4 style="color: var(--bs-body-color);font-weight: bold;">รายละเอียดการโอนเงิน</h4>
                </div>
                <div style="text-align: center; text-align: -webkit-center; margin-top: 10px;">
                    <img id="qr" src="https://promptpay.io/0993000067720.png" width="150px" height="150px"
                        alt="" style="display: none;">
                </div>
                <div style="text-align: center;">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">💰มูลนิธิเมตตาธรรมรัศมี</h5>
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">ธนาคารกสิการไทย</h5>
                </div>
                <div>
                    <div class="row d-flex justify-content-center align-items-center" style="margin-right: -12px;margin-top: 5px;">
                        <div class="col-8 d-flex justify-content-end justify-content-xl-center align-items-xl-center">
                            <input class="form-control" type="text" id="accountNumber1"
                                placeholder="171-1-75423-3" value="171-1-75423-3" style="text-align: center;" readonly>
                            <button class="btn btn-secondary" onclick="copyToClipboard('accountNumber1')" style="margin-left: 10px;">คัดลอก</button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center ; margin-top: 5px;">----- หรือ -----</h5>
                </div>

                <div style="text-align: center;">
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">💰มูลนิธิเมตตาธรรมรัศมี</h5>
                    <h5 style="color: var(--bs-emphasis-color);text-align: center;">ธนาคารไทยพาณิชย์</h5>
                </div>
                <div>
                    <div class="row d-flex justify-content-center align-items-center" style="margin-right: -12px;margin-top: 5px;">
                        <div class="col-8 d-flex justify-content-end justify-content-xl-center align-items-xl-center">
                            <input class="form-control" type="text" id="accountNumber2"
                                placeholder="649-242269-4" value="649-242269-4" style="text-align: center;" readonly>
                            <button class="btn btn-secondary" onclick="copyToClipboard('accountNumber2')" style="margin-left: 10px;">คัดลอก</button>
                        </div>
                    </div>
                </div>
                <!-- จบ card-body -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let cachedDetails = null;
        const pricePerUnit = {{ $campaignData[0]['campaign']->price ?? 0 }};
        
        // เอาไว้เก็บค่าที่ผู้ใช้เลือก/กรอก (ชื่อนามสกุล) ครั้งแรก
        let selectedNameValue = "";
        let isNewName = false; // เช็คว่ากรอกเป็น "newName" ไหม

        // ดึงข้อมูลชื่อที่เคยร่วมบุญจากหลังบ้าน
        window.onload = function() {
            fetch('/fetch_formcampaigh_details')
                .then(response => response.json())
                .then(details => {
                    cachedDetails = details;
                    console.log('ข้อมูลที่ดึงมา:', cachedDetails);
                })
                .catch(error => console.error('Error fetching details:', error));
        };

        // ฟังก์ชันสร้างอินพุต “ช่องเลือก/กรอกชื่อ” สำหรับ index=0 เท่านั้น
        function createMainInputField() {
            const inputDiv = document.createElement('div');
            inputDiv.className = 'input-container';

            let options = `<select name="name[]" id="donorName0" onchange="checkNewEntry(this)" 
                                style="width: 100%; text-align: center; height: 45.4286px;" required>
                                <option value="">--กดเลือกรายนามที่เคยร่วมบุญ--</option>`;

            cachedDetails.forEach(detail => {
                options += `<option value="${detail}">${detail}</option>`;
            });

            options += `<option value="new">เพิ่มรายการใหม่</option></select>`;

            const newInput = `<input type="text" name="newName[]" id="newDonorName0"
                                style="width: 100%; text-align: center; height: 45.4286px; display: none;" 
                                placeholder="ชื่อ-นามสกุล" required>`;

            inputDiv.innerHTML = `
                <label for="donorName0">กรอกชื่อ - สกุล</label>
                ${options}
                ${newInput}
            `;

            document.getElementById('donationInputs').appendChild(inputDiv);

            // เมื่อมีการเปลี่ยนแปลงช่อง newDonorName0 ให้เซ็ตค่าลง selectedNameValue
            document.getElementById('newDonorName0').addEventListener('input', () => {
                selectedNameValue = document.getElementById('newDonorName0').value.trim();
                isNewName = true;
                applyValueToHiddenFields();
            });
        }

        // ฟังก์ชันสร้าง hidden input สำหรับชุด 2,3,...,count
        // โดยจะเก็บ value เดียวกับที่เลือกหรือกรอกจากชุดแรกเสมอ
        function createHiddenInputFields(index) {
            const inputDiv = document.createElement('div');
            inputDiv.className = 'input-container';

            // สร้าง name[] กับ newName[] ให้ครบ ตามโครงสร้างหลังบ้าน
            // แต่เป็น hidden เพื่อให้ข้อมูลเป็นค่าเดียวกันกับชุดแรก
            inputDiv.innerHTML = `
                <input type="hidden" name="name[]" id="donorNameHidden${index}">
                <input type="hidden" name="newName[]" id="newDonorNameHidden${index}">
            `;

            document.getElementById('donationInputs').appendChild(inputDiv);
        }

        // เรียกทุกครั้งที่เปลี่ยนตัวเลข ‘จำนวนกองบุญ’
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

            // เคลียร์ช่องก่อนสร้างใหม่
            const donationInputsContainer = document.getElementById('donationInputs');
            donationInputsContainer.innerHTML = '';
            
            const qrImage = document.getElementById('qr');

            // ถ้ามีจำนวนกองบุญ
            if (count > 0) {
                // คำนวนยอดรวม
                const totalAmount = count * pricePerUnit;
                document.getElementById('totalAmountDisplay').innerText = totalAmount.toFixed(2) + " บาท";
                
                // อัปเดต QR PromptPay
                qrImage.src = `https://promptpay.io/0993000067720/${totalAmount}`;
                qrImage.style.display = 'block';

                // สร้าง input หลัก 1 ช่องสำหรับชุดที่ 1
                if (cachedDetails) {
                    createMainInputField();
                } else {
                    console.error('ยังไม่มีข้อมูลใน cachedDetails');
                }

                // สร้าง hidden input สำหรับชุดที่ 2 ถึงชุดที่ count
                for (let i = 1; i < count; i++) {
                    createHiddenInputFields(i);
                }

            } else {
                document.getElementById('totalAmountDisplay').innerText = "0.00 บาท";
                qrImage.style.display = 'none';
            }
        }

        // เรียกเมื่อมีการเปลี่ยนค่าใน select (donorName0)
        function checkNewEntry(select) {
            const newInput = document.getElementById('newDonorName0');
            
            if (select.value === "new") {
                // ถ้าเลือก "new" ให้แสดง input text ใหม่
                newInput.style.display = 'block';
                newInput.required = true;
                newInput.value = "";
                // เคลียร์ค่า selectedNameValue
                selectedNameValue = "";
                isNewName = true;
            } else {
                // ถ้าเลือกชื่อที่มีอยู่แล้ว
                newInput.style.display = 'none';
                newInput.required = false;
                newInput.value = "";

                selectedNameValue = select.value;
                isNewName = false;

                // อัปเดต hidden fields
                applyValueToHiddenFields();
            }
        }

        // ฟังก์ชันใช้เซ็ตค่าลง hidden fields ทั้งหมด
        function applyValueToHiddenFields() {
            // ดึงจำนวนกองบุญ
            const countInput = document.getElementById('donationCount');
            let count = parseInt(countInput.value, 10);
            if (isNaN(count) || count < 1) return;

            // วนเซ็ตค่าให้ hidden input ของชุดที่ 2..count
            for (let i = 1; i < count; i++) {
                const donorNameHidden = document.getElementById(`donorNameHidden${i}`);
                const newDonorNameHidden = document.getElementById(`newDonorNameHidden${i}`);

                if (!donorNameHidden || !newDonorNameHidden) continue;

                if (isNewName) {
                    // ถ้าผู้ใช้กรอกชื่อใหม่
                    donorNameHidden.value = "new";       // name[] เป็นค่าว่าง
                    newDonorNameHidden.value = selectedNameValue; // newName[] เป็นค่าที่กรอก
                } else {
                    // ถ้าเลือกชื่อจากรายการเก่า
                    donorNameHidden.value = selectedNameValue;    // name[] เป็นค่าที่เลือก
                    newDonorNameHidden.value = "";                // newName[] เป็นค่าว่าง
                }
            }
        }

        // ปุ่ม submit
        function submitForm() {
            const fileInput = document.getElementById('evidence');

            // เช็คว่ามีไฟล์หรือไม่
            if (fileInput.files.length === 0) {
                Swal.fire({
                    title: "กรุณากรอกข้อมูลให้ครบ",
                    text: "คุณยังไม่ได้เลือกไฟล์แนบหลักฐานการโอนเงิน กรุณาตรวจสอบอีกครั้ง",
                    icon: "warning",
                    confirmButtonText: "ตกลง"
                });
                return;
            }

            // เช็คช่องกรอก/เลือกชื่อ (เฉพาะชุดที่ 1)
            const donorNameSelect = document.getElementById('donorName0');
            const newDonorNameInput = document.getElementById('newDonorName0');

            // กรณีเลือก new แต่ยังไม่ได้กรอก
            if (donorNameSelect.value === "new" && !newDonorNameInput.value.trim()) {
                Swal.fire({
                    title: "กรุณากรอกชื่อ-นามสกุล",
                    text: "คุณเลือก 'เพิ่มรายการใหม่' แต่ยังไม่ได้กรอกชื่อ",
                    icon: "warning",
                    confirmButtonText: "ตกลง"
                });
                return;
            }

            // กรณีไม่ได้เลือกอะไรจาก select
            if (!donorNameSelect.value) {
                Swal.fire({
                    title: "กรุณากรอกข้อมูลให้ครบ",
                    text: "กรุณาเลือกรายชื่อ หรือสร้างชื่อใหม่",
                    icon: "warning",
                    confirmButtonText: "ตกลง"
                });
                return;
            }

            // ผ่านเงื่อนไขทั้งหมดแล้ว
            // เซ็ตค่าซ้ำให้ hidden fields อีกรอบกันลืม
            applyValueToHiddenFields();

            Swal.fire({
                title: "กำลังประมวลผล",
                text: "กรุณารอสักครู่...",
                icon: "info",
                showConfirmButton: false,
                allowOutsideClick: false
            });

            setTimeout(() => {
                document.getElementById("uploadForm").submit();
            }, 800);
        }

        // ปุ่มคัดลอกเลขบัญชี
        function copyToClipboard(id) {
            const inputField = document.getElementById(id);

            if (!inputField) {
                Swal.fire({
                    title: "เกิดข้อผิดพลาด!",
                    text: "ไม่พบช่องที่ต้องการคัดลอก",
                    icon: "error",
                    confirmButtonText: "ตกลง"
                });
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
    </script>
</body>
</html>
