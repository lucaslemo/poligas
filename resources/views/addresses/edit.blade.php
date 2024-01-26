<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Editar endereço
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Endereços</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">{{ $address->customer ? 'Clientes' : 'Fornecedores' }}</li>
                        <li class="breadcrumb-item">Endereços</li>
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
                            <h5 class="card-title">Editando endereço: {{ $address->street }}</h5>
                            <p  class="mb-5">{{ $address->customer ? 'Cliente: '.$address->customer->name : 'Fornecedor: '.$address->vendor->name }}</p>
                            <form id="createCustomerForm" action="{{ route('addresses.update', $address->id ) }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="zipCodeInput" class="col-sm-2 col-form-label">CEP</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control zip_code_mask" id="zipCodeInput" value="{{ $address->zip_code }}" name="zip_code" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="streetInput" class="col-sm-2 col-form-label">Rua</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control toggle-focus" id="streetInput" value="{{ $address->street }}" name="street" required>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control toggle-focus" id="numberInput" placeholder="Número" value="{{ $address->number }}" name="number" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="complementInput" class="col-sm-2 col-form-label">Complemento</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="complementInput" value="{{ $address->complement }}" name="complement">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="neighborhoodInput" class="col-sm-2 col-form-label">Bairro</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="neighborhoodInput" value="{{ $address->neighborhood }}" name="neighborhood" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="cityInput" class="col-sm-2 col-form-label">Cidade</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="cityInput" value="{{ $address->city }}" name="city" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="stateInput" class="col-sm-2 col-form-label">Estado</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="stateInput" value="{{ $address->state }}" name="state" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    @if(!$address->primary)
                                    <button id="deleteAddressButton" class="btn btn-danger" type="button">
                                        Excluir
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Um endereço principal não pode ser excluido">
                                        Excluir
                                    </button>
                                    @endif
                                    <x-forms.button-with-spinner id="createCustomer" type="submit"
                                        class="btn btn-primary w-20" formId="createCustomerForm">
                                        Atualizar
                                    </x-forms.button-with-spinner>
                                </div>
                            </form>
                            <form id="deleteAddressForm" class="visually-hidden" action="{{ route('addresses.destroy', $address->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @if(!$address->primary)
    @push('scripts')

        <script type="text/javascript">

            $(document).ready(function() {
                $('#deleteAddressButton').on('click', function() {
                    $('#deleteAddressForm').submit();
                });
            });
            
        </script>

    @endpush
    @endif
</x-app>
