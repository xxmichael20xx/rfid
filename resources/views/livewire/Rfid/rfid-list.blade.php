<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">RFID Panel - Listing</h1>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-8">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-body px-3 pb-3">
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
        <div class="col-4">
            <div class="app-card app-card-chart h-100 shadow-sm" wire:ignore.self>
                <div class="app-card-header p-3 bg-portal-green">
                    <div class="row">
                        <h4 class="app-card-title">RFID Registration</h4>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <form method="POST" wire:submit.prevent="create">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="home_owner_id" class="text-dark">Home Owner*</label>
                                <select
                                    name="home_owner_id"
                                    id="home_owner_id"
                                    class="form-select @error('rfidForm.home_owner_id') is-invalid @enderror"
                                    wire:model="rfidForm.home_owner_id">
                                    <option value="" disabled selected>Select home owner</option>
                                    @forelse ($unassignedHomeOwners as $item)
                                        <option value="{{ $item->id }}">{{ $item->full_name }}</option>
                                    @empty
                                        <option value="" disabled>No available home owner</option>
                                    @endforelse
                                </select>
                                @error('rfidForm.home_owner_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('rfid form.', '', $message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="rfid" class="text-dark">RFID*</label>
                                <input
                                    name="rfid"
                                    id="rfid"
                                    class="form-control @error('rfidForm.rfid') is-invalid @enderror"
                                    wire:model="rfidForm.rfid">
                                @error('rfidForm.rfid')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('rfid form.', '', $message) }}</strong>
                                    </span>
                                @enderror
                                <small class="text-help">Note: Initial setup until the feature for rfid is integrated.</small>
                            </div>
                        </div>

                        <div class="row">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                /* new Choices('#home_owner_id', {
                    searchEnabled: true,
                    searchChoices: true,
                    allowHTML: true,
                }) */

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
                                    Livewire.emit('delete-rfid', { id: id })
                                }
                            })
                        })
                    })
                }
            })

        </script>
    @endsection
</div>
