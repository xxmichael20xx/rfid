<?php

namespace App\Http\Livewire;

use App\Models\Rfid;
use App\Models\RfidMonitoring;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class RfidPanel extends Component
{
    public $tappedRfid;

    public $plateNumber;

    public $homeOwner;

    public $homeOwnerName;

    public function rfidPanelEvent($id)
    {
        $this->tappedRfid = $id;

        $rfid = Rfid::with(
            'vehicle',
            'vehicle.homeOwner',
        )->where('rfid', $id)->first();

        if (! $rfid) {
            $this->emit('rfidPanel.invalid-rfid');
            return false;
        }

        if (! $rfid->vehicle || ! $rfid->vehicle->homeOwner) {
            $this->emit('rfidPanel.invalid-rfid');
            return false;
        }

        $this->homeOwner = $rfid->vehicle->homeOwner->last_full_name;
        $this->plateNumber = $rfid->vehicle->plate_number;
        $this->homeOwnerName = $rfid->vehicle->homeOwner->first_name;

        $this->emit('rfidPanel.success');
    }

    public function uploadCapture($value)
    {
        // Decode the base64 data
        $base64Image = Str::replace('data:image/jpeg;base64,', '', $value);
        $fileData = base64_decode($base64Image);

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

    public function rfidPanelSaveshot($value)
    {
        $captureUrl = $this->uploadCapture($value);

        $today = Carbon::now();
        $currentDate = $today->format('m/d/Y');
        $currentTime = $today->format('h:i A');
        $monitoring = RfidMonitoring::where('rfid', $this->tappedRfid)
            ->where('date', $currentDate)
            ->where('time_out', '=', 'N/A')
            ->first();

            
        if (! $monitoring) {
            $newMonitoring = RfidMonitoring::create([
                'rfid' => $this->tappedRfid,
                'date' => $currentDate,
                'time_in' => $currentTime,
                'capture_in' => $captureUrl
            ]);

            if ($newMonitoring) {
                $this->emit('rfidPanel.in', [
                    'name' => $this->homeOwnerName,
                    'capture' => $captureUrl
                ]);
            }
        } else {
            $monitoring->update([
                'time_out' => $currentTime,
                'capture_out' => $captureUrl
            ]);

            $this->emit('rfidPanel.out', [
                'name' => $this->homeOwnerName,
                'capture' => $captureUrl
            ]);
        }

        $this->tappedRfid = '';
        $this->plateNumber = '';
        $this->homeOwner = '';
        $this->homeOwnerName = '';
        
        $this->dispatchBrowserEvent('guardUpdateList');
        $this->emitTo('Guard.Rfid.guard-rfid-monitoring', 'testinghehe');
    }

    public function render()
    {
        return view('livewire.rfid-panel')
            ->extends('layouts.auth')
            ->section('content');
    }
}
