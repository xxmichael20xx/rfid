<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\Lot;
use App\Models\Visitor;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class GuardVisitorEntry extends Component
{
    protected $listeners = [
        'showVisitorEntry' => 'showVisitorEntry'
    ];

    public $data;
    public $visitor;
    public $lotsCarousels = [];
    public $captureImage;

    public function showVisitorEntry($params)
    {
        $this->data = HomeOwner::find($params['id']);
        $this->visitor = Visitor::find($params['visitorId']);
        $this->lotsCarousels = collect($this->data->blockLots)->map(function($item) {
            $block = Block::find($item->block);
            $lot = Lot::find($item->lot);

            if ($lotImage = $lot->image) {
                return [
                    'name' => sprintf('Block %s - Lot %s', $block->block, $lot->lot),
                    'image' => '/uploads/' . $lotImage
                ];
            }
        })->filter()->all();

        $this->emit('show.visitor-entry');
    }

    public function updateCapture($value)
    {
        $this->captureImage = Str::replace('data:image/jpeg;base64,', '', $value);
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
        $fileName = $this->visitor->id . '_' . now()->format('Y_m_d-H_i_s') . '.jpg';
        return Storage::putFileAs('images/visitor', $file, $fileName);
    }

    public function logVisitoEntry()
    {
        $isTimeIn = $isTimeOut = false;
        if (! $this->visitor->time_in) {
            $captureUrl = $this->uploadCapture();

            $this->visitor->update([
                'time_in' => now(),
                'capture_in' => $captureUrl
            ]);

            $isTimeIn = true;
        } else if (! $this->visitor->time_out) {
            $this->visitor->update([
                'time_out' => now()
            ]);

            $isTimeOut = true;
        }

        if ($isTimeIn || $isTimeOut) {
            $this->emit('visitor.entry.success');
        }
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-entry');
    }
}
