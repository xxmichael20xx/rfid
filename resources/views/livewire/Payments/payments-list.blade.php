<div>
    @section('styles')
        <style>
            .offcanvas-backdrop.show {
                display: none !important;
            }
        </style>
    @endsection

    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Manage Association Payments</h1>
            <div class="col-auto">
                <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newPaymentModal">
                    <i class="fa fa-money-bill"></i> Add New
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow h-100">
                <div class="app-card-body p-3 p-lg-4 d-flex justify-content-center">
                    <i class="fa fa-money-bill fa-3x me-3"></i>
                    <div>
                        <h4 class="stats-type text-dark mb-1">Cash On-hand</h4>
                        <div class="stats-figure">₱{{ number_format($cashOnHand) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow h-100">
                <div class="app-card-body p-3 p-lg-4 d-flex justify-content-center">
                    <i class="fa fa-warning fa-3x me-3"></i>
                    <div>
                        <h4 class="stats-type text-dark mb-1">Due Today</h4>
                        <div class="stats-figure">{{ number_format($dueToday) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <p class="card-title h5">List of payments</p>
                            </div>
                            <div class="col-8 text-right">
                                <div class="row justify-content-end">
                                    <div class="col-4 d-flex flex-column">
                                        <div class="input-container input-group me-2">
                                            <input type="search" name="search" id="search" class="form-control" placeholder="Search..." value="{{ request()->get('search') }}">
                                            <button class="btn btn-secondary" type="button" id="search-btn">Search</button>
                                        </div>
                                        @if ($hasSearchFilter)
                                            <a href="{{ route('payments.list') }}" class="text-help mt-2">Clear search/filters</a>
                                        @endif
                                    </div>
                                    <div class="col-2">
                                        <button
                                            class="btn btn-secondary text-white"
                                            type="button"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#paymentsFilterDrawer"
                                            aria-controls="paymentsFilterDrawer"
                                            >
                                            <i class="fa fa-filter"></i> Filters
                                        </button>

                                        {{-- <button type="button" class="btn btn-primary text-white" wire:click="exportToCsv">
                                            <i class="fa fa-cloud-arrow-down"></i> Export to CSV
                                        </button> --}}

                                        <div
                                            class="offcanvas offcanvas-end"
                                            tabindex="-1"
                                            id="paymentsFilterDrawer"
                                            aria-labelledby="paymentsFilterDrawerLabel"
                                            wire:ignore.self
                                        >
                                            <div class="offcanvas-header">
                                                <h5 class="offcanvas-title" id="paymentsFilterDrawerLabel">Payments Filters</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <div class="input-container" wire:ignore.self>
                                                            <label for="filter_by_status">Filter by status</label>
                                                            <select
                                                                name="filter_by_status"
                                                                id="filter_by_status"
                                                                class="form-select fitler-change"
                                                                wire:model.lazy="filters.status">
                                                                <option value="all" disabled>Select filter</option>
                                                                <option value="pending">Pending</option>
                                                                <option value="paid">Paid</option>
                                                            </select>
                                                            @php
                                                                $resetFilterShow = 'display: none;';
                                                                if ($filters['status'] !== 'all') {
                                                                    $resetFilterShow = 'display: block;';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="text-help text-success reset-filter fw-bold clickable"
                                                                data-id="filter_by_status"
                                                                style="{{ $resetFilterShow }}"
                                                            >
                                                                Reset filter
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="input-container" wire:ignore.self>
                                                            <label for="filter_by_mode">Filter by mode</label>
                                                            <select
                                                                name="filter_by_mode"
                                                                id="filter_by_mode"
                                                                class="form-select fitler-change"
                                                                wire:model.lazy="filters.mode">
                                                                <option value="all" disabled>Select filter</option>
                                                                @foreach ($paymentModes as $paymentMode)
                                                                    <option value="{{ $paymentMode }}">{{ $paymentMode }}</option>
                                                                @endforeach
                                                            </select>
                                                            @php
                                                                $resetFilterShow = 'display: none;';
                                                                if ($filters['mode'] !== 'all') {
                                                                    $resetFilterShow = 'display: block;';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="text-help text-success reset-filter fw-bold clickable"
                                                                data-id="filter_by_mode"
                                                                style="{{ $resetFilterShow }}"
                                                            >
                                                                Reset filter
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="input-container" wire:ignore.self>
                                                            <label for="filter_by_month">Filter by month</label>
                                                            <select
                                                                name="filter_by_month"
                                                                id="filter_by_month"
                                                                class="form-select fitler-change"
                                                                wire:model.lazy="filters.month">
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
                                                            @php
                                                                $resetFilterShow = 'display: none;';
                                                                if ($filters['month'] !== 'all') {
                                                                    $resetFilterShow = 'display: block;';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="text-help text-success reset-filter fw-bold clickable"
                                                                data-id="filter_by_month"
                                                                style="{{ $resetFilterShow }}"
                                                            >
                                                                Reset filter
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="input-container" wire:ignore.self>
                                                            <label for="filter_by_year">Filter by year</label>
                                                            <select
                                                                name="filter_by_year"
                                                                id="filter_by_year"
                                                                class="form-select fitler-change"
                                                                wire:model.lazy="filters.year">
                                                                <option value="all" disabled>Select year</option>
                                                                @php
                                                                    $currentYear = date('Y');
                                                                    $years = range($currentYear - 3, $currentYear + 2);
                                                                @endphp
                                                                @foreach ($years as $year)
                                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                                @endforeach
                                                            </select>
                                                            @php
                                                                $resetFilterShow = 'display: none;';
                                                                if ($filters['year'] !== 'all') {
                                                                    $resetFilterShow = 'display: block;';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="text-help text-success reset-filter fw-bold clickable"
                                                                data-id="filter_by_year"
                                                                style="{{ $resetFilterShow }}"
                                                            >
                                                                Reset filter
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <button type="button" class="btn btn-success text-white" id="filter-payments">Filter Payments</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="theme-separator">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Name</th>
                                                <th class="cell">Block & Lot</th>
                                                <th class="cell">Amount</th>
                                                <th class="cell">Due Date</th>
                                                <th class="cell">Status</th>
                                                <th class="cell">Received By</th>
                                                <th class="cell">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($payments as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->biller->last_full_name }}</td>
                                                    <td class="cell">{{ $data->block_lot_item }}</td>
                                                    <td class="cell">
                                                        ₱{{ number_format($data->amount, 2) }}
                                                        @if ($data->reference)
                                                            <br>
                                                            Referenece: {{ $data->reference }}
                                                        @endif
                                                    </td>
                                                    <td class="cell">
                                                        @php
                                                            $dueDate = Carbon\Carbon::parse($data->due_date);
                                                            $diffInDays = Carbon\Carbon::now()->diffInDays($dueDate);
                                                            $dueClass = 'text-dark';

                                                            if ($diffInDays <= 3 && $data->status != 'paid') {
                                                                $dueClass = 'text-danger fw-bold';
                                                            }
                                                        @endphp
                                                        <p class="{{ $dueClass }}">{{ Carbon\Carbon::parse($dueDate)->format('M d, Y') }}</p>
                                                    </td>
                                                    <td class="cell">
                                                        @php
                                                            $status = $data->status;
                                                            $badgeClass = 'danger';

                                                            if ($status == 'paid') {
                                                                $badgeClass = 'success';
                                                            }
                                                        @endphp
                                                        <div class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</div>
                                                    </td>
                                                    <td class="cell">
                                                        {{ $data->payment_received_by }}

                                                        @if ($data->payment_received_by !== 'N/A')
                                                            <small class="text-help m-0 p-0 d-block">{{ \Carbon\Carbon::parse($data->date_paid)->format('M d, Y @ h:i A') }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="cell">
                                                        @if ($data->status !== 'paid' && $data->status !== 'failed')
                                                            <button type="button" class="btn btn-success text-white px-2 manage-payment" data-id="{{ $data->id }}">
                                                                <i class="fa fa-money-bill"></i> Manage
                                                            </button>
                                                            <button type="button" class="btn btn-warning text-dark px-2 reminder-payment" data-data="{{ $data }}">
                                                                <i class="fa fa-warning"></i> Send Reminder
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="8">No result(s)</td>
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
    </div>

    @livewire('payments.payment-remit')

    <div class="modal fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="preCreateSubmit">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newPaymentModalLabel">Add Payment Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3 d-flex flex-column" wire:ignore>
                                    <label for="home_owner_id">Biller<span class="required">*</span></label>
                                    <select
                                        name="home_owner_id"
                                        id="home_owner_id"
                                        class="form-select"
                                        wire:model.lazy="form.home_owner_id"
                                    >
                                        <option value="" selected disabled>Select biller</option>
                                        @forelse ($homeOwners as $data)
                                            <option value="{{ $data->id }}">{{ $data->last_full_name }}</option>
                                        @empty
                                            <option value="" disabled>No available biller</option>
                                        @endforelse
                                    </select>

                                    @error('form.home_owner_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="type_id">Block & Lot<span class="required">*</span></label>
                                    <select
                                        name="type_id"
                                        id="type_id"
                                        class="form-select"
                                        wire:model.lazy="form.block_lot">
                                        <option value="" disabled>Select block & lot</option>
                                        @forelse ($homeOwnerBlockLots as $homeOwnerBlockLot)
                                            <option value="{{ $homeOwnerBlockLot['id'] }}">{{ $homeOwnerBlockLot['block_lot'] }}</option>
                                        @empty
                                            <option value="" disabled>No block & lot</option>
                                        @endforelse
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
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="amount">Amount<span class="required">*</span></label>
                                    <input
                                        id="amount"
                                        name="amount"
                                        type="number"
                                        class="form-control @error('form.amount') is-invalid @enderror"
                                        wire:model.lazy="form.amount">

                                    @error('form.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="recurring_date">Recurring Date<span class="required">*</span></label>
                                    <select
                                        name="recurring_date"
                                        id="recurring_date"
                                        class="form-select @error('form.recurring_date') is-invalid @enderror"
                                        wire:model.lazy="form.recurring_date"
                                        wire:change="changeRecurringDate">
                                        <option value="" disabled selected>Select day</option>
                                        @foreach (range(1, 28) as $day)
                                            <option value="{{ $day }}">Every {{ getOrdinalSuffix($day) }}</option>
                                        @endforeach
                                    </select>

                                    @error('form.recurring_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror

                                    @if ($recurringNotes)
                                        <p class="text-help">Note: {{ $recurringNotes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="input-container">
                                    <label>Payment Mode</label>
                                    <p class="fw-bold text-dark d-block m-0">Cash</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_paid" wire:model.lazy="form.is_paid">
                                    <label class="form-check-label text-dark" for="is_paid">Mark as paid</label>
                                </div>
                            </div>
                            @if ($form['is_paid'] == true)
                                <div class="col-12">
                                    <div class="input-container mb-3">
                                        <label for="reference">Reference (optional)</label>
                                        <textarea
                                            id="reference"
                                            name="reference"
                                            class="form-control form-control--textarea @error('form.reference') is-invalid @enderror"
                                            wire:model.lazy="form.reference"
                                            rows="5"></textarea>

                                        @error('form.reference')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary text-white">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="preparePaymentForm" tabindex="-1" aria-labelledby="preparePaymentFormLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="preSubmitPayForm">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="preparePaymentFormLabel">Payment Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label>Biller</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        wire:model.lazy="payForm.billerName"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label>Block & Lot</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        wire:model.lazy="payForm.blockLot"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="amount">Amount<span class="required">*</span></label>
                                    <input
                                        id="amount"
                                        name="amount"
                                        type="number"
                                        class="form-control @error('payForm.amount') is-invalid @enderror"
                                        readonly
                                        wire:model.lazy="payForm.amount">

                                    @error('payForm.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('pay form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="pay_due_date">Due Date</label>
                                    <input
                                        id="pay_due_date"
                                        name="pay_due_date"
                                        type="text"
                                        class="form-control"
                                        readonly
                                        wire:model.lazy="payForm.due_date">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="input-container">
                                    <label>Payment Mode</label>
                                    <p class="fw-bold text-dark d-block m-0">Cash</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pay_form_is_paid" wire:model.lazy="payForm.is_paid">
                                    <label class="form-check-label text-dark" for="pay_form_is_paid">Mark as paid</label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary text-white">Mark as paid</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                /** Add keydown event to search input */
                const search = document.getElementById('search')
                search.addEventListener('keydown', (event) => {
                    if (event.keyCode === 13 && search.value !== '') {
                        window.location.href = setUrlParam('search', search.value)
                    }
                })

                /** Define click event to search */
                $(document).on('click', '#search-btn', function() {
                    if (search.value) {
                        window.location.href = setUrlParam('search', search.value)
                    }
                })

                /** Define click events on update status button */
                $(document).on('click', '.manage-payment', function() {
                    const id = $(this).data('id')

                    Livewire.emit('preparePayForm', id)
                })

                /** Define the event to display pay form */
                Livewire.on('prepared.pay-form', function() {
                    const preparePaymentForm = new bootstrap.Modal('#preparePaymentForm', {})
                    preparePaymentForm.show()
                })

                /** Define the event for amount confirmation on mark as paid */
                Livewire.on('pre-submit.pay-form', function() {
                    let html = `
                        <p>
                            You changes the <b>amount</b> and form payment is marked as <b>paid</b>.
                            <br>
                            The recurring payment will use this amount!
                        </p>
                    `
                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        html: html,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes confirm',
                        cancelButtonText: 'Cancel'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            Livewire.emit('payFormSubmit')
                        }
                    })
                })

                /** Define click event on filter off-canvas/drawer */
                $(document).on('click', '#filter-payments', function() {
                    const status = $('#filter_by_status').val() || 'all'
                    const mode = $('#filter_by_mode').val() || 'all'
                    const month = $('#filter_by_month').val() || 'all'
                    const year = $('#filter_by_year').val() || 'all'
                    const filters = [
                        {key: 'status', value: status},
                        {key: 'mode', value: mode},
                        {key: 'month', value: month},
                        {key: 'year', value: year},
                    ]

                    let currentUrl = new URL(window.location.href)
                    let urlSearch = new URLSearchParams(currentUrl.search)

                    filters.forEach(function(filter) {
                        if (filter.value === 'all') {
                            urlSearch.delete(filter.key)
                        } else {
                            if (filter.value !== 'all' && typeof filter.value == 'string') {
                                if (urlSearch.size < 1) {
                                    urlSearch.append(filter.key, filter.value)
                                } else {
                                    urlSearch.set(filter.key, filter.value)
                                }
                            }
                        }
                    })

                    if (urlSearch.size < 1) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No filter selected'
                        })
                    } else {
                        currentUrl.search = '?' + urlSearch.toString()
                        window.location.href = currentUrl
                    }
                })

                /** Define reset filter individually */
                $(document).on('click', '.reset-filter', function() {
                    const id = $(this).data('id')
                    const filter = $('#' + id)

                    filter.val('all').change()
                    $(this).hide()
                })

                /** Define filter change event */
                $(document).on('change', '.fitler-change', function() {
                    const id = $(this).attr('id')
                    const resetFilter = $(`.reset-filter[data-id="${id}"]`)

                    resetFilter.show()
                })

                /** Define click event for button reminder-payment */
                $(document).on('click', '.reminder-payment', function() {
                    const data = $(this).data('data')
                    if (! data.biller.email) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Biller has no email',
                            text: 'Can\'t send email reminder because the biller has no email.'
                        })
                        return false
                    }

                    Livewire.emit('sendReminder', data)
                })

                /** Define function set set the URL parameter */
                function setUrlParam(key, value) {
                    let currentUrl = new URL(window.location.href)
                    let urlSearch = new URLSearchParams(currentUrl.search)

                    if (urlSearch.size < 1) {
                        urlSearch.append(key, value)
                    } else {
                        urlSearch.set(key, value)
                    }

                    currentUrl.search = '?' + urlSearch.toString()
                    return currentUrl
                }

                $('#home_owner_id').select2({
                    dropdownParent: '#newPaymentModal'
                })

                $('#home_owner_id').on('select2:select', function(e) {
                    const value = e.params.data.id

                    @this.changeCreatePaymentBiller(value)
                })

                Livewire.on('payment.pre.submit', function(e) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Payment Amount: ' + e.amount,
                        text: 'Please confirm the Amount',
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm',
                        cancelButtonText: 'Cancel'
                    }).then(function(event) {
                        if (event.isConfirmed) {
                            @this.create()
                        }
                    })
                })
            })
        </script>
    @endsection
</div>
