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
    <form method="POST" wire:submit.prevent="update" class="col-12">
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
                                    <div class="col-6">
                                        <div class="input-container mb-3">
                                            <label for="end_time">End time<span class="required">*</span></label>
                                            <input
                                                id="end_time"
                                                name="end_time"
                                                type="time"
                                                class="form-control @error('model.end_time') is-invalid @enderror"
                                                wire:model.lazy="model.end_time">
                
                                            @error('model.end_time')
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
                                            class="form-control @error('model.gallery') is-invalid @enderror d-none"
                                            wire:model="model.gallery"
                                            accept="image/*"
                                            multiple
                                        >
            
                                        @error('model.gallery')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        <button type="button" class="btn btn-secondary d-block" id="galleryTrigger">Select image(s)</button>
                                        <small class="text-help">Note: Upon uploading new images, the existing gallery will be deleted!</small>
                                    </div>
                                </div>

                                @if ($model['gallery'])
                                    <div class="col-12 mt-3">
                                        <p class="h5 mb-0">Selected Images</p>

                                        <div class="row">
                                            @foreach ($model['gallery'] as $gallery)
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

                            @if (count($model['galleries']) > 0)
                                <div class="row mb-3 mt-5">
                                    <div class="col-12">
                                        <p class="card-title h5">Gallery Images</p>
                                        <hr class="theme-separator">
                                    </div>

                                    <div class="col-12">
                                        <div
                                            id="activityGallery"
                                            class="carousel slide"
                                            data-bs-ride="carousel"
                                            data-bs-infinite="true"
                                            style="max-height: 500px;"
                                        >
                                            <div class="carousel-indicators">
                                                @php $btnsCount = 0; @endphp
                                                @foreach ($model['galleries'] as $galleryKey => $galleryImage)
                                                    <button
                                                        type="button"
                                                        data-bs-target="#activityGallery"
                                                        data-bs-slide-to="{{ $galleryKey }}"
                                                        class="{{ ($btnsCount) == 0 ? 'active' : '' }}"
                                                        aria-current="true"
                                                        aria-label="Slide {{ $galleryKey }}"
                                                    ></button>
                                                    @php $btnsCount = $btnsCount + 1; @endphp
                                                @endforeach
                                            </div>
                                            <div class="carousel-inner">
                                                @php $imagesCount = 0; @endphp
                                                @foreach ($model['galleries'] as $galleryKey => $galleryImage)
                                                    <div class="carousel-item text-center {{ ($imagesCount) == 0 ? 'active' : '' }}">
                                                        <img
                                                            src="{{ $galleryImage['image'] }}"
                                                            class="img-fluid key-{{ $imagesCount }}"
                                                            style="max-height: 500px;"
                                                        />
                                                    </div>
                                                    @php $imagesCount = $imagesCount + 1; @endphp
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#activityGallery" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#activityGallery" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
