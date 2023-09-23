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
        <div class="col-8">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-header p-3">
                    <div class="row">
                        <h4 class="app-card-title">Form details</h4>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <form method="POST" wire:submit.prevent="update">
                        @csrf
    
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="first_name" class="text-dark">First Name*</label>
                                <input
                                    id="first_name"
                                    name="first_name"
                                    type="text"
                                    class="form-control @error('model.first_name') is-invalid @enderror"
                                    placeholder="Ex. John"
                                    wire:model="model.first_name"
                                    autofocus
                                >
    
                                @error('model.first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label for="last_name" class="text-dark">Last Name*</label>
                                <input
                                    id="last_name"
                                    name="last_name"
                                    type="text"
                                    class="form-control @error('model.last_name') is-invalid @enderror"
                                    placeholder="Ex. Doe"
                                    wire:model="model.last_name"
                                >
    
                                @error('model.last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label for="middle_name" class="text-dark">Middle Name</label>
                                <input
                                    id="middle_name"
                                    name="middle_name"
                                    type="text"
                                    class="form-control @error('model.middle_name') is-invalid @enderror"
                                    placeholder="Ex. Eli"
                                    wire:model="model.middle_name"
                                >
    
                                @error('model.middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="block" class="text-dark">Block</label>
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
    
                                @error('block')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="lot" class="text-dark">Lot</label>
                                <select name="lot" id="lot" class="form-select" wire:model="modelSelectedLot" required>
                                    <option value="" selected disabled>Select lot</option>
                                    @forelse ($lots as $data)
                                        <option value="{{ $data->id }}" @if($model->lot == $data->id) selected @endif>{{ $data->lot }}</option>
                                    @empty
                                        <option value="" disabled>No available lot</option>
                                    @endforelse
                                </select>
    
                                @error('lot')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="contact_no" class="text-dark">Contact Number</label>
                                <input
                                    id="contact_no"
                                    name="contact_no"
                                    type="number"
                                    class="form-control @error('model.contact_no') is-invalid @enderror"
                                    placeholder="Ex. 09123456789"
                                    wire:model="model.contact_no"
                                >
    
                                @error('model.contact_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('model.', '', $message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
