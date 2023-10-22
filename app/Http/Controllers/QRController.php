<?php

namespace App\Http\Controllers;

use App\Services\QRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QRController extends Controller
{
    public function __construct(
        protected QRService $qrService
    )
    {}

    public function downloadQr(Request $request)
    {
        [$codeName, $codeUrl] = $this->qrService->googleQr($request->id);

        // Fetch the image data from the URL
        $imageData = Http::get($codeUrl)->body();

        // Set the response headers
        $headerAttachment = sprintf('attachment; filename="%s"', $codeName);
        $headers = [
            'Content-Type' => 'image/png', // Set the content type to PNG
            'Content-Disposition' => $headerAttachment, // Trigger download with a specific filename
        ];

        // Create a response with the image data and headers
        return response($imageData, 200, $headers);
    }
}
