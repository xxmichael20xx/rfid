@extends('layouts.print')

@section('title')
    Visitors
@endsection

@section('range')
    {{ $range }}
@endsection

@section('content')
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
@endsection