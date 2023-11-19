<?php

namespace App\Http\Livewire\Payments;

use App\Models\Payment;
use App\Models\PaymentRemit as PaymentRemitModel;
use Livewire\Component;

class PaymentRemit extends Component
{
    /**
     * List of Payment Remit
     */
    public $paymentRemits;
    
    /**
     * Form for remit
     */
    public $remitForm;
    
    /**
     * Number of cash on-hand
     */
    public $cashOnHand;

    /**
     * Create a new remit
     */
    public function submitRemit()
    {
        // Validate the form
        $this->validate([
            'remitForm.title' => ['required'],
            'remitForm.amount' => ['required', 'numeric', 'min: 100', 'max:' . $this->cashOnHand]
        ]);

        // Add the remit
        PaymentRemitModel::create($this->remitForm);

        // Emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Remit Added',
            'message' => 'Remit has been successfully added!',
            'reload' => true
        ]);
    }

    public function deleteRemit($id)
    {
        $remit = PaymentRemitModel::find($id);

        if (! $remit) {
            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'Remit data not found!',
            ]);
        }

        $remit->delete();

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Delete success',
            'message' => 'Remit has been deleted!',
            'reload' => true
        ]);
    }

    public function mount()
    {
        $this->paymentRemits = PaymentRemitModel::latest()->get();

        $tempCashOnHand = Payment::where('mode', 'Cash')->where('status', 'paid')->sum('amount');
        $tempRemit = PaymentRemitModel::sum('amount');

        $this->cashOnHand = $tempCashOnHand - $tempRemit;

        $this->remitForm = [
            'title' => '',
            'amount' => '0'
        ];
    }

    public function render()
    {
        return view('livewire.Payments.payment-remit');
    }
}
