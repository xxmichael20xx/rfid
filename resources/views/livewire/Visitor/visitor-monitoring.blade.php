<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Visitor Monitoring</h1>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <p class="card-title h5">List of Visitors</p>
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
                                            <a href="{{ route('visitor-monitoring.index') }}" class="text-help mt-2">Clear search/filters</a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="theme-separator">
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Visitor</th>
                                                <th class="cell">For</th>
                                                <th class="cell">Entry date</th>
                                                <th class="cell">Exit date</th>
                                                <th class="cell">Capture</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($visitors as $visitor)
                                                @php
                                                    $isArchived = (bool) $visitor->for->deleted_at;
                                                    $archivedText = 'text-dark';

                                                    if ($isArchived) {
                                                        $archivedText = 'text-danger';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="cell">{{ $visitor->last_full_name }}</td>
                                                    <td class="cell">
                                                        <span
                                                            class="fw-bold {{ $archivedText }}"
                                                            @if ($isArchived)
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                data-bs-title="This Home Owner is archived"
                                                            @endif
                                                        >
                                                            {{ $visitor->for->last_full_name }}
                                                        </span>
                                                    </td>
                                                    <td class="cell">
                                                        @php
                                                            $timeIn = $visitor->time_in;
                                                            $timeOut = $visitor->time_out;
                                                        @endphp
                                                        @if ($timeIn)
                                                            {{ Carbon\Carbon::parse($timeIn)->format('M d, Y @ h:ia') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="cell">
                                                        @if ($timeOut)
                                                            {{ Carbon\Carbon::parse($timeOut)->format('M d, Y @ h:ia') }}
                                                            @if ($visitor->notes)
                                                                <br>
                                                                <small class="text-help">Notes: {{ $visitor->notes }}</small>
                                                            @endif
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="cell">
                                                        @if ($visitor['capture_in'])
                                                            <button
                                                                type="button"
                                                                class="btn btn-success text-white view-time-in"
                                                                data-type="Time In"
                                                                data-date="{{ \Carbon\Carbon::parse($visitor['time_in'])->format('M d, Y') }}"
                                                                data-time="{{ $visitor['time_in'] }}"
                                                                data-img="{{ $visitor['capture_in'] }}"
                                                            >
                                                                <i class="fa fa-image"></i> Capture
                                                            </button>
                                                        @endif
                                                    </td>
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
                let previewCaptureModal = new bootstrap.Modal('#previewCaptureModal', {})
                let previewCaptureTime = document.getElementById('previewCaptureTime')
                let previewCaptureType = document.getElementById('previewCaptureType')
    
                /** Define view capture for time-in and time-out */
                $(document).on('click', '.view-time-in, .view-time-out', function() {
                    const type = $(this).data('type')
                    const image = $(this).data('img')
                    const date = $(this).data('date')
                    const time = $(this).data('time')
                    let icon = `<i class="fa fa-image"></i> Captured`
    
                    previewCapture.setAttribute('src', image)
                    previewCaptureTime.innerHTML = `${date} @ ${time}`
                    previewCaptureType.innerHTML = `${icon} Image`
    
                    previewCaptureModal.show()
                })
            })
        </script>
    @endsection
</div>
