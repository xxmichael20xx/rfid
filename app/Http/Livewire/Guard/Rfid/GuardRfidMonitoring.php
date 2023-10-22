<?php

namespace App\Http\Livewire\Guard\Rfid;

use App\Models\Rfid;
use App\Models\RfidMonitoring;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class GuardRfidMonitoring extends Component
{
    protected $listeners = [
        'logEntry',
        'validateEntry',
        'updateCapture'
    ];

    public $monitorings;
    public $homeOwner;
    public $tappedRfid;
    public $captureImage;

    public function logEntry()
    {
        $id = $this->tappedRfid;
        $today = Carbon::now();
        $currentDate = $today->format('m/d/Y');
        $currentTime = $today->format('h:i A');
        $monitoring = RfidMonitoring::where('rfid', $id)
            ->where('date', $currentDate)
            ->where('time_out', '=', 'N/A')
            ->first();

            
        $captureUrl = $this->uploadCapture();
        if (! $monitoring) {
            $newMonitoring = RfidMonitoring::create([
                'rfid' => $id,
                'date' => $currentDate,
                'time_in' => $currentTime,
                'capture_in' => $captureUrl
            ]);

            if ($newMonitoring) {
                $this->emit('new-entry', [
                    'date' => $currentDate,
                    'time' => $currentTime
                ]);
            }

            $this->captureImage = null;
        } else {
            $monitoring->update([
                'time_out' => $currentTime,
                'capture_out' => $captureUrl
            ]);

            $this->emit('updated-entry', [
                'date' => $currentDate,
                'time' => $currentTime
            ]);
        }

        $this->fetchLatest();
    }

    public function uploadCapture()
    {
        // Decode the base64 data
        $fileData = base64_decode($this->captureImage);

        // save it to temporary dir first.
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $fileData);

        // this just to help us get file info.
        $tmpFile = new File($tmpFilePath);

        // convert data to actual file
        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true
        );

        // set file name
        $fileName = $this->tappedRfid . '_' . now()->format('Y_m_d-H_i_s') . '.jpg';
        return Storage::putFileAs('images/monitoring', $file, $fileName);
    }

    /**
     * Check if the scanned rfid exists
     */
    public function validateEntry($id)
    {
        $rfidExists = Rfid::with(
            'vehicle',
            'vehicle.rfid',
            'vehicle.homeOwner.profiles',
            'vehicle.homeOwner.vehicles',
            'vehicle.homeOwner.vehicles.rfid'
        )->where('rfid', $id)->first();

        if (! $rfidExists) {
            $this->emit('invalid-rfid');
            return false;
        }

        $this->tappedRfid = $id;
        $this->homeOwner = $rfidExists->vehicle->homeOwner;
        $this->emit('homeowner-data');
    }

    /**
     * Start the camera
     */
    public function updateCapture($value)
    {
        $this->captureImage = Str::replace('data:image/jpeg;base64,', '', $value);
    }

    protected function fetchLatest()
    {
        $this->monitorings = RfidMonitoring::with(['rfidData', 'rfidData.vehicle', 'rfidData.vehicle.homeOwner'])->latest()->get();
    }

    public function mount()
    {
        $this->fetchLatest();
    }

    public function render()
    {
        return view('livewire.Guard.Rfid.guard-rfid-monitoring')
            ->extends('layouts.guard')
            ->section('content');
    }
}
