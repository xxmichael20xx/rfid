<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">User Management</h1>
            <div class="col-auto">
                <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#newUserModal">
                    <i class="fa fa-user-plus"></i> Add New
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
                <a href="{{ route('user-management.index') }}" class="text-help">Clear search</a>
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
                                                <th class="cell">Name</th>
                                                <th class="cell">Role</th>
                                                <th class="cell">Email</th>
                                                <th class="cell">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->full_name }}</td>
                                                    <td class="cell">{{ ucfirst($data->role) }}</td>
                                                    <td class="cell">{{ $data->email }}</td>
                                                    <td class="cell">
                                                        <button type="button" class="btn btn-success text-white update-role" data-id="{{ $data->id }}">
                                                            <i class="fa fa-pencil"></i> Update role
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

    {{-- Modals --}}
    <!-- newUserModal Modal -->
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
                                <div class="form-floating mb-3">
                                    <input
                                        id="first_name"
                                        name="first_name"
                                        type="text"
                                        class="form-control @error('createForm.first_name') is-invalid @enderror"
                                        placeholder="Ex. John"
                                        wire:model.lazy="createForm.first_name"
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
                                        placeholder="Ex. John"
                                        wire:model.lazy="createForm.last_name">
                                    <label for="last_name">Last Name*</label>
                                    @error('createForm.last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="middle_name"
                                        name="middle_name"
                                        type="text"
                                        class="form-control @error('createForm.middle_name') is-invalid @enderror"
                                        placeholder="Ex. John"
                                        wire:model.lazy="createForm.middle_name">
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
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="form-control @error('createForm.email') is-invalid @enderror"
                                        placeholder="Ex. example@john.com"
                                        wire:model.lazy="createForm.email">
                                    <label for="email">Email*</label>
                                    @error('createForm.email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('create form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="form-control @error('createForm.password') is-invalid @enderror"
                                        wire:model.lazy="createForm.password">
                                    <label for="password">Password*</label>
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
                                <div class="form-floating mb-3">
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
                                    <label for="role">Role*</label>
        
                                    @error('createForm.role')
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

                /** Define click event on update-role buttons */
                const updateButtons = document.querySelectorAll('.update-role')
                if (updateButtons.length > 0) {
                    Array.from(updateButtons).forEach((updateButton) => {
                        updateButton.addEventListener('click', () => {
                            const id = updateButton.getAttribute('data-id')
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
                    })
                }
            })
        </script>
    @endsection
</div>
