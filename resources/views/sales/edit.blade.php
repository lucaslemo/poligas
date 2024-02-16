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
                            <h5 class="card-title">Selecionar produtos</h5>
                            <p class="mb-5">Indentificador da venda: <strong>{{ $sale->uuid }}</strong></p>
                            <form id="editSaleForm" action="{{ route('sales.update', $sale->id) }}" class="row g-3"
                                method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="productSelect" class="col-sm-2 col-form-label">
                                        Cliente
                                    </label>
                                    <div class="col-sm-10">
                                        <select id="customerSelect" data-old-value="{{ $sale->get_customer_id }}"
                                            class="form-select select2" name="get_customer_id"></select>
                                    </div>
                                </div>
                                @hasrole('Administrador')
                                    <div class="row mb-3">
                                        <label for="productSelect" class="col-sm-2 col-form-label">
                                            Vendedor
                                        </label>
                                        <div class="col-sm-10">
                                            <select id="userSelect" data-old-value="{{ $sale->get_user_id }}"
                                                class="form-select select2" name="get_user_id"></select>
                                        </div>
                                    </div>
                                @endhasrole
                                <div class="row mb-3">
                                    <label for="productSelect" class="col-sm-2 col-form-label">Produto em
                                        estoque</label>
                                    <div class="col-sm-10">
                                        <select id="productSelect" data-old-value="{{ old('get_product_id') }}"
                                            class="form-select select2" name="get_product_id"></select>
                                        <small id="labelProductInStocks" style="display: none">
                                            <i class="bi bi-info-circle text-secondary"></i>
                                            <span id="infoProductStocks"></span>
                                        </small>
                                    </div>
                                </div>
                                <div id="first_hidden_layer" style="display: none">

                                    <div class="row mb-3">
                                        <label for="valueInput" class="col-sm-2 col-form-label">Valor unitário</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <span class="input-group-text" id="money-addon">R$</span>
                                                <input type="text" class="form-control money" id="valueInput" name="value">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="quantityInput" class="col-sm-2 col-form-label">Quantidade</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="quantityInput" name="quantity">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button id="attachStockButton" type="button"
                                            class="btn btn-outline-primary w-20">
                                            Atribuir produto
                                        </button>
                                    </div>

                                    <p>Lista dos produtos cadastrados no sistema</p>

                                    <table id="saleStocksDataTable" class="table table-sm" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Produto</th>
                                                <th scope="col">Marca</th>
                                                <th scope="col">Fornecedor</th>
                                                <th scope="col">Valor de compra</th>
                                                <th scope="col">Valor de venda</th>
                                                <th scope="col">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                    <div id="second_hidden_layer" style="display: none">
                                        <div class="text-center">
                                            <x-forms.button-with-spinner id="editSale" type="submit"
                                                class="btn btn-primary w-20" formId="editSaleForm">
                                                Consolidar venda
                                            </x-forms.button-with-spinner>
                                        </div>
                                    </div>

                                </div>
                            </form>
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
                        if($('#saleStocksDataTable').DataTable().data().count() == 0){
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
                        },
                    ],
                    "language": {
                        "paginate": {
                            "next": "Próxima",
                            "previous": "Anterior"
                        },
                        "search": "Buscar",
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ produtos do estoque",
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ produtos do estoque)",
                        "lengthMenu": "Mostrar _MENU_ produtos do estoque",
                        "infoThousands": ".",
                        "emptyTable": "Nenhum registro encontrado",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "loadingRecords": "Carregando...",
                    },
                });

                const routeCustomers = "{{ route('customers.getCustomers') }}";
                const selectCustomers = $("#customerSelect").select2({
                    placeholder: 'Selecione...',
                    theme: "bootstrap-5",
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    language: {
                        noResults: function() {
                            return `Nenhum registro encontrado. <a href="{{ route('customers.create') }}">Cadastrar</a>`;
                        }
                    },
                    allowClear: true,
                    multiple: false,
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    ajax: {
                        url: routeCustomers,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                            }
                        },
                        cache: true
                    }
                });

                const oldCustomer = $("#customerSelect").data('old-value');
                if (oldCustomer != '' && oldCustomer != null) {
                    const routeCustomer = "{{ route('customers.getCustomer', ':id') }}".replace(':id', oldCustomer);
                    $.get(routeCustomer, function(response) {
                        const option = new Option(response.name, response.id, true, true);
                        $("#customerSelect").append(option).trigger('change');
                    });
                }

                const routeUsers = "{{ route('users.getUsers', ['role' => 'Gerente']) }}";
                const selectUsers = $("#userSelect").select2({
                    placeholder: 'Selecione...',
                    theme: "bootstrap-5",
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    language: {
                        noResults: function() {
                            return `Nenhum registro encontrado. <a href="{{ route('users.create') }}">Cadastrar</a>`;
                        }
                    },
                    allowClear: true,
                    multiple: false,
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    ajax: {
                        url: routeUsers,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                            }
                        },
                        cache: true
                    }
                });

                const oldUser = $("#userSelect").data('old-value');
                if (oldUser != '' && oldUser != null) {
                    const routeUser = "{{ route('users.getUser', ':id') }}".replace(':id', oldUser);
                    $.get(routeUser, function(response) {
                        const name = `${response.first_name} ${response.last_name}`;
                        const option = new Option(name, response.id, true, true);
                        $("#userSelect").append(option).trigger('change');
                    });
                }

                const routeProducts = "{{ route('products.getProducts') }}";
                const selectProducts = $("#productSelect").select2({
                    placeholder: 'Selecione...',
                    theme: "bootstrap-5",
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    language: {
                        noResults: function() {
                            return `Nenhum registro encontrado. <a href="{{ route('stocks.create') }}">Cadastrar</a>`;
                        }
                    },
                    allowClear: true,
                    multiple: false,
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    ajax: {
                        url: routeProducts,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                                filter: 'stocked',
                            }
                        },
                        cache: true
                    }
                });

                $("#productSelect").on('change', function() {
                    if ($(this).val()) {
                        const product = $(this).select2('data')[0].text;
                        const route = "{{ route('stocks.productStocks', ':product') }}".replace(':product',
                            product);
                        $.get(route, function(response) {
                            const stocks = response.stocks == 1 ? `${response.stocks} produto` :
                                `${response.stocks} produtos`;
                            const brands = response.brands == 1 ? `${response.brands} marca` :
                                `${response.brands} marcas`;
                            const vendors = response.vendors == 1 ? `${response.vendors} fornecedor` :
                                `${response.vendors} fornecedores`;
                            $('#infoProductStocks').html(
                                `Existem ${stocks}, de ${brands} e ${vendors}.`
                            );
                            $('#labelProductInStocks').show();
                            $('#valueInput').val(response.value ? formatMoney(response.value, 'strip') :
                                '');
                            $('#quantityInput').val(1);
                            $('#first_hidden_layer').slideDown();
                            tableStocks.columns.adjust().draw();
                        });
                    }
                });

                $('#quantityInput').on('change', function() {
                    $(this).val() > 1 ?
                        $('#attachStockButton').html('Atribuir produtos') :
                        $('#attachStockButton').html('Atribuir produto');
                });

                $('#attachStockButton').on('click', function() {

                    const route = "{{ route('sales.assignStocks', $sale->id) }}";
                    $.ajax({
                        url: route,
                        method: 'PUT',
                        dataType: 'json',
                        data: {
                            get_product_id: $("#productSelect").val(),
                            value: $('#valueInput').val(),
                            quantity: $('#quantityInput').val(),
                        },
                        success: function(response) {
                            $("#productSelect").trigger('change');
                            tableStocks.ajax.reload(null, false);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                            alert(xhr.responseJSON.error || xhr.responseJSON.message);
                        }
                    });
                });

                $(document).on('click', '.btn_detach', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('stocks.unassignSale', ':id') }}".replace(':id', id);

                    $.ajax({
                        url: route,
                        method: 'PUT',
                        dataType: 'json',
                        data: {
                            sale_id: "{{ $sale->id }}",
                        },
                        success: function(response) {
                            $("#productSelect").trigger('change');
                            tableStocks.ajax.reload(null, false);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                            alert(xhr.responseJSON.error || xhr.responseJSON.message);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app>
