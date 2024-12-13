<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class LineLoginController extends Controller
{
    public function redirectToLine()
    {
        $client_id = env('LINE_CLIENT_ID');
        $redirect_uri = urlencode(env('LINE_REDIRECT_URI'));
        $state = csrf_token(); // ใช้ CSRF Token สำหรับตรวจสอบ

        $url = "https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}&state={$state}&scope=openid%20profile%20email";

        return redirect($url);
    }

    public function handleLineCallback(Request $request)
    {
        // ตรวจสอบว่ามี 'code' หรือไม่
        if (!$request->has('code')) {
            return redirect('/')->with('error', 'LINE login failed');
        }

        $code = $request->get('code');
        $client_id = env('LINE_CLIENT_ID');
        $client_secret = env('LINE_CLIENT_SECRET');
        $redirect_uri = env('LINE_REDIRECT_URI');

        // ขอ Access Token
        $response = Http::asForm()->post('https://api.line.me/oauth2/v2.1/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        if ($response->failed()) {
            return redirect('/')->with('error', 'Failed to fetch LINE access token');
        }

        $data = $response->json();
        $access_token = $data['access_token'];

        // ดึงข้อมูลโปรไฟล์ผู้ใช้
        $profileResponse = Http::withToken($access_token)->get('https://api.line.me/v2/profile');

        if ($profileResponse->failed()) {
            return redirect('/')->with('error', 'Failed to fetch LINE profile');
        }

        $profile = $profileResponse->json();

        function removeEmojis($string)
        {
            return $string ;
        }

        // ลบอิโมจิออกจากชื่อผู้ใช้
        if (isset($profile['displayName'])) {
            $profile['displayName'] = removeEmojis($profile['displayName']);
        }

        // เก็บข้อมูลโปรไฟล์ใน Session
        $request->session()->put('profile', $profile);

        // เปลี่ยนเส้นทางไปหน้า Dashboard
        return redirect()->route('welcome');
    }


    public function showDashboard(Request $request)
    {
        // ตรวจสอบว่ามีข้อมูลโปรไฟล์ใน Session หรือไม่
        if (!$request->session()->has('profile')) {
            // หากไม่มีข้อมูล ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอิน
            return redirect()->route('line.login')->with('error', 'Please log in to continue.');
        }

        // ดึงข้อมูลโปรไฟล์จาก Session
        $profile = $request->session()->get('profile');

        $campaigns = DB::table('campaigns')
            ->select('campaigns.*', DB::raw('COALESCE(sub.total_value, 0) AS total_value'))
            ->leftJoinSub(
                DB::table('campaign_transactions')
                    ->select('campaignsid', DB::raw('SUM(value) AS total_value'))
                    ->groupBy('campaignsid'),
                'sub',
                'campaigns.id',
                '=',
                'sub.campaignsid'
            )
            ->where('campaigns.status', 'เปิดกองบุญ')
            ->whereRaw('campaigns.stock - COALESCE(sub.total_value, 0) >= 0')
            ->orderByDesc('campaigns.id')
            ->get();

        // แสดงหน้า Dashboard
        return view('welcome', compact('profile', 'campaigns'));
    }
}
