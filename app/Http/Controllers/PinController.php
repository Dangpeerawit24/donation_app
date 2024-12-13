<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PinController extends Controller
{
    private $correctPin;

    public function __construct()
    {
        $this->correctPin = env('PIN_PUSH', '1234'); // ดึงค่า PIN จาก .env
    }

    public function showForm(Request $request)
{
    // รับ Query Parameters
    $queryParams = $request->query();

    // Debug เพื่อดู Query Parameters
    if (empty($queryParams)) {
        dd("ไม่มี Query Parameters", $queryParams);
    }

    // ส่งค่าไปยัง View
    return view('pin', compact('queryParams'));
}


public function verifyPin(Request $request)
{
    $request->validate([
        'pin' => 'required|digits:4',
    ]);

    // Debug ดูค่าที่ส่งมา
    // dd($request->all());

    if ($request->pin === $this->correctPin) {
        session(['authenticated_pin' => true]);

        // ดึง Query Parameters ยกเว้น `_token` และ `pin`
        $queryParams = $request->except(['_token', 'pin']);

        if (empty($queryParams)) {
            return redirect()->route('pin.form')->with('error', 'ไม่มีข้อมูลที่ต้องส่งต่อ');
        }

        return redirect()->route('pushevidence.index', $queryParams);
                        //  ->with('success', 'ยืนยันรหัส PIN สำเร็จ');
    }

    return redirect()->back()->with('error', 'รหัส PIN ไม่ถูกต้อง');
}


}
