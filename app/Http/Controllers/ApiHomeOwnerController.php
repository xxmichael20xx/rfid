<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\Notification;
use App\Models\User;
use App\Models\Visitor;
use App\Services\QRService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ApiHomeOwnerController extends Controller
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

    /**
     * List all visitors
     */
    public function visitorList()
    {
        // get all visitors
        $visitors = $this->getHomeOwner()->visitors;

        return response()->json([
            'status' => true,
            'message' => 'List of visitors',
            'data' => $visitors
        ]);
    }

    /**
     * Add a new visitor
     */
    public function visitorAdd(Request $request)
    {
        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
        ]);

        // get the current home owner
        $homeOwnerId = $this->getHomeOwner()->id;

        // crate the visitor data
        $token = sprintf('%s_%s_%s', $homeOwnerId, time(), Str::random(4));

        // create new visitor
        Visitor::create([
            'home_owner_id' => $homeOwnerId,
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'token' => $token,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Visitor has been created!'
        ]);
    }

    /**
     * Get the current Home Owner
     */
    public function getHomeOwner()
    {
        $email = request()->user()->email;
        return HomeOwner::with(['visitors'])
            ->where('email', $email)
            ->first();
    }

    /**
     * Download the QR Code
     */
    public function downloadQr(Request $request)
    {
        $request->validate([
            'id' => ['required', Rule::exists('visitors', 'id')]
        ]);

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

    /**
     * Get all the homeowners notifications
     */
    public function notificationsAll(Request $request)
    {
        $user = $request->user();
        $homeOwnerId = $user->home_owner_id;

        // fetch all notifications
        $notifications = Notification::where('home_owner_id', $homeOwnerId)
            ->latest()
            ->get();

        // Update all notifications as read
        Notification::where('home_owner_id', $homeOwnerId)
            ->latest()
            ->update([
                'is_read' => true
            ]);

        return response()->json([
            'status' => true,
            'total' => $notifications->count(),
            'data' => $notifications
        ]);
    }

    /**
     * Fetch all unread notifications of the homeowner
     */
    public function notificationsUnread(Request $request)
    {
        $user = $request->user();
        $homeOwnerId = $user->home_owner_id;

        $notifications = Notification::where('home_owner_id', $homeOwnerId)
            ->where('is_read', false)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'total' => $notifications->count(),
            'data' => $notifications
        ]);
    }

    /**
     * Get all officers
     */
    public function officersAll(Request $request)
    {
        $admin = User::where('role', 'Admin')
            ->orderBy('last_name', 'DESC')
            ->get();
        $guards = User::where('role', 'Guard')
            ->orderBy('last_name', 'DESC')
            ->get();
        $treasurers = User::where('roke', 'Treasurer')
            ->orderBy('last_name', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => array_merge($admin, $guards, $treasurers)
        ]);
    }
}
