<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Block & Lots Management</h1>
            <div class="col-auto">
                <a href="{{ route('block-management.create') }}" class="btn btn-success text-white">
                    <i class="fa fa-user-plus"></i> Add New
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-body px-3 pb-3">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left visitors-table">
                            <thead class="bg-portal-green">
                                <tr>
                                    <th class="cell">Block</th>
                                    <th class="cell">Number of Lots</th>
                                    <th class="cell">Available Lots</th>
                                    <th class="cell">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($blocks as $data)
                                    <tr>
                                        <td class="cell">{{ $data->block }}</td>
                                        <td class="cell">{{ $data->lots->count() }}</td>
                                        <td class="cell">{{ $data->available_lots->count() }}</td>
                                        <td class="cell d-flex">
                                            <button
                                                type="button"
                                                class="btn btn-info text-white p-2"
                                                wire:click="setActiveBlock({{ $data->id }})"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="View Block Lots">
                                                <i class="fa fa-list clickable"></i>
                                            </button>
                                        </td>
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

    {{-- Modals --}}
    <!-- blockLotsModal Modal -->
    <div class="modal fade" id="blockLotsModal" tabindex="-1" aria-labelledby="blockLotsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            @if ($activeBlock)
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="blockLotsModalLabel">{{ $activeBlock->block }} - List of lots</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table app-table-hover mb-0 text-left visitors-table">
                                <thead class="bg-portal-green">
                                    <tr>
                                        <th class="cell">Lot</th>
                                        <th class="cell">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activeBlock->lots as $data)
                                        <tr>
                                            <td class="cell">{{ $data->lot }}</td>
                                            <td class="cell d-flex">
                                                @php
                                                    $lotId = "block-".$activeBlock->id."-lot-".$data->id;
                                                    $lotCanDelete = (bool) ($data->availability == 'available');
                                                @endphp
                                                @if ($lotCanDelete)
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger text-white p-2 lot-confirm-delete"
                                                        data-id="{{ $lotId }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="Delete Lot">
                                                        <i class="fa fa-trash clickable"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="hidden"
                                                        id="{{ $lotId }}"
                                                        wire:click="deleteLot({{ $data->id }})">
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger text-white p-2 lot-delete-restrict"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="Can't delete Lot due because Lot is currently in use.">
                                                        <i class="fa fa-trash clickable"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="cell text-center" colspan="2">No result(s)</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @section('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const blockLotsModal = new bootstrap.Modal('#blockLotsModal', {})
                Livewire.on('block-management.show-list', () => {
                    blockLotsModal.show()

                    /** Initialize click event for lot delete confirmation */
                    const lotConfirmDeletes = document.querySelectorAll('.lot-confirm-delete')
                    if (lotConfirmDeletes.length > 0) {
                        lotConfirmDeletes.forEach((item) => {
                            const id = item.getAttribute('data-id')
                            item.addEventListener('click', function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Are you sure?',
                                    text: 'Lot will be deleted!',
                                    showConfirmButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((e) => {
                                    if (e.isConfirmed) {
                                        document.getElementById(id).click()
                                    }
                                })
                            })
                        })
                    }

                    /** Initialize click event on restricting lot delete */
                    const lotDeleteRestrict = document.querySelectorAll('.lot-delete-restrict')
                    if (lotDeleteRestrict.length > 0) {
                        lotDeleteRestrict.forEach((item) => {
                            item.addEventListener('click', () => {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Delete Restricted',
                                    text: 'Can\'t delete Lot due because Lot is currently in use.',
                                })
                            })
                        })
                    }

                    /** Initialize Bootstrap 5 Tooltips */
                    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
                })
    
                Livewire.on('block-management.no-data', () => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Data not found',
                        text: 'Data of the block is not found'
                    })
                })
            })
        </script>
    @endsection
</div>
