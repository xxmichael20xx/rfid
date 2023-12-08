<div>
    @section('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endsection

    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Report - Visitors</h1>

            <button type="button" class="btn btn-success text-white" wire:click="exportData">Generate Report</button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-8">
                                <p class="card-title h5">Visitors</p>
                            </div>
                            <div class="col-4 d-flex justify-content-end" wire:ignore>
                                <label for="date-range-picker" class="col-form-label me-2">Select range</label>
                                <input type="text" id="date-range-picker" class="form-control w-auto border-secondary" />
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($records as $record)
                                                @php
                                                    $isArchived = (bool) $record->for->deleted_at;
                                                    $archivedText = 'text-dark';

                                                    if ($isArchived) {
                                                        $archivedText = 'text-danger';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="cell">{{ $record->last_full_name }}</td>
                                                    <td class="cell">
                                                        <span
                                                            class="fw-bold {{ $archivedText }}"
                                                            @if ($isArchived)
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                data-bs-title="This Home Owner is archived"
                                                            @endif
                                                        >
                                                            {{ $record->for->last_full_name }}
                                                        </span>
                                                    </td>
                                                    <td class="cell">
                                                        @php
                                                            $timeIn = $record->time_in;
                                                            $timeOut = $record->time_out;
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
                                                            @if ($record->notes)
                                                                <br>
                                                                <small class="text-help">Notes: {{ $record->notes }}</small>
                                                            @endif
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="4">No result(s)</td>
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
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $(document).ready(function() {
                const currentDate = moment()

                $('#date-range-picker').daterangepicker({
                    opens: 'left',
                    alwaysShowCalendars: true,
                    startDate: currentDate.clone().startOf('month'),
                    endDate: currentDate.clone().endOf('month'),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                    }
                })

                $('#date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                    const startDate = picker.startDate.format('YYYY-MM-DD');
                    const endDate = picker.endDate.format('YYYY-MM-DD');

                    @this.dateRange = [startDate, endDate]
                    @this.loadRecords()
                });
            })
        </script>
    @endsection
</div>
