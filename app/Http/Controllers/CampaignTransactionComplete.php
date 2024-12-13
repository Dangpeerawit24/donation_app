<?php

namespace App\Http\Controllers;

use App\Models\Campaign_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignTransactionComplete extends Controller
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
            ->whereIn('status', ['ส่งภาพกองบุญแล้ว', 'รายนามเข้าระบบเรียบร้อยแล้ว'])
            ->get();

        if (Auth::user()->type === 'admin') {
            return view('admin.campaign_transaction_complete', compact('transactions', 'name', 'campaignId'));
        } elseif (Auth::user()->type === 'manager') {
            return view('manager.campaign_transaction_complete', compact('transactions', 'name', 'campaignId'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
