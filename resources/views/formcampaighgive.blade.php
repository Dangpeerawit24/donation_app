<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบกองบุญออนไลน์</title>
    <link rel="icon" type="" href="{{ asset('img/AdminLogo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
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
                    <form action="{{ Route('formcampaighgive.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 style="color: var(--bs-body-color);font-weight: bold;">กรอกข้อมูลผู้ร่วมกิจกรรม</h4>
                        </div>
                        <div>
                            <h5 style="color: var(--bs-emphasis-color);">กรุณากรอก ชื่อ-นามสกุล ผู้ร่วมกิจกรรม</h5>
                        </div>
                        <div>
                            <input style="width: 100%; text-align: center; height: 45.4286px;" type="text" name="name" id="name" required placeholder="กรุณากรอก ชื่อ-นามสกุล ผู้ร่วมกิจกรรม">
                        </div>
                        <div id="donationInputs" class="input-container"></div>
                        <div class="d-flex justify-content-start" style="margin-top: 9px;">
                            <h5 style="color: var(--bs-emphasis-color);font-weight: bold;">แนบหลักฐานสลิปที่เคยร่วมบุญ</h5>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" style="margin-top: 2px;"><input
                                class="form-control" type="file" name="evidence" required></div>
                        <input type="hidden" id="campaignsid" name="campaignsid" value="{{ $data['campaign']->id }}">
                        <input type="hidden" id="campaignsname" name="campaignsname"
                            value="{{ $data['campaign']->name }}">
                        <input type="hidden" name="lineId" value="{{ $data['profile']['userId'] }}">
                        <input type="hidden" id="respond" name="respond" value="{{ $data['campaign']->respond }}">
                        <input type="hidden" name="lineName" value="{{ $data['profile']['displayName'] }}">
                        <input type="hidden" name="transactionID"
                            value="TX-{{ now()->timestamp }}-{{ rand(1000, 9999) }}">
                        <div class="d-flex justify-content-center align-items-center"
                            style="margin-top: 12px;margin-bottom: px;"><button class="btn btn-primary"
                                type="submit">ยืนยันส่งข้อมูล</button></div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
