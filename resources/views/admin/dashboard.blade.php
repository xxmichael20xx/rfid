@extends('layouts.admin')

@section('content')
<h1 class="app-page-title">Dashboard</h1>
<div class="row mb-5">
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4 d-flex justify-content-center">
                <i class="fa fa-walking fa-3x me-3"></i>
                <div>
                    <h4 class="stats-type text-dark mb-1">Visitors Today</h4>
                    <div class="stats-figure">{{ $visitorsToday }}</div>
                </div>
            </div>
            <a class="app-card-link-mask" href="{{ route('visitor-monitoring.index') }}"></a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4 d-flex justify-content-center">
                <i class="fa fa-house fa-3x me-3"></i>
                <div>
                    <h4 class="stats-type text-dark mb-1">Total of Households</h4>
                    <div class="stats-figure">{{ $totalHomeOwners }}</div>
                </div>
            </div>
            <a class="app-card-link-mask" href="{{ route('homeowners.list') }}"></a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4 d-flex justify-content-center">
                <i class="fa fa-tasks fa-3x me-3"></i>
                <div>
                    <h4 class="stats-type text-dark mb-1">Activities Today</h4>
                    <div class="stats-figure">{{ $activitiesToday }}</div>
                </div>
            </div>
            <a class="app-card-link-mask" href="{{ route('activities.list', ['filter' => 'today']) }}"></a>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-8">
        @livewire('chart.total-residence-chart')
    </div>
    <div class="col-4">
        <div class="app-card shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <div class="d-flex justify-content-end">
                    <i class="fa fa-users fa-3x mb-5 text-success"></i>
                </div>
                <h2 class="app-page-title text-start">Residence in Glen Ville</h2>
                <p>
                    This data shows the residence of Glen Ville Subdivision over a 5-year period,
                    with the population increasing by a few million each year.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-6">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <p class="card-title h5">Recent Visitors</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Visitor</th>
                                            <th class="cell">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($visitors as $visitor)
                                            <tr>
                                                <td class="cell">
                                                    {{ $visitor->last_full_name }}
                                                </td>
                                                <td class="cell">{{ Carbon\Carbon::parse($visitor->date_visited)->format('M d, Y @ h:ia') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="cell" colspan="2">No recent visitor</td>
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
    <div class="col-6">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-12">
                            <p class="card-title h5">Recent Activities</p>
                            <hr class="theme-separator">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left visitors-table">
                                    <thead class="bg-portal-green">
                                        <tr>
                                            <th class="cell">Activity</th>
                                            <th class="cell">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($activities as $data)
                                            <tr>
                                                <td class="cell">
                                                    {{ $data->title }}
                                                    <br>
                                                    Location: {{ $data->location }}
                                                </td>
                                                <td class="cell">
                                                    @if ($data->start_date === $data->end_date)
                                                        {{ \Carbon\Carbon::parse($data->start_date)->format('M d, Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($data->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($data->end_date)->format('M d, Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="cell" colspan="2">No activity yet</td>
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

<div class="row g-4 mb-4">
    <div class="col-12">
        @livewire('chart.activity-chart')
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        @livewire('chart.visitors-chart')
    </div>
</div>
@endsection