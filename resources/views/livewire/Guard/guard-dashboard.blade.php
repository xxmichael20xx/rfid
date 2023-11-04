<div>
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
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            @livewire('chart.visitors-chart', ['hasExport' => true])
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            @livewire('chart.rfid-chart', ['hasExport' => true])
        </div>
    </div>
</div>
