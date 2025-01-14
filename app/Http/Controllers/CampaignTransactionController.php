<?php

namespace App\Http\Controllers;

use App\Models\Campaign_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Http;

class CampaignTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // รับค่า campaign_id และ name จาก Query String
        $campaignId = $request->query('campaign_id');
        $name = $request->query('name');

        // ตรวจสอบว่ามี campaign_id และ name
        if (!$campaignId || !$name) {
            return redirect()->back()->with('error', 'ข้อมูลไม่ครบถ้วน');
        }

        // ดึงข้อมูลที่ต้องการ
        $transactions = campaign_transaction::where('campaignsid', $campaignId)
            ->whereIn('status', ['รอดำเนินการ', 'แอดมินจะส่งภาพกองบุญให้ท่านได้อนุโมทนาอีกครั้ง'])
            ->get();

        if (Auth::user()->type === 'admin') {
            return view('admin.campaigns_transaction', compact('transactions', 'name', 'campaignId'));
        } elseif (Auth::user()->type === 'manager') {
            return view('manager.campaigns_transaction', compact('transactions', 'name', 'campaignId'));
        }
    }

    public function gettransactions(Request $request)
    {
        // รับค่า campaign_id และ name จาก Query String
        $campaignId = $request->query('campaign_id');

        // ดึงข้อมูลที่ต้องการ
        $transactions = campaign_transaction::where('campaignsid', $campaignId)
            ->whereIn('status', ['รอดำเนินการ', 'แอดมินจะส่งภาพกองบุญให้ท่านได้อนุโมทนาอีกครั้ง'])
            ->get();

        return response()->json($transactions);
    }

    public function noti(Request $request)
    {
        // รับค่า campaignId จาก Query String
        $campaignId = $request->query('campaign_id');

        if (!$campaignId) {
            return response()->json(['message' => 'campaign_id is required'], 400);
        }

        // ตรวจสอบว่ามี campaignId ในตาราง campaigns หรือไม่
        $campaign = DB::table('campaigns')
            ->where('id', $campaignId)
            ->first();

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        // ดึง campaignsid จากตาราง campaign_transactions ที่มี status = "รอดำเนินการ"
        $pendingTransactions = DB::table('campaign_transactions')
            ->where('campaignsid', $campaignId)
            ->where('status', 'รอดำเนินการ')
            ->get(['id', 'value']);

        // หาผลรวม value ที่ status = "รอดำเนินการ"
        $totalPendingValue = $pendingTransactions->sum('value');

        // หาผลรวม value ทั้งหมดของ campaignsid
        $totalValue = DB::table('campaign_transactions')
            ->where('campaignsid', $campaignId)
            ->sum('value');

        // ส่งข้อความผ่าน LINE API
        $groupId = env('groupId'); // ระบุ Group ID
        $lineToken = env('LINE_CHANNEL_ACCESS_TOKEN'); // รับ Token จาก .env

        $response = $this->pushFlexMessage(
            $groupId,
            $campaign->name,
            number_format($totalPendingValue, 0),
            number_format($totalValue, 0),
            $campaignId,
            $lineToken
        );

        return redirect()->back()->with('success', "ส่งแจ้งเตือนเข้ากลุ่ม เรียบร้อยแล้ว.");
    }

    protected function pushFlexMessage($groupId, $campaignname, $totalPendingValue, $totalValue, $campaignId, $lineToken)
    {
        $url = 'https://api.line.me/v2/bot/message/push';

        $flexMessage = [
            'to' => $groupId,
            'messages' => [
                [
                    'type' => 'flex',
                    'altText' => 'แจ้งเตือนกองบุญ',
                    'contents' => [
                        'type' => 'bubble',
                        'body' => [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => [
                                [
                                    'type' => 'text',
                                    'text' => 'แจ้งเตือนกองบุญ',
                                    'weight' => 'bold',
                                    'size' => 'xl',
                                    'color' => '#1DB446'
                                ],
                                [
                                    'type' => 'text',
                                    'text' => "กองบุญ: {$campaignname}",
                                    'size' => 'md',
                                    'color' => '#111111'
                                ],
                                [
                                    'type' => 'text',
                                    'text' => "ยอดใหม่: {$totalPendingValue}",
                                    'size' => 'md',
                                    'color' => '#111111'
                                ],
                                [
                                    'type' => 'text',
                                    'text' => "ยอดรวม: {$totalValue}",
                                    'size' => 'md',
                                    'color' => '#111111'
                                ]
                            ]
                        ],
                        'footer' => [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => [
                                [
                                    'type' => 'button',
                                    'style' => 'link',
                                    'action' => [
                                        'type' => 'uri',
                                        'label' => 'ดูรายละเอียด',
                                        'uri' => "https://donation.kuanimtungpichai.com/admin/campaigns_transaction?campaign_id={$campaignId}&name=" . urlencode($campaignname)
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization' => "Bearer $lineToken",
        ];

        $response = Http::withHeaders($headers)->post($url, $flexMessage);

        return $response->json();
    }



    /**
     * Show the form for creating a new resource.
     */
    public function success(Request $request)
    {
        // รับค่า campaignId จาก Query String
        $campaignId = $request->query('campaign_id');

        if (!$campaignId) {
            return response()->json(['message' => 'campaign_id is required'], 400);
        }

        // ค้นหาและอัปเดตข้อมูลในตาราง campaign_transactions
        $updatedRows = DB::table('campaign_transactions')
            ->where('campaignsid', $campaignId) // เช็คว่า campaignsid ตรงกับ campaignId
            ->whereIn('form', ['IB', 'P']) // form ต้องเป็น IB, หรือ P
            ->update(['status' => 'ส่งภาพกองบุญแล้ว']); // อัปเดต status

        // ตรวจสอบผลลัพธ์การอัปเดต
        if ($updatedRows > 0) {
            return redirect()->back()->with('success', "Updated $updatedRows เรียบร้อยแล้ว.");
        } else {
            return redirect()->back()->with('success', "Updated $updatedRows เรียบร้อยแล้ว.");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|integer',
            'transactionID' => 'required',
            'details' => 'required',
            'wish',
            'lineName' => 'required',
            'form' => 'required',
            'status' => 'required',
            'campaignsid' => 'required',
        ]);

        $lineName = $request['lineName'];

        // ค้นหา user_id จากตาราง line_users
        $lineId = DB::table('line_users')
            ->where('display_name', $lineName)
            ->orderBy('created_at', 'desc')
            ->value('user_id'); // ถ้าไม่มี user_id จะได้ null

        // ตรวจสอบว่ามี user_id หรือไม่
        $qrUrl = null; // กำหนดค่าเริ่มต้นของ QR Code URL เป็น null
        if ($lineId) {
            // ถ้ามี user_id ให้สร้าง QR Code
            $qrData = env('APP_URL') . "/pushevidence?transactionID={$request['transactionID']}";

            $qrFolder = public_path('img/qr-codes/');
            if (!is_dir($qrFolder)) {
                mkdir($qrFolder, 0777, true);
            }

            $qrFileName = 'qrcode_' . time() . '_' . uniqid() . '.png';
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
        }

        // บันทึกข้อมูลลงในตาราง campaign_transactions
        DB::table('campaign_transactions')->insert([
            'campaignsid' => $request['campaignsid'],
            'campaignsname' => $request['campaignsname'],
            'lineId' => $lineId ?? $lineName, // ถ้าไม่มี user_id ให้ใช้ lineName แทน
            'lineName' => $request['lineName'],
            'value' => $request['value'],
            'details2' => $request['details'],
            'wish' => $request['wish'] ?? null,
            'form' => $request['form'],
            'transactionID' => $request['transactionID'],
            'qr_url' => $qrUrl, // เก็บ path ของ QR Code (null ถ้าไม่มี user_id)
            'status' => "รอดำเนินการ",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูล เรียบร้อยแล้ว.');
    }

    public function getPendingTransactions()
    {
        // ดึงรายการที่ status = 'รอดำเนินการ'
        $transactions = Campaign_transaction::join('campaigns', 'campaign_transactions.campaignsid', '=', 'campaigns.id')
            ->where('campaigns.status', 'เปิดกองบุญ')
            ->where('campaign_transactions.status', 'รอดำเนินการ')
            ->select(
                'campaigns.name as campaign_name',
                'campaigns.id as campaign_id',
                DB::raw('COUNT(campaign_transactions.id) as total_transactions')
            )
            ->groupBy('campaigns.name', 'campaigns.id')
            ->get();

        // ส่งกลับเป็น JSON
        return response()->json($transactions);
    }

    /**
     * Display the specified resource.
     */
    public function show(campaign_transaction $campaign_transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(campaign_transaction $campaign_transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, campaign_transaction $campaign_transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(campaign_transaction $campaign_transaction)
    {
        //
    }
}
