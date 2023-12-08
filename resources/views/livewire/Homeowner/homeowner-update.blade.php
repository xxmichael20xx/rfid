<div>
    <form method="POST" wire:submit.prevent="update" class="col-12">
        @csrf
        <div class="row g-4 mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 class="app-page-title">Update Home Owner - {{ $modelFullName }}</h1>
                <div class="col-auto">
                    <a href="{{ route('homeowners.list') }}" class="btn btn-success text-white">
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
                                    <p class="card-title h5">Profile Details</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="last_name">Last Name<span class="required">*</span></label>
                                        <input
                                            id="last_name"
                                            name="last_name"
                                            type="text"
                                            class="form-control @error('model.last_name') is-invalid @enderror"
                                            wire:model.lazy="model.last_name">
                                        @error('model.last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="first_name" class="form-label">First Name<span class="required">*</span></label>
                                        <input
                                            id="first_name"
                                            name="first_name"
                                            type="text"
                                            class="form-control @error('model.first_name') is-invalid @enderror"
                                            wire:model.lazy="model.first_name"
                                            autofocus>
                                        @error('model.first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input
                                            id="middle_name"
                                            name="middle_name"
                                            type="text"
                                            class="form-control @error('model.middle_name') is-invalid @enderror"
                                            wire:model.lazy="model.middle_name">
                                        @error('model.middle_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="input-container">
                                        <label for="gender">Gender<span class="required">*</span></label>
                                        <div class="form-check form-check-inline w-100">
                                            <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="model.gender" id="gender-male" value="male">
                                            <label class="form-check-label mb-0 ms-2" for="gender-male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input p-2" type="radio" name="gender" wire:model.lazy="model.gender" id="gender-female" value="female">
                                            <label class="form-check-label mb-0 ms-2" for="gender-female">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container">
                                        <label for="date_of_birth">Date of birth<span class="required">*</span></label>
                                        <input
                                            id="date_of_birth"
                                            name="date_of_birth"
                                            type="date"
                                            class="form-control @error('model.date_of_birth') is-invalid @enderror"
                                            wire:model.lazy="model.date_of_birth">

                                        @error('model.date_of_birth')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container">
                                        <label for="age">Age</label>
                                        <input
                                            id="age"
                                            name="age"
                                            type="text"
                                            class="form-control disabled"
                                            value="{{ $model['age'] }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="email">Email</label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            class="form-control @error('model.email') is-invalid @enderror"
                                            wire:model.lazy="model.email">
            
                                        @error('model.email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="contact_no">Contact Number<span class="required">*</span></label>
                                        <input
                                            id="contact_no"
                                            name="contact_no"
                                            type="tel"
                                            class="form-control @error('model.contact_no') is-invalid @enderror"
                                            wire:model.lazy="model.contact_no">
            
                                        @error('model.contact_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <img
                                        src="{{ $model['profile_path'] }}"
                                        alt="Image Preview"
                                        id="imagePreview"
                                        class="img-fluid mb-3 rounded shadow"
                                        style="width: 250px;"
                                        wire:ignore
                                    />
                                    <div class="mb-3">
                                        <label for="profileSelect" class="form-label">Profile</label>
                                        <input
                                            id="profileSelect"
                                            name="profile"
                                            type="file"
                                            class="form-control @error('model.profileUpdate') is-invalid @enderror"
                                            wire:model.lazy="model.profileUpdate"
                                            accept="image/*"
                                        >

                                        @error('model.profileUpdate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('model.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if (count($lotsCarousels) > 0)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p class="card-title h5">Block & Lots Mapping</p>
                                        <hr class="theme-separator">
                                    </div>

                                    <div class="col-12">
                                        <div
                                            id="lotCarousels"
                                            class="carousel slide"
                                            data-bs-ride="true"
                                            style="max-height: 500px;"
                                        >
                                            <div class="carousel-indicators">
                                                @php $btnsCount = 0; @endphp
                                                @foreach ($lotsCarousels as $lotsCarouselKey => $lotsCarousel)
                                                    <button
                                                        type="button"
                                                        data-bs-target="#lotCarousels"
                                                        data-bs-slide-to="{{ $lotsCarouselKey }}"
                                                        class="{{ ($btnsCount) == 0 ? 'active' : '' }}"
                                                        aria-current="true"
                                                        aria-label="Slide {{ $lotsCarouselKey }}"
                                                    ></button>
                                                    @php $btnsCount = $btnsCount + 1; @endphp
                                                @endforeach
                                            </div>
                                            <div class="carousel-inner">
                                                @php $imagesCount = 0; @endphp
                                                @foreach ($lotsCarousels as $lotsCarouselKey => $lotsCarousel)
                                                    <div class="carousel-item text-center {{ ($imagesCount) == 0 ? 'active' : '' }}">
                                                        <img
                                                            src="{{ $lotsCarousel['image'] }}"
                                                            class="img-fluid key-{{ $imagesCount }}"
                                                            style="max-height: 500px;"
                                                        />
                                                        <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, .60);">
                                                            <h5 class="text-white">{{ $lotsCarousel['name'] }}</h5>
                                                        </div>
                                                    </div>
                                                    @php $imagesCount = $imagesCount + 1; @endphp
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#lotCarousels" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#lotCarousels" data-bs-slide="next">
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

        <div class="row">
            <div class="col-12">
                <a href="{{ route('homeowners.list') }}" class="btn btn-danger text-white me-2">Cancel</a>
                <button type="submit" class="btn btn-primary text-white">Save</button>
            </div>
        </div>
    </form>

    @section('styles')
        <style>
            .carousel-item .carousel-caption {
                top: 0;
                left: 0;
                right: unset;
                bottom: unset;
                width: fit-content;
                height: fit-content;
                padding: 2em;
                border-radius: 5px
            }

            .carousel-item .carousel-caption h5 {
                margin: 0;
            }
        </style>
    @endsection

    @section('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const profileSelect = document.getElementById('profileSelect')
                const imagePreview = document.getElementById('imagePreview')

                if (profileSelect && imagePreview) {
                    profileSelect.addEventListener('change', (e) => {
                        // Create a FileReader object to read the selected file
                        const reader = new FileReader()
                        const selectedFile = profileSelect.files[0]

                        // Check if a file was selected
                        if (selectedFile) {
                            // Add an event listener to the FileReader to handle the file reading
                            reader.addEventListener('load', () => {
                                // Set the source of the image preview to the loaded data URL
                                imagePreview.src = reader.result
                                imagePreview.style.display = 'block'
                            });

                            // Read the selected file as a data URL
                            reader.readAsDataURL(selectedFile)
                        } else {
                            // Clear the image preview if no file is selected
                            imagePreview.src = ''
                            imagePreview.style.display = 'none'
                        }
                    })
                }

                $('#date_of_birth').on('change', function() {
                    const value = $(this).val()
                    const today = new Date()
                    const birthDate = new Date(value)

                    let age = today.getFullYear() - birthDate.getFullYear()
                    const monthDiff = today.getMonth() - birthDate.getMonth()

                    if (birthDate > today) {
                        age = 'Invalid selected date'
                    } else {
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--
                        }
                    }

                    $('#age').val(age)
                })
            })
        </script>
    @endsection
</div>
