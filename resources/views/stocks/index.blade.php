<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Listar estoque
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Estoque</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Estoque</li>
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
                            <div class="d-flex justify-content-between">
                                <h5 id="tableTitle" class="card-title">Estoque geral</h5>

                                <div class="row card-title w-25">
                                    <div class="col-sm-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="datatableSwitch">
                                            <label class="form-check-label fs-6" for="datatableSwitch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p id="tableDetails"></p>

                            <table id="stocksDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Quantidade em estoque</th>
                                        <th scope="col">Marcas e fornecedores</th>
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

            $('#datatableSwitch').on('change', function() {

                const isChecked = $(this).is(':checked');
                const labelType = isChecked ? 'produtos' : 'tipos de produtos';
                const labelSwitch = isChecked ? 'Detalhada' : 'Geral';
                const type = isChecked ? 'detailed' : 'general';

                // Mudanças visuais da página
                $('#tableTitle').html(isChecked ? 'Estoque detalhado' : 'Estoque geral');
                $('#tableDetails').html(`Lista dos ${labelType} cadastrados no estoque`);
                $('label[for="datatableSwitch"]').html(labelSwitch);

                // Mudanças da datatable
                $('#stocksDataTable').DataTable().destroy();
                $('#stocksDataTable thead th:eq(2)').html(isChecked ? 'Marca' : 'Quantidade em estoque');
                $('#stocksDataTable thead th:eq(3)').html(isChecked ? 'Fornecedor' : 'Marcas e fornecedores');
                $('#stocksDataTable tbody').empty();
                $('#stocksDataTable tfoot').empty();

                const dataFormatFunction = (data, type, full, meta) => {
                    const created_at = new Date(data);
                    const message = isChecked ? 'registrado' : 'último registro';
                    const formatted_date = formatDateForDatatable(created_at);
                    return `<small class="text-secondary">${message} em: <span class="fw-bold">${formatted_date}</span></small>`;
                }

                const columns = isChecked
                    ?   [
                            {data: 'id', name: 'id'},
                            {data: 'product.name', name: 'product.name'},
                            {data: 'brand.name', name: 'brand.name'},
                            {data: 'vendor.name', name: 'vendor.name'},
                            {data: 'created_at', name: 'created_at', width: '30%', orderable: false, searchable: false, render: dataFormatFunction},
                        ]
                    :   [
                            {data: 'id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'product_count', name: 'product_count'},
                            {data: 'brands_and_vendors', name: 'brands_and_vendors', orderable: false, searchable: false},
                            {data: 'latest_stock', name: 'latest_stock', width: '30%', orderable: false, searchable: false, render: dataFormatFunction},
                        ];

                const routeStocksDataTable = "{{ route('stocks.load') }}";
                const tableStocks = $('#stocksDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routeStocksDataTable,
                        data: {type: type},
                    },
                    drawCallback: function(settings) {
                        if (!isChecked) {
                            const api = this.api();
                            const tr = api.rows(function(idx, data, node) {
                                return data.name === 'Total';
                            });
                            if (tr) {
                                $('#stocksDataTable tfoot').empty();
                                $(api.table().node()).append(
                                    $('<tfoot></tfoot>').html(tr.nodes())
                                );
                                tr.remove();
                            }
                        }
                    },
                    "columns": columns,
                    "language": {
                        "paginate": {
                            "next": "Próxima",
                            "previous": "Anterior"
                        },
                        "search": "Buscar",
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ :entity".replace(':entity', labelType),
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ :entity)".replace(':entity', labelType),
                        "lengthMenu": "Mostrar _MENU_ :entity".replace(':entity', labelType),
                        "infoThousands": ".",
                        "emptyTable": "Nenhum registro encontrado",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "loadingRecords": "Carregando...",
                    },
                });

            }).trigger('change');
        });
    </script>
    @endpush
</x-app>
