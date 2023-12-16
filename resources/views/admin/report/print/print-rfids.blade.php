@extends('layouts.print')

@section('title')
    RFIDs
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
                        <th class="cell">Home Owner Id</th>
                        <th class="cell">Name</th>
                        <th class="cell">Date</th>
                        <th class="cell">Time In | Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="cell">{{ $record['rfid'] }}</td>
                            <td class="cell">{{ $record->rfidData->vehicle->homeOwner->last_full_name }}</td>
                            <td class="cell">{{ $record['date'] }}</td>
                            <td class="cell">{{ $record['time_in'] }} | {{ $record['time_out'] }}</td>
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
@endsection