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
                                                <th class="cell">Date Visited</th>
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
                                                        @if($visitor->date_visited)
                                                            {{ Carbon\Carbon::parse($visitor->date_visited)->format('M d, Y @ h:ia') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="3">No result(s)</td>
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

    @section('scripts')
        <script>
            $(document).ready(function() {

            })
        </script>
    @endsection
</div>
