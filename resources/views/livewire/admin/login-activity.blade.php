<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Login Activities</h1>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <p class="card-title h5">List of Login</p>
                            </div>
                            <div class="col-8 text-right" wire:ignore>
                                <div class="row justify-content-end">
                                    <form
                                        class="col-4 d-flex flex-column"
                                        action=""
                                        method="GET"
                                    >
                                        <div class="input-container input-group me-2">
                                            <input
                                                type="search"
                                                name="search"
                                                id="search"
                                                class="form-control"
                                                placeholder="Search..."
                                                value="{{ request()->get('search') }}"
                                                required
                                            >
                                            <button class="btn btn-secondary" type="submit" id="search-btn">Search</button>
                                        </div>
                                        @if (request()->get('search'))
                                            <a href="{{ route('login.activities') }}" class="text-help mt-2">Clear search/filters</a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="theme-separator">
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">User</th>
                                                <th class="cell">Browser</th>
                                                <th class="cell">IP Address</th>
                                                <th class="cell">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($records as $record)
                                                <tr>
                                                    <td class="cell">{{ $record->user->last_full_name }}</td>
                                                    <td class="cell">{{ $record->browser }}</td>
                                                    <td class="cell">{{ $record->ip }}</td>
                                                    <td class="cell">{{ $record->time }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="5">No result(s)</td>
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
    
    <div class="modal fade" id="previewCaptureModal" tabindex="-1" aria-labelledby="previewCaptureModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog--md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <img src="" class="img-fluid w-100" id="previewCapture" alt="preview-capture">
                        <div class="alert alert-success text-center mt-3">
                            <h3 id="previewCaptureType"></h3>
                            <h4 class="text-dark text-center mt-2" id="previewCaptureTime"></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                // 
            })
        </script>
    @endsection
</div>
