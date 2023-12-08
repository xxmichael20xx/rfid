<div>
    <div class="row g-4 mb-4" wire:ignore>
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Manage {{ ucfirst(request('type')) }}</h1>
            @if(request('type') == 'officers')
                <div class="col-auto">
                    <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newUserModal">
                        <i class="fa fa-user-plus"></i> Add New
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4" wire:ignore>
                                <p class="card-title h5">List of {{ ucfirst(request('type')) }}</p>
                            </div>
                            <div class="col-8 text-right" wire:ignore>
                                <div class="row justify-content-end">
                                    <form
                                        class="col-4 d-flex flex-column"
                                        action=""
                                        method="GET"
                                    >
                                        <div class="input-container input-group me-2">
                                            <input
                                                type="search"
                                                name="search"
                                                id="search"
                                                class="form-control"
                                                placeholder="Search..."
                                                value="{{ request()->get('search') }}"
                                                required
                                            >
                                            <button class="btn btn-secondary" type="submit" id="search-btn">Search</button>
                                        </div>
                                        @if (request()->get('search'))
                                            <a href="{{ route('user-management.index', ['type' => request('type')]) }}" class="text-help mt-2">Clear search/filters</a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="theme-separator">
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table" wire:ignore>
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Name</th>
                                                @if(request('type') == 'officers')
                                                    <th class="cell">Role</th>
                                                @endif
                                                <th class="cell">Email</th>
                                                <th class="cell">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->last_full_name }}</td>
                                                    @if(request('type') == 'officers')
                                                        <td class="cell">{{ ucfirst($data->role) }}</td>
                                                    @endif
                                                    <td class="cell">{{ $data->email }}</td>
                                                    <td class="cell">
                                                        @if(request('type') == 'officers')
                                                            <button
                                                                type="button"
                                                                class="btn btn-success text-white p-2"
                                                                wire:click="prepareUpdate({{ $data->id }})"
                                                            >
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                class="btn btn-danger text-white p-2 prepare-delete"
                                                                data-id="{{ $data->id }}"
                                                            >
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @else
                                                            @if($data->home_owner_id)
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success text-white p-2"
                                                                    wire:click="previewView({{ $data->home_owner_id }})"
                                                                >
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            @else
                                                                No user account.
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="@if(request('type') == 'officers') 4 @else 3 @endif">No result(s)</td>
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

    <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="create">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newUserModalLabel">New User Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="first_name">First Name<span class="required">*</span></label>
                                    <input
                                        id="first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('createForm.first_name') is-invalid @enderror"
                                        wire:model.lazy="createForm.first_name"
                                        autofocus>

                                    @error('createForm.first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="last_name">Last Name<span class="required">*</span></label>
                                    <input
                                        id="last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('createForm.last_name') is-invalid @enderror"
                                        wire:model.lazy="createForm.last_name">

                                    @error('createForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
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
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="email">Email<span class="required">*</span></label>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="form-control @error('createForm.email') is-invalid @enderror"
                                        wire:model.lazy="createForm.email">

                                    @error('createForm.email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="password">Password<span class="required">*</span></label>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="form-control @error('createForm.password') is-invalid @enderror"
                                        wire:model.lazy="createForm.password">

                                    @error('createForm.password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="role">Role<span class="required">*</span></label>
                                    <select
                                        name="role"
                                        id="role"
                                        class="form-select"
                                        wire:model.lazy="createForm.role">
                                        <option value="" selected disabled>Select role</option>
                                        @forelse ($roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @empty
                                            <option value="" disabled>No available role</option>
                                        @endforelse
                                    </select>

                                    @error('createForm.role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5 mb-3">
                            <div class="col-12">
                                <p class="card-title h5">Contact Details</p>
                                <hr class="theme-separator">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="contact_email">Email<span class="required">*</span></label>
                                    <input
                                        id="contact_email"
                                        name="contact_email"
                                        type="email"
                                        class="form-control @error('createForm.contact_email') is-invalid @enderror"
                                        wire:model.lazy="createForm.contact_email">

                                    @error('createForm.contact_email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="contact_phone">Phone<span class="required">*</span></label>
                                    <input
                                        id="contact_phone"
                                        name="contact_phone"
                                        type="tel"
                                        class="form-control @error('createForm.contact_phone') is-invalid @enderror"
                                        wire:model.lazy="createForm.contact_phone">

                                    @error('createForm.contact_phone')
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


    <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="update">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateUserModalLabel">Update User Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="first_name">First Name<span class="required">*</span></label>
                                    <input
                                        id="first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('updateForm.first_name') is-invalid @enderror"
                                        wire:model.lazy="updateForm.first_name"
                                        autofocus>

                                    @error('updateForm.first_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="last_name">Last Name<span class="required">*</span></label>
                                    <input
                                        id="last_name"
                                        name="last_name"
                                        type="text"
                                        class="form-control @error('updateForm.last_name') is-invalid @enderror"
                                        wire:model.lazy="updateForm.last_name">

                                    @error('updateForm.last_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="middle_name">Middle Name</label>
                                    <input
                                        id="middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('updateForm.middle_name') is-invalid @enderror"
                                        wire:model.lazy="updateForm.middle_name">

                                    @error('updateForm.middle_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="role">Role<span class="required">*</span></label>
                                    <select
                                        name="role"
                                        id="role"
                                        class="form-select"
                                        wire:model.lazy="updateForm.role">
                                        <option value="" selected disabled>Select role</option>
                                        @forelse ($roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @empty
                                            <option value="" disabled>No available role</option>
                                        @endforelse
                                    </select>

                                    @error('updateForm.role')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5 mb-3">
                            <div class="col-12">
                                <p class="card-title h5">Contact Details</p>
                                <hr class="theme-separator">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="contact_email">Email<span class="required">*</span></label>
                                    <input
                                        id="contact_email"
                                        name="contact_email"
                                        type="email"
                                        class="form-control @error('updateForm.contact_email') is-invalid @enderror"
                                        wire:model.lazy="updateForm.contact_email">

                                    @error('updateForm.contact_email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-container mb-3">
                                    <label for="contact_phone">Phone<span class="required">*</span></label>
                                    <input
                                        id="contact_phone"
                                        name="contact_phone"
                                        type="number"
                                        class="form-control @error('updateForm.contact_phone') is-invalid @enderror"
                                        wire:model.lazy="updateForm.contact_phone">

                                    @error('updateForm.contact_phone')
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

    @if($homeOwner)
        <div class="modal fade" id="showHomeownerDetailModal" tabindex="-1" aria-labelledby="showHomeownerDetailModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog--md">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mx-auto text-center">
                                @if ($homeOwner?->profile)
                                    <img
                                        src="{{ $homeOwner?->profile }}"
                                        alt="Home-Owner-Avatar"
                                        class="img-fluid mb-3 rounded shadow"
                                        style="width: 250px;"
                                    />
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6 mx-auto text-center">
                                <p class="card-title h5">Home Owner: {{ $homeOwner?->last_full_name }}</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-dark"><b>Date of Birth:</b> {{ $homeOwner?->date_of_birth }}</p>
                            </div>
                            <div class="col-6">
                                <p class="text-dark"><b>Age:</b> {{ $homeOwner?->age }} year(s) old</p>
                            </div>
                            <div class="col-6">
                                <p class="text-dark"><b>Gender:</b> {{ ucfirst($homeOwner?->gender) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-dark"><b>Email:</b> {{ $homeOwner?->email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6">
                                <p class="text-dark"><b>Contact Number:</b> {{ $homeOwner?->contact_no }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-dark"><b>Account Email:</b> {{ $homeOwner->account->email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-dark"><b>Member Since:</b> {{ \Carbon\Carbon::parse($homeOwner?->created_at)->format('M y, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

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

                /** Define click event on update-role buttons */
                $(document).on('click', '.update-role', function() {
                    const id = $(this).data('id')
                    const options = {
                        option1: 'Admin',
                        option2: 'Guard',
                        option3: 'Treasurer'
                    };

                    Swal.fire({
                        title: 'Update User Role',
                        input: 'select',
                        inputOptions: options,
                        inputPlaceholder: 'Select new role',
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Cancel',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You must select an option';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const selectedOption = options[result.value]
                            Livewire.emit('updateRole', { id: id, role: selectedOption })
                        }
                    });
                })

                /** Define event to show update modal */
                Livewire.on('show.prepared-user', function() {
                    const updateUserModal = new bootstrap.Modal('#updateUserModal', {})
                    updateUserModal.show()
                })

                /** Define click event to delete user */
                $(document).on('click', '.prepare-delete', function() {
                    const id = $(this).data('id')

                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: 'User will be deleted and can\'t be undone!',
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, confirm!',
                        cancelButtonText: 'Cancel'
                    }).then(function(e) {
                        if (e.isConfirmed) {
                            Livewire.emit('deleteUser', id)
                        }
                    })
                })

                /** Define event to display home owner details */
                Livewire.on('show.admin-homeowner', function() {
                    const showHomeownerDetailModal = new bootstrap.Modal('#showHomeownerDetailModal', {})
                    showHomeownerDetailModal.show()
                })
            })
        </script>
    @endsection
</div>
