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
                        <th class="cell">Transaction Date</th>
                        <th class="cell">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @forelse ($records as $record)
                        <tr>
                            <td class="cell">{{ $record->type }}</td>
                            <td class="cell">{{ \Carbon\Carbon::parse($record->transaction_date)->format('M d, Y') }}</td>
                            <td class="cell">Php {{ number_format($record->amount) }}</td>
                        </tr>
                        
                        @php
                            $total = $total + $record->amount;
                        @endphp
                    @empty
                        <tr>
                            <td class="cell text-center" colspan="3">No result(s)</td>
                        </tr>
                    @endforelse

                    @if ($records->count() > 0)
                        <tr>
                            <td class="cell" colspan="2"></td>
                            <td>
                                <b>Total: </b>
                                <b>Php {{ number_format($total) }}</b>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection