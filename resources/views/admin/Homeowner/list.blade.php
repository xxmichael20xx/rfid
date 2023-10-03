@extends('layouts.admin')
@section('content')

<h1 class="app-page-title">Home Owner List</h1>
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div class="col-auto">
            <input type="search" name="search" id="search" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <a href="{{ route('homeowners.create') }}" class="btn btn-success text-white">
                <i class="fa fa-user-plus"></i> Add New
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="container">
                    @if ($search)
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="py-3">
                                    <h5>Search results for `{{ $search }}`</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Name</th>
                                            <th class="cell">RFID</th>
                                            <th class="cell">Block</th>
                                            <th class="cell">Lot</th>
                                            <th class="cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($homeOwners as $data)
                                            @php
                                                $rfid = ($data->rfid) ? $data->rfid->rfid : 'No assigned RFID';
                                            @endphp
                                            <tr>
                                                <td class="cell">{{ $data->full_name }}</td>
                                                <td class="cell">{{ $rfid }}</td>
                                                <td class="cell">{{ $data->myBlock->block }}</td>
                                                <td class="cell">{{ $data->myLot->lot }}</td>
                                                <td class="cell d-flex">
                                                    @livewire('homeowner.homeowner-delete', ['modelId' => $data->id])
                                                    
                                                    <a href="{{ route('homeowners.update', ['id' => $data->id]) }}" class="btn btn-info text-white p-2 ms-2">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
            
                                                    <a href="{{ route('homeowners.view', ['id' => $data->id]) }}" class="btn btn-success text-white p-2 ms-2">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
@endsection

@section('scripts')
<script>
    window.addEventListener("DOMContentLoaded", (event) => {
        /** Define the search enter keypress */
        const search = document.getElementById('search')
        search.addEventListener('keydown', (event) => {
            if (event.keyCode === 13 && search.value !== '') {
                let currentUrl = window.location.href
                let noParams = currentUrl.split('?')[0]

                let newParam = noParams+'?search='+search.value
                window.location.href = newParam
            }
        })

        /** Initialize the events for file changes on beeps */
        var channel = Echo.channel('my-channel');
        channel.listen('.scan-rfid', function(data) {
            alert(JSON.stringify(data));
        });
    })
</script>
@endsection
