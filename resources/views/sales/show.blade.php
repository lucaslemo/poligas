<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Editar venda
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Vendas</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Vendas</li>
                        <li class="breadcrumb-item active">Selecionar produtos</li>
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
                            <h5 class="card-title">Finalizar compra</h5>
                            <p class="mb-5">Indentificador da venda: <strong>{{ $sale->uuid }}</strong></p>
                            <div class="row g-3">
                                <div class="row mb-3">
                                    <label for="customerSelect" class="col-sm-2 col-form-label">
                                        Cliente
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ $sale->customer->name }}" disabled>
                                    </div>
                                </div>
                                @hasrole('Administrador')
                                    <div class="row mb-3">
                                        <label for="userSelect" class="col-sm-2 col-form-label">
                                            Vendedor
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $sale->user->first_name . ' ' . $sale->user->last_name }}" disabled>
                                        </div>
                                    </div>
                                @endhasrole
                                <div class="row mb-3">
                                    <label for="totalValue" class="col-sm-2 col-form-label">Valor da venda</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text" id="money-addon">R$</span>
                                            <input type="text" class="form-control money" id="totalValue"
                                                value="{{ $sale->total_value }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="deliverymanSelect" class="col-sm-2 col-form-label">
                                        Entregador
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            value="{{ $sale->deliveryman ? $sale->deliveryman->first_name . ' ' . $sale->deliveryman->last_name : '-' }}" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="paymentTypeSelect" class="col-sm-2 col-form-label">
                                        Tipo de pagamento
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            value="{{ $sale->paymentType->name }}" disabled>
                                    </div>
                                </div>

                                <p>Lista dos produtos dessa venda</p>

                                <table id="saleStocksDataTable" class="table table-sm" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Produto</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Fornecedor</th>
                                            <th scope="col">Valor de compra</th>
                                            <th scope="col">Valor de venda</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {

                const routeStocksDataTable = "{{ route('stocks.load') }}";
                const tableStocks = $('#saleStocksDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routeStocksDataTable,
                        data: {
                            type: 'sale',
                            sale_id: "{{ $sale->id }}"
                        },
                    },
                    "drawCallback": function(settings) {
                        if ($('#saleStocksDataTable').DataTable().data().count() == 0) {
                            $('#second_hidden_layer').hide()
                        } else {
                            $('#first_hidden_layer').show();
                            $('#second_hidden_layer').show()
                        }
                    },
                    "columns": [{
                            data: 'id',
                            name: 'id',
                            width: '5%',
                        },
                        {
                            data: 'product.name',
                            name: 'product.name',
                            width: '10%',
                        },
                        {
                            data: 'brand.name',
                            name: 'brand.name',
                        },
                        {
                            data: 'vendor.name',
                            name: 'vendor.name',
                        },
                        {
                            data: 'vendor_value',
                            name: 'vendor_value',
                            width: '15%',
                            render: (data) => formatMoney(data)
                        },
                        {
                            data: 'sales.0.pivot.sale_value',
                            name: null,
                            orderable: false,
                            searchable: false,
                            width: '15%',
                            render: function(data, type, full, meta) {
                                return formatMoney(data)
                            }
                        },
                        {
                            data: 'detachButton',
                            name: 'detachButton',
                            width: '1%',
                            orderable: false,
                            searchable: false,
                            visible: {{ $sale->status == 'opened' ? 'true' : 'false' }},
                        },
                    ],
                    "language": {
                        "paginate": {
                            "next": "Próxima",
                            "previous": "Anterior"
                        },
                        "search": "Buscar",
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ produtos",
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ produtos)",
                        "lengthMenu": "Mostrar _MENU_ produtos",
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
