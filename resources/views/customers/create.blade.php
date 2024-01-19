<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Cadastrar cliente
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
                            <h5 class="card-title">Cadastrar novo cliente</h5>
                            <form id="createCustomerForm" action="{{ route('customers.store') }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <h6 class="card-title fs-6">Dados pessoais</h6>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="nameInput" class="col-sm-2 col-form-label">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nameInput" name="name" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label id="codeLabel" for="codeInput" class="col-sm-2 col-form-label">
                                        {{ old('type') != 'Pessoa Jurídica' ? 'CPF' : 'CNPJ' }}
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control {{ old('type') != 'Pessoa Jurídica' ? 'cpf_mask' : 'cnpj_mask' }}"
                                        id="codeInput" value="{{ old('code') }}" name="code">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="phoneNumerInput" class="col-sm-2 col-form-label">Telefone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control cell_phone_mask" id="phoneNumerInput" value="{{ old('phone_number') }}"
                                        name="phone_number">
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Tipo</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput1" value="Pessoa Física" {{ old('type') == 'Pessoa Jurídica' ? '' : 'checked' }}>
                                            <label class="form-check-label" for="typeInput1">
                                                Pessoa Física
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput2" value="Pessoa Jurídica" {{ old('type') == 'Pessoa Jurídica' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="typeInput2">
                                                Pessoa Jurídica
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <h6 class="card-title fs-6">Endereço principal</h6>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="zipCodeInput" class="col-sm-2 col-form-label">CEP</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control zip_code_mask" id="zipCodeInput" value="{{ old('zip_code') }}" name="zip_code" required>
                                        <small>
                                            <i class="bi bi-info-circle text-secondary"></i>
                                            Preencha o CEP, tentaremos buscar o resto para você.
                                        </small>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="streetInput" class="col-sm-2 col-form-label">Rua</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control toggle-focus" id="streetInput" name="street" value="{{ old('street') }}" required>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control toggle-focus" id="numberInput" placeholder="Número" value="{{ old('number') }}" name="number" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="complementInput" class="col-sm-2 col-form-label">Complemento</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="complementInput" value="{{ old('complement') }}" name="complement">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="neighborhoodInput" class="col-sm-2 col-form-label">Bairro</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="neighborhoodInput" value="{{ old('neighborhood') }}" name="neighborhood" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="cityInput" class="col-sm-2 col-form-label">Cidade</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="cityInput" value="{{ old('city') }}" name="city" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="stateInput" class="col-sm-2 col-form-label">Estado</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="stateInput" value="{{ old('state') }}" name="state" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createCustomer" type="submit"
                                        class="btn btn-primary w-20" formId="createCustomerForm">
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

        $('#zipCodeInput').blur(function() {
            const zipCode = $(this).val().replace(/[^0-9]/, "");
            if (zipCode.length == 8) {
                const urlSearchZipCode = `https://viacep.com.br/ws/${zipCode}/json/`;
                $.ajax({
                    url: urlSearchZipCode,
                    method: 'GET',
                    dataType: 'json',
                    crossDomain: true,
                    contentType: 'application/json',
                    success: function(response) {
                        try{
                            $('#streetInput').val(response.logradouro);
                            $('#numberInput').val(response.siafi);
                            $('#complementInput').val(response.complemento);
                            $('#neighborhoodInput').val(response.bairro);
                            $('#cityInput').val(response.localidade);
                            $('#stateInput').val(translateStates(response.uf));
                            $('.toggle-focus').addClass("customFocusedInput")
                            setTimeout(function() { $('.toggle-focus').removeClass("customFocusedInput") }, 2000)
                        } catch(error) {
                            console.error(error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    },
                });
            }
        });

    </script>
    @endpush
</x-app>
