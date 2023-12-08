<div>
    <div class="modal fade" id="showVisitorExit" tabindex="-1" aria-labelledby="showVisitorExitLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <form method="POST" wire:submit.prevent="visitorNotes" class="col-12">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col text-center">
                                <h2 class="card-title text-dark">Visitor Exit Recorded!</h2>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-container">
                                    <label for="notes" class="form-label">Visitor Notes</label>
                                    <textarea
                                        type="text"
                                        class="form-control form-control--textarea mt-2 @error('form.notes') is-invalid @enderror"
                                        wire:model.lazy="form.notes"
                                        placeholder="Please add the visitor notes here"></textarea>

                                    @error('form.notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary text-white">Save Notes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let showVisitorExit = null

            /** Define event to display the modal */
            Livewire.on('show.visitor-exit', function() {
                showVisitorExit = new bootstrap.Modal('#showVisitorExit', {
                    backdrop: 'static',
                    keyboard: false
                })
                showVisitorExit.show()
            })

            /** Define event to close modal */
            Livewire.on('close.visitor-exit', function() {
                showVisitorExit.hide()

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Exit logged'
                    })
                }, 1500);
            })
        })
    </script>
</div>
