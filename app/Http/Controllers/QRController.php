<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Services\QRService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class QRController extends Controller
{
    /**
     * QRService instance
     *
     * @var QRService
     */
    protected $qrService;

    public function __construct()
    {
        $this->qrService = new QRService;
    }

    public function downloadQr(Request $request)
    {
        [$codeName, $codeUrl] = $this->qrService->googleQr($request->id);

        // Fetch the image data from the URL
        $imageData = Http::get($codeUrl)->body();

        // Create a temporary file and save the image data to it
        $tempFilePath = tempnam(sys_get_temp_dir(), 'qr_');
        file_put_contents($tempFilePath, $imageData);

        // Create an UploadedFile instance
        $originalName = $codeName;
        $mimeType = 'image/png'; // Set the appropriate MIME type for PNG
        $fileSize = filesize($tempFilePath);

        $uploadedFile = new UploadedFile(
            $tempFilePath,
            $originalName,
            $mimeType,
            $fileSize,
            false,
            true // delete the file when done (temporary)
        );

        // Store the image data in the storage
        Storage::putFileAs('images/qr', $uploadedFile, $codeName);

        // Update the Visitor QR data
        $visitorToken = Visitor::find($request->id);
        $visitorToken->update([
            'qr_image' => 'images/qr/' . $codeName
        ]);

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
