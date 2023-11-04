<div class="card shadow-lg border-0 mt-5">
    <div class="card-body">
        <div class="container">
            <div class="row p-5 pt-2 position-relative">
                @if ($hasExport)
                    <button
                        type="button"
                        class="btn btn-success text-white w-auto position-absolute top-0 end-0"
                        wire:click="exportData"
                    >
                        <i class="fa fa-cloud-download"></i> Export
                    </button>
                @endif

                <label for="rfid-chart-change" class="lead fw-bold mb-2 text-center">RFID Chart</label>
                <div class="col-2 mx-auto">
                    <select
                        wire:change="change"
                        class="form-select"
                        id="rfid-chart-change"
                        name="rfid-chart-change"
                        wire:model.lazy="type">
                        <option value="days" selected>Last 7 Days</option>
                        <option value="weeks">Last 4 Weeks</option>
                        <option value="months">Last 4 Months</option>
                    </select>
                </div>
                <canvas width="100%" id="rfid-chart" class="p-5 pb-0"></canvas>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const chart = new Chart(
                document.getElementById('rfid-chart'),
                {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: `{{ $title }}`,
                            data: @json($rows),
                            backgroundColor: @json($colors)
                        }]
                    }
                }
            )

            Livewire.on('updateRfidhart', data => {
                chart.data = data
                chart.update()
            })
        })
    </script>
</div>
<div>
    {{-- Stop trying to control. --}}
</div>
