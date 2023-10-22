<?php

namespace App\Services;

use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRService
{
    /**
     * Generate a QR Code with an identifier
     */
    public function generateQr($id)
    {
        // Generate a random 20-character string
        $randomString = Str::random(32);

        // Append the current timestamp to the random string
        $timestamp = now()->timestamp;
        $codeToken = $id . '_' . $randomString . $timestamp;
        $codeName = 'qr_code_' . $timestamp . '.png';

        $qrCode = QrCode::format('png')->generate($codeToken);

        return response()->download($qrCode, $codeName, [
            'Content-Type' => 'image/png'
        ]);
    }
}
