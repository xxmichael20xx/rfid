<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\HomeOwner;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class GuardVisitorExit extends Component
{
    public $data;
    public $visitor;
    public $form;
    public $captureImage;

    protected $listeners = [
        'showVisitorExit' => 'showVisitorExit',
    ];

    public function visitorNotes()
    {
        // get the time_in of the visitor
        $timeIn = Carbon::parse($this->data->time_in);
        $timeOut = Carbon::parse($this->data->time_out);
        $requiredRule = 'nullable';

        // check if time_out is more than >= 24-hours on time_in
        if ($timeOut->diffInHours($timeIn) >= 24) {
            $requiredRule = 'required';
        }

        // validate the notes form
        $this->validate([
            'form.notes' => [$requiredRule],
        ], [
            'form.notes.required' => 'Please enter a note since the visitor exit is more than 24-hours.'
        ]);

        // save the notes
        $this->data->update([
            'notes' => $this->form['notes']
        ]);

        $this->form = [
            'id' => '',
            'notes' => ''
        ];

        $this->emit('close.visitor-exit');
    }

    public function showVisitorExit($params)
    {
        $this->data = HomeOwner::find($params['id']);
        $this->visitor = Visitor::find($params['visitorId']);
        $this->form['id'] = $params['id'];

        $this->emit('show.visitor-exit');
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

    public function updateCapture($value)
    {
        $this->captureImage = Str::replace('data:image/jpeg;base64,', '', $value);

        $captureUrl = $this->uploadCapture();
        
        $this->visitor->update([
            'time_out' => now(),
            'capture_out' => $captureUrl,
        ]);

        $this->emit('close.visitor-exit', [
            'capture' => $captureUrl,
        ]);
    }

    public function mount()
    {
        $this->form = [
            'id' => null,
            'notes' => '',
        ];
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-exit');
    }
}
