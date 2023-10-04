<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">RFID Panel - Monitoring</h1>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success text-dark">
                                    <i class="fa fa-info-circle"></i> Tap your RFID to log your entry
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Home Owner Id</th>
                                                <th class="cell">Name</th>
                                                <th class="cell">Date</th>
                                                <th class="cell">Time In | Time Out</th>
                                                <th class="cell">Captures</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($monitorings as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->rfid }}</td>
                                                    <td class="cell">{{ $data->rfidData->homeOwner->full_name }}</td>
                                                    <td class="cell">{{ $data->date }}</td>
                                                    <td class="cell">{{ $data->time_in }} | {{ $data->time_out }}</td>
                                                    <td class="cell">
                                                        <button
                                                            type="button"
                                                            class="btn btn-success text-white view-time-in"
                                                            data-date="{{ $data->date }}"
                                                            data-time="{{ $data->time_in }}"
                                                            data-img="{{ $data->capture_in }}"
                                                        >
                                                            <i class="fa fa-sign-in"></i> View Time In
                                                        </button>

                                                        @if ($data->time_out !== 'N/A')
                                                            <button
                                                                type="button"
                                                                class="btn btn-secondary text-white view-time-out"
                                                                data-date="{{ $data->date }}"
                                                                data-time="{{ $data->time_out }}"
                                                                data-img="{{ $data->capture_out }}"
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
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateProfileModalLabel">Home Owner: {{ $homeOwner?->full_name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="text-dark"><b>Block:</b> {{ $homeOwner?->myBlock->block }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-dark"><b>Lot:</b> {{ $homeOwner?->myLot->lot }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="text-dark"><b>Contact Number:</b> {{ $homeOwner?->contact_no }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="text-dark"><b>Member Since:</b> {{ \Carbon\Carbon::parse($homeOwner?->created_at)->format('M y, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            @php
                                                $rfid = ($homeOwner?->rfid) ? $homeOwner->rfid->rfid : 'No assigned RFID';
                                            @endphp
                                            <p class="text-dark"><b>RFID:</b> {{ $rfid }}</p>
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
                            <h4 class="text-dark text-center mt-2" id="previewCaptureTime"></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let homeOwnerData = new bootstrap.Modal('#homeOwnerData', {})
                let previewCaptureModal = new bootstrap.Modal('#previewCaptureModal', {})
                let previewCapture = document.getElementById('previewCapture')
                let previewCaptureTime = document.getElementById('previewCaptureTime')

                /** Define pusher event to file RFID Tap */
                let channel = window.Echo.channel('my-channel')
                channel.listen('.scan-id', function({ id }) {
                    if (id || id !== '') {
                        Livewire.emit('validateEntry', id)
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Invalid',
                            text: 'The scanned id is invalid!'
                        })
                    }
                })

                /** Define validated home owner data */
                Livewire.on('homeowner-data', () => {
                    setTimeout(() => {
                        homeOwnerData.show()
                        restartSnapshot()
                    }, 500);
                })

                /** Define events for new entry log */
                Livewire.on('new-entry', ({date, time}) => {
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid RFID',
                        text: 'Please scan again otherwise consult to the guard for an assitance'
                    })
                })

                /** Define approve entry click event */
                const approveEntry = document.getElementById('approve-entry')
                if (approveEntry) {
                    approveEntry.addEventListener('click', () => {
                        Livewire.emit('logEntry')
                    })
                }

                /** Define view capture for time-in and time-out */
                const viewTimeInOut = document.querySelectorAll('.view-time-in, .view-time-out')
                if (viewTimeInOut.length > 0) {
                    Array.from(viewTimeInOut).forEach((item) => {
                        item.addEventListener('click', () => {
                            const image = item.getAttribute('data-img')
                            const date = item.getAttribute('data-date')
                            const time = item.getAttribute('data-time')
                            previewCapture.setAttribute('src', image)
                            previewCaptureTime.innerHTML = `${date} @ ${time}`

                            previewCaptureModal.show()
                        })
                    })
                }
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
        </script>
    @endsection
</div>
