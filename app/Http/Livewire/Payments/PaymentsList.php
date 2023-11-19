<?php

namespace App\Http\Livewire\Payments;

use App\Exports\PaymentListExport;
use App\Models\HomeOwner;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentRemit;
use App\Models\PaymentType;
use App\Notifications\PaymentReminder;
use App\Rules\NotPastDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PaymentsList extends Component
{
    /**
     * Define event listeners
     */
    protected $listeners = [
        'updateStatus',
        'preparePayForm',
        'payFormSubmit',
        'sendReminder'
    ];

    /**
     * List if payments
     */
    public $payments;

    /**
     * List of home owners
     */
    public $homeOwners;

    /**
     * Define the new payment form
     */
    public $form;

    /**
     * List of payment types
     */
    public $paymentTypes;

    /**
     * Let of payment modes
     */
    public $paymentModes;

    /**
     * Recurring notes
     */
    public $recurringNotes;

    /**
     * Define the payment form
     */
    public $payForm;

    /**
     * Define the pay form minimum amount
     */
    public $minPayFormAmount;

    /**
     * Define if page has search or filters
     */
    public $hasSearchFilter;

    /**
     * Define filters
     */
    public $filters;

    /**
     * Define the filter type
     */
    public $filterType;

    /**
     * Define the filter mode
     */
    public $filterMode;

    /**
     * Define the filter month
     */
    public $filterMonth;

    /**
     * Define the filter year
     */
    public $filterYear;

    /**
     * Number of payment due today
     */
    public $dueToday;

    /**
     * Number of cash on-hand
     */
    public $cashOnHand;

    /*
     * HomeOwner Block Lots
     */
    public $homeOwnerBlockLots;

    /**
     * Create a new payment
     */
    public function create()
    {
        // Validate the form
        $this->validate([
            'form.home_owner_id' => ['required', Rule::exists('home_owners', 'id')],
            'form.block_lot' => ['required'],
            'form.type_id' => ['required'],
            'form.mode' => ['required_if:form.is_paid,1,true,yes,checked'],
            'form.amount' => ['required', 'numeric', 'min:50'],
            'form.due_date' => ['required', 'date', new NotPastDate],
            'form.reference' => ['nullable'],
            'form.recurring_date' => ['sometimes'],
            'form.is_paid' => ['sometimes']
        ], [
            'form.home_owner_id.required' => 'Biller field is required',
            'form.home_owner_id.exists' => 'Invalid selected biller',
        ]);

        // Check if mark payment as paid
        if ($this->form['is_paid']) {
            $this->form['status'] = 'paid';
            $this->form['date_paid'] = now();
        }

        // Create new payment
        Payment::create($this->form);

        // Emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Add Success',
            'message' => 'New payment has been successfully added!',
            'reload' => true
        ]);
    }

    /**
     * Update the status of the payment
     */
    public function updateStatus($id)
    {
        $payment = Payment::find($id);
        $payment->update([
            'status' => 'paid',
            'date_paid' => now()
        ]);

        // Emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Payment has been marked as `Paid`',
            'reload' => true
        ]);
    }

    public function preparePayForm($id)
    {
        // Fetch the payment
        $payment = Payment::find($id);

        // Check if the payment is not found
        if (! $payment) {
            // Dispatch event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'Payment not found!',
            ]);
        }

        // Populate the pay form
        $this->payForm = [
            'id' => $id,
            'billerName' => $payment->biller->last_full_name,
            'blockLot' => $payment->block_lot_item,
            'paymentType' => $payment->paymentType->type,
            'amount' => $payment->amount,
            'mode' => empty($payment->mode) ? 'Cash' : $payment->mode,
            'reference' => empty($payment->reference) ? null : $payment->reference,
            'is_paid' => false
        ];

        // Set the minimum pay form amount
        $this->minPayFormAmount = $payment->amount;

        // Dispatch event to display the modal
        $this->emit('prepared.pay-form');
    }

    /**
     * Do a pre submit check
     */
    public function preSubmitPayForm()
    {
        // Check if the current payment amount isn't the same on the form
        // and check if the form is marked as paid
        // If yes, then a confirmation dialog will occur
        /* if ($this->minPayFormAmount !== (int) $this->payForm['amount'] && (bool) $this->payForm['is_paid']) {
            $this->emit('pre-submit.pay-form');
        } else {
            $this->payFormSubmit();
        } */

        $this->payFormSubmit();
    }

    /**
     * Save Payment
     */
    public function payFormSubmit()
    {
        // Validate the pay form
        $this->validate([
            'payForm.amount' => ['required', 'numeric', 'min:50'],
            'payForm.mode' => ['required'],
            'payForm.reference' => ['required_if:payForm.mode,Bank Transfer,GCash']
        ]);

        // Set the payment to update
        $payment = Payment::find($this->payForm['id']);

        // Set the payment update data
        $paymentUpdateData = [
            'amount' => $this->payForm['amount'],
            'mode' => $this->payForm['mode'],
            'reference' => $this->payForm['reference'],
        ];

        $paymentProceed = true;

        // If the pay form is marked as paid, then set the paid data
        if ($this->payForm['is_paid']) {
            $paymentUpdateData = array_merge($paymentUpdateData, [
                'transaction_date' => now(),
                'date_paid' => now(),
                'status' => 'paid'
            ]);

            if ((int) $payment->amount > $this->payForm['amount']) {
                $paymentProceed = false;

                $this->emit('show.dialog', [
                    'icon' => 'warning',
                    'title' => 'Insufficient Amount',
                    'message' => 'The payment amount is insufficient. Must not be less than ' . number_format($payment->amount),
                    'reload' => false
                ]);
            }
        }

        if ($paymentProceed) {
            // Update the payment
            $payment->update($paymentUpdateData);

            if ($this->payForm['is_paid']) {
                // Create new payment if payment is recurring
                $this->processRecurringPayment($payment);
            }

            // Emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Payment Success',
                'message' => 'Payment has been successfully recorded',
                'reload' => true
            ]);
        }
    }

    /**
     * Process the recurring payment
     */
    public function processRecurringPayment($payment)
    {
        // Check if the payment is recurring
        if ($payment->is_recurring) {
            // Convert the existing 'due_date' and 'recurring_day' to Carbon instances
            $dueDate = Carbon::parse($payment->due_date);
            $frequency = $payment->paymentType->frequency;
            $newDueDate = $dueDate->copy();

            if ($frequency === 'monthly') {
                // For monthly payments
                $newDueDate->addMonthsNoOverflow()->day($payment->recurring_date);
            } elseif ($frequency === 'annually') {
                // For annually payments
                $newDueDate->addYear()->day($payment->recurring_date);
            }

            // Set and modify the payment data
            $newPaymentData = $payment->toArray();
            $modifiedData = [
                'mode' => null,
                'transaction_date' => null,
                'date_paid' => null,
                'due_date' => $newDueDate->toDateTimeString(),
                'reference' => null,
                'status' => 'pending'
            ];

            // Iterate through the update array and overwrite values in the original array
            foreach ($modifiedData as $key => $value) {
                if (array_key_exists($key, $newPaymentData)) {
                    $newPaymentData[$key] = $value;
                }
            }

            // Create new recurring payment
            Payment::create($newPaymentData);
        }
    }

    /**
     * Export the data to CSV
     */
    public function exportToCsv()
    {
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = 'payment_history_' . $timestamp . '.xlsx';

        return Excel::download(new PaymentListExport($this->payments), $filename);
    }

    public function changeType()
    {
        $typeId = (int) $this->form['type_id'];
        $paymentType = PaymentType::find($typeId);
        $recurringFrequency = $paymentType->frequency;
        $message = '';

        if ($paymentType->is_recurring) {
            $message = sprintf("Payment will occur %s", $recurringFrequency);
        }

        $this->form['amount'] = $paymentType->amount;

        $this->recurringNotes = $message;
    }

    /**
     * Send an email reminder
     */
    public function sendReminder($payment)
    {
        $billerId = data_get($payment, 'home_owner_id');
        $homeOwner = HomeOwner::find($billerId);

        if (! $homeOwner) {
            // Dispatch event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'Payment Biller not found!',
            ]);
            return;
        }

        $paymentData = Payment::find(data_get($payment, 'id'));
        $dueDate = Carbon::parse($paymentData->due_date);

        $title = 'Payment Reminder';
        $amount = number_format($paymentData->amount, 2);
        $paymentType = $paymentData->paymentType->type;
        $content = sprintf('Payment `%s` is due on `%s` with an amount of ₱`%s`.', $paymentType, $dueDate->format('M d, Y'), $amount);

        // Create new notification
        Notification::create([
            'home_owner_id' => $billerId,
            'title' => $title,
            'content' => $content
        ]);

        $homeOwner->notify(new PaymentReminder($payment, $homeOwner));
        // Dispatch event for the notification
        $this->emit('show.dialog', [
            'icon' => 'info',
            'title' => 'Email sent',
            'message' => 'Payment reminder email has been sent!',
        ]);
    }

    /**
     * Change the block & lots select options
     */
    public function changeCreatePaymentBiller()
    {
        $homeOwner = HomeOwner::find($this->form['home_owner_id']);

        $this->homeOwnerBlockLots = $homeOwner->block_lot_items;
    }

    /**
     * Initialize component data
     */
    public function mount()
    {
        // Set the value for the payments
        $this->payments = Payment::with(['biller', 'paymentType'])->latest()->get();

        // Set the value of homeOwners
        $this->homeOwners = HomeOwner::orderBy('last_name', 'asc')->get();

        // Set the value of paymentTypes
        $this->paymentTypes = PaymentType::orderBy('type', 'asc')->get();

        // Set the value of paymentModes
        $this->paymentModes = ['Cash', 'Bank Transfer', 'GCash'];

        $this->homeOwnerBlockLots = $this->homeOwners->first()->block_lot_items;
    
        // Set the fields of the form
        $this->form = [
            'home_owner_id' => $this->homeOwners->first()->id,
            'block_lot' => $this->homeOwners->first()->block_lot_items->first()['id'],
            'type_id' => $this->paymentTypes->first()->id,
            'mode' => 'Cash',
            'amount' => $this->paymentTypes->first()->amount,
            'due_date' => Carbon::now()->format('Y-m-d'),
            'reference' => '',
            'is_paid' => false,
            'is_recurring' => false,
            'recurring_date' => null
        ];

        // Set the fields of the pay form
        $this->payForm = [
            'billerName' => '',
            'paymentType' => '',
            'amount' => 0,
            'mode' => null,
            'reference' => null,
            'is_paid' => false
        ];

        // Set the search criteria
        $search = request()->get('search');
        $type = request()->get('type');
        $mode = request()->get('mode');
        $month = request()->get('month');
        $year = request()->get('year');

        $this->filters = [
            'type' => 'all',
            'mode' => 'all',
            'month' => 'all',
            'year' => 'all',
        ];

        if ($search || $type || $mode || $month || $year) {
            $this->hasSearchFilter = true;

            // Initialize the initial query
            $paymentsQuery = Payment::with(['biller', 'paymentType']);

            // Check if search query exists
            if ($search) {
                $keyword = "%{$search}%";
                $paymentsQuery->where(function ($query) use ($keyword) {
                    $query->where(function ($query) use ($keyword) {
                        $query->whereHas('biller', function ($query) use ($keyword) {
                            $query->where(function ($query) use ($keyword) {
                                $query->where(DB::raw("CONCAT(last_name, ', ', first_name, COALESCE(', ', middle_name, ''))"), 'LIKE', $keyword)
                                    ->orWhere(function ($query) use ($keyword) {
                                        $query->whereNull('middle_name')
                                            ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $keyword);
                                    });
                            });
                        })
                        ->orWhere('reference', 'LIKE', $keyword);
                    });
                });
            }

            // Check if type query exists
            if ($type) {
                $this->filters['type'] = $type;
                $paymentsQuery->where('type_id', $type);
            }

            // Check if mode query exists
            if ($mode) {
                $this->filters['mode'] = $mode;
                $paymentsQuery->where('mode', $mode);
            }

            // Check if month query exists
            if ($month) {
                $this->filters['month'] = $month;
                $paymentsQuery->whereMonth('due_date', '=', $month);
            }

            // Check if year query exists
            if ($year) {
                $this->filters['year'] = $year;
                $paymentsQuery->whereYear('due_date', $year);
            }

            $this->payments = $paymentsQuery->get();
        } else {
            $this->hasSearchFilter = false;
        }

        $this->dueToday = Payment::whereDate('due_date', Carbon::now())->count();

        $tempCashOnHand = Payment::where('mode', 'Cash')->where('status', 'paid')->sum('amount');
        $tempRemit = PaymentRemit::sum('amount');

        $this->cashOnHand = $tempCashOnHand - $tempRemit;
    }

    /**
     * Define what blade file to render
     */
    public function render()
    {
        $role = auth()->user()->role;
        $layout = $role == 'Admin' ? 'layouts.admin' : 'layouts.treasurer';
        return view('livewire.Payments.payments-list')
            ->extends($layout)
            ->section('content');
    }
}
