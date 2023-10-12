<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">RFID Panel - Listing</h1>
            <a href="{{ route('rfid.monitoring') }}" class="btn btn-success text-white">Go to Monitoring</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">RFID</th>
                                                <th class="cell">Home Owner</th>
                                                <th class="cell">Date Registered</th>
                                                <th class="cell">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($rfids as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->rfid }}</td>
                                                    <td class="cell">{{ $data->homeOwner->full_name }}</td>
                                                    <td class="cell">{{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y @ h:s A') }}</td>
                                                    <td class="cell d-flex">
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger text-white p-2 confirm-delete-rfid"
                                                            data-id="{{ $data->id }}"
                                                            data-name="{{ $data->homeOwner->full_name }}">
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
                                <p class="card-title h5">RFID Registration</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" wire:submit.prevent="create" class="col-12">
                                @csrf
        
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <select
                                                name="home_owner_id"
                                                id="home_owner_id"
                                                class="form-select @error('rfidForm.home_owner_id') is-invalid @enderror"
                                                wire:model.lazy="rfidForm.home_owner_id">
                                                <option value="" disabled selected>Select home owner</option>
                                                @forelse ($unassignedHomeOwners as $item)
                                                    <option value="{{ $item->id }}">{{ $item->full_name }}</option>
                                                @empty
                                                    <option value="" disabled>No available home owner</option>
                                                @endforelse
                                            </select>
                                            <label for="home_owner_id">Home Owner*</label>
        
                                            @error('rfidForm.home_owner_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('rfid form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input
                                                name="rfid"
                                                id="rfid"
                                                class="form-control @error('rfidForm.rfid') is-invalid @enderror"
                                                type="text"
                                                wire:model.lazy="rfidForm.rfid">
                                            <label for="rfid">RFID*</label>
        
                                            @error('rfidForm.rfid')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('rfid form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <button type="button" class="btn btn-info text-white" id="scan-id">Scan Id</button>
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
    
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                /** Initialize click event for confirm-delete-rfid */
                const confirmDeleteRfid = document.querySelectorAll('.confirm-delete-rfid')
                if (confirmDeleteRfid.length > 0) {
                    Array.from(confirmDeleteRfid).forEach((item) => {
                        const id = item.getAttribute('data-id')
                        const name = item.getAttribute('data-name')
                        item.addEventListener('click', () => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure?',
                                text: `RFID for \'${name}\' will be deleted and this can\'t be undone!`,
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!'
                            }).then((e) => {
                                if (e.isConfirmed) {
                                    Livewire.emit('deleteRfid', { id: id })
                                }
                            })
                        })
                    })
                }

                /** Initialize click event for scan-id */
                const scanId = document.getElementById('scan-id')
                if (scanId) {
                    let loading

                    scanId.addEventListener('click', () => {
                        loading = Swal.fire({
                            title: 'Please scan an id',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: true,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        })

                        let channel = window.Echo.channel('my-channel')
                        channel.listen('.scan-id', function({ id }) {
                            loading.close()

                            setTimeout(() => {
                                if (id || id !== '') {
                                    Livewire.emit('setRfid', id)

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Scan Success',
                                        text: 'Successfully scanned an id!'
                                    })
                                    
                                } else {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Invalid',
                                        text: 'The scanned id is invalid!'
                                    })
                                }
                            }, 500)
                        })
                    })
                }
            })
        </script>
    @endsection
</div>
