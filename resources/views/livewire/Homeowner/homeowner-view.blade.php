<div>
    <h1 class="app-page-title">Manage Home Owner Details</h1>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
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
    <div class="row g-4 mb-4">
        <div class="col-6">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-12">
                                @if ($data->profile)
                                    <img
                                        src="{{ $data->profile }}"
                                        alt="Image Preview"
                                        class="img-fluid mb-3 rounded shadow"
                                        style="width: 250px;"
                                    />
                                @endif
                            </div>
                            <div class="col-12">
                                <p class="card-title h5">Home Owner: {{ $data->full_name }}</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="text-dark"><b>Block:</b> {{ $data->myBlock->block }}</p>
                            </div>
                            <div class="col-6">
                                <p class="text-dark"><b>Lot:</b> {{ $data->myLot->lot }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="text-dark"><b>Contact Number:</b> {{ $data->contact_no }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
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
    </div>
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-12 d-flex justify-content-between mb-3">
                                <p class="card-title h5">List of profiles</p>
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
            
                                                        <div wire:ignore>
                                                            @livewire('profile.profile-view', ['profileId' => $profile->id])
                                                        </div>
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
                                <div class="form-floating mb-3">
                                    <input
                                        id="first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('createForm.first_name') is-invalid @enderror"
                                        placeholder="Ex. John"
                                        wire:model="createForm.first_name"
                                        autofocus>
                                    <label for="first_name">First Name*</label>
        
                                    @error('createForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('createForm.last_name') is-invalid @enderror"
                                        placeholder="Ex. Doe"
                                        wire:model="createForm.last_name">
                                    <label for="last_name">Last Name*</label>
                                    
                                    @error('createForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="form-floating mb-3">
                                    <input
                                        id="middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('createForm.middle_name') is-invalid @enderror"
                                        placeholder="Ex. Eli"
                                        wire:model="createForm.middle_name">
                                    <label for="middle_name">Middle Name</label>
        
                                    @error('createForm.middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        type="date"
                                        class="form-control @error('createForm.date_of_birth') is-invalid @enderror"
                                        wire:model="createForm.date_of_birth">
                                    <label for="date_of_birth">Date of birth*</label>
        
                                    @error('createForm.date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="contact_no"
                                        name="contact_no"
                                        type="number"
                                        class="form-control @error('createForm.contact_no') is-invalid @enderror"
                                        placeholder="Ex. 09123456789"
                                        wire:model="createForm.contact_no">
                                    <label for="contact_no">Contact Number</label>
        
                                    @error('createForm.contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        class="form-control form-control--textarea @error('createForm.notes') is-invalid @enderror"
                                        wire:model="createForm.notes"
                                        rows="5"></textarea>
                                    <label for="notes">Notes</label>
        
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

    <!-- Modal for profile update form -->
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
                                <div class="form-floating mb-3">
                                    <input
                                        id="update_first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('updateForm.first_name') is-invalid @enderror"
                                        placeholder="Ex. John"
                                        wire:model="updateForm.first_name"
                                        autofocus>
                                    <label for="update_first_name">First Name*</label>
        
                                    @error('updateForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="update_last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('updateForm.last_name') is-invalid @enderror"
                                        placeholder="Ex. Doe"
                                        wire:model="updateForm.last_name">
                                    <label for="update_last_name">Last Name*</label>
        
                                    @error('updateForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="form-floating mb-3">
                                    <input
                                        id="update_middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('updateForm.middle_name') is-invalid @enderror"
                                        placeholder="Ex. Eli"
                                        wire:model="updateForm.middle_name">
                                    <label for="update_middle_name">Middle Name</label>
        
                                    @error('updateForm.middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="update_date_of_birth"
                                        name="date_of_birth"
                                        type="date"
                                        class="form-control @error('updateForm.date_of_birth') is-invalid @enderror"
                                        wire:model="updateForm.date_of_birth">
                                    <label for="update_date_of_birth">Date of birth*</label>
        
                                    @error('updateForm.date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="update_contact_no"
                                        name="contact_no"
                                        type="number"
                                        class="form-control @error('updateForm.contact_no') is-invalid @enderror"
                                        placeholder="Ex. 09123456789"
                                        wire:model="updateForm.contact_no">
                                    <label for="update_contact_no">Contact Number</label>
        
                                    @error('updateForm.contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('update form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea
                                        id="update_notes"
                                        name="notes"
                                        class="form-control form-control--textarea @error('updateForm.notes') is-invalid @enderror"
                                        wire:model="updateForm.notes"
                                        rows="5"></textarea>
                                    <label for="update_notes">Notes</label>
        
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
            })
        </script>
    @endsection
</div>
