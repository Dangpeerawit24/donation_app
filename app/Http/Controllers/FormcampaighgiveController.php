<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FormcampaighgiveController extends Controller
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

        return view('formcampaighgive', compact('campaignData'));
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
            'transactionID' => 'required|string',
            'evidence' => 'required|file|mimes:jpeg,png,jpg|max:5048',
        ]);

        // อัปโหลดไฟล์หลักฐานการโอนเงิน
        $fileName = null;
        if ($request->hasFile('evidence')) {
            $fileName = time() . '_' . uniqid() . '.' . $request->evidence->extension();
            $request->evidence->move(public_path('img/evidence/'), $fileName);
        }

        // บันทึกข้อมูลลงในฐานข้อมูล
        DB::table('campaign_transactions')->insert([
            'campaignsid' => $validated['campaignsid'],
            'campaignsname' => $validated['campaignsname'],
            'lineId' => $validated['lineId'],
            'lineName' => $validated['lineName'],
            'details' => $validated['name'],
            'evidence' => $fileName,
            'transactionID' => $validated['transactionID'],
            'value' => "0",
            'status' => "ข้อมูลของท่านเข้าระบบเรียบร้อยแล้ว",
            'notify' => "1",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->sendLineMessage($validated['lineId'], $validated['lineName'], $validated['campaignsname']);

        return redirect('/line')
            ->with('success', 'บันทึกข้อมูลและสร้าง QR Code สำเร็จ!')
            ->with('lineName', $validated['lineName'])
            ->with('campaignname', $validated['campaignsname']);
    }

    private function sendLineMessage($userId, $lineName, $campaignsname)
    {
        $lineAccessToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // ดึง Access Token จาก .env
        $Text = "🙏ขอขอบคุณ {$lineName}\n" .
            "✨ที่ได้ร่วมกิจกรรม {$campaignsname}\n" .
            "ข้อมูลของท่านเข้าระบบเรียบร้อยแล้ว\n";

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
