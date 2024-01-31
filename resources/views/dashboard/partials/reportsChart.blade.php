<x-dashboards.cardWithFilters title="Informes">
    <div id="reportsChart"></div>
</x-dashboards.cardWithFilters>

@push('scripts')
    <script type="text/javascript">

        $(document).ready(async function() {
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
            const reportChart = new ApexCharts(document.querySelector("#reportsChart"), options);
            reportChart.render();

            $('#current_filter').on('change', function() {
                const filter = $('#current_filter').val();
                const route = "{{ route('sales.loadChart', ['filter' => ':filter', 'chartType' => 'reportChart']) }}".replace(':filter', filter);
                $.get(route, function(response) {
                    reportChart.updateOptions({
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
            });
        });
    </script>
@endpush
