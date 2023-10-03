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
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="card-title h5">Form Details</p>
                                <hr class="theme-separator">
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" wire:submit.prevent="create" class="col-12">
                                @csrf
        
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input
                                                id="block"
                                                name="block"
                                                type="text"
                                                class="form-control @error('newBlock.block') is-invalid @enderror"
                                                placeholder="Ex. Block XYZ"
                                                wire:model="newBlock.block"
                                                autofocus>
                                            <label for="block">Block*</label>
                
                                            @error('newBlock.block')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <textarea
                                                type="text"
                                                class="form-control form-control--textarea mt-2 @error('newBlock.details') is-invalid @enderror"
                                                wire:model="newBlock.details"
                                                placeholder="Enter block details"></textarea>
                                            <label for="details">Details</label>
                
                                            @error('newBlock.details')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label>Lots*</label>
                                    </div>
                                    @foreach ($newBlock['lots'] as $newBlockKey => $newBlockLot)
                                        <div class="col-12 mb-5 @if($newBlockKey > 0) border-top pt-5 @endif">
                                            <div class="d-flex justify-content-between">
                                                <div class="form-floating">
                                                    <input
                                                        type="text"
                                                        class="form-control @error('newBlock.lots.'.$newBlockKey.'.lot') is-invalid @enderror"
                                                        placeholder="Ex. Block XYZ"
                                                        wire:model="newBlock.lots.{{ $newBlockKey }}.lot">
                                                    <label>Lot #{{ $newBlockKey + 1 }} name</label>
                                                </div>
                                                @if ($newBlockKey > 0)
                                                    <button type="button" class="btn btn-danger text-white ms-3" wire:click="removeLot({{ $newBlockKey }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error('newBlock.lots.'.$newBlockKey.'.lot')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>Duplicate value for lot fields. Each lot should be unique</strong>
                                                </span>
                                            @enderror
        
                                            <div class="form-floating">
                                                <textarea
                                                    type="text"
                                                    class="form-control form-control--textarea mt-2 @error('newBlock.lots.'.$newBlockKey.'.details') is-invalid @enderror"
                                                    wire:model="newBlock.lots.{{ $newBlockKey }}.details"
                                                    placeholder="Lot #{{ $newBlockKey + 1 }} details"></textarea>
                                                <label>Lot #{{ $newBlockKey + 1 }} details</label>
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
</div>
