<div>
    <h1 class="app-page-title">Update Home Owner - {{ $modelFullName }}</h1>
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
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="card-title h5">Form details</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
    
                        <div class="row">
                            <form method="POST" wire:submit.prevent="update" class="col-12">
                                @csrf
            
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                id="first_name"
                                                name="first_name"
                                                type="text"
                                                class="form-control @error('model.first_name') is-invalid @enderror"
                                                placeholder="Ex. John"
                                                wire:model="model.first_name"
                                                autofocus>
                                            <label for="first_name">First Name*</label>
                                            
                
                                            @error('model.first_name')
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
                                                class="form-control @error('model.last_name') is-invalid @enderror"
                                                placeholder="Ex. Doe"
                                                wire:model="model.last_name">
                                            <label for="last_name">Last Name*</label>
                
                                            @error('model.last_name')
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
                                                class="form-control @error('model.middle_name') is-invalid @enderror"
                                                placeholder="Ex. Eli"
                                                wire:model="model.middle_name">
                                            <label for="middle_name">Middle Name</label>
                
                                            @error('model.middle_name')
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
                                                class="form-select load-change"
                                                wire:model="model.block"
                                                wire:change="setLots"
                                                required>
                                                <option value="" selected disabled>Select block</option>
                                                @forelse ($blocks as $data)
                                                    <option value="{{ $data->id }}" @if($model->block == $data->id) selected @endif>{{ $data->block }}</option>
                                                @empty
                                                    <option value="" disabled>No available block</option>
                                                @endforelse
                                            </select>
                                            <label for="block">Block*</label>
                
                                            @error('block')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="lot" id="lot" class="form-select" wire:model="modelSelectedLot" required>
                                                <option value="" selected disabled>Select lot</option>
                                                @forelse ($lots as $data)
                                                    <option value="{{ $data->id }}" @if($model->lot == $data->id) selected @endif>{{ $data->lot }}</option>
                                                @empty
                                                    <option value="" disabled>No available lot</option>
                                                @endforelse
                                            </select>
                                            <label for="lot">Lot*</label>
                
                                            @error('lot')
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
                                                class="form-control @error('model.contact_no') is-invalid @enderror"
                                                placeholder="Ex. 09123456789"
                                                wire:model="model.contact_no">
                                            <label for="contact_no">Contact Number*</label>
                
                                            @error('model.contact_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('model.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
            
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <a href="{{ route('homeowners.list') }}" class="btn btn-danger text-white me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary text-white">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
