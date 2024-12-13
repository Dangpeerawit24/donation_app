<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCodeEntry;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeController extends Controller
{
    public function index()
    {
        return view('admin.qrcode');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'filename' => 'required|string|max:255',
        ]);

        // สร้างชื่อไฟล์ที่มีนามสกุล .png
        $fileName = $request->filename . '.png';
        $qrCodePath = 'img/qr-codes/line/' . $fileName;
        $fullPath = public_path($qrCodePath);

        // สร้าง QR Code ด้วย endroid/qr-code
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($request->url)
            ->size(200)
            ->build();

        // บันทึกภาพ QR Code ในโฟลเดอร์ public/qr-codes
        if (!file_exists(public_path('img/qr-codes/line'))) {
            mkdir(public_path('img/qr-codes/line'), 0755, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
        }
        file_put_contents($fullPath, $qrCode->getString());

        // บันทึกข้อมูลลงในฐานข้อมูล
        $entry = new QRCodeEntry();
        $entry->url = $request->url;
        $entry->filename = $fileName;
        $entry->save();

        return view('admin.qrcode', [
            'qrCodePath' => $qrCodePath,
        ]);
    }
}
