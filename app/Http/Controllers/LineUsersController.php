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
        $query = DB::table('line_users')
            ->select('user_id', 'display_name', 'picture_url', 'created_at')
            ->whereIn('id', function ($subQuery) use ($filter) {
                $subQuery->select(DB::raw('MAX(id)'))
                    ->from('line_users')
                    ->groupBy('user_id')
                    ->when($filter === 'month', function ($query) {
                        $query->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year);
                    })
                    ->when($filter === '3months', function ($query) {
                        $query->where('created_at', '>=', now()->subMonths(3));
                    })
                    ->when($filter === 'year', function ($query) {
                        $query->where('created_at', '>=', now()->subYear());
                    });
            });

        // เรียงลำดับและดึงข้อมูล
        $users = $query->orderBy('created_at', 'desc')->get();

        // ส่งข้อมูลกลับเป็น JSON
        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        // ตรวจสอบและ validate ข้อมูล
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        // อัปเดตเฉพาะแถวล่าสุดที่ตรงกับ user_id
        $latestRowId = DB::table('line_users')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc') // เลือกแถวล่าสุดตาม created_at
            ->value('id'); // ดึงค่า id ของแถวล่าสุด

        if ($latestRowId) {
            $updated = DB::table('line_users')
                ->where('id', $latestRowId) // อัปเดตเฉพาะแถวที่ตรงกับ id
                ->update([
                    'display_name' => $validated['display_name'],
                    'updated_at' => now(), // อัปเดต timestamp
                ]);

            // ตรวจสอบว่าการอัปเดตสำเร็จหรือไม่
            if ($updated) {
                return redirect()->back()->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว.');
            }
        }

        return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ต้องการแก้ไข.');
    }
}
