<div>
    <h1 class="app-page-title">Update Activity - {{ $modelTitle }}</h1>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="col-auto">
                <a href="{{ route('activities.list') }}" class="btn btn-success text-white">
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
                                                id="title"
                                                name="title"
                                                type="text"
                                                class="form-control @error('model.title') is-invalid @enderror"
                                                placeholder="Ex. Fun Run"
                                                wire:model.lazy="model.title"
                                                autofocus>
                                            <label for="title">Title<span class="required">*</span></label>
                
                                            @error('model.title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                id="end_date"
                                                name="end_date"
                                                type="date"
                                                class="form-control @error('model.end_date') is-invalid @enderror"
                                                wire:model.lazy="model.end_date">
                                            <label for="end_date">End date</label>
                
                                            @error('model.end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('model.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                id="location"
                                                name="location"
                                                type="text"
                                                class="form-control @error('form.location') is-invalid @enderror"
                                                placeholder="Ex. Central Gym"
                                                wire:model.lazy="form.location"
                                                autofocus>
                                            <label for="location">Location<span class="required">*</span></label>
                
                                            @error('form.location')
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
                                            <textarea
                                                id="description"
                                                name="description"
                                                class="form-control form-control--textarea @error('model.title') is-invalid @enderror"
                                                wire:model.lazy="model.description"
                                                rows="5"></textarea>
                                            <label for="description">Description</label>
                
                                            @error('model.description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <a href="{{ route('activities.list') }}" class="btn btn-danger text-white">Cancel</a>
                                        <button type="submit" class="btn btn-success text-white">Update</button>
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
