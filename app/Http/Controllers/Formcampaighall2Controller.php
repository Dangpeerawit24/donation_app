<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Formcampaighall2Controller extends Controller
{
    public function index(Request $request)
    {
        $campaign_id = $request->query('campaign_id');
        if (!$campaign_id) {
            return redirect()->back()->with('error', 'ข้อมูลไม่ครบถ้วน');
        }

        // ดึงข้อมูลที่ต้องการ
        $campaigns = DB::table('campaigns')->where('id', $campaign_id)->get();
        $profile = $request->session()->get('profile'); // ข้อมูลผู้ใช้จาก Session

        $campaignData = $campaigns->map(function ($campaign) use ($profile) {
            return [
                'campaign' => $campaign,
                'profile' => $profile,
            ];
        });

        return view('formcampaighall2', compact('campaignData'));
    }

    public function fetchformcampaighalldetails(Request $request)
    {
        // ดึง Line ID จาก Session
        $lineId = session('profile.userId'); // ใช้ข้อมูลจาก Session ที่เก็บไว้หลัง Login

        if (!$lineId) {
            return response()->json(['error' => 'Line ID not found'], 400);
        }

        // Query เพื่อดึงรายละเอียดการบริจาคตาม lineId
        $details = DB::table('campaign_transactions')
            ->where('lineId', $lineId)
            ->pluck('details'); // ดึงข้อมูลคอลัมน์ details

        $allDetails = [];
        foreach ($details as $detail) {
            // แยก string ด้วย "," และลบช่องว่าง
            $detailsArray = array_map('trim', explode(',', $detail));
            $allDetails = array_merge($allDetails, $detailsArray);
        }

        // ลบค่าที่ว่างเปล่า
        $allDetails = array_filter($allDetails, function ($value) {
            return !is_null($value) && $value !== '';
        });


        // ลบค่าซ้ำและจัดรูปแบบใหม่
        $uniqueDetails = array_unique($allDetails);

        // ส่งข้อมูลในรูปแบบ JSON
        return response()->json(array_values($uniqueDetails));
    }

    public function store(Request $request)
    {
        Log::info('Form data:', $request->all());

        // Validate ข้อมูลที่ได้รับจากฟอร์ม
        $validated = $request->validate([
            'campaignsid' => 'required|integer',
            'campaignsname' => 'required|string',
            'lineId' => 'required|string',
            'lineName' => 'required|string',
            'name' => 'required|string',
            'wish' => 'required|string',
            'value' => 'required|integer|min:1',
            'transactionID' => 'required|string',
            'evidence' => 'required|file|mimes:jpeg,png,jpg|max:5048',
        ]);

        // อัปโหลดไฟล์หลักฐานการโอนเงิน
        $fileName = null;
        if ($request->hasFile('evidence')) {
            $fileName = time() . '.' . $request->evidence->extension();
            $request->evidence->move(public_path('img/evidence/'), $fileName);
        }

        // สร้างข้อมูลสำหรับ QR Code
        $qrData = env('APP_URL') . "/pushevidence?transactionID={$validated['transactionID']}";
        
        $qrFolder = public_path('img/qr-codes/');
        if (!is_dir($qrFolder)) {
            mkdir($qrFolder, 0777, true);
        }

        $qrFileName = 'qrcode_' . time() . '.png';
        $qrFilePath = $qrFolder . $qrFileName;

        // ใช้ Endroid\QrCode สร้าง QR Code
        $qrCode = new QrCode($qrData);
        $qrCode->setSize(300);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // บันทึก QR Code เป็นไฟล์
        $result->saveToFile($qrFilePath);

        // ใช้ APP_URL สำหรับเก็บ Path ของ QR Code
        $qrUrl = env('APP_URL') . '/img/qr-codes/' . $qrFileName;

        $status = $validated['respond'] === 'ไม่ส่งข้อความ' ? 'ข้อมูลของท่านเข้าระบบเรียบร้อยแล้ว' : ($validated['respond'] ?? 'รอดำเนินการ');

        // บันทึกข้อมูลลงในฐานข้อมูล
        DB::table('campaign_transactions')->insert([
            'campaignsid' => $validated['campaignsid'],
            'campaignsname' => $validated['campaignsname'],
            'lineId' => $validated['lineId'],
            'lineName' => $validated['lineName'],
            'value' => $validated['value'],
            'details2' => $validated['name'],
            'wish' => $validated['wish'],
            'evidence' => $fileName,
            'transactionID' => $validated['transactionID'],
            'qr_url' => $qrUrl, // เก็บ path ของ QR Code
            'status' => $status,
            'notify' => "1",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->sendLineMessage($validated['lineId'], $validated['lineName'], $validated['campaignsname'], $validated['respond']);

        return redirect('/line')
            ->with('success', 'บันทึกข้อมูลและสร้าง QR Code สำเร็จ!')
            ->with('lineName', $validated['lineName'])
            ->with('campaignname', $validated['campaignsname']);
    }

    private function sendLineMessage($userId, $lineName, $campaignsname, $respond)
    {
        $respond = $respond === 'ไม่ส่งข้อความ' ? 'ข้อมูลของท่านเข้าระบบเรียบร้อยแล้ว' : ($respond ?? '');
        $lineAccessToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // ดึง Access Token จาก .env
        $Text = "🙏ขออนุโมทนากับคุณ {$lineName}\n" .
            "✨ที่ร่วมกองบุญ{$campaignsname}\n" .
            "{$respond}";

        $message = [
            'to' => $userId,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $Text,
                ]
            ]
        ];

        // ส่งข้อความ
        Http::withToken($lineAccessToken)
            ->post('https://api.line.me/v2/bot/message/push', $message);
    }
}
