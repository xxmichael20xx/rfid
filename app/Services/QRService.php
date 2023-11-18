<?php

namespace App\Services;

use App\Models\Visitor;
use Illuminate\Support\Str;

class QRService
{
    public function googleQr($id)
    {
        // Append the current timestamp to the random string
        $visitorData = Visitor::find($id);
        $timestamp = now()->timestamp;
        $codeToken = $visitorData->token;
        $codeName = 'qr_code_' . $timestamp . '.png';
        $codeUrl = 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' . $codeToken;

        // Generate the URL for the QR code using the Google Charts API
        return [$codeName, $codeUrl];
    }
}
