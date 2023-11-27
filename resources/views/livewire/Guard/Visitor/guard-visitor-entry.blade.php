<div>
    @if($data)
        <div class="modal fade" id="showVisitorEntryModal" tabindex="-1" aria-labelledby="showVisitorEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog--md">
                <div class="modal-content">
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col text-center">
                                    <h2 class="card-title text-dark">Visitor Entry Recorded!</h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 mx-auto text-center">
                                    @if ($data?->profile)
                                        <img
                                            src="{{ $data?->profile }}"
                                            alt="Home-Owner-Avatar"
                                            class="img-fluid mb-3 rounded shadow"
                                            style="width: 250px;"
                                        />
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6 mx-auto text-center">
                                    <p class="card-title h5">Home Owner: {{ $data?->last_full_name }}</p>
                                    <hr class="theme-separator">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-dark"><b>Date of Birth:</b> {{ $data?->date_of_birth }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-dark"><b>Age:</b> {{ $data?->age }} year(s) old</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-dark"><b>Gender:</b> {{ ucfirst($data?->gender) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-dark"><b>Email:</b> {{ $data?->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-dark"><b>Contact Number:</b> {{ $data?->contact_no }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-dark"><b>Member Since:</b> {{ \Carbon\Carbon::parse($data?->created_at)->format('M y, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </div>
    @endif

    @vite(['resources/js/app.js'])
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            /** Define event to display the modal */
            Livewire.on('show.visitor-entry', function() {
                const showVisitorEntryModal = new bootstrap.Modal('#showVisitorEntryModal', {})
                showVisitorEntryModal.show()
            })
        })
    </script>
</div>
