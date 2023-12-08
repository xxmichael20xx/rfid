<div>
    @if($data)
        <div class="modal fade" id="showVisitorEntryModal" tabindex="-1" aria-labelledby="showVisitorEntryModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog--lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="row mb-4">
                                    <div class="col text-center">
                                        <h2 class="card-title text-dark">Visitor Entry Recorded!</h2>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-6 mx-auto text-center">
                                        @if ($data?->profile)
                                            <img
                                                src="{{ $data?->profile }}"
                                                alt="Home-Owner-Avatar"
                                                class="img-fluid mb-3 rounded shadow"
                                                style="width: 250px;"
                                            />
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 mx-auto text-center">
                                        <p class="card-title h5">Home Owner: {{ $data?->last_full_name }}</p>
                                        <hr class="theme-separator">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-dark"><b>Visitor:</b> {{ $visitor?->full_name }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-dark"><b>Contact Number:</b> {{ $data?->contact_no }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col" wire:ignore>
                                    <div class="row">
                                        <div class="col-md-12 captureCanvas text-center">
                                            <div id="my_camera"></div>
                                            <button type="button" onClick="takeSnapshot()" class="btn btn-primary text-white mt-2">
                                                <i class="fa fa-camera"></i> Take Snapshot
                                            </button>
                                        </div>
                                        <div class="col-md-12 d-none captureValid text-center">
                                            <div id="results"></div>
                                            <button class="btn btn-primary text-white mt-2" onclick="approveEntry()">
                                                <i class="fa fa-save"></i> Save Entry
                                            </button>
                                            <button type="button" class="btn btn-secondary text-white mt-2" onclick="restartSnapshot()">
                                                <i class="fa fa-undo"></i> Retry
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (count($lotsCarousels) > 0)
                            <div class="row">
                                <div class="col-6 mx-auto">
                                    <div class="row mb-3 mt-5">
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
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

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

    @vite(['resources/js/app.js'])
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script>
        let showVisitorEntryModal

        $(document).ready(function() {
            /** Define event to display the modal */
            Livewire.on('show.visitor-entry', function() {
                showVisitorEntryModal = new bootstrap.Modal('#showVisitorEntryModal', {
                    backdrop: 'static',
                    keyboard: false
                })
                showVisitorEntryModal.show()

                /** Image captures */
                setTimeout(function() {
                    restartSnapshot()
                }, 500);
            })

            Livewire.on('visitor.entry.success', function() {
                restartSnapshot()

                showVisitorEntryModal.hide()
            })
        })

        function takeSnapshot() {
            const captureCanvas = $('.captureCanvas')
            const captureValid = $('.captureValid')
            captureCanvas.addClass('d-none')
            captureValid.removeClass('d-none')

            Webcam.snap(function(data_uri) {
                document.getElementById('results').innerHTML = `<img src="${data_uri}" alt="captured-image" class="img-fluid w-100 rounded" />`
                Webcam.reset()

                @this.updateCapture(data_uri)
            })
        }

        function restartSnapshot() {
            const captureCanvas = $('.captureCanvas')
            const captureValid = $('.captureValid')
            captureCanvas.removeClass('d-none')
            captureValid.addClass('d-none')

            Webcam.set({
                width: 490,
                height: 350,
                image_format: 'jpeg',
                jpeg_quality: 90
            })
            Webcam.attach('#my_camera')
        }

        function approveEntry() {
            @this.logVisitoEntry()

            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Entry logged'
                })
            }, 1500);
        }
    </script>
</div>
