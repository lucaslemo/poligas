<div class="card">

    <x-dashboards.filter id="paymentTypesChartFilter" />

    <div class="card-body pb-0">
      <h5 class="card-title">Vendas por tipos de pagamentos <span id="labelFor-paymentTypesChartFilter-chart" data-label="today">| Hoje</span></h5>

      <div id="paymentTypeChart" style="min-height: 400px;" class="echart"></div>

    </div>
</div>

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

            $(document).on('click', '.filterFor_paymentTypesChartFilter', function() {
                const filter = $(this).data('filter');
                const html =$(this).html();
                $('#labelFor-paymentTypesChartFilter-chart').data('label', filter);
                $('#labelFor-paymentTypesChartFilter-chart').html(`| ${html}`);
                $('#labelFor-paymentTypesChartFilter-chart').trigger('change');
            });

            $('#labelFor-paymentTypesChartFilter-chart').on('change', function() {
                const filter = $(this).data('label');
                const route = "{{ route('sales.loadChart', ['filter' => ':filter', 'chartType' => 'paymentTypeChart']) }}".replace(':filter', filter);
                $.getJSON(route, function(response) {
                    paymentTypechart.setOption({
                        series: [{
                            data: response
                        }]
                    })
                });
            }).trigger('change');
        });
    </script>
@endpush
