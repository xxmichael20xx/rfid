<div>
    @if($data)
        <div class="modal fade" id="showVisitorEntryModal" tabindex="-1" aria-labelledby="showVisitorEntryModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog--md">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="col" wire:ignore>
                                    <div class="row" id="overlay">
                                        <div class="col-md-12 captureCanvas text-center">
                                            <div id="my_camera"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row mb-4">
                                    <div class="col-12 text-center">
                                        <p class="h2 text-center text-dark" id="visitor-panel-today"></p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 mx-auto text-center">
                                        <p class="card-title h5">Home Owner: {{ $data?->last_full_name }}</p>
                                        <hr class="theme-separator">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <p class="text-dark"><b>Visitor:</b> {{ $visitor?->full_name }}</p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <p class="text-dark"><b>Contact Number:</b> {{ $data?->contact_no }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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

        #overlay .countdown {
            text-align: center;
            color: white;
            background-color: rgba(0, 0, 0, .5);
            width: fit-content;
            border-radius: 6px;
            position: absolute;
            top: 1em;
            left: 50%;
            transform: translateX(-50%);
            font-size: 3em;
            padding: .5rem 1rem;
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

                // Initial call to display the date and time
                updateDateTime();

                // Update the date and time every second
                setInterval(updateDateTime, 1000);

                setTimeout(countdownSnapshot, 1000);

                /** Image captures */
                setTimeout(function() {
                    restartSnapshot()
                }, 500);
            })

            Livewire.on('visitor.entry.success', function(e) {
                // restartSnapshot()

                showVisitorEntryModal.hide()

                Swal.fire({
                    icon: 'success',
                    title: 'Welcome to Glenn Ville!',
                    timer: 5000,
                    showConfirmButton: false,
                    showCancelButton: false,
                    allowOutsideClick: false,
                    html: `<img src="/uploads/${e.capture}" alt="preview-capture-in" class="img-fluid">`,
                })
            })

            function updateDateTime() {
                const dateTimeElement = document.getElementById('visitor-panel-today')
                const now = new Date()
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: true
                };

                const dateTimeString = now.toLocaleString('en-US', options)
                dateTimeElement.innerText = dateTimeString
            }

            function countdownSnapshot() {
                let overlayElement = document.getElementById("overlay");

                // Create countdown element
                let countdownElement = document.createElement("div");
                countdownElement.className = "countdown";
                overlayElement.appendChild(countdownElement);

                let countdownValue = 5

                function updateCountdown() {
                    countdownElement.textContent = countdownValue

                    if (countdownValue <= 0) {
                        takeSnapshot()
                    } else {
                        countdownValue--;
                        setTimeout(updateCountdown, 1000)
                    }
                }

                // Start the countdown
                updateCountdown();
            }

            function approveEntry() {
                @this.logVisitoEntry()

                setTimeout(() => {
                    $('#html5-qrcode-button-camera-permission').trigger('click')
                    Swal.fire({
                        icon: 'success',
                        title: 'Entry logged'
                    })
                }, 500);
            }

            function takeSnapshot() {
                Webcam.snap(function(data_uri) {
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
        })
    </script>
</div>
