<div>
    <h1 class="app-page-title">Manage Home Owner Details</h1>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="col-auto">
                <a href="{{ route('homeowners.list') }}" class="btn btn-success text-white">
                    <i class="fa fa-hand-point-left"></i> Go back
                </a>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-6">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-header p-3">
                    <div class="row">
                        <h4 class="app-card-title">Home Owner: {{ $data->full_name }}</h4>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-dark"><b>Block:</b> {{ $data->myBlock->block }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-dark"><b>Lot:</b> {{ $data->myLot->lot }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-dark"><b>Contact Number:</b> {{ $data->contact_no }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-dark"><b>Member Since:</b> {{ \Carbon\Carbon::parse($data->created_at)->format('M y, Y') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            @php
                                $rfid = ($data->rfid) ? $data->rfid->rfid : 'No assigned RFID';
                            @endphp
                            <p class="text-dark"><b>RFID:</b> {{ $rfid }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-header p-3">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <h4 class="app-card-title">List of profiles</h4>
                            <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newProfileModal">
                                <i class="fa fa-user-plus"></i> Add New
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left visitors-table">
                            <thead class="bg-portal-green">
                                <tr>
                                    <th class="cell">Id</th>
                                    <th class="cell">Name</th>
                                    <th class="cell">Date of Birth</th>
                                    <th class="cell">Contact No</th>
                                    <th class="cell">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->profiles as $profile)
                                    <tr>
                                        <td class="cell">{{ $profile->id }}</td>
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

                                            @livewire('profile.profile-view', ['profileId' => $profile->id])
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

    <!-- Modal for new profile form -->
    <div class="modal fade" id="newProfileModal" tabindex="-1" aria-labelledby="newProfileModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="create">
                @csrf
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newProfileModalLabel">New Profile Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="first_name" class="text-dark">First Name*</label>
                                <input
                                    id="first_name"
                                    name="first_name"
                                    type="text"
                                    class="form-control @error('createForm.first_name') is-invalid @enderror"
                                    placeholder="Ex. John"
                                    wire:model="createForm.first_name"
                                    autofocus
                                >
    
                                @error('createForm.first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="last_name" class="text-dark">Last Name*</label>
                                <input
                                    id="last_name"
                                    name="last_name"
                                    type="text"
                                    class="form-control @error('createForm.last_name') is-invalid @enderror"
                                    placeholder="Ex. Doe"
                                    wire:model="createForm.last_name"
                                >
    
                                @error('createForm.last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6 mt-3">
                                <label for="middle_name" class="text-dark">Middle Name</label>
                                <input
                                    id="middle_name"
                                    name="middle_name"
                                    type="text"
                                    class="form-control @error('createForm.middle_name') is-invalid @enderror"
                                    placeholder="Ex. Eli"
                                    wire:model="createForm.middle_name"
                                >
    
                                @error('createForm.middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="date_of_birth" class="text-dark">Date of birth*</label>
                                <input
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    type="date"
                                    class="form-control @error('createForm.date_of_birth') is-invalid @enderror"
                                    wire:model="createForm.date_of_birth"
                                >
    
                                @error('createForm.date_of_birth')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('createForm.', '', $message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="contact_no" class="text-dark">Contact Number</label>
                                <input
                                    id="contact_no"
                                    name="contact_no"
                                    type="number"
                                    class="form-control @error('createForm.contact_no') is-invalid @enderror"
                                    placeholder="Ex. 09123456789"
                                    wire:model="createForm.contact_no"
                                >
    
                                @error('createForm.contact_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('createForm.', '', $message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="notes" class="text-dark">Notes</label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    class="form-control form-control--textarea @error('createForm.notes') is-invalid @enderror"
                                    wire:model="createForm.notes"
                                    rows="5"></textarea>
    
                                @error('createForm.notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('createForm.', '', $message) }}</strong>
                                    </span>
                                @enderror
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

    <!-- Modal for profile update form -->
    @if ($updateForm)
        <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog--md">
                <form method="POST" wire:submit.prevent="update">
                    @csrf
                    
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="updateProfileModalLabel">Update Profile Form</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="update_first_name" class="text-dark">First Name*</label>
                                    <input
                                        id="update_first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('updateForm.first_name') is-invalid @enderror"
                                        placeholder="Ex. John"
                                        wire:model="updateForm.first_name"
                                        autofocus
                                    >
        
                                    @error('updateForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="update_last_name" class="text-dark">Last Name*</label>
                                    <input
                                        id="update_last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('updateForm.last_name') is-invalid @enderror"
                                        placeholder="Ex. Doe"
                                        wire:model="updateForm.last_name"
                                    >
        
                                    @error('updateForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="update_middle_name" class="text-dark">Middle Name</label>
                                    <input
                                        id="update_middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('updateForm.middle_name') is-invalid @enderror"
                                        placeholder="Ex. Eli"
                                        wire:model="updateForm.middle_name"
                                    >
        
                                    @error('updateForm.middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="update_date_of_birth" class="text-dark">Date of birth*</label>
                                    <input
                                        id="update_date_of_birth"
                                        name="date_of_birth"
                                        type="date"
                                        class="form-control @error('updateForm.date_of_birth') is-invalid @enderror"
                                        wire:model="updateForm.date_of_birth"
                                    >
        
                                    @error('updateForm.date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('updateForm.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="update_contact_no" class="text-dark">Contact Number</label>
                                    <input
                                        id="update_contact_no"
                                        name="contact_no"
                                        type="number"
                                        class="form-control @error('updateForm.contact_no') is-invalid @enderror"
                                        placeholder="Ex. 09123456789"
                                        wire:model="updateForm.contact_no"
                                    >
        
                                    @error('updateForm.contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('updateForm.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label for="update_notes" class="text-dark">Notes</label>
                                    <textarea
                                        id="update_notes"
                                        name="notes"
                                        class="form-control form-control--textarea @error('updateForm.notes') is-invalid @enderror"
                                        wire:model="updateForm.notes"
                                        rows="5"></textarea>
        
                                    @error('updateForm.notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('updateForm.', '', $message) }}</strong>
                                        </span>
                                    @enderror
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
    @endif

    @section('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                /** Initialize click event for confirm-delete-profile */
                const confirmDeleteProfile = document.querySelectorAll('.confirm-delete-profile')
                if (confirmDeleteProfile.length > 0) {
                    Array.from(confirmDeleteProfile).forEach((item) => {
                        const id = item.getAttribute('data-id')
                        const name = item.getAttribute('data-name')
                        item.addEventListener('click', () => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure?',
                                text: `Profile \'${name}\' will be deleted and this can\'t be undone!`,
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!'
                            }).then((e) => {
                                if (e.isConfirmed) {
                                    Livewire.emit('delete-profile', { id: id })
                                }
                            })
                        })
                    })
                }

                /** Initialize Livewire event listener - show update modal */
                Livewire.on('show.update-modal', () => {
                    const updateProfileModal = new bootstrap.Modal('#updateProfileModal', {})
                    updateProfileModal.show()
                })
            })
        </script>
    @endsection
</div>
