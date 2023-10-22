<?php

namespace App\Http\Livewire\Payments;

use App\Models\PaymentType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PaymentsTypes extends Component
{
    /**
     * Define event listeners
     */
    protected $listeners = [
        'deletePaymentType',
        'prepareUpdate'
    ];

    /**
     * Types of payments
     */
    public $paymentTypes;

    /**
     * Types of payment frequencies
     */
    public $frequencies;

    /**
     * Create form for Payment Type
     */
    public $createForm;

    /**
     * Update form for Payment Type
     */
    public $updateForm;

    /**
     * Create a new Payment Type
     */
    public function create()
    {
        // Validate the form
        $this->validate([
            'createForm.type' => ['required', Rule::unique('payment_types', 'type')],
            'createForm.amount' => ['required', 'numeric', 'min:100', 'max: 999999'],
            'createForm.frequency' => ['required']
        ]);

        // Add the new Payment Type to database
        PaymentType::create($this->createForm);

        // Dispatch event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'Payment type has been successfully created!',
            'reload' => true
        ]);
    }

    public function deletePaymentType($id)
    {
        // Fetch the data to archive
        $paymentType = PaymentType::find($id);

        // Check if data to archive doesn't exist
        if (! $paymentType) {
            // Dispatch event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'Payment Type not found!',
            ]);
        } else {
            // Archive the payment type
            $paymentType->delete();

            // Dispatch event for the notification
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Archive Success',
                'message' => 'Payment type has been successfully archived!',
                'reload' => true
            ]);
        }
    }

    /**
     * Prepare the data to to update
     */
    public function prepareUpdate($id)
    {
        // Set the data from the database
        $this->updateForm = PaymentType::find($id)->toArray();

        // Dispatch event to display the modal
        $this->emit('prepared.setting-payment');
    }

    /**
     * Update the payment type
     */
    public function update()
    {
        // Validate the update form
        $id = $this->updateForm['id'];
        $this->validate([
            'updateForm.type' => [
                'required',
                Rule::unique('payment_types', 'type')->ignore($id, 'id')
            ],
            'updateForm.amount' => ['required', 'numeric', 'min:100', 'max: 999999'],
            'updateForm.frequency' => ['required']
        ]);

        // Set the data to update
        $paymentType = PaymentType::find($id);

        // Update the data
        $paymentType->update($this->updateForm);

        // Dispatch event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Payment type has been successfully updated!',
            'reload' => true
        ]);
    }

    /**
     * Initialize component data
     */
    public function mount()
    {
        // Set the value of the paymentTypes
        $this->paymentTypes = PaymentType::orderBy('type', 'asc')->get();

        // Set the value of the frequencies
        $this->frequencies = ['monthly', 'annually'];
        
        // Set the value of the createForm
        $this->createForm = [
            'type' => '',
            'amount' => 0,
            'recurring_day' => 1,
            'frequency' => 'monthly',
            'is_recurring' => false
        ];
    }

    /**
     * Define what blade file to render
     */
    public function render()
    {
        return view('livewire.payments.payments-types')
            ->extends('layouts.admin')
            ->section('content');
    }
}
