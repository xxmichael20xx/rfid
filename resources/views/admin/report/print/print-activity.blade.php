@extends('layouts.print')

@section('title')
    Activities
@endsection

@section('range')
    {{ $range }}
@endsection

@section('content')
<div class="row mt-5">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table app-table-hover mb-0 text-left visitors-table">
                <thead class="bg-portal-green">
                    <tr>
                        <th class="cell">Title</th>
                        <th class="cell">Location</th>
                        <th class="cell">Description</th>
                        <th class="cell">Activity Date</th>
                        <th class="cell">Start Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="cell">{{ $record->title }}</td>
                            <td class="cell">{{ $record->location }}</td>
                            <td class="cell">{{ strLimit($record->description) }}</td>
                            <td class="cell">
                                @if ($record->start_date === $record->end_date)
                                    {{ \Carbon\Carbon::parse($record->start_date)->format('M d, Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($record->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($record->end_date)->format('M d, Y') }}
                                @endif
                            </td>
                            <td class="cell">{{ \Carbon\Carbon::parse($record->start_time)->format('h:ia') }}</td>
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
@endsection