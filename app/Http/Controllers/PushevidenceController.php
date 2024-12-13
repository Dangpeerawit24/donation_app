<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PushevidenceController extends Controller
{
    public function index(Request $request)
    {
        // รับค่าจาก Query String
        $transactionID = $request->query('transactionID');

        if (!$transactionID) {
            return redirect()->back()->with('error', 'ข้อมูลไม่ครบถ้วน');
        }

        if (!$request->session()->has('authenticated_pin')) {
            // เก็บ Query Parameters ทั้งหมด
            $queryParams = $request->query();

            // Redirect พร้อมส่ง Query Parameters กลับไป
            return redirect()->route('pin.form', $queryParams)
                ->with('error', 'กรุณาใส่รหัส PIN ก่อนเข้าใช้งาน');
        }


        $namess = DB::table('campaign_transactions')
            ->where('transactionID', $transactionID)
            ->get();

        return view('pushevidence', [
            'names' => $namess,
            'transactionID' => $transactionID,
        ]);
    }

    public function index2(Request $request)
    {
        // รับค่าจาก Query String
        $transactionID = $request->query('transactionID');

        if (!$transactionID) {
            return redirect()->back()->with('error', 'ข้อมูลไม่ครบถ้วน');
        }

        $namess = DB::table('campaign_transactions')
            ->where('transactionID', $transactionID)
            ->get();

        return view('pushevidence2', [
            'names' => $namess,
            'transactionID' => $transactionID,
        ]);
    }

    public function pushevidencetouser(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูล
        $validated = $request->validate([
            'transactionID' => 'required|string',
            'userid' => 'required|string',
            'campaignname' => 'required|string',
            'url_img' => 'required|file|mimes:jpeg,png,jpg|max:7048',
        ]);

        // จัดการไฟล์อัปโหลด
        $fileName = null;
        if ($request->hasFile('url_img')) {
            $fileName = time() . '.' . $request->url_img->extension(); // ตั้งชื่อไฟล์ใหม่
            $request->url_img->move(public_path('img/pushimg/'), $fileName); // ย้ายไฟล์ไปยังโฟลเดอร์
        }

        // URL ของรูปภาพที่อัปโหลด
        $imageUrl = asset('img/pushimg/' . $fileName);
        $campaignname = $validated['campaignname'];
        // อัปเดตข้อมูลในตาราง campaign_transactions
        $updated = DB::table('campaign_transactions')
            ->where('transactionID', $validated['transactionID'])
            ->update([
                'status' => "ส่งภาพกองบุญแล้ว",
                'url_img' => $imageUrl,
                'updated_at' => now(),
            ]);

        if ($updated) {
            // ข้อความที่ต้องการส่ง
            $message =  "ภาพจากกองบุญ\n" .
                "✨ $campaignname\n" .
                "ขอนุโมทนาครับ🙏";

            // ส่งข้อความและรูปภาพ
            $this->sendPushMessage($validated['userid'], $message, $imageUrl);

            return redirect()->back()->with('success', 'ส่งภาพกองบุญเรียบร้อยแล้ว!');
        }

        return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ต้องการอัปเดต');
    }

    public function pushevidencetouser2(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูล
        $validated = $request->validate([
            'transactionID' => 'required|string',
            'userid' => 'required|string',
            'campaignname' => 'required|string',
            'campaignsid' => 'required|integer', // เพิ่มการตรวจสอบ campaignsid
            'url_img' => 'required|file|mimes:jpeg,png,jpg|max:7048',
        ]);

        // จัดการไฟล์อัปโหลด
        $fileName = null;
        if ($request->hasFile('url_img')) {
            $fileName = time() . '.' . $request->url_img->extension(); // ตั้งชื่อไฟล์ใหม่
            $request->url_img->move(public_path('img/pushimg/'), $fileName); // ย้ายไฟล์ไปยังโฟลเดอร์
        }

        // URL ของรูปภาพที่อัปโหลด
        $imageUrl = asset('img/pushimg/' . $fileName);
        $campaignname = $validated['campaignname'];

        // อัปเดตข้อมูลในตาราง campaign_transactions
        $updated = DB::table('campaign_transactions')
            ->where('transactionID', $validated['transactionID'])
            ->update([
                'status' => "ส่งภาพกองบุญแล้ว",
                'url_img' => $imageUrl,
                'updated_at' => now(),
            ]);

        if ($updated) {
            // ข้อความที่ต้องการส่ง
            $message =  "ภาพจากกองบุญ\n" .
                "✨ $campaignname\n" .
                "ขอนุโมทนาครับ🙏";

            // ส่งข้อความและรูปภาพ
            $this->sendPushMessage($validated['userid'], $message, $imageUrl);

            // Redirect ไปยังหน้า campaigns_transaction
            $type = auth()->user()->type; // ดึงประเภทของผู้ใช้
            $campaignId = $request->campaignsid;
            $campaignName = $request->campaignname;

            return redirect("/$type/campaigns_transaction?campaign_id=$campaignId&name=$campaignName")
                ->with('success', 'ส่งภาพกองบุญเรียบร้อยแล้ว!');
        }

        return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ต้องการอัปเดต');
    }


    // ฟังก์ชันสำหรับส่ง Push Message
    private function sendPushMessage($userId, $message, $imageUrl)
    {
        // Channel Access Token ของ LINE Messaging API
        $channelAccessToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // เก็บใน .env

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to' => $userId, // User ID ของผู้รับ
            'messages' => [
                [
                    'type' => 'image', // รูปภาพ
                    'originalContentUrl' => $imageUrl, // URL รูปภาพ
                    'previewImageUrl' => $imageUrl, // URL รูปภาพตัวอย่าง
                ],
                [
                    'type' => 'text', // ข้อความ
                    'text' => $message,
                ],
            ],
        ]);

        if (!$response->successful()) {
            // คุณสามารถบันทึก log หรือจัดการข้อผิดพลาดได้ที่นี่
            logger()->error('Failed to send push message', ['response' => $response->body()]);
        }
    }
}
