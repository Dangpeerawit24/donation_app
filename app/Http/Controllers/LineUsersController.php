<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineUsersController extends Controller
{
    public function index()
    {
        return view('admin.lineusers');
    }

    public function getLineUsers(Request $request)
    {
        $filter = $request->query('filter', 'month'); // ค่าเริ่มต้นคือ 'month'

        // สร้าง Query Builder สำหรับตาราง line_users
        $query = DB::table('line_users')->select('user_id', 'display_name', 'picture_url', 'created_at');

        // กรองข้อมูลตามช่วงเวลา
        if ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($filter === '3months') {
            $query->where('created_at', '>=', now()->subMonths(3));
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        // เรียงลำดับและดึงข้อมูล
        $users = $query->orderBy('created_at', 'desc')->get();

        // ส่งข้อมูลกลับเป็น JSON
        return response()->json($users);
    }
}
