<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(request $request)
    {
        $profile = $request->session()->get('profile');
        // Query ดึงข้อมูล Campaign ที่เปิดให้ร่วมบุญ
        $campaigns = Campaign::select('campaigns.*', DB::raw('COALESCE(SUM(t.value), 0) as total_value'))
            ->leftJoin('campaign_transactions as t', 'campaigns.campaign_id', '=', 't.campaignsid')
            ->where('campaigns.status', 'เปิดกองบุญ')
            ->groupBy('campaigns.campaign_id')
            ->havingRaw('campaigns.stock - COALESCE(SUM(t.value), 0) >= 0')
            ->orderBy('campaigns.updated_at', 'DESC')
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('welcome', compact('campaigns', 'profile'));
    }
}
