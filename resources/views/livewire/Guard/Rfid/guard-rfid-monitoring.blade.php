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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($monitorings as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->rfid }}</td>
                                                    <td class="cell">{{ $data->rfidData->homeOwner->full_name }}</td>
                                                    <td class="cell">{{ $data->date }}</td>
                                                    <td class="cell">{{ $data->time_in }} | {{ $data->time_out }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="4">No result(s)</td>
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
            <div class="modal-dialog modal-dialog--md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateProfileModalLabel">Home Owner: {{ $homeOwner?->full_name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Decline Entry</button>
                        <button type="button" class="btn btn-primary text-white" id="approve-entry">Approve Entry</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let homeOwnerData = new bootstrap.Modal('#homeOwnerData', {})

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
                    homeOwnerData.show()
                })

                /** Define events for new entry log */
                Livewire.on('new-entry', ({date, time}) => {
                    homeOwnerData.hide()
                    Swal.fire({
                        icon: 'success',
                        title: 'Entry Success',
                        text: 'Successfully added entry: ' + date + ' @ ' + time
                    })
                })

                /** Define events for update entry log */
                Livewire.on('updated-entry', ({date, time}) => {
                    homeOwnerData.hide()
                    Swal.fire({
                        icon: 'success',
                        title: 'Log Update',
                        text: 'Successfully updated entry: ' + date + ' @ ' + time
                    })
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
            })
        </script>
    @endsection
</div>
