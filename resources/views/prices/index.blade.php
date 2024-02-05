<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Listar preços
    </x-slot>
    <main id="main" class="main">

        <div class="modal fade" id="modalChangeUpdatePrice" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="createPrice" action="{{ route('prices.store') }}" method="POST" accept-charset="utf-8"
                    enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 id="modalTitle" class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="modalMessages"></div>
                            <input id="productInput" name="get_product_id" type="hidden"
                                value="{{ old('get_product_id') }}">
                            <div class="row my-3">
                                <label for="valueInput" class="col-sm-2 col-form-label">Valor</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-text" id="money-addon">R$</span>
                                        <input type="text" class="form-control money" id="valueInput"
                                            value="{{ old('value') }}" name="value" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Preços</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Preços</li>
                        <li class="breadcrumb-item active">Listar</li>
                    </ol>
                </nav>
            </div>
        </div>
        <x-alerts.messages />
        <div id="clientMessages"></div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Preços</h5>
                            <p>Lista dos preços dos produtos cadastrados no sistema</p>

                            <table id="productsDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Preço</th>
                                        <th scope="col">Última atualização</th>
                                        <th scope="col">Ação</th>
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
                const routePricesDataTable = "{{ route('prices.load') }}";
                const tablePrices = $('#productsDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routePricesDataTable,
                    },
                    "columns": [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            width: '40%',
                        },
                        {
                            data: 'value',
                            name: 'value',

                            render: function(data, type, full, meta) {
                                return data ? formatMoney(data) : '-';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                var created_at = new Date(data);
                                var formatted_date = formatDateForDatatable(created_at);
                                return data ?
                                    `<small class="text-secondary">registrado em: <span class="fw-bold">${formatted_date}</span></small>` :
                                    '-';
                            }
                        },
                        {
                            data: 'updateButton',
                            name: 'updateButton',
                            orderable: false,
                            searchable: false,
                            width: '1%',
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

                $(document).on('click', '.btn_update', function() {
                    const product = $(this).data('product');
                    const id = $(this).data('id');
                    const value = $(this).data('value');
                    $('#modalTitle').html(`Atualizar preço do ${product}`);
                    $('#productInput').val(id);
                    $('#valueInput').val(value ? formatMoney(value, 'strip') : '');
                    $('#modalChangeUpdatePrice').modal('show');
                });

                $('#createPrice').submit(function(event) {
                    event.preventDefault();
                    const data = new FormData($(this)[0]);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            tablePrices.ajax.reload(null, false);
                            $('#modalChangeUpdatePrice').modal('hide');
                            $('#clientMessages').empty();
                            $('#clientMessages').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                        },
                        error: function(xhr, status, error) {
                            $('#modalMessages').empty();
                            $('#modalMessages').html(`
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Erro ao atualizar novo preço.${' '}
                                    <small class="fw-bold">(${xhr.responseJSON.error || xhr.responseJSON.message})</small>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                        }
                    });
                });

                $('#modalChangeUpdatePrice').on('hidden.bs.modal', function() {
                    $('#modalMessages').empty();
                    $('#createPrice')[0].reset();
                });
            });
        </script>
    @endpush
</x-app>
