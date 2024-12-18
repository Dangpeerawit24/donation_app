<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\LineUser;

class WebhookController extends Controller
{
    /**
     * ดึงข้อมูลโปรไฟล์ผู้ใช้จาก LINE Messaging API
     */
    public function getProfile($userId)
    {
        $channelAccessToken = env('LINE_CHANNEL_ACCESS_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $channelAccessToken,
        ])->get("https://api.line.me/v2/bot/profile/{$userId}");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Error getting user profile:', ['userId' => $userId, 'response' => $response->body()]);
        return null;
    }

    /**
     * จัดการ Webhook ที่ส่งมาจาก LINE
     */
    public function handle(Request $request)
    {
        Log::info('Webhook Received:', $request->all());

        if ($request->has('events')) {
            foreach ($request->input('events') as $event) {
                $userId = $event['source']['userId'] ?? null;

                if ($userId) {
                    try {
                        // ดึงข้อมูลโปรไฟล์
                        $profile = $this->getProfile($userId);

                        if ($profile) {
                            // เก็บข้อมูลในฐานข้อมูล
                            LineUser::Create(
                                ['user_id' => $userId],
                                [
                                    'display_name' => $profile['displayName'],
                                    'picture_url' => $profile['pictureUrl'] ?? null,
                                    'status_message' => $profile['statusMessage'] ?? null,
                                ]
                            );

                            Log::info('User Profile Saved:', ['userId' => $userId]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error processing Webhook:', ['message' => $e->getMessage()]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
