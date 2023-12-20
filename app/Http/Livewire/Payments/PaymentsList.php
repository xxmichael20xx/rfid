<?php

namespace App\Http\Livewire\Payments;

use App\Exports\PaymentListExport;
use App\Models\HomeOwner;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentRemit;
use App\Models\PaymentType;
use App\Notifications\PaymentReminder;
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
            'form.amount' => ['required', 'numeric', 'min:50'],
            'form.reference' => ['nullable'],
            'form.recurring_date' => ['sometimes'],
            'form.is_paid' => ['sometimes']
        ], [
            'form.home_owner_id.required' => 'Biller field is required',
            'form.home_owner_id.exists' => 'Invalid selected biller',
        ]);

        // set the due date based on the recurring date
        $_dueDate = Carbon::now()->addMonth(1)->day(data_get($this->form, 'recurring_date'));
        data_set($this->form, 'due_date', $_dueDate->format('Y-m-d h:i:s'));

        // Check if mark payment as paid
        if ($this->form['is_paid']) {
            $this->form['status'] = 'paid';
            $this->form['date_paid'] = now();
            $this->form['received_by'] = auth()->user()->id;
        }

        // Create new payment
        $newPayment = Payment::create($this->form);

        // process recurring
        if ($this->form['is_paid']) {
            $this->processRecurringPayment($newPayment);
        }

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
            'amount' => $payment->amount,
            'due_date' => Carbon::parse($payment->due_date)->format('M d, Y @ h:i A'),
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

        if ($paymentProceed) {
            // Update the payment
            data_set($paymentUpdateData, 'received_by', auth()->user()->id);
            $payment->update($paymentUpdateData);

            // Create new payment if payment is recurring
            $this->processRecurringPayment($payment);

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
        // Convert the existing 'due_date' and 'recurring_day' to Carbon instances
        $dueDate = Carbon::parse($payment->due_date);
        $newDueDate = $dueDate->copy();

        $newDueDate->addMonthsNoOverflow()->day(now()->day);

        // Set and modify the payment data
        $newPaymentData = $payment->toArray();
        $modifiedData = [
            'mode' => 'Cash',
            'transaction_date' => null,
            'date_paid' => null,
            'due_date' => $newDueDate->toDateTimeString(),
            'reference' => null,
            'status' => 'pending',
            'received_by' => null
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

    /**
     * Export the data to CSV
     */
    public function exportToCsv()
    {
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = 'payment_history_' . $timestamp . '.xlsx';

        return Excel::download(new PaymentListExport($this->payments), $filename);
    }

    public function changeRecurringDate()
    {
        $recurringDate = $this->form['recurring_date'];
        $message = sprintf("Payment will occur %s of the month", getOrdinalSuffix($recurringDate));

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
        $content = sprintf('Payment is due on `%s` with an amount of ₱`%s`.', $dueDate->format('M d, Y @ h:i A'), $amount);

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
    public function changeCreatePaymentBiller($value)
    {
        $this->form['home_owner_id'] = $value;
        $homeOwner = HomeOwner::find($this->form['home_owner_id']);

        $this->homeOwnerBlockLots = $homeOwner->block_lot_items;

        $currentBlockLot = $this->form['block_lot'];
        $newDefaultBlockLot = data_get($this->homeOwnerBlockLots, '0.id', $currentBlockLot);

        data_set($this->form, 'block_lot', $newDefaultBlockLot);
    }

    public function preCreateSubmit()
    {
        $this->emit('payment.pre.submit', [
            'amount' => '₱' . number_format($this->form['amount'], 2)
        ]);
    }

    /**
     * Initialize component data
     */
    public function mount()
    {
        // Set the value for the payments
        $this->payments = Payment::with(['biller', 'paymentType'])->orderBy('id', 'DESC')->get();

        // Set the value of homeOwners
        $this->homeOwners = HomeOwner::orderBy('last_name', 'asc')->get();

        // Set the value of paymentTypes
        $this->paymentTypes = PaymentType::orderBy('type', 'asc')->get();

        // Set the value of paymentModes
        $this->paymentModes = ['Cash', 'Bank Transfer', 'GCash'];

        if ($this->homeOwners->count() > 0) {
            $defaultHomeOwnerId = $this->homeOwners->first()->id;
            $defaultBlockLot = $this->homeOwners->first()->block_lot_items->first()['id'];
            $this->homeOwnerBlockLots = $this->homeOwners->first()->block_lot_items;
        } else {
            $defaultHomeOwnerId = null;
            $defaultBlockLot = [];
            $this->homeOwnerBlockLots = [];
        }
    
        // Set the fields of the form
        $this->form = [
            'home_owner_id' => $defaultHomeOwnerId,
            'block_lot' => $defaultBlockLot,
            'amount' => 800,
            'due_date' => Carbon::now()->format('Y-m-d'),
            'reference' => '',
            'is_paid' => false,
            'is_recurring' => false,
            'recurring_date' => null
        ];

        $this->recurringNotes = sprintf("Payment will occur %s of the month", getOrdinalSuffix(1));

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
        $status = request()->get('status');
        $mode = request()->get('mode');
        $month = request()->get('month');
        $year = request()->get('year');

        $this->filters = [
            'status' => 'all',
            'mode' => 'all',
            'month' => 'all',
            'year' => 'all',
        ];

        if ($search || $status || $mode || $month || $year) {
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
                                $query->where(DB::raw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $keyword)
                                      ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $keyword)
                                      ->orWhere(DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name)"), 'LIKE', $keyword)
                                      ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $keyword)
                                      ->orWhere('first_name', 'LIKE', $keyword)
                                      ->orWhere('middle_name', 'LIKE', $keyword)
                                      ->orWhere('last_name', 'LIKE', $keyword);
                            });
                        });
                    })
                    ->orWhere('reference', 'LIKE', $keyword);
                });
            }            

            // Check if type query exists
            if ($status) {
                $inStatus = $status == 'all' ? ['pending', 'paid', 'failed'] : [$status];

                $this->filters['status'] = $status;
                $paymentsQuery->whereIn('status', $inStatus);
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

        $this->dueToday = Payment::whereDate('due_date', Carbon::now())->where('status', '!=', 'paid')->count();

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
