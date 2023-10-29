<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Manage Association Types</h1>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <p class="card-title h5">List of Types</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Type</th>
                                                <th class="cell">Amount</th>
                                                <th class="cell">Frequency</th>
                                                <th class="cell">Recurring Day</th>
                                                <th class="cell">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($paymentTypes as $paymentType)
                                                <tr>
                                                    <td class="cell">{{ $paymentType->type }}</td>
                                                    <td class="cell">₱{{ number_format($paymentType->amount) }}</td>
                                                    <td class="cell">
                                                        {{ toTitle($paymentType->frequency) }}
                                                        <small class="text-dark d-block">Mode: {{ ($paymentType->is_recurring) ? 'Recurring' : 'One-Time' }}</small>
                                                    </td>
                                                    <td class="cell">
                                                        @php
                                                            $recurringDay = $paymentType->recurring_day;
                                                            $ordinalDay = getOrdinalSuffix($recurringDay);
                                                            $frequency = $paymentType->frequency;
                                                            $every = '';
                                                            $recurringHelp = '';

                                                            if ($frequency == 'monthly') {
                                                                $every = sprintf("Every %s of the month", $ordinalDay);
                                                            } else {
                                                                $every = sprintf("Every %s of the year", $ordinalDay);
                                                                $recurringHelp = 'Note: Based on first payment';
                                                            }
                                                        @endphp
                                                        {{ $every }}
                                                        <br>
                                                        <small class="text-help">{{ $recurringHelp }}</small>
                                                    </td>
                                                    <td class="cell-d-flex">
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger text-white p-2 confirm-delete"
                                                            data-id="{{ $paymentType->id }}"
                                                            data-type="{{ $paymentType->type }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-info text-white p-2 prepare-update"
                                                            data-id="{{ $paymentType->id }}">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No result(s)</td>
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
                                <p class="card-title h5">Settings Form</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" wire:submit.prevent="create" class="col-12">
                                @csrf
        
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="type">Type<span class="required">*</span></label>
                                            <input
                                                name="type"
                                                id="type"
                                                class="form-control @error('createForm.type') is-invalid @enderror"
                                                type="text"
                                                wire:model.lazy="createForm.type">
        
                                            @error('createForm.type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('create form.', '', $message) }}</strong>
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
                                                class="form-control @error('createForm.amount') is-invalid @enderror"
                                                type="number"
                                                wire:model.lazy="createForm.amount">
        
                                            @error('createForm.amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="day">Day<span class="required">*</span></label>
                                            <select
                                                name="day"
                                                id="day"
                                                class="form-select @error('createForm.day') is-invalid @enderror"
                                                wire:model.lazy="createForm.day">
                                                <option value="" disabled selected>Select day</option>
                                                @foreach (range(1, 28) as $day)
                                                    <option value="{{ $day }}">{{ $day }}</option>
                                                @endforeach
                                            </select>
                                            <label for="reference">Day of the payment should be made</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="frequency">Frequency<span class="required">*</span></label>
                                            <select
                                                name="frequency"
                                                id="frequency"
                                                class="form-select @error('createForm.frequency') is-invalid @enderror"
                                                wire:model.lazy="createForm.frequency">
                                                <option value="" disabled selected>Select frequency</option>
                                                @foreach ($frequencies as $frequency)
                                                    <option value="{{ $frequency }}">{{ toTitle($frequency) }}</option>
                                                @endforeach
                                            </select>
        
                                            @error('createForm.frequency')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_recurring" wire:model.lazy="createForm.is_recurring">
                                            <label class="form-check-label text-dark" for="is_recurring">Recurring Payment</label>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary text-white">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateFormModal" tabindex="-1" aria-labelledby="updateFormModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <form method="POST" wire:submit.prevent="update">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="updateFormModalLabel">Update - Settings Form</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="input-container mb-3">
                                        <label for="type">Type<span class="required">*</span></label>
                                        <input
                                            name="type"
                                            id="type"
                                            class="form-control @error('updateForm.type') is-invalid @enderror"
                                            type="text"
                                            wire:model.lazy="updateForm.type">
    
                                        @error('updateForm.type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="input-container mb-3">
                                        <label for="update_amount">Amount<span class="required">*</span></label>
                                        <input
                                            name="amount"
                                            id="update_amount"
                                            class="form-control @error('updateForm.amount') is-invalid @enderror"
                                            type="number"
                                            wire:model.lazy="updateForm.amount">
    
                                        @error('updateForm.amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="input-container mb-3">
                                        <label for="recurring_day">Day<span class="required">*</span></label>
                                        <select
                                            name="recurring_day"
                                            id="recurring_day"
                                            class="form-select @error('updateForm.recurring_day') is-invalid @enderror"
                                            wire:model.lazy="updateForm.recurring_day">
                                            <option value="" disabled>Select day</option>
                                            @foreach (range(1, 28) as $day)
                                                <option value="{{ $day }}">{{ $day }}</option>
                                            @endforeach
                                        </select>
                                        <label for="reference">Day of the payment should be made</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="input-container mb-3">
                                        <label for="frequency">Frequency<span class="required">*</span></label>
                                        <select
                                            name="frequency"
                                            id="frequency"
                                            class="form-select @error('updateForm.frequency') is-invalid @enderror"
                                            wire:model.lazy="updateForm.frequency">
                                            <option value="" disabled selected>Select frequency</option>
                                            @foreach ($frequencies as $frequency)
                                                <option value="{{ $frequency }}">{{ toTitle($frequency) }}</option>
                                            @endforeach
                                        </select>
    
                                        @error('updateForm.frequency')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success text-white">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @section('scripts')
        <script>
            $(document).ready(function() {
                /** Define click event on delete button */
                $(document).on('click', '.confirm-delete', function() {
                    const id = $(this).data('id')
                    const type = $(this).data('type')

                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: `Payment Type '${type}' will be archived!`,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            Livewire.emit('deletePaymentType', id)
                        }
                    })
                })

                /** Define click event on edit button */
                $(document).on('click', '.prepare-update', function() {
                    const id = $(this).data('id')

                    Livewire.emit('prepareUpdate', id)
                })

                /** Define event to show update modal form */
                Livewire.on('prepared.setting-payment', function() {
                    const updateFormModal = new bootstrap.Modal('#updateFormModal', {})
                    updateFormModal.show()
                })
            })
        </script>
    @endsection
</div>
