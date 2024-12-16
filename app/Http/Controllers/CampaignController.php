<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->get();
        $Results = DB::table('campaigns')
            ->leftJoin(
                DB::raw('(SELECT campaignsid, SUM(value) as total_value 
                  FROM campaign_transactions 
                  GROUP BY campaignsid) as ct'),
                'campaigns.id',
                '=',
                'ct.campaignsid'
            )
            ->select('campaigns.*', DB::raw('IFNULL(ct.total_value, 0) as total_value'))
            ->orderByDesc('campaigns.created_at')
            ->get();

        if (Auth::user()->type === 'admin') {
            return view('admin.campaigns', compact('categories', 'Results'));
        } elseif (Auth::user()->type === 'manager') {
            return view('manager.campaigns', compact('categories', 'Results'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoriesID' => 'required|integer',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'details' => 'required',
            'status' => 'required',
            'respond' => 'required',
            'campaign_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:7048',
        ]);

        $data = $request->all();

        // อัปโหลดรูปภาพ
        if ($request->hasFile('campaign_img')) {
            $fileName = time() . '.' . $request->campaign_img->extension();
            $request->campaign_img->move(public_path('img/campaign/'), $fileName);
            $data['campaign_img'] = $fileName;
        }

        // บันทึกข้อมูล
        $campaign = Campaign::create($data);

        if ($campaign->status == "เปิดกองบุญ") {
            // ข้อความ Broadcast
            $lineToken = env('LINE_CHANNEL_ACCESS_TOKEN');
            $linkapp = env('APP_URL');
            $priceMessage = ($campaign->price == 1) ? "ตามกำลังศรัทธา" : "{$campaign->price} บาท";
            // สร้างข้อความสำหรับส่ง
            $message = "🎉 ขอเชิญร่วมกองบุญ 🎉\n" .
                "✨ {$campaign->name}\n" .
                "💰 ร่วมบุญ: {$priceMessage}\n" .
                "📋 " . $campaign->description;
            // "📋 " . str_replace(",", "\n", $campaign->description);

            $message2 = "แสดงหลักฐานการร่วมบุญ\n" .
                "💰 มูลนิธิเมตตาธรรมรัศมี\n" .
                "ธ.กสิกรไทย เลขที่บัญชี 171-1-75423-3\n" .
                "ธ.ไทยพาณิชย์ เลขที่บัญชี 649-242269-4\n\n" .
                "📌 ร่วมบุญผ่านระบบกองบุญออนไลน์ได้ที่ : https://liff.line.me/2006463554-1M9q5zzK";

            // $imageUrl = asset('img/campaign/' . $campaign->campaign_img);
            $imageUrl = "https://images.unsplash.com/photo-1720048169707-a32d6dfca0b3?w=700&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8"; 

            // ส่งคำขอไปยัง LINE OA
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $lineToken",
            ])->post('https://api.line.me/v2/bot/message/broadcast', [
                'messages' => [
                    [
                        'type' => 'image',
                        'originalContentUrl' => $imageUrl, // รูปภาพ
                        'previewImageUrl' => $imageUrl, // รูปตัวอย่าง
                    ],
                    [
                        'type' => 'text',
                        'text' => $message, // ข้อความ
                    ],
                    [
                        'type' => 'text',
                        'text' => $message2, // ข้อความ
                    ],
                ],
            ]);

            // ตรวจสอบการส่งข้อความ Broadcast
            if ($response->successful()) {
                return redirect()->back()->with('success', 'เพิ่มกองบุญและส่งข้อความ Broadcast พร้อมรูปภาพเรียบร้อยแล้ว.');
            } else {
                return redirect()->back()->with('success', 'เพิ่มกองบุญสำเร็จ แต่ส่งข้อความ Broadcast ไม่สำเร็จ.');
            }
        } else {
            return redirect()->back()->with('success', 'เพิ่มกองบุญสำเร็จ');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'categoriesID' => 'required|integer',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'details' => 'required',
            'status' => 'required',
            'campaign_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:7048',
        ]);

        $Campaign = Campaign::findOrFail($id);

        $Campaign->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'stock' => $validated['stock'],
            'details' => $validated['details'],
            'status' => $validated['status'] ?? null,
        ]);

        // อัปโหลดรูปภาพถ้ามี
        if ($request->hasFile('campaign_img')) {
            // ลบรูปภาพเก่า (ถ้ามี)
            if ($Campaign->campaign_img && file_exists(public_path('img/campaign/' . $Campaign->campaign_img))) {
                unlink(public_path('img/campaign/' . $Campaign->campaign_img));
            }

            // อัปโหลดรูปภาพใหม่
            $fileName = time() . '.' . $request->campaign_img->extension();
            $request->campaign_img->move(public_path('img/campaign/'), $fileName);

            // อัปเดตรูปภาพในฐานข้อมูล
            $Campaign->update(['campaign_img' => $fileName]);
        }

        return redirect()->back()->with('success', 'แก้ไขข้อมูลกองบุญ เรียบร้อยแล้ว.');
    }

    public function Closed($id)
    {
        $campaign = Campaign::findOrFail($id);

        $campaign->update([
            'status' => "ปิดกองบุญแล้ว"
        ]);

        // Push ข้อความไปยัง LINE OA ก่อนทำการลบ
        $lineToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // LINE Access Token
        $message = "🙏 ขออนุญาตปิดกองบุญครับ 🙏\n" .
            "🙏 ขออนุญาตปิดกองบุญครับ 🙏\n\n" .
            "กองบุญ : {$campaign->name}\n\n" .
            "ขออานิสงส์แห่งบุญนี้ส่งผลให้ทุกท่านมีชีวิตที่สว่างไสว เจริญรุ่งเรือง สมปรารถนาในสิ่งที่ตั้งใจทุกประการ";

        // ส่งคำขอไปยัง LINE API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $lineToken",
        ])->post('https://api.line.me/v2/bot/message/broadcast', [
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message, // ข้อความที่ต้องการส่ง
                ],
            ],
        ]);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'ไม่สามารถส่งข้อความแจ้งเตือนได้.');
        }

        return response()->json(['success' => true, 'message' => 'ปิดกองบุญเรียบร้อยแล้ว.']);
    }

    public function pushmessage(Request $request)
    {
        $campaign_id = $request->query('campaign_id');
        if (!$campaign_id) {
            return redirect()->back()->with('error', 'ข้อมูลไม่ครบถ้วน');
        }

        // ตรวจสอบว่ามี campaign นี้อยู่ในฐานข้อมูล
        $campaign = DB::table('campaigns')->where('id', $campaign_id)->first();
        if (!$campaign) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลกองบุญ');
        }

        // ตรวจสอบข้อมูลที่ส่งมา
        $validated = $request->validate([
            'textareaInput' => 'required',
            'campaign_imgpush' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:7048',
        ]);

        // อัปโหลดรูปภาพและบันทึกในฐานข้อมูล
        $fileName = null;
        if ($request->hasFile('campaign_imgpush')) {
            $fileName = time() . '.' . $request->campaign_imgpush->extension();
            $request->campaign_imgpush->move(public_path('img/campaignpush/'), $fileName);

            // อัปเดตในตาราง campaigns
            DB::table('campaigns')->where('id', $campaign_id)->update([
                'campaign_imgpush' => $fileName,
            ]);
        }

        // เตรียมข้อมูลสำหรับข้อความ Broadcast
        $priceMessage = $campaign->price == 1 ? 'ตามกำลังศรัทธา' : number_format($campaign->price, 2) . ' บาท';
        $description = $validated['textareaInput'] ?? '';

        $message = "{$description}\n" .
            "✨ กองบุญ{$campaign->name}\n" .
            "💰 ร่วมบุญ: {$priceMessage}\n\n" .
            "แสดงหลักฐานการร่วมบุญ\n" .
            "💰 มูลนิธิเมตตาธรรมรัศมี\n" .
            "ธ.กสิกรไทย เลขที่บัญชี 171-1-75423-3\n" .
            "ธ.ไทยพาณิชย์ เลขที่บัญชี 649-242269-4\n\n" .
            "📌 ร่วมบุญผ่านระบบกองบุญออนไลน์ได้ที่ : https://liff.line.me/2006463554-1M9q5zzK";

        // $imageUrl = $fileName
        //     ? asset('img/campaignpush/' . $fileName)
        //     : asset('img/campaign/' . $campaign->campaign_img);

        $imageUrl = "https://images.unsplash.com/photo-1720048169707-a32d6dfca0b3?w=700&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8"; 

        // ส่งข้อความ Broadcast ผ่าน LINE API
        $lineToken = env('LINE_CHANNEL_ACCESS_TOKEN');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $lineToken",
        ])->post('https://api.line.me/v2/bot/message/broadcast', [
            'messages' => [
                [
                    'type' => 'image',
                    'originalContentUrl' => $imageUrl,
                    'previewImageUrl' => $imageUrl,
                ],
                [
                    'type' => 'text',
                    'text' => $message,
                ],
            ],
        ]);

        // ตรวจสอบผลลัพธ์ของการส่งข้อความ
        if ($response->successful()) {
            return redirect()->back()->with('success', 'pushmessage เรียบร้อยแล้ว.');
        } else {
            return redirect()->back()->with('error', 'pushmessage ไม่สำเร็จ.');
        }
    }



    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        // ลบไฟล์รูปภาพหากมีอยู่
        if ($campaign->campaign_img && file_exists(public_path('img/campaign/' . $campaign->campaign_img))) {
            unlink(public_path('img/campaign/' . $campaign->campaign_img));
        }

        // ลบข้อมูลในฐานข้อมูล
        $campaign->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลกองบุญเรียบร้อยแล้ว.');
    }
}
