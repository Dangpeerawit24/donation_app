<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('super-admin.users', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create($request->all());
        return redirect()->back()->with('success', 'เพิ่มข้อมูล สมาชิก เรียบร้อยแล้ว.');
    }

    public function edit(User $user)
    {
        return view('user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        $data = $request->all();
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->back()->with('success', 'อัพเดตข้อมูล สมาชิก เรียบร้อยแล้ว.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว.');
    }
}
