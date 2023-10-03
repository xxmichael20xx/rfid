<?php

namespace App\Http\Livewire\Payments;

use App\Models\HomeOwner;
use App\Models\Payment;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PaymentsList extends Component
{
    protected $listeners = ['updateStatus'];

    public $payments;
    public $homeOwners;
    public $form = [
        'home_owner_id' => '',
        'type' => '',
        'mode' => '',
        'amount',
        'transaction_date',
        'reference'
    ];

    public $types = ['Guard Security', 'Chrismas Party', 'Maintenance'];
    public $modes = ['Cash', 'Bank Transfer', 'GCash'];

    public $search;

    protected function rules()
    {
        return  [
            'form.home_owner_id' => ['required', Rule::exists('home_owners', 'id')],
            'form.type' => ['required'],
            'form.mode' => ['required'],
            'form.amount' => ['required', 'numeric', 'min:100'],
            'form.transaction_date' => ['required', 'date'],
            'form.reference' => ['nullable']
        ];
    }

    public function create()
    {
        // validate the form
        $this->validate($this->rules(), [
            'form.home_owner_id.required' => 'Biller field is required',
            'form.home_owner_id.exists' => 'Selected biller is not found',
        ]);

        // create new payment
        Payment::create($this->form);

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Add Success',
            'message' => 'New payment has been successfully added!',
            'reload' => true
        ]);
    }

    public function updateStatus($id)
    {
        $payment = Payment::find($id);
        $payment->update([
            'status' => 'paid',
            'paid_on' => now()
        ]);

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Payment has been marked as `Paid`',
            'reload' => true
        ]);
    }

    public function mount()
    {
        $this->payments = Payment::with(['biller'])->latest()->get();
        $this->homeOwners = HomeOwner::all();

        if ($search = request()->get('search')) {
            $this->search = $search;

            $keyword = "%{$search}%";
            $this->payments = Payment::with(['biller'])
                ->whereHas('biller', function($query) use($keyword) {
                    $query->where('home_owners.first_name', 'LIKE', $keyword)
                        ->orWhere('home_owners.last_name', 'LIKE', $keyword)
                        ->orWhere('home_owners.middle_name', 'LIKE', $keyword);
                })
                ->orWhere('type', 'LIKE', $keyword)
                ->orWHere('mode', 'LIKE', $keyword)
                ->orWHere('reference', 'LIKE', $keyword)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.payments.payments-list')
            ->extends('layouts.admin')
            ->section('content');
    }
}
