<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\Visitor;
use App\Services\QRService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    public function downloadAppQr(Request $request)
    {
        $user = $request->user();
        $token = sprintf('%s_%s_%s', $user->home_owner_id, time(), Str::random(4));
        $visitor = Visitor::create(array_merge($request->toArray(), [
            'token' => $token,
            'home_owner_id' => $user->home_owner_id
        ]));

        return $this->qrGenerate($visitor->id);
    }

    public function downloadQr(Request $request)
    {
        return $this->qrGenerate($request->id);

    }

    protected function qrGenerate($id)
    {
        [$codeName, $codeUrl] = $this->qrService->googleQr($id);

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
        $visitorToken = Visitor::find($id);
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
