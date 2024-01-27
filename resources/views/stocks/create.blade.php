<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Cadastrar estoque
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Estoque</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Estoque</li>
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
                            <h5 class="card-title mb-5">Cadastrar novos produtos no estoque</h5>
                            <form id="createStockForm" action="{{ route('stocks.store') }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="productSelect" class="col-sm-2 col-form-label">Produto</label>
                                    <div class="col-sm-10">
                                        <select id="productSelect" data-old-value="{{ old('get_product_id') }}"
                                        class="form-select select2" name="get_product_id"></select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="brandSelect" class="col-sm-2 col-form-label">Marca</label>
                                    <div class="col-sm-10">
                                        <select id="brandSelect" data-old-value="{{ old('get_brand_id') }}"
                                        class="form-select select2" name="get_brand_id"></select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="vendorSelect" class="col-sm-2 col-form-label">Fornecedor</label>
                                    <div class="col-sm-10">
                                        <select id="vendorSelect" data-old-value="{{ old('get_vendor_id') }}"
                                        class="form-select select2" name="get_vendor_id"></select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="vendorValueInput" class="col-sm-2 col-form-label">Valor unitário</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text" id="money-addon">R$</span>
                                            <input type="text" class="form-control money" id="vendorValueInput"
                                                value="{{ old('vendor_value') }}" name="vendor_value" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="quantityInput" class="col-sm-2 col-form-label">Quantidade</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="quantityInput"
                                        value="{{ old('quantity') }}" name="quantity" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createStock" type="submit"
                                        class="btn btn-primary w-20" formId="createStockForm">
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

            const routeProducts = "{{route('products.getProducts')}}";
            const selectProducts = $("#productSelect").select2({
                placeholder: 'Selecione...',
                theme: "bootstrap-5",
                escapeMarkup: function (markup) { return markup; },
                language: {
                    noResults: function(){
                        return `Nenhum registro encontrado. <a href="{{ route('products.create') }}">Cadastrar</a>`;
                    }
                },
                allowClear: true,
                multiple: false,
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                ajax: {
                    url: routeProducts,
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

            const routeBrands = "{{route('brands.getBrands')}}";
            const selectBrands = $("#brandSelect").select2({
                placeholder: 'Selecione...',
                theme: "bootstrap-5",
                escapeMarkup: function (markup) { return markup; },
                language: {
                    noResults: function(){
                        return `Nenhum registro encontrado. <a href="{{ route('brands.create') }}">Cadastrar</a>`;
                    }
                },
                allowClear: true,
                multiple: false,
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                ajax: {
                    url: routeBrands,
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

            const routeVendors = "{{route('vendors.getVendors')}}";
            const selectVendors = $("#vendorSelect").select2({
                placeholder: 'Selecione...',
                theme: "bootstrap-5",
                escapeMarkup: function (markup) { return markup; },
                language: {
                    noResults: function(){
                        return `Nenhum registro encontrado. <a href="{{ route('vendors.create') }}">Cadastrar</a>`;
                    }
                },
                allowClear: true,
                multiple: false,
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                ajax: {
                    url: routeVendors,
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

            const oldProduct = $("#productSelect").data('old-value');
            if (oldProduct != '' && oldProduct != null) {
                const routeProduct = "{{ route('products.getProduct', ':id') }}".replace(':id', oldProduct);
                $.get(routeProduct, function(response) {
                    const option = new Option(response.name, response.id, true, true);
                    $("#productSelect").append(option).trigger('change');
                });
            }

            const oldBrand = $("#brandSelect").data('old-value');
            if (oldBrand != '' && oldBrand != null) {
                const routeBrand = "{{ route('brands.getBrand', ':id') }}".replace(':id', oldBrand);
                $.get(routeBrand, function(response) {
                    const option = new Option(response.name, response.id, true, true);
                    $("#brandSelect").append(option).trigger('change');
                });
            }

            const oldVendor = $("#vendorSelect").data('old-value');
            if (oldVendor != '' && oldVendor != null) {
                const routeVendor = "{{ route('vendors.getVendor', ':id') }}".replace(':id', oldVendor);
                $.get(routeVendor, function(response) {
                    const option = new Option(response.name, response.id, true, true);
                    $("#vendorSelect").append(option).trigger('change');
                });
            }

        });
    </script>
    @endpush
</x-app>
