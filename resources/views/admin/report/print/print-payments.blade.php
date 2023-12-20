@extends('layouts.print')

@section('title')
    {{ ucfirst($filterBy) }} Payments
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
                        <th class="cell">Name</th>
                        <th class="cell">Block & Lot</th>
                        <th class="cell">Amount</th>
                        <th class="cell">Due Date</th>
                        <th class="cell">Status</th>
                        <th class="cell">Received By</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @forelse ($records as $record)
                        <tr>
                            <td class="cell">{{ $record->biller->last_full_name }}</td>
                            <td class="cell">{{ $record->block_lot_item }}</td>
                            <td class="cell">
                                Php {{ number_format($record->amount, 2) }}
                                @if ($record->reference)
                                    <br>
                                    Referenece: {{ $record->reference }}
                                @endif
                            </td>
                            <td class="cell">
                                @php
                                    $dueDate = Carbon\Carbon::parse($record->due_date);
                                    $diffInDays = Carbon\Carbon::now()->diffInDays($dueDate);
                                    $dueClass = 'text-dark';

                                    if ($diffInDays <= 3 && $record->status != 'paid') {
                                        $dueClass = 'text-danger fw-bold';
                                    }
                                @endphp
                                <p class="{{ $dueClass }}">{{ Carbon\Carbon::parse($dueDate)->format('M d, Y') }}</p>
                            </td>
                            <td class="cell">
                                @php
                                    $status = $record->status;
                                    $badgeClass = 'danger';

                                    if ($status == 'paid') {
                                        $badgeClass = 'success';
                                    }
                                @endphp
                                <div class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</div>
                            </td>
                            <td class="cell">
                                {{ $record->payment_received_by }}

                                @if ($record->payment_received_by !== 'N/A')
                                    <small class="text-help m-0 p-0 d-block">{{ \Carbon\Carbon::parse($record->date_paid)->format('M d, Y @ h:i A') }}</small>
                                @endif
                            </td>
                        </tr>

                        @php
                            $total = $total + $record->amount;
                        @endphp
                    @empty
                        <tr>
                            <td class="cell text-center" colspan="6">No result(s)</td>
                        </tr>
                    @endforelse

                    @if ($records->count() > 0)
                        <tr>
                            <td class="cell" colspan="5"></td>
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