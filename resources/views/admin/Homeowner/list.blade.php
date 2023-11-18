@extends('layouts.admin')
@section('content')

<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="app-page-title">Manage Home Owners</h1>
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
                    <div class="row">
                        <div class="col-4">
                            <p class="card-title h5">List of Home Owners</p>
                        </div>
                        <div class="col-8 text-right">
                            <div class="row justify-content-end">
                                <form
                                    class="col-4 d-flex flex-column"
                                    action=""
                                    method="GET"
                                >
                                    <div class="input-container input-group me-2">
                                        <input
                                            type="search" name="search"
                                            id="search" class="form-control"
                                            placeholder="Search..."
                                            value="{{ request()->get('search') }}"
                                            required
                                        >
                                        <button class="btn btn-secondary" type="submit" id="search-btn">Search</button>
                                    </div>
                                    @if (request()->get('search'))
                                        <a href="{{ route('homeowners.list') }}" class="text-help mt-2">Clear search/filters</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="theme-separator">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table app-table-hover mb-0 text-left visitors-table">
                                        <thead class="bg-portal-green">
                                            <tr>
                                                <th class="cell">Name</th>
                                                <th class="cell">Vehicles Owned</th>
                                                <th class="cell">Lots Owned</th>
                                                <th class="cell">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($homeOwners as $data)
                                                <tr>
                                                    <td class="cell">{{ $data->full_name }}</td>
                                                    <td class="cell">{{ $data->blockLots->count() }}</td>
                                                    <td class="cell">{{ $data->vehicles->count() }}</td>
                                                    <td class="cell d-flex">
                                                        @livewire('homeowner.homeowner-delete', ['modelId' => $data->id])

                                                        <a href="{{ route('homeowners.view', ['id' => $data->id]) }}" class="btn btn-success text-white p-2 ms-2">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('homeowners.update', ['id' => $data->id]) }}" class="btn btn-info text-white p-2 ms-2">
                                                            <i class="fa fa-pencil"></i>
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
</div>
@endsection
