<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignstatusController extends Controller
{
    public function campaignstatus(Request $request)
    {
        // ตรวจสอบว่ามี session 'profile' หรือไม่
        $userId = $request->query('userId');

        // ดึงข้อมูลจากฐานข้อมูลที่เกี่ยวข้องกับ userid
        $Datas = DB::table('campaign_transactions')
            ->where('lineId', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('campaignstatus', compact('Datas'));
    }
}
