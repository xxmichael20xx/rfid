<div class="modal fade" id="updateAccount" tabindex="-1" aria-labelledby="updateAccountLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <form method="POST" wire:submit.prevent="updateAccount">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateAccountLabel">Update Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-container mb-3">
                                <label for="email">Email<span class="required">*</span></label>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="form-control @error('form.email') is-invalid @enderror"
                                        wire:model.lazy="form.email">

                                    @error('form.email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-container mb-3">
                                <label for="new_password">New Password</label>
                                    <input
                                        id="new_password"
                                        name="new_password"
                                        type="password"
                                        class="form-control @error('form.new_password') is-invalid @enderror"
                                        wire:model.lazy="form.new_password">

                                    @error('form.new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-container mb-3">
                                <label for="contact_email">Contact Email<span class="required">*</span></label>
                                <input
                                    id="contact_email"
                                    name="contact_email"
                                    type="email"
                                    class="form-control @error('form.contact_email') is-invalid @enderror"
                                    wire:model.lazy="form.contact_email">

                                @error('form.contact_email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-container mb-3">
                                <label for="contact_phone">Contact Phone<span class="required">*</span></label>
                                <input
                                    id="contact_phone"
                                    name="contact_phone"
                                    type="tel"
                                    class="form-control @error('form.contact_phone') is-invalid @enderror"
                                    wire:model.lazy="form.contact_phone">

                                @error('form.contact_phone')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_replace('form.', '', $message) }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="theme-separator"></div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="input-container mb-3">
                                <label for="current_password">Current Password<span class="required">*</span></label>
                                    <input
                                        id="current_password"
                                        name="current_password"
                                        type="password"
                                        class="form-control @error('form.current_password') is-invalid @enderror"
                                        wire:model.lazy="form.current_password"
                                        autocomplete ="off">

                                    @error('form.current_password')
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
                    <button type="submit" class="btn btn-primary text-white">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
