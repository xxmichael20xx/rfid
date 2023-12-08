<div>
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="alert alert-success d-flex flex-column w-100 align-items-center" wire:ignore>
                <span class="display-4 text-dark">RFID Panel - Monitoring</span>
                <span id="datetime" class="display-2 text-dark"></span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success text-dark d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fa fa-info-circle me-2"></i> Tap your RFID to log your entry
                                    </span>
                                    <div class="input-container">
                                        <input type="text" class="form-control bg-white" id="tapped_id" name="tapped_id" placeholder="Focus here and tap the RFID">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">RFID</th>
                                                <th class="cell">Name</th>
                                                <th class="cell">Date</th>
                                                <th class="cell">Time In | Time Out</th>
                                                <th class="cell">Captures</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($monitorings as $data)
                                                <tr>
                                                    <td class="cell">{{ $data['rfid'] }}</td>
                                                    <td class="cell">{{ $data['home_owner'] }}</td>
                                                    <td class="cell">{{ $data['date'] }}</td>
                                                    <td class="cell">{{ $data['time_in'] }} | {{ $data['time_out'] }}</td>
                                                    <td class="cell">
                                                        <button
                                                            type="button"
                                                            class="btn btn-success text-white view-time-in"
                                                            data-type="Time In"
                                                            data-date="{{ $data['date'] }}"
                                                            data-time="{{ $data['time_in'] }}"
                                                            data-img="{{ $data['capture_in'] }}"
                                                        >
                                                            <i class="fa fa-sign-in"></i> View Time In
                                                        </button>

                                                        @if ($data['time_out'] !== 'N/A')
                                                            <button
                                                                type="button"
                                                                class="btn btn-secondary text-white view-time-out"
                                                                data-type="Time Out"
                                                                data-date="{{ $data['date'] }}"
                                                                data-time="{{ $data['time_out'] }}"
                                                                data-img="{{ $data['capture_out'] }}"
                                                            >
                                                                <i class="fa fa-sign-out"></i> View Time Out
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="5">No result(s)</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="homeOwnerData" tabindex="-1" aria-labelledby="homeOwnerDataLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog--lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            @if ($homeOwner?->profile)
                                                <img
                                                    src="{{ $homeOwner?->profile }}"
                                                    alt="Home-Owner-Avatar"
                                                    class="img-fluid mb-3 rounded shadow"
                                                    style="width: 250px;"
                                                />
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <p class="card-title h5">Home Owner: {{ $homeOwner?->last_full_name }}</p>
                                            <hr class="theme-separator">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="text-dark"><b>Date of Birth:</b> {{ $homeOwner?->date_of_birth }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-dark"><b>Age:</b> {{ $homeOwner?->age }} year(s) old</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-dark"><b>Gender:</b> {{ ucfirst($homeOwner?->gender) }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="text-dark"><b>Email:</b> {{ $homeOwner?->email ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-dark"><b>Contact Number:</b> {{ $homeOwner?->contact_no }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="text-dark"><b>Member Since:</b> {{ \Carbon\Carbon::parse($homeOwner?->created_at)->format('M y, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
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

                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="card-title h5">Family Members</p>
                                            <hr class="theme-separator">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                                    <thead class="bg-portal-green">
                                                        <tr>
                                                            <th class="cell">Name</th>
                                                            <th class="cell">Date of Birth</th>
                                                            <th class="cell">Contact No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($homeOwner?->profiles == null)
                                                        @else
                                                            @forelse ($homeOwner?->profiles as $profile)
                                                                <tr>
                                                                    <td class="cell">{{ $profile->full_name }}</td>
                                                                    <td class="cell">{{ \Carbon\Carbon::parse($profile->date_of_borth)->format('M d, Y') }}</td>
                                                                    <td class="cell">{{ $profile->contact_no ?? 'No contact number' }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td class="cell text-center" colspan="3">No result(s)</td>
                                                                </tr>
                                                            @endforelse
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($vehicleData)
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between mb-3">
                                                <p class="card-title h5">Vehicles Data</p>
                                            </div>
                                            <hr class="theme-separator">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-dark"><b>Plate Number:</b> {{ $vehicleData->plate_number }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-dark"><b>Car Type (Name):</b> {{ $vehicleData->car_type }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-dark"><b>Date Registered:</b> {{ $vehicleData->created_at }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="previewCaptureModal" tabindex="-1" aria-labelledby="previewCaptureModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog--md">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container">
                            <img src="" class="img-fluid w-100" id="previewCapture" alt="preview-capture">
                            <div class="alert alert-success text-center mt-3">
                                <h3 id="previewCaptureType"></h3>
                                <h4 class="text-dark text-center mt-2" id="previewCaptureTime"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

        <script>
            $(document).ready(function() {
                let homeOwnerData = new bootstrap.Modal('#homeOwnerData', {})
                let previewCaptureModal = new bootstrap.Modal('#previewCaptureModal', {})
                let previewCapture = document.getElementById('previewCapture')
                let previewCaptureTime = document.getElementById('previewCaptureTime')
                let previewCaptureType = document.getElementById('previewCaptureType')

                /** Define pusher event to file RFID Tap */
                /* let channel = window.Echo.channel('my-channel')
                channel.listen('.scan-id', function({ id }) {
                    if (id || id !== '') {
                        Livewire.emit('validateEntry', id)
                    } else {
                        $('#tapped_id').val('')
                        Swal.fire({
                            icon: 'info',
                            title: 'Invalid',
                            text: 'The scanned id is invalid!'
                        })
                    }
                }) */

                /** Define validated home owner data */
                Livewire.on('homeowner-data', () => {
                    setTimeout(() => {
                        homeOwnerData.show()
                        restartSnapshot()
                    }, 500);
                })

                /** Define events for new entry log */
                Livewire.on('new-entry', ({date, time}) => {
                    $('#tapped_id').val('')
                    homeOwnerData.hide()
                    Swal.fire({
                        icon: 'success',
                        title: 'Entry Success',
                        text: 'Successfully added entry: ' + date + ' @ ' + time
                    })

                    Webcam.reset()
                    setTimeout(() => {
                        restartSnapshot()
                    }, 500);
                })

                /** Define events for update entry log */
                Livewire.on('updated-entry', ({date, time}) => {
                    $('#tapped_id').val('')
                    homeOwnerData.hide()
                    Swal.fire({
                        icon: 'success',
                        title: 'Log Update',
                        text: 'Successfully updated entry: ' + date + ' @ ' + time
                    })

                    Webcam.reset()
                    setTimeout(() => {
                        restartSnapshot()
                    }, 500);
                })

                /** Define event for invalid rfid */
                Livewire.on('invalid-rfid', () => {
                    $('#tapped_id').val('')
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid RFID',
                        text: 'Please scan again otherwise consult to the guard for an assitance'
                    })
                })

                /** Define approve entry click event */
                $(document).on('click', '#approve-entry', function() {
                    $('#tapped_id').val('')
                    Livewire.emit('logEntry')
                })

                /** Define view capture for time-in and time-out */
                $(document).on('click', '.view-time-in, .view-time-out', function() {
                    const type = $(this).data('type')
                    const image = $(this).data('img')
                    const date = $(this).data('date')
                    const time = $(this).data('time')
                    let icon = `<i class="fa fa-sign-in"></i>`

                    if (type == 'Time Out') {
                        icon = `<i class="fa fa-sign-out"></i>`
                    }

                    previewCapture.setAttribute('src', image)
                    previewCaptureTime.innerHTML = `${date} @ ${time}`
                    previewCaptureType.innerHTML = `${icon} ${type}`

                    previewCaptureModal.show()
                })

                /** Initialize the change/input on the RFID input field */
                const inputElement = document.getElementById('tapped_id')
                let debouncedValidateEntry = _.debounce((id) => {
                    Livewire.emit('validateEntry', id)
                }, 1500)

                inputElement.addEventListener('input', function(event) {
                    const id = event.target.value
                    debouncedValidateEntry(id)
                })

                /** Set interval to focus the rfid field */
                setInterval(function() {
                    $('#tapped_id').focus();
                }, 2500);
            })

            function takeSnapshot() {
                const captureCanvas = $('.captureCanvas')
                const captureValid = $('.captureValid')
                captureCanvas.addClass('d-none')
                captureValid.removeClass('d-none')

                Webcam.snap(function(data_uri) {
                    document.getElementById('results').innerHTML = `<img src="${data_uri}" alt="captured-image" class="img-fluid w-100 rounded" />`
                    Webcam.reset()

                    Livewire.emit('updateCapture', data_uri)
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
                Livewire.emit('logEntry')
            }

            function updateDateTime() {
                const dateTimeElement = document.getElementById('datetime')
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

            // Initial call to display the date and time
            updateDateTime();

            // Update the date and time every second
            setInterval(updateDateTime, 1000);
        </script>
    @endsection
</div>
