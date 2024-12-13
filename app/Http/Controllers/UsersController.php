<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->type === 'superadmin') {
            $Users = User::all();
        } else if (Auth::user()->type === 'admin') {
            if (Auth::user()->email === 'dang.peerawit24@gmail.com') {
                $Users = User::orderByRaw("CASE WHEN email = ? THEN 0 ELSE 1 END", [Auth::user()->email])
                    ->get();
            } else {
                $Users = User::where('email', '!=', 'dang.peerawit24@gmail.com')->orderByRaw("CASE WHEN email = ? THEN 0 ELSE 1 END", [Auth::user()->email])->get();
            }
        } else {
            $Users = User::whereNotIn('type', [1, 3])
                ->where(function ($query) {
                    $query->where('type', '!=', 2)
                        ->orWhere('email', '=', Auth::user()->email);
                })
                ->orderByRaw("CASE WHEN email = ? THEN 0 ELSE 1 END", [Auth::user()->email]) // จัดลำดับ email ที่ตรงกับตัวเองก่อน
                ->get();
        }

        if (Auth::user()->type === 'admin') {
            return view('admin.users', compact('Users'));
        } elseif (Auth::user()->type === 'manager') {
            return view('manager.users', compact('Users'));
        }
        return view('home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'type' => 'required',
        ]);

        User::create($request->all());
        return redirect()->back()->with('success', 'เพิ่มข้อมูล สมาชิก เรียบร้อยแล้ว.');
    }

    public function update(Request $request, $id)
    {
        // Validation rules
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id, // ไม่ซ้ำกับผู้ใช้อื่น แต่ยอมให้ซ้ำกับตัวเอง
            'password' => 'nullable|min:6', // กำหนดให้ password เป็น null ได้
            'type' => 'required',
        ]);

        // ดึงข้อมูล User จากฐานข้อมูล
        $User = User::findOrFail($id);

        // เตรียมข้อมูลสำหรับการอัปเดต
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'type' => $validated['type'],
        ];

        // ถ้ามีการกรอก password จะอัปเดต
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        // อัปเดตข้อมูล User
        $User->update($updateData);

        return redirect()->back()->with('success', 'อัพเดตข้อมูลสมาชิกเรียบร้อยแล้ว.');
    }

    public function destroy($id)
    {
        try {
            $User = User::findOrFail($id); // ค้นหาสินค้าโดย ID

            $User->delete(); // ลบสินค้า

            // คืนค่าการแจ้งเตือนหรือกลับไปยังหน้าก่อนหน้า
            return redirect()->back()->with('success', 'ลบข้อมูล สมาชิก เรียบร้อยแล้ว.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete User.');
        }
    }
}
