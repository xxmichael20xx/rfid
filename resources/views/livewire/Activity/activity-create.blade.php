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
    <form method="POST" wire:submit.prevent="create" class="col-12">
        @csrf
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
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="title">Title<span class="required">*</span></label>
                                            <input
                                                id="title"
                                                name="title"
                                                type="text"
                                                class="form-control @error('form.title') is-invalid @enderror"
                                                wire:model.lazy="form.title"
                                                autofocus>
                
                                            @error('form.title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
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
                                                class="form-control @error('form.location') is-invalid @enderror"
                                                wire:model.lazy="form.location">
                
                                            @error('form.location')
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
                                            <label for="start_time">Start time<span class="required">*</span></label>
                                            <input
                                                id="start_time"
                                                name="start_time"
                                                type="time"
                                                class="form-control @error('form.start_time') is-invalid @enderror"
                                                wire:model.lazy="form.start_time">
                
                                            @error('form.start_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="end_time">End time<span class="required">*</span></label>
                                            <input
                                                id="end_time"
                                                name="end_time"
                                                type="time"
                                                class="form-control @error('form.end_time') is-invalid @enderror"
                                                wire:model.lazy="form.end_time">
                
                                            @error('form.end_time')
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
                                                class="form-control @error('form.start_date') is-invalid @enderror"
                                                wire:model.lazy="form.start_date">
                
                                            @error('form.start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
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
                                                class="form-control @error('form.end_date') is-invalid @enderror"
                                                wire:model.lazy="form.end_date">
                
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
                                        <div class="input-container mb-3">
                                            <label for="description">Description<span class="required">*</span></label>
                                            <textarea
                                                id="description"
                                                name="description"
                                                class="form-control form-control--textarea @error('form.description') is-invalid @enderror"
                                                wire:model.lazy="form.description"
                                                rows="5"></textarea>
                
                                            @error('form.description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('form.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('activities.list') }}" class="btn btn-danger text-white">Cancel</a>
                                        <button type="submit" class="btn btn-primary text-white">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <p class="card-title h5">Gallery</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-container mb-2">
                                        <label for="gallery" id="galleryTriggerLabel">Images</label>
                                        <input
                                            id="gallery"
                                            name="gallery"
                                            type="file"
                                            class="form-control @error('form.gallery') is-invalid @enderror d-none"
                                            wire:model="form.gallery"
                                            accept="image/*"
                                            multiple
                                        >
            
                                        @error('form.gallery')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('form.', '', $message) }}</strong>
                                            </span>
                                        @enderror

                                        <button type="button" class="btn btn-secondary d-block" id="galleryTrigger">Select image(s)</button>
                                    </div>
                                </div>

                                @if ($form['gallery'])
                                    <div class="col-12">
                                        <p class="h5 mb-0">Selected Images</p>

                                        <div class="row">
                                            @foreach ($form['gallery'] as $gallery)
                                                <img
                                                    src="{{ $gallery->temporaryUrl() }}"
                                                    alt="Image Preview"
                                                    class="img-fluid mb-3 rounded shadow col-6"
                                                    style="height: 250px;"
                                                />
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @section('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('click', '#galleryTrigger', function() {
                    $('#galleryTriggerLabel').trigger('click')
                })
            })
        </script>
    @endsection
</div>
