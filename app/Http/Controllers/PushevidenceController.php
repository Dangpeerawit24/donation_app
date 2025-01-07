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
        $validated = $request->validate([
            'transactionID' => 'required|string',
            'userid'        => 'required|string',
            'campaignname'  => 'required|string',
            'url_img.*'     => 'required|file|mimes:jpeg,png,jpg|max:10048', // รับหลายไฟล์
        ]);
    
        $campaignname   = $validated['campaignname'];
        $transactionID  = $validated['transactionID'];
        $userId         = $validated['userid'];
        
        // ตัวแปรสำหรับเก็บ URL รูปภาพ (ถ้าต้องการใช้ URL)
        $imageUrls      = [];
        // ตัวแปรสำหรับเก็บชื่อไฟล์
        $fileNames      = [];
    
        // จัดการไฟล์อัปโหลด
        if ($request->hasFile('url_img')) {
            foreach ($request->file('url_img') as $file) {
                // ตั้งชื่อไฟล์ใหม่ไม่ให้ซ้ำกัน
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                
                // ย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
                $file->move(public_path('img/pushimg/'), $fileName);
                
                // เก็บ URL รูปภาพไว้ในอาเรย์ (ถ้าคุณต้องการใช้งาน URL ต่อ)
                $imageUrls[] = asset('img/pushimg/' . $fileName);
    
                // เก็บ "ชื่อไฟล์" ไว้ในอาเรย์
                $fileNames[] = $fileName;
            }
        }
    
        // แปลงอาเรย์ชื่อไฟล์ให้เป็น string โดยคั่นด้วยเครื่องหมาย ,
        $fileNamesString = implode(',', $fileNames);
    
        // อัปเดตข้อมูลในตาราง โดยเก็บชื่อไฟล์ไว้ในคอลัมน์ url_img
        $updated = DB::table('campaign_transactions')
            ->where('transactionID', $transactionID)
            ->update([
                'status'    => "ส่งภาพกองบุญแล้ว",
                'url_img'   => $fileNamesString, // เก็บชื่อไฟล์คั่นด้วย ,
                'updated_at'=> now(),
            ]);

        if ($updated) {
            // ส่งรูปภาพทั้งหมดก่อน
            foreach ($imageUrls as $imageUrl) {
                $this->sendPushImage($userId, $imageUrl);
            }

            // หลังส่งรูปภาพทั้งหมด ส่งข้อความตามไป
            $message = "ภาพจากกองบุญ$campaignname\nขออนุโมทนาครับ🙏";
            $this->sendPushText($userId, $message);

            return redirect()->back()->with('success', 'ส่งภาพกองบุญเรียบร้อยแล้ว!');
        }

        return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ต้องการอัปเดต');
    }

    // ฟังก์ชันสำหรับส่งรูปภาพ
    private function sendPushImage($userId, $imageUrl)
    {
        $channelAccessToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // Channel Access Token

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to' => $userId,
            'messages' => [
                [
                    'type' => 'image', // รูปภาพ
                    'originalContentUrl' => $imageUrl,
                    'previewImageUrl' => $imageUrl,
                ],
            ],
        ]);
    }

    // ฟังก์ชันสำหรับส่งข้อความ
    private function sendPushText($userId, $message)
    {
        $channelAccessToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // Channel Access Token

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to' => $userId,
            'messages' => [
                [
                    'type' => 'text', // ข้อความ
                    'text' => $message,
                ],
            ],
        ]);
    }
}
