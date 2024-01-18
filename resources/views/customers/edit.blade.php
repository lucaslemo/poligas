<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Editar cliente
    </x-slot>
    @push('styles')
        <style type="text/css">
            .customFocusedInput {
                border-color: rgba(82,168,236,.8);
                outline: 0;
                outline: thin dotted \9;
                -moz-box-shadow: 0 0 8px rgba(82,168,236,.6);
                box-shadow: 0 0 8px rgba(82,168,236,.6) !important;
            }
        </style>
    @endpush
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Clientes</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Clientes</li>
                        <li class="breadcrumb-item active">Editar</li>
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
                            <h5 class="card-title">Editando cliente: {{ $customer->name }}</h5>
                            <h6 class="card-title fs-6">Dados pessoais</h6>
                            <form id="editCustomerForm" action="{{ route('customers.update', $customer->id) }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="nameInput" class="col-sm-2 col-form-label">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nameInput" name="name" value="{{ $customer->name }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label id="codeLabel" for="codeInput" class="col-sm-2 col-form-label">
                                        {{ $customer->type != 'Pessoa Jurídica' ? 'CPF' : 'CNPJ' }}
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control {{ $customer->type != 'Pessoa Jurídica' ? 'cpf_mask' : 'cnpj_mask' }}"
                                        id="codeInput" value="{{ $customer->code }}" name="code">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="phoneNumerInput" class="col-sm-2 col-form-label">Telefone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control cell_phone_mask" id="phoneNumerInput" value="{{ $customer->phone_number }}"
                                        name="phone_number">
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Tipo</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput1" value="Pessoa Física" {{ $customer->type == 'Pessoa Física' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="typeInput1">
                                                Pessoa Física
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput2" value="Pessoa Jurídica" {{ $customer->type == 'Pessoa Jurídica' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="typeInput2">
                                                Pessoa Jurídica
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="text-center">
                                    <x-forms.button-with-spinner id="editCustomer" type="submit"
                                        class="btn btn-primary w-20" formId="editCustomerForm">
                                        Atualizar
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
        $(document).on('change', '.form-check-input' ,function() {
            $('#codeLabel').removeClass('cpf_mask');
            if( $('#typeInput1').is(':checked') ) {
                $('#codeLabel').html('CPF');
                $('#codeInput').mask('000.000.000-00', {reverse: true});
            } else if ( $('#typeInput2').is(':checked') ) {
                $('#codeLabel').html('CNPJ');
                $('#codeInput').mask('00.000.000/0000-00', {reverse: true});
            }
        });
    </script>
    @endpush
</x-app>
