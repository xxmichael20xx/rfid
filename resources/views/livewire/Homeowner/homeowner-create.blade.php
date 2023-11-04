<div>
    <h1 class="app-page-title">Add Home Owner</h1>
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
        <div class="col-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <form method="POST" wire:submit.prevent="create" class="col-12">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <p class="card-title h5">Profile Details</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="last_name">Last Name<span class="required">*</span></label>
                                        <input
                                            id="last_name"
                                            name="last_name"
                                            type="text"
                                            class="form-control @error('form.last_name') is-invalid @enderror"
                                            wire:model.lazy="form.last_name">
                                        @error('form.last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="first_name" class="form-label">First Name<span class="required">*</span></label>
                                        <input
                                            id="first_name"
                                            name="first_name"
                                            type="text"
                                            class="form-control @error('form.first_name') is-invalid @enderror"
                                            wire:model.lazy="form.first_name"
                                            autofocus>
                                        @error('form.first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
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
                                            class="form-control @error('form.middle_name') is-invalid @enderror"
                                            wire:model.lazy="form.middle_name">
                                        @error('form.middle_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="input-container">
                                        <label for="gender">Gender<span class="required">*</span></label>
                                        <div class="form-check form-check-inline w-100">
                                            <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="form.gender" id="gender-male" value="male">
                                            <label class="form-check-label mb-0 ms-2" for="gender-male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="form.gender" id="gender-female" value="female">
                                            <label class="form-check-label mb-0 ms-2" for="gender-female">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container">
                                        <label for="date_of_birth">Date of birth<span class="required">*</span></label>
                                        <input
                                            id="date_of_birth"
                                            name="date_of_birth"
                                            type="date"
                                            class="form-control @error('form.date_of_birth') is-invalid @enderror"
                                            wire:model.lazy="form.date_of_birth">

                                        @error('form.date_of_birth')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
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
                                        <label for="email">Email</label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            class="form-control @error('form.email') is-invalid @enderror"
                                            wire:model.lazy="form.email">
            
                                        @error('form.email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="contact_no">Contact Number<span class="required">*</span></label>
                                        <input
                                            id="contact_no"
                                            name="contact_no"
                                            type="number"
                                            class="form-control @error('form.contact_no') is-invalid @enderror"
                                            wire:model.lazy="form.contact_no">
            
                                        @error('form.contact_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    @if ($form['profile'])
                                        <img
                                            src="{{ $form['profile']->temporaryUrl() }}"
                                            alt="Image Preview"
                                            class="img-fluid mb-3 rounded shadow"
                                            style="width: 250px;"
                                        />
                                    @endif
                                    <div class="mb-3">
                                        <label for="profile" class="form-label">Profile</label>
                                        <input
                                            id="profile"
                                            name="profile"
                                            type="file"
                                            class="form-control @error('form.profile') is-invalid @enderror"
                                            wire:model.lazy="form.profile"
                                            accept="image/*"
                                        >

                                        @error('form.profile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5 mb-3">
                                <div class="col-12">
                                    <p class="card-title h5">Account Details</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-8">
                                    <div class="input-container" wire:ignore>
                                        <label for="block-lots">Block & Lots</label>
                                        <select
                                            id="block-lots"
                                            multiple="multiple"
                                            class="form-control @error('form.block_lots') is-invalid @enderror"
                                            wire:model.lazy="form.block_lots">
                                            @forelse ($availableLBlockLots as $key => $availableLBlockLot)
                                                <optgroup label="{{ $key }}">
                                                    @foreach ($availableLBlockLot as $lotKey => $lot)
                                                        <option value="{{ $lot }}">{{ $lotKey }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @empty
                                                <option value="" disabled>No available Block & Lot</option>
                                            @endforelse
                                        </select>

                                        @error('form.block_lots')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    @if ($errors->any())
                                        @foreach ($errors->keys() as $key)
                                            @if ($key == 'form.block_lots')
                                                @foreach ($errors->get($key) as $message)
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-5 mb-3">
                                <div class="col-12">
                                    <p class="card-title h5">Payment Details</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-8">
                                    <div class="input-container" wire:ignore>
                                        <label for="payments">Payments</label>
                                        <select
                                            id="payments"
                                            multiple="multiple"
                                            class="form-control @error('form.payments') is-invalid @enderror"
                                            wire:model.lazy="form.payments">
                                            @forelse ($paymentTypes as $key => $paymentType)
                                                <option value="{{ $paymentType->id }}">{{ $paymentType->type }}</option>
                                            @empty
                                                <option value="" disabled>No available payments</option>
                                            @endforelse
                                        </select>

                                        @error('form.payments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror

                                        <small class="text-help">Click the field to display list of available payments</small>
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <div>
                                        <a href="{{ route('homeowners.list') }}" class="btn btn-danger text-white me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary text-white">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                $('#block-lots').select2()
                $('#block-lots').on('select2:select', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('selectLot', id)
                })
                $('#block-lots').on('select2:unselect', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('unSelectLot', id)
                })

                $('#date_of_birth').on('change', function() {
                    const value = $(this).val()
                    const today = new Date()
                    const birthDate = new Date(value)

                    let age = today.getFullYear() - birthDate.getFullYear()
                    const monthDiff = today.getMonth() - birthDate.getMonth()

                    if (birthDate > today) {
                        age = 'Invalid selected date'
                    } else {
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--
                        }
                    }

                    $('#age').val(age)
                })

                $('#payments').select2()
                $('#payments').on('select2:select', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('selectPayment', id)
                })
                $('#payments').on('select2:unselect', function (e) {
                    const id = e.params.data.id

                    Livewire.emit('unSelectPayment', id)
                })
            })
        </script>
    @endsection
</div>
