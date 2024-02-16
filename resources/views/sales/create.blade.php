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
                                    <label for="productSelect" class="col-sm-2 col-form-label">
                                        Cliente
                                    </label>
                                    <div class="col-sm-10">
                                        <select id="customerSelect" data-old-value="{{ old('get_customer_id') }}"
                                            class="form-select select2" name="get_customer_id"></select>
                                    </div>
                                </div>
                                @hasrole('Administrador')
                                    <div class="row mb-3">
                                        <label for="productSelect" class="col-sm-2 col-form-label">
                                            Vendedor
                                        </label>
                                        <div class="col-sm-10">
                                            <select id="userSelect" data-old-value="{{ old('get_user_id') ?? auth()->user()->id }}"
                                                class="form-select select2" name="get_user_id"></select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" value="{{ auth()->user()->id }}" name="get_user_id">
                                @endhasrole
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createSale" type="submit"
                                        class="btn btn-primary w-20" formId="createSaleForm">
                                        Iniciar venda
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

                const routeUsers = "{{ route('users.getUsers') }}";
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
                                roles: 'Administrador|Gerente'
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
            });
        </script>
    @endpush
</x-app>
