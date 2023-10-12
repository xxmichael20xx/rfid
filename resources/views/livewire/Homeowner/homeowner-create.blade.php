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
        <div class="col-12 col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="card-title h5">Form details</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
    
                        <form method="POST" wire:submit.prevent="create" class="col-12">
                            @csrf
        
                            <div class="row mb-3">
                                <div class="col-12">
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
                                            wire:model="form.profile"
                                            accept="image/*"
                                        >

                                        @error('form.profile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            id="first_name"
                                            name="first_name"
                                            type="text"
                                            class="form-control @error('form.first_name') is-invalid @enderror"
                                            placeholder="Ex. John"
                                            wire:model="form.first_name"
                                            autofocus>
                                        <label for="first_name">First Name*</label>
                                        @error('form.first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
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
                                            class="form-control @error('form.last_name') is-invalid @enderror"
                                            placeholder="Ex. John"
                                            wire:model="form.last_name"
                                            autofocus>
                                        <label for="last_name">Last Name*</label>
                                        @error('form.last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
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
                                            class="form-control @error('form.middle_name') is-invalid @enderror"
                                            placeholder="Ex. John"
                                            wire:model="form.middle_name"
                                            autofocus>
                                        <label for="middle_name">Middle Name</label>
                                        @error('form.middle_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select
                                            name="block"
                                            id="block"
                                            class="form-select"
                                            wire:model="form.block"
                                            wire:change="setLots">
                                            <option value="" selected>Select block</option>
                                            @forelse ($blocks as $data)
                                                <option value="{{ $data->id }}">{{ $data->block }}</option>
                                            @empty
                                                <option value="" disabled>No available block</option>
                                            @endforelse
                                        </select>
                                        <label for="block">Block*</label>
            
                                        @error('form.block')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="lot" id="lot" class="form-select" wire:model="form.lot">
                                            <option value="" selected disabled>Select lot</option>
                                            @forelse ($lots as $lot)
                                                <option value="{{ $lot->id }}">{{ $lot->lot }}</option>
                                            @empty
                                                <option value="" disabled>No available lot</option>
                                            @endforelse
                                        </select>
                                        <label for="lot">Lot*</label>
            
                                        @error('form.lot')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input
                                            id="contact_no"
                                            name="contact_no"
                                            type="number"
                                            class="form-control @error('form.contact_no') is-invalid @enderror"
                                            placeholder="Ex. 09123456789"
                                            wire:model="form.contact_no"
                                        >
                                        <label for="contact_no">Contact Number*</label>
            
                                        @error('form.contact_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="{{ route('homeowners.list') }}" class="btn btn-danger text-white me-2">Cancel</a>
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
