<div>
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <p class="card-title h5">Remitted Cash List</p>
                                <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#paymentRemitModal">
                                    <i class="fa fa-money-bill"></i> New Remit
                                </button>
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
                                                <th class="cell">Title</th>
                                                <th class="cell">Amount</th>
                                                <th class="cell">Date Remitted</th>
                                                <th class="cell">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($paymentRemits as $paymentRemit)
                                                <tr>
                                                    <td class="cell">₱{{ $paymentRemit->title }}</td>
                                                    <td class="cell">₱{{ number_format($paymentRemit->amount) }}</td>
                                                    <td class="cell">{{ Carbon\Carbon::parse($paymentRemit->date_remitted)->format('M d, Y - h:ia') }}</td>
                                                    <td class="cell">
                                                        <button
                                                            type="button"
                                                            class="visually-hidden"
                                                            id="remit-{{ $paymentRemit->id }}"
                                                            wire:click="deleteRemit({{ $paymentRemit->id }})">
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger text-white p-2 confirm-delete-remit"
                                                            data-id="#remit-{{ $paymentRemit->id }}">
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
    </div>

    <div class="modal fade" id="paymentRemitModal" tabindex="-1" aria-labelledby="paymentRemitModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form method="POST" wire:submit.prevent="submitRemit">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="paymentRemitModalLabel">Remit Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="remit_title">Title<span class="required">*</span></label>
                                    <input
                                        id="remit_title"
                                        name="remit_title"
                                        type="text"
                                        class="form-control @error('remitForm.title') is-invalid @enderror"
                                        wire:model.lazy="remitForm.title">

                                    @error('remitForm.title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('remit form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="remit_amount">Amount<span class="required">*</span></label>
                                    <input
                                        id="remit_amount"
                                        name="remit_amount"
                                        type="number"
                                        class="form-control @error('remitForm.amount') is-invalid @enderror"
                                        wire:model.lazy="remitForm.amount">

                                    @error('remitForm.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('remit form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
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

    @vite(['resources/js/app.js'])
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            /** Define click event to confirm-delete-remit */
            $(document).on('click', '.confirm-delete-remit', function() {
                const id = $(this).data('id')

                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'Remitance will be deleted!',
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
        })
    </script>
</div>
