<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Create a new controller instance.
     *
     * @return RedirectResponse
     */
    public function login(Request $request)
    {
        $input = $request->all();

        // ตรวจสอบ Validation
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // หากผู้ใช้งานล็อกอินอยู่แล้ว
        if (Auth::check() && Auth::user()->type) {
            return redirect()->route(Auth::user()->type . '.dashboard'); // เรียกใช้ Route ตาม type
        }

        // ตรวจสอบข้อมูลการ Login
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            // กำหนดเส้นทางตามประเภทผู้ใช้งาน
            if (Auth::user()->type == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->type == 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif (Auth::user()->type == 'user') {
                return redirect()->route('user.dashboard');
            } else {
                // หากไม่มีประเภทผู้ใช้ที่รองรับ
                Auth::logout();
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }
        } else {
            // ข้อมูลการ Login ไม่ถูกต้อง
            return redirect()->route('login')
                ->with('error', 'Email or Password is incorrect.');
        }
    }


    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->type) {
            return route(Auth::user()->type . '.dashboard'); // เรียกใช้ Route ตาม type
        }

        return '/login'; // กรณีไม่มี type หรือไม่ได้ Login
    }
}
