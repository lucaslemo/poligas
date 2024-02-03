<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Cadastrar venda
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Vendas</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Vendas</li>
                        <li class="breadcrumb-item active">Cadastrar</li>
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
                            <h5 class="card-title mb-5">Cadastrar nova venda</h5>
                            <form id="createSaleForm" action="{{ route('sales.store') }}" class="row g-3" method="POST"
                                accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
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
                                <div class="row mb-3 " id="first_hidden_layer" style="display: none">
                                    <label for="brandSelect" class="col-sm-2 col-form-label">Marca</label>
                                    <div class="col-sm-10">
                                        <select id="brandSelect" data-old-value="{{ old('get_brand_id') }}"
                                            class="form-select select2" name="get_brand_id"></select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createSale" type="submit"
                                        class="btn btn-primary w-20" formId="createSaleForm">
                                        Cadastrar
                                    </x-forms.button-with-spinner>
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

                const routeBrands = "{{ route('brands.getBrands') }}";
                const selectBrands = $("#brandSelect").select2({
                    placeholder: 'Selecione...',
                    theme: "bootstrap-5",
                    language: {
                        noResults: function() {
                            return `Nenhum registro encontrado.`;
                        }
                    },
                    allowClear: true,
                    multiple: false,
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    ajax: {
                        url: routeBrands,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                                filter: 'stocked',
                                product_id: $("#productSelect").val(),
                            }
                        },
                        cache: true
                    }
                });

                $("#productSelect").on('change', function() {
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
                        console.log(response)
                        $('#infoProductStocks').html(
                            `Existem ${stocks}, de ${brands} e ${vendors} fornecedores diferentes.`
                        );
                        $('#labelProductInStocks').show();
                        $('#first_hidden_layer').slideDown();
                    });
                });

                $("#brandSelect").on('change', function() {
                    
                });
            });
        </script>
    @endpush
</x-app>
