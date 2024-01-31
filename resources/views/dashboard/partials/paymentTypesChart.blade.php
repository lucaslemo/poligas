<x-dashboards.cardWithFilters title="Vendas por tipos de pagamentos">
    <div id="paymentTypeChart" style="min-height: 400px;" class="echart"></div>
</x-dashboards.cardWithFilters>


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const options = {
                tooltip: {trigger: 'item'},
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [{
                    name: 'Tipos de pagamento',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: []
                }]
            };
            const paymentTypechart = echarts.init(document.querySelector("#paymentTypeChart"));
            paymentTypechart.setOption(options)

            $('#current_filter').on('change', function() {
                const filter = $('#current_filter').val();
                const route = "{{ route('sales.loadChart', ['filter' => ':filter', 'chartType' => 'paymentTypeChart']) }}".replace(':filter', filter);
                $.getJSON(route, function(response) {
                    paymentTypechart.setOption({
                        series: [{
                            data: response
                        }]
                    })
                });
            });
        });
    </script>
@endpush
