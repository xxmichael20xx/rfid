<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">RFID Panel - Monitoring</h1>
            <a href="{{ route('rfid.list') }}" class="btn btn-success text-white">Back to list</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
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
                                                    <td class="cell">{{ $data->rfidData->vehicle->homeOwner->last_full_name }}</td>
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
    </div>
    
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                /** Initialize click event for confirm-delete-rfid */
                const confirmDeleteRfid = document.querySelectorAll('.confirm-delete-rfid')
                if (confirmDeleteRfid.length > 0) {
                    Array.from(confirmDeleteRfid).forEach((item) => {
                        const id = item.getAttribute('data-id')
                        const name = item.getAttribute('data-name')
                        item.addEventListener('click', () => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure?',
                                text: `RFID for \'${name}\' will be deleted and this can\'t be undone!`,
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Yes, confirm'
                            }).then((e) => {
                                if (e.isConfirmed) {
                                    Livewire.emit('deleteRfid', { id: id })
                                }
                            })
                        })
                    })
                }
            })
        </script>
    @endsection
</div>
