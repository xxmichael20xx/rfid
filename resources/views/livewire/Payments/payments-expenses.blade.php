<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Manage Expenses</h1>
        </div>
    </div>

    <div class="row mb-4" wire:ignore>
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 mx-auto">
                            <p class="card-title h5">Expenses Chat</p>
                            <select
                                wire:change="changeChartYear"
                                class="form-select"
                                id="payments-expenses-change"
                                name="payments-expenses-change"
                                wire:model.lazy="chartData.year">
                                <option value="" disabled>Filter by year</option>
                                @php
                                    $currentYear = date('Y');
                                    $years = range($currentYear - 3, $currentYear + 2);
                                @endphp
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <hr class="theme-separator">
                        </div>
                        <div class="col-12">
                            <canvas width="100%" id="payments-expenses"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <p class="card-title h5">Expenses List</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-3" wire:ignore>
                                <div class="input-container">
                                    <label for="filter_month">Filter by month</label>
                                    <select
                                        name="filter_month"
                                        id="filter_month"
                                        class="form-select"
                                        wire:model.lazy="filters.month"
                                        wire:change="changeFilter">
                                        <option value="all" disabled>Select month</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 mb-3" wire:ignore>
                                <div class="input-container">
                                    <label for="filter_year">Filter by year</label>
                                    <select
                                        name="filter_year"
                                        id="filter_year"
                                        class="form-select"
                                        wire:model.lazy="filters.year"
                                        wire:change="changeFilter">
                                        <option value="all" disabled>Select year</option>
                                        @php
                                            $currentYear = date('Y');
                                            $years = range($currentYear - 3, $currentYear + 2);
                                        @endphp
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Type</th>
                                                <th class="cell">Amount</th>
                                                <th class="cell">Transaction Date</th>
                                                <th class="cell">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($expenses as $expense)
                                                <tr>
                                                    <td class="cell">{{ $expense->type }}</td>
                                                    <td class="cell">₱{{ number_format($expense->amount) }}</td>
                                                    <td class="cell">{{ Carbon\Carbon::parse($expense->transaction_date)->format('M d, Y') }}</td>
                                                    <td class="cell">
                                                        <button
                                                            type="button"
                                                            class="visually-hidden"
                                                            id="expense-{{ $expense->id }}"
                                                            wire:click="deleteExpense({{ $expense->id }})">
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger text-white p-2 confirm-delete-expense"
                                                            data-id="#expense-{{ $expense->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="4">No result(s)</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow-lg border-0" wire:ignore.self>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <p class="card-title h5">New Expense</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" wire:submit.prevent="create" class="col-12">
                                @csrf
        
                                <div class="row mb-3" wire:ignore>
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="type">Type<span class="required">*</span></label>
                                            <select
                                                id="expense-type"
                                                class="form-control @error('form.type') is-invalid @enderror"
                                                wire:model.lazy="form.type">
                                                <option value="" disabled>Select expense type</option>
                                                @foreach ($expenseTypes as $expenseType)
                                                    <option value="{{ $expenseType }}">{{ $expenseType }}</option>
                                                @endforeach
                                            </select>
        
                                            @error('form.type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="amount">Amount<span class="required">*</span></label>
                                            <input
                                                name="amount"
                                                id="amount"
                                                class="form-control @error('form.amount') is-invalid @enderror"
                                                type="number"
                                                wire:model.lazy="form.amount">
        
                                            @error('form.amount')
                                                <span class="invalid-feedback mb-3" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="transaction_date">Transaction Date<span class="required">*</span></label>
                                            <input
                                                name="transaction_date"
                                                id="transaction_date"
                                                class="form-control @error('form.transaction_date') is-invalid @enderror"
                                                type="date"
                                                wire:model.lazy="form.transaction_date">
        
                                            @error('form.transaction_date')
                                                <span class="invalid-feedback mb-3" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <button type="submit" class="btn btn-primary text-white">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewire('payments.payment-remit')
    
    @section('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                /** Initialize the select2 dropdown */
                $('#expense-type').select2({
                    tags: true
                })
                $('#expense-type').on('select2:select', function(e) {
                    const id = e.params.data.id
                    Livewire.emit('selectExpenseType', id)
                })

                /** Define click event to delete expense */
                $(document).on('click', '.confirm-delete-expense', function() {
                    const id = $(this).data('id')

                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: 'Expense will be deleted!',
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm',
                        cancelButtonText: 'Cancel'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            $(id).click()
                        }
                    })
                })

                /** Define the chart config */
                const chart = new Chart(
                    document.getElementById('payments-expenses'),
                    {
                        type: 'bar',
                        data: {
                            labels: @json($chartData['labels']),
                            datasets: [{
                                label: `{{ $chartData['title'] }}`,
                                data: @json($chartData['rows']),
                                backgroundColor: @json($chartData['colors'])
                            }]
                        }
                    }
                )

                Livewire.on('updateExpensesChart', data => {
                    chart.data = data
                    chart.update()
                })
            })
        </script>
    @endsection
</div>
