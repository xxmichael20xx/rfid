@extends('layouts.admin')
@section('content')

<div class="row g-4 mb-4">
    <div class="col-12">
        <h1 class="app-page-title">Activities @if($filter) - Today @endif</h1>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div class="col-auto">
            <input type="search" name="search" id="search" class="form-control" placeholder="Search...">
            @if ($filter || $search)
                <a href="{{ route('activities.list') }}" class="text-help">Clear filters/search</a>
            @endif
        </div>
        <div class="col-auto">
            <a href="{{ route('activities.create') }}" class="btn btn-success text-white">
                <i class="fa fa-user-plus"></i> Add New
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="app-card app-card-chart h-100 shadow-sm">
            <div class="app-card-body px-3 pb-3">
                @if ($search)
                    <div class="py-3">
                        <h5>Search results for `{{ $search }}`</h5>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table app-table-hover mb-0 text-left visitors-table">
                        <thead class="bg-portal-green">
                            <tr>
                                <th class="cell">Title</th>
                                <th class="cell">Location</th>
                                <th class="cell">Description</th>
                                <th class="cell">Activity Date</th>
                                <th class="cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activities as $data)
                                <tr>
                                    <td class="cell">{{ $data->title }}</td>
                                    <td class="cell">{{ $data->location }}</td>
                                    <td class="cell">{{ strLimit($data->description) }}</td>
                                    <td class="cell">
                                        @if ($data->start_date === $data->end_date)
                                            {{ \Carbon\Carbon::parse($data->start_date)->format('M d, Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($data->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($data->end_date)->format('M d, Y') }}
                                        @endif
                                    </td>
                                    <td class="cell d-flex">
                                        @livewire('activity.activity-delete', ['modelId' => $data->id])

                                        <a href="{{ route('activities.update', ['id' => $data->id]) }}" class="btn btn-info text-white p-2 ms-2">
                                            <i class="fa fa-pencil"></i>
                                        </a>
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
@endsection

@section('scripts')
<script>
    window.addEventListener("DOMContentLoaded", (event) => {
        const search = document.getElementById('search')

        search.addEventListener('keydown', (event) => {
            if (event.keyCode === 13 && search.value !== '') {
                let currentUrl = window.location.href
                let noParams = currentUrl.split('?')[0]

                let newParam = noParams+'?search='+search.value
                window.location.href = newParam
            }
        })
    })
</script>
@endsection
