<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Listar vendas
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Vendas</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Vendas</li>
                        <li class="breadcrumb-item active">Listar</li>
                    </ol>
                </nav>
            </div>
        </div>
        <x-alerts.messages />
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Vendas</h5>
                            <p>Lista das vendas cadastradas no sistema</p>

                            <table id="salesDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Vendedor</th>
                                        <th scope="col">Entregador</th>
                                        <th scope="col">Valor total</th>
                                        <th scope="col">Tipo de pagamento</th>
                                        <th scope="col">Registro</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const routeSalesDataTable = "{{ route('sales.load') }}";
            const tableSales = $('#salesDataTable').DataTable({
                searching: true,
                responsive: true,
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: routeSalesDataTable,
                },
                "columns": [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'customer.name',
                        name: 'customer.name',
                    },
                    {
                        data: 'user.first_name',
                        name: 'user.first_name',
                        render: function(data, type, full, meta) {
                            return `${data} ${full.user.last_name}`;
                        }
                    },
                    {
                        data: 'deliveryman.first_name',
                        name: 'deliveryman.first_name',
                        render: function(data, type, full, meta) {
                            return data ? `${data} ${full.user.last_name}` : '-';
                        }
                    },
                    {
                        data: 'total_value',
                        name: 'total_value',
                        render: function(data, type, full, meta) {
                            return formatMoney(data);
                        }
                    },
                    {
                        data: 'payment_type.name',
                        name: 'paymentType.name',
                        render: function(data, type, full, meta) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            console.log(full)
                            var created_at = new Date(data);
                            var formatted_date = formatDateForDatatable(created_at);
                            return `<small class="text-secondary">registrado em: <span class="fw-bold">${formatted_date}</span></small>`;
                        }
                    },
                ],
                "language": {
                    "paginate": {
                        "next": "Próxima",
                        "previous": "Anterior"
                    },
                    "search": "Buscar",
                    "info": "Mostrando de _START_ a _END_ de _TOTAL_ vendas",
                    "infoEmpty": "Não há registros disponíveis",
                    "infoFiltered": "(Filtrados de _MAX_ vendas)",
                    "lengthMenu": "Mostrar _MENU_ vendas",
                    "infoThousands": ".",
                    "emptyTable": "Nenhum registro encontrado",
                    "zeroRecords": "Nenhum registro correspondente encontrado",
                    "loadingRecords": "Carregando...",
                },
            });
        });
    </script>
    @endpush
</x-app>
