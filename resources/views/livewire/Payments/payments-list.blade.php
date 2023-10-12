<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Payments History</h1>
            <div class="col-auto">
                <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newPaymentModal">
                    <i class="fa fa-money-bill"></i> Add New
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex">
                <div class="form-floating me-2">
                    <input type="search" name="search" id="search" class="form-control" placeholder="Search..." value="{{ $search }}">
                    <label>Search...</label>
                </div>
            </div>
            @if ($search)
                <a href="{{ route('payments.list') }}" class="text-help">Clear search</a>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        @if ($search)
                            <div class="row py-3">
                                <div class="col-12">
                                    <h5>Search results for `{{ $search }}`</h5>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Transaction Date</th>
                                                <th class="cell">Name</th>
                                                <th class="cell">Association Payment</th>
                                                <th class="cell">Mode of Payment</th>
                                                <th class="cell">Amount</th>
                                                <th class="cell">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($payments as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->transaction_date }}</td>
                                                    <td class="cell">{{ $data->biller->full_name }}</td>
                                                    <td class="cell">{{ $data->type }}</td>
                                                    <td class="cell">{{ $data->mode }}</td>
                                                    <td class="cell">
                                                        ₱{{ number_format($data->amount, 2) }}
                                                        @if ($data->reference)
                                                            <br>
                                                            Referenece: {{ $data->reference }}
                                                        @endif
                                                    </td>
                                                    <td class="cell">
                                                        @if ($data->status == 'paid')
                                                            Paid on: {{ $data->paid_on }}
                                                        @else
                                                            <button type="button" class="btn btn-warning text-white px-2 update-status" data-id="{{ $data->id }}">
                                                                <i class="fa fa-money-bill"></i>
                                                            </button>
                                                        @endif
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
    </div>

    {{-- Modals --}}
    <!-- newPaymentModal Modal -->
    <div class="modal fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form method="POST" wire:submit.prevent="create">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newPaymentModalLabel">New Payment Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <select
                                        name="home_owner_id"
                                        id="home_owner_id"
                                        class="form-select"
                                        wire:model.lazy="form.home_owner_id">
                                        <option value="" selected disabled>Select biller</option>
                                        @forelse ($homeOwners as $data)
                                            <option value="{{ $data->id }}">{{ $data->full_name }}</option>
                                        @empty
                                            <option value="" disabled>No available biller</option>
                                        @endforelse
                                    </select>
                                    <label for="home_owner_id">Biller*</label>
        
                                    @error('form.home_owner_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <select
                                        name="type"
                                        id="type"
                                        class="form-select"
                                        wire:model.lazy="form.type">
                                        <option value="" selected disabled>Select payment</option>
                                        @forelse ($types as $data)
                                            <option value="{{ $data }}">{{ $data }}</option>
                                        @empty
                                            <option value="" disabled>No available payment</option>
                                        @endforelse
                                    </select>
                                    <label for="type">Association Payment*</label>
        
                                    @error('form.type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <select
                                        name="mode"
                                        id="mode"
                                        class="form-select"
                                        wire:model.lazy="form.mode">
                                        <option value="" selected disabled>Select payment mode</option>
                                        @forelse ($modes as $data)
                                            <option value="{{ $data }}">{{ $data }}</option>
                                        @empty
                                            <option value="" disabled>No available payment mode</option>
                                        @endforelse
                                    </select>
                                    <label for="mode">Payment Mode*</label>
        
                                    @error('form.mode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="amount"
                                        name="amount"
                                        type="text"
                                        class="form-control @error('form.amount') is-invalid @enderror"
                                        placeholder="Ex. John"
                                        wire:model.lazy="form.amount"
                                        autofocus>
                                    <label for="amount">Amount*</label>
        
                                    @error('form.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="transaction_date"
                                        name="transaction_date"
                                        type="date"
                                        class="form-control @error('form.transaction_date') is-invalid @enderror"
                                        wire:model.lazy="form.transaction_date">
                                    <label for="transaction_date">Transaction date*</label>
        
                                    @error('form.transaction_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea
                                        id="reference"
                                        name="reference"
                                        class="form-control form-control--textarea @error('form.reference') is-invalid @enderror"
                                        wire:model.lazy="form.reference"
                                        rows="5"></textarea>
                                    <label for="reference">Reference (optional)</label>
        
                                    @error('form.reference')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success text-white">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                /** Add keydown event to search input */
                const search = document.getElementById('search')
                search.addEventListener('keydown', (event) => {
                    if (event.keyCode === 13 && search.value !== '') {
                        let currentUrl = window.location.href
                        let noParams = currentUrl.split('?')[0]

                        let newParam = noParams+'?search='+search.value
                        window.location.href = newParam
                    }
                })

                /** Define click events on update status button */
                const updateStatus = document.querySelectorAll('.update-status')
                if (updateStatus.length > 0) {
                    Array.from(updateStatus).forEach((item) => {
                        item.addEventListener('click', () => {
                            const id = item.getAttribute('data-id')

                            Swal.fire({
                                icon: 'info',
                                title: 'Are you sure?',
                                text: 'Payment will be marked as `Paid`!',
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Yes, proceed'
                            }).then((event) => {
                                if (event.isConfirmed) {
                                    Livewire.emit('updateStatus', id)
                                }
                            })
                        })
                    })
                }
            })
        </script>
    @endsection
</div>
