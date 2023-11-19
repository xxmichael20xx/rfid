<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Manage RFID</h1>
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
                                <p class="card-title h5">List of RFID</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">RFID</th>
                                                <th class="cell">Home Owner</th>
                                                <th class="cell">Vehicle</th>
                                                <th class="cell">Date Registered</th>
                                                <th class="cell">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($rfids as $data)
                                                @php
                                                    $vehicleData = App\Models\HomeOwnerVehicle::where('id', $data->vehicle_id)->first();
                                                    $homeOwner = App\Models\HomeOwner::withTrashed()->where('id', $vehicleData->home_owner_id)->first();

                                                    $isArchived = (bool) $homeOwner->deleted_at;
                                                    $archivedText = 'text-dark';

                                                    if ($isArchived) {
                                                        $archivedText = 'text-danger';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="cell">{{ $data->rfid }}</td>
                                                    <td class="cell">
                                                        <span
                                                            class="fw-bold {{ $archivedText }}"
                                                            @if ($isArchived)
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                data-bs-title="This Home Owner is archived"
                                                            @endif
                                                        >
                                                            {{ $homeOwner->last_full_name }}
                                                        </span>
                                                    </td>
                                                    <td class="cell">
                                                        {{ $data->vehicle->car_type }}
                                                        <br>
                                                        <span class="text-dark fw-bold">{{ $data->vehicle->plate_number }}</span>
                                                    </td>
                                                    <td class="cell">{{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y @ h:s A') }}</td>
                                                    <td class="cell d-flex">
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger text-white p-2 confirm-delete-rfid"
                                                            data-id="{{ $data->id }}"
                                                            data-name="{{ $homeOwner->full_name }}">
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
        
                                <div class="row mb-3" wire:ignore>
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="vehicle_id">Home Owner - Vehicle<span class="required">*</span></label>
                                            <select
                                                id="vehicle_id"
                                                class="form-control @error('rfidForm.vehicle_id') is-invalid @enderror"
                                                wire:model.lazy="form.vehicle_id">
                                                @forelse ($unassignedVehicles as $key => $unassignedVehicle)
                                                    <optgroup label="{{ $key }}">
                                                        @foreach ($unassignedVehicle as $vehicleKey => $vehicleData)
                                                            <option value="{{ $vehicleData['vehicle_id'] }}">{{ $vehicleData['vehicle'] }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @empty
                                                    <option value="" disabled>No available vehicle to assign</option>
                                                @endforelse
                                            </select>
        
                                            @error('rfidForm.vehicle_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('rfid form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="rfid">RFID<span class="required">*</span></label>
                                            <input
                                                name="rfid"
                                                id="rfid"
                                                class="form-control @error('rfidForm.rfid') is-invalid @enderror"
                                                type="text"
                                                wire:model.lazy="rfidForm.rfid">
        
                                            @error('rfidForm.rfid')
                                                <span class="invalid-feedback mb-3" role="alert">
                                                    <strong>{{ str_replace('rfid form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                            <small class="text-help fw-bold">Note: Please click the RFID input before tapping the RFID</small>
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
    
    @section('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        
        <script>
            $(document).ready(function() {
                /** Initialize click event for confirm-delete-rfid */
                $(document).on('click', '.confirm-delete-rfid', function() {
                    const id = $(this).data('id')
                    const name = $(this).data('name')

                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: `RFID for \'${name}\' will be deleted and this can\'t be undone!`,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            Livewire.emit('deleteRfid', { id: id })
                        }
                    })
                })

                /** Initialize the select2 for vehicles and events */
                $('#vehicle_id').select2()
                $('#vehicle_id').on('select2:select', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('selectVehicle', id)
                })
                $('#vehicle_id').on('select2:unselect', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('unselectVehicle', id)
                })
            })
        </script>
    @endsection
</div>
