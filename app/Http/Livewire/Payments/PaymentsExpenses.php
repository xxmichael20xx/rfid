<?php

namespace App\Http\Livewire\Payments;

use App\Models\PaymentExpense;
use App\Rules\NotFutureDate;
use Carbon\Carbon;
use Livewire\Component;

class PaymentsExpenses extends Component
{
    /**
     * Define event listeners
     */
    protected $listeners = [
        'selectExpenseType'
    ];
    /**
     * The expense form
     */
    public $form;

    /**
     * The list of expenses
     */
    public $expenses;

    /**
     * The table filters
     */
    public $filters;

    /**
     * Set the expense type on change
     */
    public function selectExpenseType($id)
    {
        $this->form['type'] = $id;
    }

    /**
     * The list of expense types
     */
    public $expenseTypes;

    public function create()
    {
        // Validate the form
        $this->validate([
            'form.type' => ['required'],
            'form.amount' => ['required', 'numeric', 'min:10', 'max:999999'],
            'form.transaction_date' => ['required', 'date', new NotFutureDate]
        ]);

        // Create new expense
        PaymentExpense::create($this->form);

        // Emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Expense Added',
            'message' => 'Expense has been successfully added!',
            'reload' => true
        ]);
    }

    /**
     * Filter the expenses
     */
    public function changeFilter()
    {
        $this->expenses = $this->fetchExpenses();
    }

    public function fetchExpenses()
    {
        return PaymentExpense::whereMonth('transaction_date', '=', $this->filters['month'])
            ->whereYear('transaction_date', '=', $this->filters['year'])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function deleteExpense($id)
    {
        $expense = PaymentExpense::find($id);

        if (! $expense) {
            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'Expense data not found!',
            ]);
        }

        $expense->delete();

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Delete success',
            'message' => 'Expense has been deleted!',
            'reload' => true
        ]);
    }

    /**
     * Initialize component data
     */
    public function mount()
    {
        // Set the filters
        $this->filters = [
            'year' => request()->get('filter_year') ?? now()->year,
            'month' => request()->get('filter_month') ?? now()->month
        ];

        // Fetch all expenses
        $this->expenses = $this->fetchExpenses();

        // Initialize the form
        $this->form = [
            'type' => '',
            'amount' => '0',
            'transaction_date' => ''
        ];

        // Set the expense types
        $this->expenseTypes = $this->expenses->pluck('type')->toArray();
    }

    /**
     * Define what blade file to render
     */
    public function render()
    {
        $role = auth()->user()->role;
        $layout = $role == 'Admin' ? 'layouts.admin' : 'layouts.treasurer';
        return view('livewire.Payments.payments-expenses')
            ->extends($layout)
            ->section('content');
    }
}
