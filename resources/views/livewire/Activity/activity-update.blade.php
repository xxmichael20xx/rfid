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
                                        <div class="input-container mb-3">
                                            <label for="title">Title<span class="required">*</span></label>
                                            <input
                                                id="title"
                                                name="title"
                                                type="text"
                                                class="form-control @error('model.title') is-invalid @enderror"
                                                wire:model.lazy="model.title"
                                                autofocus>
                
                                            @error('model.title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="location">Location<span class="required">*</span></label>
                                            <input
                                                id="location"
                                                name="location"
                                                type="text"
                                                class="form-control @error('model.location') is-invalid @enderror"
                                                wire:model.lazy="model.location">
                
                                            @error('model.location')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="start_time">Start time<span class="required">*</span></label>
                                            <input
                                                id="start_time"
                                                name="start_time"
                                                type="time"
                                                class="form-control @error('model.start_time') is-invalid @enderror"
                                                wire:model.lazy="model.start_time">
                
                                            @error('model.start_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="start_date">Start date<span class="required">*</span></label>
                                            <input
                                                id="start_date"
                                                name="start_date"
                                                type="date"
                                                class="form-control @error('model.start_date') is-invalid @enderror"
                                                wire:model.lazy="model.start_date">
                
                                            @error('model.start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('model.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="end_date">End date<span class="required">*</span></label>
                                            <input
                                                id="end_date"
                                                name="end_date"
                                                type="date"
                                                class="form-control @error('model.end_date') is-invalid @enderror"
                                                wire:model.lazy="model.end_date">
                
                                            @error('model.end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('model.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
            
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="input-container mb-3">
                                            <label for="description">Description<span class="required">*</span></label>
                                            <textarea
                                                id="description"
                                                name="description"
                                                class="form-control form-control--textarea @error('model.description') is-invalid @enderror"
                                                wire:model.lazy="model.description"
                                                rows="5"></textarea>
                
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
