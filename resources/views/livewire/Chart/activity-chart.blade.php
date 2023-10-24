<div class="card shadow-lg border-0 mt-5">
    <div class="card-body">
        <div class="container">
            <div class="row p-5 pt-2">
                <label for="activity-chart-change" class="lead fw-bold mb-2 text-center">Activities Chart</label>
                <div class="col-2 mx-auto">
                    <select
                        wire:change="change"
                        class="form-select"
                        id="activity-chart-change"
                        name="activity-chart-change"
                        wire:model.lazy="type">
                        <option value="days" selected>Last 7 Days</option>
                        <option value="weeks">Last 4 Weeks</option>
                        <option value="months">Last 4 Months</option>
                    </select>
                </div>
                <canvas width="100%" id="activity-chart" class="p-5 pb-0"></canvas>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const chart = new Chart(
                document.getElementById('activity-chart'),
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

            Livewire.on('updateActivityChart', data => {
                chart.data = data
                chart.update()
            })
        })
    </script>
</div>
