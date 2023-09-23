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
            <div class="app-card app-card-chart h-100 shadow-sm">
                <div class="app-card-header p-3">
                    <div class="row">
                        <h4 class="app-card-title">Form details</h4>
                    </div>
                </div>
                <div class="app-card-body p-3 p-lg-4">
                    <form method="POST" wire:submit.prevent="create">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="block" class="text-dark">Block*</label>
                                <input
                                    id="block"
                                    name="block"
                                    type="text"
                                    class="form-control @error('newBlock.block') is-invalid @enderror"
                                    placeholder="Ex. Block XYZ"
                                    wire:model="newBlock.block"
                                    autofocus
                                >
    
                                @error('newBlock.block')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="details" class="text-dark">Details</label>
                                <textarea
                                    type="text"
                                    class="form-control form-control--textarea mt-2 @error('newBlock.details') is-invalid @enderror"
                                    wire:model="newBlock.details"
                                    placeholder="Enter block details"></textarea>
    
                                @error('newBlock.details')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <label>Lots*</label>
                            </div>
                            @foreach ($newBlock['lots'] as $newBlockKey => $newBlockLot)
                                <div class="col-12 mb-3 @if($newBlockKey > 0) border-top pt-3 @endif">
                                    <div class="d-flex justify-content-between">
                                        <input
                                            type="text"
                                            class="form-control @error('newBlock.lots.'.$newBlockKey.'.lot') is-invalid @enderror"
                                            placeholder="Ex. Block XYZ"
                                            wire:model="newBlock.lots.{{ $newBlockKey }}.lot"
                                        >
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

                                    <textarea
                                        type="text"
                                        class="form-control form-control--textarea mt-2 @error('newBlock.lots.'.$newBlockKey.'.details') is-invalid @enderror"
                                        wire:model="newBlock.lots.{{ $newBlockKey }}.details"
                                        placeholder="Enter lot details"></textarea>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-info text-white" wire:click="addLot">Add Lot</button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
