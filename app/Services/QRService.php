<?php

namespace App\Services;

use Illuminate\Support\Str;

class QRService
{
    public function googleQr($id)
    {
        // Generate a random 20-character string
        $randomString = Str::random(32);

        // Append the current timestamp to the random string
        $timestamp = now()->timestamp;
        $codeToken = $id . '_' . $randomString . $timestamp;
        $codeName = 'qr_code_' . $timestamp . '.png';
        $codeUrl = 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' . $codeToken;

        // Generate the URL for the QR code using the Google Charts API
        return [$codeName, $codeUrl];
    }
}
