<div>
    <h1 class="app-page-title">Add Activity</h1>
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
                            <form method="POST" wire:submit.prevent="create" class="col-12">
                                @csrf
            
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                id="title"
                                                name="title"
                                                type="text"
                                                class="form-control @error('form.title') is-invalid @enderror"
                                                placeholder="Ex. Fun Run"
                                                wire:model="form.title"
                                                autofocus>
                                            <label for="title">Title*</label>
                
                                            @error('form.title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
        
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                id="location"
                                                name="location"
                                                type="text"
                                                class="form-control @error('form.location') is-invalid @enderror"
                                                placeholder="Ex. Central Gym"
                                                wire:model="form.location"
                                                autofocus>
                                            <label for="location">Location*</label>
                
                                            @error('form.location')
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
                                                id="start_date"
                                                name="start_date"
                                                type="date"
                                                class="form-control @error('form.start_date') is-invalid @enderror"
                                                wire:model="form.start_date">
                                            <label for="start_date">Start date</label>
                
                                            @error('form.start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
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
                                                class="form-control @error('form.end_date') is-invalid @enderror"
                                                wire:model="form.end_date">
                                            <label for="end_date">End date</label>
                
                                            @error('form.end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
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
                                                class="form-control form-control--textarea @error('form.title') is-invalid @enderror"
                                                wire:model="form.description"
                                                rows="5"></textarea>
                                            <label for="description">Description</label>
                
                                            @error('form.description')
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
                                        <button type="submit" class="btn btn-success text-white">Save</button>
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
