<div class="app-card app-card-chart h-100 shadow-sm">
    <div class="row pt-2">
        <canvas width="100%" id="total-residence-chart" class="p-5 pb-0"></canvas>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const chart = new Chart(
                document.getElementById('total-residence-chart'),
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
        })
    </script>
</div>
