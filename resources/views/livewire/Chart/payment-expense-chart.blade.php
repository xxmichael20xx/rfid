<div class="card shadow-lg border-0 mt-5">
    <div class="card-body">
        <div class="container">
            <div class="row p-5 pt-2">
                <label for="payment-expenses-chart-change" class="lead fw-bold mb-2 text-center">Expenses Chart</label>
                <div class="col-2 mx-auto">
                    <select
                        wire:change="change"
                        class="form-select"
                        id="payment-expenses-chart-change"
                        name="payment-expenses-chart-change"
                        wire:model.lazy="type">
                        <option value="weeks" selected>Weeks</option>
                        <option value="months">Months</option>
                        <option value="years">Years</option>
                    </select>
                </div>
                <canvas width="100%" id="payment-expenses-chart" class="p-5 pb-0"></canvas>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const chart = new Chart(
                document.getElementById('payment-expenses-chart'),
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

            Livewire.on('updatePaymentExpensesChart', data => {
                chart.data = data
                chart.update()
            })
        })
    </script>
</div>
