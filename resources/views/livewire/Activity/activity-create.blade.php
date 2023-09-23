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
        <div class="col-8">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-header p-3">
                    <div class="row">
                        <h4 class="app-card-title">Form details</h4>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <form method="POST" wire:submit.prevent="create">
                        @csrf
    
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="title" class="text-dark">Title*</label>
                                <input
                                    id="title"
                                    name="title"
                                    type="text"
                                    class="form-control @error('form.title') is-invalid @enderror"
                                    placeholder="Ex. Fun Run"
                                    wire:model="form.title"
                                    autofocus
                                >
    
                                @error('form.title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label for="location" class="text-dark">Location*</label>
                                <input
                                    id="location"
                                    name="location"
                                    type="text"
                                    class="form-control @error('form.location') is-invalid @enderror"
                                    placeholder="Ex. Central Gym"
                                    wire:model="form.location"
                                    autofocus
                                >
    
                                @error('form.location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="start_date" class="text-dark">Start date</label>
                                <input
                                    id="start_date"
                                    name="start_date"
                                    type="date"
                                    class="form-control @error('form.start_date') is-invalid @enderror"
                                    wire:model="form.start_date"
                                >
    
                                @error('form.start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('form.', '', $message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label for="end_date" class="text-dark">End date</label>
                                <input
                                    id="end_date"
                                    name="end_date"
                                    type="date"
                                    class="form-control @error('form.end_date') is-invalid @enderror"
                                    wire:model="form.end_date"
                                >
    
                                @error('form.end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ str_replace('form.', '', $message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="text-dark">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    class="form-control form-control--textarea @error('form.title') is-invalid @enderror"
                                    wire:model="form.description"
                                    rows="5"></textarea>
    
                                @error('form.description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
