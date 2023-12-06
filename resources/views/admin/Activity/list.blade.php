@extends('layouts.admin')
@section('content')

<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="app-page-title">Activities @if($filter) - Today @endif</h1>

        <a href="{{ route('activities.create') }}" class="btn btn-success text-white">
            <i class="fa fa-user-plus"></i> Add New
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <p class="card-title h5">List of Activities</p>
                        </div>
                        <div class="col-8 text-right">
                            <div class="row justify-content-end">
                                <div class="col-4 d-flex flex-column">
                                    <div class="input-container input-group me-2">
                                        <input type="search" name="search" id="search" class="form-control" placeholder="Search..." value="{{ request()->get('search') }}">
                                        <button class="btn btn-secondary" type="button" id="search-btn">Search</button>
                                    </div>
                                    @if (request()->get('search') || request()->get('filter'))
                                        <a href="{{ route('activities.list') }}" class="text-help mt-2">Clear search/filters</a>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <div class="input-container">
                                        <select name="filter-by" id="filter-by" class="form-select">
                                            <option value="" selected disabled>Filter by</option>
                                            <option value="today">Today</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="theme-separator">
                        </div>
                    </div>
                    <div class="row">
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
                                                <td class="cell">{{ \Carbon\Carbon::parse($data->start_time)->format('h:ia') }}</td>
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.addEventListener("DOMContentLoaded", (event) => {
        /** Add keydown event to search input */
        const search = document.getElementById('search')
        search.addEventListener('keydown', (event) => {
            if (event.keyCode === 13 && search.value !== '') {
                let currentUrl = window.location.href
                let noParams = currentUrl.split('?')[0]

                let newParam = noParams+'?search='+search.value
                window.location.href = newParam
            }
        })

        /** Add change event on filter-by dropdown */
        const filterBy = document.getElementById('filter-by')
        if (filterBy) {
            filterBy.addEventListener('change', () => {
                window.location.href = window.location.pathname + '?filter=' + filterBy.value
            })
        }
    })
</script>
@endsection
