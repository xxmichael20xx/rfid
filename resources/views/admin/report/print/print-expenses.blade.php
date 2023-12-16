@extends('layouts.print')

@section('title')
    Expenses
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
                        <th class="cell">Type</th>
                        <th class="cell">Amount</th>
                        <th class="cell">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="cell">{{ $record->type }}</td>
                            <td class="cell">₱{{ number_format($record->amount) }}</td>
                            <td class="cell">{{ \Carbon\Carbon::parse($record->transaction_date)->format('M d, Y') }}</td>
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
@endsection