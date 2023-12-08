<div>
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Manage Home Owner Details</h1>
            <div class="col-auto">
                <a href="{{ route('homeowners.list') }}" class="btn btn-success text-white">
                    <i class="fa fa-hand-point-left"></i> Go back
                </a>
                <a href="{{ route('homeowners.update', ['id' => $data->id]) }}" class="btn btn-info text-white p-2 ms-2">
                    <i class="fa fa-pencil"></i> Update
                </a>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-6">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-12">
                                @if ($data->profile)
                                    <img
                                        src="{{ $data->profile }}"
                                        alt="Home-Owner-Avatar"
                                        class="img-fluid mb-3 rounded shadow"
                                        style="width: 250px;"
                                    />
                                @endif
                            </div>
                            <div class="col-12">
                                <p class="card-title h5">Home Owner: {{ $data->last_full_name }}</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <p class="text-dark"><b>Date of Birth:</b> {{ $data->date_of_birth }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-dark"><b>Age:</b> {{ $data->age }} year(s) old</p>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-dark"><b>Gender:</b> {{ ucfirst($data->gender) }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="text-dark"><b>Email:</b> {{ $data->email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6">
                                <p class="text-dark"><b>Contact Number:</b> {{ $data->contact_no }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="text-dark"><b>Member Since:</b> {{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion mb-5" id="accordionHomeOwner" wire:ignore>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingListOfFamilyMembers">
                <button
                    class="accordion-button text-dark"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseLiftOfFamilyMembers"
                    aria-expanded="true"
                    aria-controls="collapseLiftOfFamilyMembers"
                >
                    <i class="fa fa-users me-2"></i> Family Members
                </button>
            </h2>
            <div id="collapseLiftOfFamilyMembers" class="accordion-collapse collapse show" aria-labelledby="headingListOfFamilyMembers" data-bs-parent="#accordionHomeOwner">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-between mb-3">
                            <p class="card-title h5">Manage Family Members</p>
                            <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newProfileModal">
                                <i class="fa fa-user-plus"></i> Add New
                            </button>
                        </div>
                        <hr class="theme-separator">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Name</th>
                                            <th class="cell">Date of Birth</th>
                                            <th class="cell">Contact No</th>
                                            <th class="cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data->profiles as $profile)
                                            <tr>
                                                <td class="cell">{{ $profile->full_name }}</td>
                                                <td class="cell">{{ \Carbon\Carbon::parse($profile->date_of_borth)->format('M d, Y') }}</td>
                                                <td class="cell">{{ $profile->contact_no ?? 'No contact number' }}</td>
                                                <td class="cell d-flex">
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger text-white p-2 confirm-delete-profile"
                                                        data-id="{{ $profile->id }}"
                                                        data-name="{{ $profile->full_name }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="btn btn-info text-white p-2 ms-2"
                                                        wire:click="setUpdate({{ $profile->id }})">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    <div wire:ignore>
                                                        @livewire('profile.profile-view', ['profileId' => $profile->id])
                                                    </div>
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

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingBlockAndLots">
                <button
                    class="accordion-button text-dark"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseBlockAndLots"
                    aria-expanded="false"
                    aria-controls="collapseBlockAndLots"
                >
                    <i class="fa fa-box me-2"></i> Block & Lots
                </button>
            </h2>
            <div id="collapseBlockAndLots" class="accordion-collapse collapse" aria-labelledby="headingBlockAndLots" data-bs-parent="#accordionHomeOwner">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-between mb-3">
                            <p class="card-title h5">Manage Block & Lots</p>
                            <button type="button" class="btn btn-success text-white" id="newBlockAndLotModalBtn">
                                <i class="fa fa-plus"></i> Add New
                            </button>
                        </div>
                        <hr class="theme-separator">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Block</th>
                                            <th class="cell">Lot</th>
                                            <th class="cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data->grouped_block_lots as $groupedKey => $groupedBlockLots)
                                            <tr>
                                                <td class="cell">Block {{ $groupedKey }}</td>
                                                <td class="cell">Lot {{ $groupedBlockLots[0]['lotName'] }}</td>
                                                <td class="cell d-flex">
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger text-white p-2 delete-lot"
                                                        data-id="{{ $groupedBlockLots[0]['id'] }}"
                                                        data-lot="Lot {{ $groupedBlockLots[0]['lotName'] }}"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @foreach ($groupedBlockLots as $groupedBlockLotKey => $groupedBlockLot)
                                                @if ($groupedBlockLotKey > 0)
                                                    <tr>
                                                        <td></td>
                                                        <td>Lot {{ $groupedBlockLot['lotName'] }}</td>
                                                        <td class="cell d-flex">
                                                            <button
                                                                type="button"
                                                                class="btn btn-danger text-white p-2 delete-lot"
                                                                data-id="{{ $groupedBlockLot['id'] }}"
                                                                data-lot="Lot {{ $groupedBlockLot['lotName'] }}"
                                                            >
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td class="cell text-center" colspan="3">No result(s)</td>
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

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingVehicles">
                <button
                    class="accordion-button text-dark"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseVehicles"
                    aria-expanded="false"
                    aria-controls="collapseVehicles"
                >
                    <i class="fa fa-car me-2"></i> Vehicles
                </button>
            </h2>
            <div id="collapseVehicles" class="accordion-collapse collapse" aria-labelledby="headingVehicles" data-bs-parent="#accordionHomeOwner">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-between mb-3">
                            <p class="card-title h5">Manage Vehicles</p>
                            <button
                                type="button"
                                class="btn btn-success text-white"
                                id="newVehicleModalBtn"
                                wire:click="setDefaults"
                            >
                                <i class="fa fa-plus"></i> Add New
                            </button>
                        </div>
                        <hr class="theme-separator">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Plate Number</th>
                                            <th class="cell">Vehicle Type</th>
                                            <th class="cell">Name</th>
                                            <th class="cell">RFID</th>
                                            <th class="cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data->vehicles as $vehicle)
                                            @php
                                                if ($rfid = $vehicle->rfid) {
                                                    $rfid = $rfid->rfid;
                                                } else {
                                                    $rfid = 'No assigned RFID';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="cell">{{ $vehicle->plate_number }}</td>
                                                <td class="cell">{{ $vehicle->car_type }}</td>
                                                <td class="cell">{{ $vehicle->car_name }}</td>
                                                <td class="cell">{{ $rfid }}</td>
                                                <td class="cell d-flex">
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger text-white p-2 delete-vehicle"
                                                        data-id="{{ $vehicle->id }}"
                                                        data-plate="{{ $vehicle->plate_number }}"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-info text-white p-2 ms-2"
                                                        wire:click="prepareUpdateVehicle({{ $vehicle->id }})"
                                                    >
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="cell text-center" colspan="5">No result(s)</td>
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

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingVisitors">
                <button
                    class="accordion-button text-dark"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseVisitors"
                    aria-expanded="false"
                    aria-controls="collapseVisitors"
                >
                    <i class="fa fa-walking me-2"></i> Visitors
                </button>
            </h2>
            <div id="collapseVisitors" class="accordion-collapse collapse" aria-labelledby="headingVisitors" data-bs-parent="#accordionHomeOwner">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-between mb-3">
                            <p class="card-title h5">Visitor Listing</p>
                            <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newVisitorQr">
                                <i class="fa fa-plus"></i> Add New
                            </button>
                        </div>
                        <hr class="theme-separator">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Visitor Name</th>
                                            <th class="cell">QR Code</th>
                                            <th class="cell">Entry date</th>
                                            <th class="cell">Exit date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data->visitors as $visitor)
                                            @php
                                                $dateVisited = $visitor->date_visited;

                                                if ($dateVisited) {
                                                    $dateVisited = Carbon\Carbon::parse($dateVisited)->format('M d, y @ h:ia');
                                                }
                                            @endphp
                                            <tr>
                                                <td class="cell">{{ $visitor->last_full_name }}</td>
                                                <td class="cell">
                                                    @if ($visitor->qr_image)
                                                        <img src="{{ asset('uploads/') }}/{{ $visitor->qr_image }}" alt="qr-code-{{ $visitor->id }}" class="img-fluid" style="width: 150px;" />
                                                    @else
                                                        <button type="button" class="btn btn-primary text-white" wire:click="generateQrCode({{ $visitor->id }})">
                                                            <i class="fa fa-qrcode"></i> Generate QR
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="cell">
                                                    @php
                                                        $timeIn = $visitor->time_in;
                                                        $timeOut = $visitor->time_out;
                                                    @endphp
                                                    @if ($timeIn)
                                                        {{ Carbon\Carbon::parse($timeIn)->format('M d, Y @ h:ia') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="cell">
                                                    @if ($timeOut)
                                                        {{ Carbon\Carbon::parse($timeOut)->format('M d, Y @ h:ia') }}
                                                        @if ($visitor->notes)
                                                            <br>
                                                            <small class="text-help">Notes: {{ $visitor->notes }}</small>
                                                        @endif
                                                    @else
                                                        N/A
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

    <div class="modal fade" id="newProfileModal" tabindex="-1" aria-labelledby="newProfileModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md-max">
            <form method="POST" wire:submit.prevent="create">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newProfileModalLabel">New Family Members Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="last_name">Last Name<span class="required">*</span></label>
                                    <input
                                        id="last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('createForm.last_name') is-invalid @enderror"
                                        wire:model.lazy="createForm.last_name"
                                        autofocus>

                                    @error('createForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="first_name">First Name<span class="required">*</span></label>
                                    <input
                                        id="first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('createForm.first_name') is-invalid @enderror"
                                        wire:model.lazy="createForm.first_name">

                                    @error('createForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="middle_name">Middle Name</label>
                                    <input
                                        id="middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('createForm.middle_name') is-invalid @enderror"
                                        wire:model.lazy="createForm.middle_name">

                                    @error('createForm.middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="date_of_birth">Date of birth<span class="required">*</span></label>
                                    <input
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        type="date"
                                        class="form-control @error('createForm.date_of_birth') is-invalid @enderror"
                                        data-age="age"
                                        wire:model.lazy="createForm.date_of_birth">

                                    @error('createForm.date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container">
                                    <label for="age">Age</label>
                                    <input
                                        id="age"
                                        name="age"
                                        type="text"
                                        class="form-control disabled"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="contact_no">Contact Number</label>
                                    <input
                                        id="contact_no"
                                        name="contact_no"
                                        type="number"
                                        class="form-control @error('createForm.contact_no') is-invalid @enderror"
                                        wire:model.lazy="createForm.contact_no">

                                    @error('createForm.contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container">
                                    <label for="gender">Gender<span class="required">*</span></label>
                                    <div class="form-check form-check-inline w-100">
                                        <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="createForm.gender" id="gender-male" value="male">
                                        <label class="form-check-label mb-0 ms-2" for="gender-male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="createForm.gender" id="gender-female" value="female">
                                        <label class="form-check-label mb-0 ms-2" for="gender-female">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="notes">Notes</label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        class="form-control form-control--textarea @error('createForm.notes') is-invalid @enderror"
                                        wire:model.lazy="createForm.notes"
                                        rows="5"></textarea>

                                    @error('createForm.notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
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

    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md-max">
            <form method="POST" wire:submit.prevent="update">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateProfileModalLabel">Update Family Member Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="update_last_name">Last Name<span class="required">*</span></label>
                                    <input
                                        id="update_last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('updateForm.last_name') is-invalid @enderror"
                                        wire:model.lazy="updateForm.last_name"
                                        autofocus>

                                    @error('updateForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="update_first_name">First Name<span class="required">*</span></label>
                                    <input
                                        id="update_first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('updateForm.first_name') is-invalid @enderror"
                                        wire:model.lazy="updateForm.first_name">

                                    @error('updateForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="update_middle_name">Middle Name</label>
                                    <input
                                        id="update_middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('updateForm.middle_name') is-invalid @enderror"
                                        wire:model.lazy="updateForm.middle_name">

                                    @error('updateForm.middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="update_date_of_birth">Date of birth<span class="required">*</span></label>
                                    <input
                                        id="update_date_of_birth"
                                        name="date_of_birth"
                                        type="date"
                                        class="form-control @error('updateForm.date_of_birth') is-invalid @enderror"
                                        data-age="update_age"
                                        wire:model.lazy="updateForm.date_of_birth">

                                    @error('updateForm.date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container">
                                    <label for="update_age">Age</label>
                                    <input
                                        id="update_age"
                                        name="age"
                                        type="text"
                                        class="form-control disabled"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="input-container mb-3">
                                    <label for="update_contact_no">Contact Number</label>
                                    <input
                                        id="update_contact_no"
                                        name="contact_no"
                                        type="number"
                                        class="form-control @error('updateForm.contact_no') is-invalid @enderror"
                                        wire:model.lazy="updateForm.contact_no">

                                    @error('updateForm.contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-container">
                                    <label for="gender">Gender<span class="required">*</span></label>
                                    <div class="form-check form-check-inline w-100">
                                        <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="updateForm.gender" id="update-gender-male" value="male">
                                        <label class="form-check-label mb-0 ms-2" for="update-gender-male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="updateForm.gender" id="update-gender-female" value="female">
                                        <label class="form-check-label mb-0 ms-2" for="update-gender-female">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="update_notes">Notes</label>
                                    <textarea
                                        id="update_notes"
                                        name="notes"
                                        class="form-control form-control--textarea @error('updateForm.notes') is-invalid @enderror"
                                        wire:model.lazy="updateForm.notes"
                                        rows="5"></textarea>

                                    @error('updateForm.notes')
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
                        <button type="submit" class="btn btn-success text-white">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="newVehicleModal" tabindex="-1" aria-labelledby="newVehicleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="createVehicle">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newVehicleModalLabel">New Vehicle</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="input-container mb-3 d-flex flex-column" wire:ignore>
                                        <label for="car_type">Vehicle Name<span class="required">*</span></label>
                                        <select
                                            id="car_type"
                                            class="form-select @error('createVehicleForm.car_type') is-invalid @enderror"
                                            wire:model.lazy="createVehicleForm.car_type"
                                        >
                                            <option value="" disabled>Select type</option>
                                            @foreach ($carTypes as $carTypeValue)
                                                <option value="{{ $carTypeValue }}">{{ $carTypeValue }}</option>
                                            @endforeach
                                        </select>

                                        @error('createVehicleForm.car_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('create vehicle form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-container mb-3 d-flex flex-column" wire:ignore>
                                        <label for="car_name">Car Name<span class="required">*</span></label>
                                        <select
                                            id="car_name"
                                            class="form-select @error('createVehicleForm.car_name') is-invalid @enderror"
                                            wire:model.lazy="createVehicleForm.car_name"
                                        >
                                            <option value="" disabled>Select name</option>
                                            @foreach($carNames as $carNameKey => $carName)
                                                <option value="{{ $carName }}">{{ $carName }}</option>
                                            @endforeach
                                        </select>

                                        @error('createVehicleForm.car_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('create vehicle form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="input-container mb-3">
                                        <label for="plate_number">Plate Number<span class="required">*</span></label>
                                        <input
                                            id="plate_number"
                                            name="plate_number"
                                            type="text"
                                            class="form-control @error('createVehicleForm.plate_number') is-invalid @enderror"
                                            wire:model.lazy="createVehicleForm.plate_number">

                                        @error('createVehicleForm.plate_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('create vehicle form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
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

    <div class="modal fade" id="newBlockAndLotModal" tabindex="-1" aria-labelledby="newBlockAndLotModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form method="POST" wire:submit.prevent="addBlockLot">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newBlockAndLotModalLabel">Assign Block & Lot</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-container" wire:ignore>
                                    <label for="block-lots">Block & Lots</label>
                                    <select
                                        id="block-lots"
                                        multiple="multiple"
                                        class="form-control @error('blockLotForm') is-invalid @enderror"
                                        wire:model.lazy="blockLotForm">
                                        @forelse ($availableLBlockLots as $key => $availableLBlockLot)
                                            <optgroup label="Block {{ $key }}">
                                                @foreach ($availableLBlockLot as $lotKey => $lot)
                                                    <option value="{{ $lot }}">Lot {{ $lotKey }}</option>
                                                @endforeach
                                            </optgroup>
                                        @empty
                                            <option value="" disabled>No available Block & Lot</option>
                                        @endforelse
                                    </select>

                                    @error('blockLotForm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('block lot form.', '', $message) }}</strong>
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

    <div class="modal fade" id="updateVehicleModal" tabindex="-1" aria-labelledby="updateVehicleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="updateVehicle">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateVehicleModalLabel">Update Vehicle</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3 d-flex flex-column" wire:ignore>
                                    <label for="car_type_u">Vehicle Name<span class="required">*</span></label>
                                    <select
                                        id="car_type_u"
                                        class="form-select @error('updateVehicleForm.car_type_u') is-invalid @enderror"
                                        wire:model.lazy="updateVehicleForm.car_type_u"
                                    >
                                        <option value="" disabled>Select type</option>
                                    </select>

                                    @error('updateVehicleForm.car_type_u')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update vehicle form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3 d-flex flex-column" wire:ignore>
                                    <label for="car_name_u">Car Name<span class="required">*</span></label>
                                    <select
                                        id="car_name_u"
                                        class="form-select @error('updateVehicleForm.car_name_u') is-invalid @enderror"
                                        wire:model.lazy="updateVehicleForm.car_name_u"
                                    >
                                        <option value="" disabled>Select name</option>
                                    </select>

                                    @error('updateVehicleForm.car_name_u')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update vehicle form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="update_plate_number">Plate Number<span class="required">*</span></label>
                                    <input
                                        id="update_plate_number"
                                        name="update_plate_number"
                                        type="text"
                                        class="form-control @error('updateVehicleForm.plate_number') is-invalid @enderror"
                                        wire:model.lazy="updateVehicleForm.plate_number"
                                        autofocus>

                                    @error('updateVehicleForm.plate_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create vehicle form.', '', $message) }}</strong>
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

    <div class="modal fade" id="newVisitorQr" tabindex="-1" aria-labelledby="newVisitorQrLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form method="POST" wire:submit.prevent="createVisitorQr">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newVisitorQrLabel">New Visitor QR</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="last_name_visitor">Last Name<span class="required">*</span></label>
                                    <input
                                        id="last_name_visitor"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('visitorForm.last_name') is-invalid @enderror"
                                        wire:model.lazy="visitorForm.last_name">
                                    @error('visitorForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('visitor form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="first_name_visitor">First Name<span class="required">*</span></label>
                                    <input
                                        id="first_name_visitor"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('visitorForm.first_name') is-invalid @enderror"
                                        wire:model.lazy="visitorForm.first_name">
                                    @error('visitorForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('visitor form.', '', $message) }}</strong>
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
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                /** Initialize click event for confirm-delete-profile */
                $(document).on('click', '.confirm-delete-profile', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: `Family Member '${name}' will be deleted and this can't be undone!`,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            Livewire.emit('deleteProfile', { id: id });
                        }
                    });
                });

                /** Add delete lot confirmation */
                $(document).on('click', '.delete-lot', function() {
                    const id = $(this).data('id');
                    const lot = $(this).data('lot');
                    Swal.fire({
                        icon: 'info',
                        title: 'Are you sure?',
                        html: `<p>Lot <b>${lot}</b> will be unassigned!</p>`,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            Livewire.emit('deleteBlockLot', id);
                        }
                    });
                });

                $(document).on('change', '#date_of_birth, #update_date_of_birth', function() {
                    const value = $(this).val();
                    const ageSelector = $(this).data('age');
                    const today = new Date();
                    const birthDate = new Date(value);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (birthDate > today) {
                        age = 'Invalid selected date';
                    } else {
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                    }

                    $('#' + ageSelector).val(age);
                });

                /** Add delete vehicle confirmation */
                $(document).on('click', '.delete-vehicle', function() {
                    const id = $(this).data('id');
                    const plate = $(this).data('plate');
                    const type = $(this).data('type');
                    Swal.fire({
                        icon: 'info',
                        title: 'Are you sure?',
                        html: `<p>Vehicle <b>${plate}</b> deleted!</p>`,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm'
                    }).then((e) => {
                        if (e.isConfirmed) {
                            Livewire.emit('deleteVehicle', id);
                        }
                    });
                });

                /** Add event to trigger update modal */
                Livewire.on('update.vehicle-prepare', (e) => {
                    const updateVehicleModal = new bootstrap.Modal('#updateVehicleModal', {})
                    updateVehicleModal.show()

                    console.log(e)

                    $('#car_type_u').select2({
                        dropdownParent: '#updateVehicleModal',
                        tags: true,
                        data: Array.from(e.carTypes).map(function(value) {
                            return { id: value, text: value }
                        }),
                        val: e.carType
                    })

                    $('#car_name_u').select2({
                        dropdownParent: '#updateVehicleModal',
                        tags: true,
                        data: Array.from(e.carNames).map(function(value) {
                            return { id: value, text: value }
                        }),
                        val: e.carName
                    })
                })

                /** Initialize select2 */
                $(document).on('click', '#newBlockAndLotModalBtn', function() {
                    const newBlockAndLotModal = new bootstrap.Modal('#newBlockAndLotModal', {})
                    newBlockAndLotModal.show()

                    setTimeout(function() {
                        $('#block-lots').select2()
                    }, 500)
                })
                $('#block-lots').on('select2:select', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('selectLot', id)
                })

                $('#block-lots').on('select2:unselect', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('unSelectLot', id)
                })

                /** Initialize the Vehicle Name & name select2 */
                $(document).on('click', '#newVehicleModalBtn', function() {
                    const newVehicleModal = new bootstrap.Modal('#newVehicleModal', {})
                    newVehicleModal.show()
                })

                /** Initialize select2 for New vehicle modal */
                $('#car_type').select2({
                    dropdownParent: '#newVehicleModal',
                    tags: true
                })

                $('#car_type').on('select2:select', function(e) {
                    const id = e.params.data.id
                    @this.createChangeCarType('create', id)
                })

                Livewire.on('create.updated-car-names', function(e) {
                    $('#car_name').select2('destroy')
                    $('#car_name').empty()

                    // update the Car Name options
                    $('#car_name').select2({
                        dropdownParent: '#newVehicleModal',
                        tags: true,
                        data: Array.from(e).map(function(value) {
                            return { id: value, text: value }
                        })
                    })
                })

                $('#car_name').select2({
                    dropdownParent: '#newVehicleModal',
                    tags: true
                })

                $('#car_name').on('select2:select', function(e) {
                    const id = e.params.data.id

                    @this.createChangeCarName('create', id)
                })

                /** Update Car */
                $('#car_type_u').on('select2:select', function(e) {
                    const id = e.params.data.id
                    @this.createChangeCarType('update', id)
                })

                Livewire.on('update.updated-car-names', function(e) {
                    $('#car_name_u').select2('destroy')
                    $('#car_name_u').empty()

                    // update the Car Name options
                    $('#car_name_u').select2({
                        dropdownParent: '#updateVehicleModal',
                        tags: true,
                        data: Array.from(e).map(function(value) {
                            return { id: value, text: value }
                        })
                    })
                })

                $('#car_name_u').on('select2:select', function(e) {
                    const id = e.params.data.id

                    @this.updateCarNameOnUpdate(id)
                })
            })
        </script>
    @endsection
    @section('styles')
        <style>
            .select2-dropdown {
                z-index: 99999 !important;
            }
        </style>
    @endsection
</div>
