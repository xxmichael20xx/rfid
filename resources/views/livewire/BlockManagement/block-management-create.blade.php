<div>
    <h1 class="app-page-title">Add Block</h1>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="col-auto">
                <a href="{{ route('block-management.list') }}" class="btn btn-success text-white">
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
                        <form method="POST" wire:submit.prevent="create" class="col-12">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-12">
                                    <p class="card-title h5">Form Details</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="input-container mb-3">
                                        <label for="block">Block<span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Block</span>
                                            <input
                                                id="block"
                                                name="block"
                                                type="number"
                                                class="form-control @error('newBlock.block') is-invalid @enderror"
                                                wire:model.lazy="newBlock.block"
                                                autofocus>
                                            @error('newBlock.block')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ str_replace('new block.', '', $message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-container mb-3">
                                        <label for="details">Details</label>
                                        <textarea
                                            type="text"
                                            class="form-control form-control--textarea mt-2 @error('newBlock.details') is-invalid @enderror"
                                            wire:model.lazy="newBlock.details"
                                            placeholder="Enter block details"></textarea>

                                        @error('newBlock.details')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace('new block.', '', $message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <label>Lots<span class="required">*</span></label>
                                </div>
                                @foreach ($newBlock['lots'] as $newBlockKey => $newBlockLot)
                                    <div class="col-12 my-2 @if($newBlockKey > 0) border-top @endif">
                                        <div class="row pt-2">
                                            <div class="col-6">
                                                <div class="input-container">
                                                    <label>Lot #{{ $newBlockKey + 1 }} name</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text" id="basic-addon1">Lot</span>
                                                        <input
                                                            type="number"
                                                            class="form-control @error('newBlock.lots.'.$newBlockKey.'.lot') is-invalid @enderror"
                                                            wire:model.lazy="newBlock.lots.{{ $newBlockKey }}.lot">
                                                        @error('newBlock.lots.'.$newBlockKey.'.lot')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ str_replace('new block.', '', $message) }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-container">
                                                    <label>Lot #{{ $newBlockKey + 1 }} details</label>
                                                    <textarea
                                                        type="text"
                                                        class="form-control form-control--textarea-sm @error('newBlock.lots.'.$newBlockKey.'.details') is-invalid @enderror"
                                                        wire:model.lazy="newBlock.lots.{{ $newBlockKey }}.details"
                                                        placeholder="Lot #{{ $newBlockKey + 1 }} details"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                @php
                                                    $lotImageKey = sprintf('newBlock.lots.%s.image', $newBlockKey);
                                                @endphp
                                                @if ($newBlock['lots'][$newBlockKey]['image'])
                                                    <img
                                                        src="{{ $newBlock['lots'][$newBlockKey]['image']->temporaryUrl() }}"
                                                        alt="Image Preview"
                                                        class="img-fluid mb-3 rounded shadow"
                                                        style="width: 250px;"
                                                    />
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label">Location Map</label>
                                                    <input
                                                        type="file"
                                                        class="form-control @error($lotImageKey) is-invalid @enderror"
                                                        wire:model.lazy="newBlock.lots.{{ $newBlockKey }}.image"
                                                        accept="image/*"
                                                    >

                                                    @error($lotImageKey)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ str_replace('new block.', '', $message) }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <small class="text-dark">Note: Wait for the selected image to appear before submitting the form to avoid issues.</small>
                                            </div>
                                            @if ($newBlockKey > 0)
                                                <div class="col-12">
                                                    <p class="text-danger clickable" wire:click="removeLot({{ $newBlockKey }})">
                                                        <i class="fa fa-times"></i> Remove lot
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <button type="button" class="btn btn-info text-white" wire:click="addLot">Add Lot</button>
                                    <div>
                                        <a href="{{ route('block-management.list') }}" class="btn btn-danger me-2 text-white">Cancel</a>
                                        <button type="submit" class="btn btn-primary text-white">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
