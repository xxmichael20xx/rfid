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
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
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
                                                            wire:click="setActiveBlock({{ $data->id }})">
                                                            <i class="fa fa-list clickable"></i>
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-success text-white p-2 ms-2"
                                                            wire:click="prepareBlock({{ $data->id }})">
                                                            <i class="fa fa-square-plus clickable"></i>
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
            </div>
        </div>
    </div>

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
                                                        data-id="{{ $lotId }}">
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

                                                <button
                                                    type="button"
                                                    class="btn btn-success text-white p-2 ms-2"
                                                    data-bs-dismiss="modal"
                                                    wire:click="prepareEditLot({{ $data->id }})">
                                                    <i class="fa fa-pencil clickable"></i>
                                                </button>
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

    <div class="modal fade" id="blockLotFormModal" tabindex="-1" aria-labelledby="blockLotFormModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form method="POST" wire:submit.prevent="createLots">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="blockLotFormModalLabel">Block - Lot Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <label>Lots*</label>
                            </div>
                            @foreach ($lotForm['lots'] as $lotFormKey => $item)
                                <div class="col-12 mb-5 @if($lotFormKey > 0) border-top pt-5 @endif">
                                    <div class="d-flex justify-content-between">
                                        <div class="input-container">
                                            <input
                                                type="text"
                                                class="form-control @error('lotForm.lots.'.$lotFormKey.'.lot') is-invalid @enderror"
                                                placeholder="Ex. Block XYZ"
                                                wire:model.lazy="lotForm.lots.{{ $lotFormKey }}.lot">
                                            <label>Lot #{{ $lotFormKey + 1 }} name</label>
                                        </div>
                                        @if ($lotFormKey > 0)
                                            <button type="button" class="btn btn-danger text-white ms-3" wire:click="removeLot({{ $lotFormKey }})">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @error('lotForm.lots.'.$lotFormKey.'.lot')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>Duplicate value for lot fields. Each lot should be unique</strong>
                                        </span>
                                    @enderror

                                    <div class="input-container">
                                        <textarea
                                            type="text"
                                            class="form-control form-control--textarea mt-2 @error('lotForm.lots.'.$lotFormKey.'.details') is-invalid @enderror"
                                            wire:model.lazy="lotForm.lots.{{ $lotFormKey }}.details"
                                            placeholder="Lot #{{ $lotFormKey + 1 }} details"></textarea>
                                        <label>Lot #{{ $lotFormKey + 1 }} details</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-between">
                                <button type="button" class="btn btn-info text-white" wire:click="addLot">Add Lot</button>
                                <div>
                                    <button type="button" class="btn btn-danger me-2 text-white" data-bs-dismiss="modal" wire:click="cancelCreate">Cancel</button>
                                    <button type="submit" class="btn btn-primary text-white">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="lotEditModal" tabindex="-1" aria-labelledby="lotEditModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form method="POST" wire:submit.prevent="updateLot">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="lotEditModalLabel">Edit - Lot Form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-container mb-3">
                                    <label for="lot">Lot<span class="required">*</span></label>
                                    <input
                                        id="lot"
                                        name="lot"
                                        type="text"
                                        class="form-control @error('editLotForm.lot') is-invalid @enderror"
                                        wire:model.lazy="editLotForm.lot">
        
                                    @error('editLotForm.lot')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('edit lot form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-container">
                                    <label>Lot details</label>
                                    <textarea
                                        type="text"
                                        class="form-control form-control--textarea-sm mt-2 @error('editLotForm.details') is-invalid @enderror"
                                        wire:model.lazy="editLotForm.details"
                                        placeholder="Lot details"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-danger me-2 text-white" data-bs-dismiss="modal" wire:click="cancelEditLot">Cancel</button>
                                <button type="submit" class="btn btn-primary text-white">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    
                /** Notification for not block data */
                Livewire.on('block-management.no-data', () => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Data not found',
                        text: 'Data of the block is not found'
                    })
                })

                /** Prepare modal for new lot */
                const blockLotFormModal = new bootstrap.Modal('#blockLotFormModal', {})
                Livewire.on('block.prepared', () => {
                    blockLotFormModal.show()
                })

                /** Notification for create failed with no data */
                Livewire.on('create.failed.no-data', () => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Failed to add lots',
                        text: 'Block data is not found'
                    })
                })

                /** Prepare modal for edit lot */
                const lotEditModal = new bootstrap.Modal('#lotEditModal', {})
                Livewire.on('lot.prepared', () => {
                    lotEditModal.show()
                })
            })
        </script>
    @endsection
</div>
