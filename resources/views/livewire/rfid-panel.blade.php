<div>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <div class="alert alert-primary text-center w-50 pt-5 pb-5" role="alert">
            <p class="h3 text-dark">Scan your RFID</p>
            <input
                type="text"
                class="form-control py-5 px-3"
                id="tapped_id"
                name="tapped_id"
                style="font-size: 2em;"
                autofocus
            >
        </div>
    </div>

    

    <div class="modal fade" id="rfidPanelModal" tabindex="-1" aria-labelledby="rfidPanelModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col" wire:ignore>
                                <div class="row" id="overlay">
                                    <div class="col-md-12 captureCanvas text-center">
                                        <div id="my_camera"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <p class="h2 text-center text-dark" id="rfid-panel-today"></p>
                            </div>

                            <div class="col-12">
                                <p class="h1 text-center text-dark">{{ $homeOwner }}</p>
                                <p class="h2 text-center text-secondary">{{ $plateNumber }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('styles')
        <style>
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
    @endsection

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

        <script>
            let rfidPanelModal = null

            $(document).ready(function() {
                rfidPanelModal = new bootstrap.Modal('#rfidPanelModal', {})

                /** Initialize the change/input on the RFID input field */
                const inputElement = document.getElementById('tapped_id')
                let debouncedRfidPanel = _.debounce((id) => {
                    @this.rfidPanelEvent(id)
                }, 1500)

                inputElement.addEventListener('input', function(event) {
                    const id = event.target.value
                    debouncedRfidPanel(id)
                })

                /** Set interval to focus the rfid field */
                setInterval(function() {
                    $('#tapped_id').focus();
                }, 2500);

                Livewire.on('rfidPanel.invalid-rfid', function() {
                    clearRfid()

                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Your is invalid. Please ask a guard for assistance.'
                    })
                })

                function clearRfid()
                {
                    $('#tapped_id').val('')
                    $('#tapped_id').focus()
                }

                Livewire.on('rfidPanel.success', function() {
                    rfidPanelModal.show()

                    startCam()
                    countdownSnapshot()
                })

                Livewire.on('rfidPanel.in', function(e) {
                    rfidPanelModal.hide()
                    clearRfid()
                    Livewire.emit('testinghehe')

                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome Back, ' + e.name + '!',
                        timer: 5000,
                        showConfirmButton: false,
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                })

                Livewire.on('rfidPanel.out', function(e) {
                    rfidPanelModal.hide()
                    clearRfid()
                    Livewire.emit('testinghehe')

                    Swal.fire({
                        icon: 'success',
                        title: 'Have a nice day, ' + e.name + '!',
                        timer: 5000,
                        showConfirmButton: false,
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                })
            })

            function startCam() {
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

            function updateDateTime() {
                const dateTimeElement = document.getElementById('rfid-panel-today')
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
                        saveSnapshot()
                    } else {
                        countdownValue--;
                        setTimeout(updateCountdown, 1000)
                    }
                }

                // Start the countdown
                updateCountdown();
            }

            function saveSnapshot() {
                document.querySelector('#overlay .countdown').remove()

                Webcam.snap(function(data_uri) {
                    Webcam.reset()

                    @this.rfidPanelSaveshot(data_uri)
                })
            }

            // Initial call to display the date and time
            updateDateTime();

            // Update the date and time every second
            setInterval(updateDateTime, 1000);
        </script>
    @endsection
</div>
