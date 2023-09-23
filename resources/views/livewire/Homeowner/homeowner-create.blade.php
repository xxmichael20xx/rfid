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
            <div class="app-card app-card-chart h-100 shadow-lg">
                <div class="app-card-header p-3">
                    <div class="row">
                        <h4 class="app-card-title">Form details</h4>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <form method="POST" wire:submit.prevent="create">
                        @csrf
    
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="first_name" class="text-dark">First Name*</label>
                                <input
                                    id="first_name"
                                    name="first_name"
                                    type="text"
                                    class="form-control @error('form.first_name') is-invalid @enderror"
                                    placeholder="Ex. John"
                                    wire:model="form.first_name"
                                    autofocus
                                >
    
                                @error('form.first_name')
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
                                    class="form-control @error('form.last_name') is-invalid @enderror"
                                    placeholder="Ex. Doe"
                                    wire:model="form.last_name"
                                >
    
                                @error('form.last_name')
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
                                    class="form-control @error('form.middle_name') is-invalid @enderror"
                                    placeholder="Ex. Eli"
                                    wire:model="form.middle_name"
                                >
    
                                @error('form.middle_name')
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
                                    class="form-select"
                                    wire:model="form.block"
                                    wire:change="setLots"
                                    required>
                                    <option value="" selected disabled>Select block</option>
                                    @forelse ($blocks as $data)
                                        <option value="{{ $data->id }}">{{ $data->block }}</option>
                                    @empty
                                        <option value="" disabled>No available block</option>
                                    @endforelse
                                </select>
    
                                @error('form.block')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="lot" class="text-dark">Lot</label>
                                <select name="lot" id="lot" class="form-select" wire:model="form.lot" required>
                                    <option value="" selected disabled>Select lot</option>
                                    @forelse ($lots as $lot)
                                        <option value="{{ $lot->id }}">{{ $lot->lot }}</option>
                                    @empty
                                        <option value="" disabled>No available lot</option>
                                    @endforelse
                                </select>
    
                                @error('form.lot')
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
                                    class="form-control @error('form.contact_no') is-invalid @enderror"
                                    placeholder="Ex. 09123456789"
                                    wire:model="form.contact_no"
                                >
    
                                @error('form.contact_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('form.', '', $message) }}</strong>
                                    </span>
                                @enderror
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
