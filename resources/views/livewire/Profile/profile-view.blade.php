<div>
    <button
        type="button"
        class="btn btn-success text-white p-2 ms-2"
        data-bs-toggle="modal"
        data-bs-target="#viewProfileModal-{{ $profile->id }}">
        <i class="fa fa-eye"></i>
    </button>

    <div class="modal fade" id="viewProfileModal-{{ $profile->id }}" tabindex="-1" aria-labelledby="viewProfileModalLabel-{{ $profile->id }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewProfileModalLabel-{{ $profile }}">Family Member details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-dark"><b>Name:</b> {{ $profile->last_full_name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-dark"><b>Date of birth:</b> {{ \Carbon\Carbon::parse($profile->date_of_birth)->format('M d, Y') }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-dark"><b>Age:</b> {{ $profile->age }} year(s) old</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-dark"><b>Gener:</b> {{ ucfirst($profile->gender) }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-dark"><b>Contact number:</b> {{ emptyContact($profile->contact_no) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-dark"><b>Date added:</b> {{ $profile->date_joined }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-dark"><b>Relation:</b> {{ $profile->relation }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
