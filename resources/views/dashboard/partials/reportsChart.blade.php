<div class="card">

    <x-dashboards.filter id="reportsChartFilter" />

    <div class="card-body">
        <h5 class="card-title">Informes <span id="labelFor-reportsChartFilter-chart" data-label="today">| Hoje</span></h5>
        <div id="reportsChart"></div>
    </div>

</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const options = {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                },
                markers: {
                    size: 4
                },
                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.4,
                        stops: [0, 90, 100]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                dataLabels: {
                    enabled: false
                },
                series: [],
                noData: {
                    text: 'Carregando...'
                }
            }
            var chart = new ApexCharts(document.querySelector("#reportsChart"), options);
            chart.render();

            $(document).on('click', '.filterFor_reportsChartFilter', function() {
                const filter = $(this).data('filter');
                const html =$(this).html();
                $('#labelFor-reportsChartFilter-chart').data('label', filter);
                $('#labelFor-reportsChartFilter-chart').html(`| ${html}`);
                $('#labelFor-reportsChartFilter-chart').trigger('change');
            });

            $('#labelFor-reportsChartFilter-chart').on('change', async function() {
                const filter = $(this).data('label');
                const route = "{{ route('sales.loadChart', ['filter' => ':filter', 'chartType' => 'reportChart']) }}".replace(':filter', filter);
                $.getJSON(route, function(response) {
                    chart.updateOptions({
                        series: [{
                            name: 'Vendas',
                            data: response.series
                        }],
                        xaxis: {
                            categories: response.categories,
                            title: {
                                text: response.label,
                                style: {
                                    color: '#012970',
                                    fontSize: '12px',
                                    fontFamily: 'Poppins, sans-serif',
                                    fontWeight: 500,
                                    cssClass: 'apexcharts-xaxis-title',
                                },
                            }
                        },
                        noData: {
                            text: 'Sem dados'
                        }
                    });
                });
            }).trigger('change');
        });
    </script>
@endpush
