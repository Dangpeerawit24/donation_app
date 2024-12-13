<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesdetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // รับค่า campaign_id และ name จาก Query String
        $categoriesID = $request->query('categoriesID');
        $name = $request->query('name');

        // ตรวจสอบว่ามี campaign_id และ name
        if (!$categoriesID || !$name) {
            return redirect()->back()->with('error', 'ข้อมูลไม่ครบถ้วน');
        }

        // ดึงข้อมูลที่ต้องการ
        $categoriesdetails = DB::table('campaigns as c')
            ->select(
                'c.id',
                'c.name',
                'c.categoriesID',
                'c.price',
                DB::raw('SUM(ct.value) AS total_value'),
                DB::raw('SUM(ct.value * c.price) AS total_value_price')
            )
            ->leftJoin('campaign_transactions as ct', 'c.id', '=', 'ct.campaignsid')
            ->where('c.categoriesID', $categoriesID)
            ->groupBy('c.id', 'c.name', 'c.categoriesID', 'c.price') // ระบุคอลัมน์ทั้งหมดที่ไม่ใช่ Aggregate
            ->get();

        if (Auth::user()->type === 'admin') {
            return view('admin.categoriesdetails', compact('categoriesdetails', 'name'));
        } elseif (Auth::user()->type === 'manager') {
            return view('manager.categoriesdetails', compact('categoriesdetails', 'name'));
        }
    }
}
