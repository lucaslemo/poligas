<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Editar fornecedor
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Fornecedores</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Fornecedores</li>
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
                            <h5 class="card-title">Editando fornecedor: {{ $vendor->name }}</h5>
                            <h6 class="card-title fs-6">Dados pessoais</h6>
                            <form id="editVendorsForm" action="{{ route('vendors.update', $vendor->id) }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="nameInput" class="col-sm-2 col-form-label">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nameInput" name="name" value="{{ $vendor->name }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label id="codeLabel" for="codeInput" class="col-sm-2 col-form-label">CNPJ</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control cnpj_mask"
                                        id="codeInput" value="{{ $vendor->cnpj }}" name="cnpj">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="phoneNumerInput" class="col-sm-2 col-form-label">Telefone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control cell_phone_mask" id="phoneNumerInput" value="{{ $vendor->phone_number }}"
                                        name="phone_number">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="editVendor" type="submit"
                                        class="btn btn-primary w-20" formId="editVendorsForm">
                                        Atualizar
                                    </x-forms.button-with-spinner>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="clientMessages"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Atribuir Endereços</h5>
                            <form id="createAddressForm" action="{{ route('addresses.store') }}" method="POST">
                                <div class="row mb-3">
                                    <label for="zipCodeInput" class="col-sm-2 col-form-label">CEP</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control zip_code_mask" id="zipCodeInput" name="zip_code">
                                        <small>
                                            <i class="bi bi-info-circle text-secondary"></i>
                                            Preencha o CEP, tentaremos buscar o resto para você.
                                        </small>
                                    </div>
                                    <div class="col-sm-1">
                                        <button id="searchZipCodeButton" type="button" class="btn btn-outline-primary w-100">Buscar</button>
                                    </div>
                                </div>
                                <div id="hidePartForm" style="display: none">
                                    <div class="row mb-3">
                                        <label for="streetInput" class="col-sm-2 col-form-label">Rua</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="streetInput" name="street">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="numberInput" placeholder="Número" name="number">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="complementInput" class="col-sm-2 col-form-label">Complemento</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="complementInput" name="complement">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="neighborhoodInput" class="col-sm-2 col-form-label">Bairro</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="neighborhoodInput" name="neighborhood">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="cityInput" class="col-sm-2 col-form-label">Cidade</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="cityInput" name="city">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="stateInput" class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="stateInput" name="state">
                                        </div>
                                    </div>
                                    <div class="row mb-3 justify-content-center">
                                        <div class="col-sm-1">
                                            <button id="cancelAddress" type="reset" class="btn btn-outline-danger w-100">Cancelar</button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button id="assingAddress" style="white-space: nowrap;" type="submit" class="btn btn-outline-primary w-100">
                                                <span id="spinnerAssing" class="spinner-border spinner-border-sm visually-hidden" role="status"
                                                aria-hidden="true"></span>
                                                <span>Atribuir</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <h5 class="card-title fs-6">Endereços</h5>
                            <p>Lista dos endereços de {{ $vendor->name }}</p>

                            <table id="addressesDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Rua</th>
                                        <th scope="col">Bairro</th>
                                        <th scope="col">CEP</th>
                                        <th scope="col">Cidade</th>
                                        <th scope="col">Principal</th>
                                        <th scope="col">Registro</th>
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
            const routeAddressesDataTable = "{{ route('addresses.load') }}";
            const tableAddresses = $('#addressesDataTable').DataTable({
                searching: true,
                responsive: true,
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: routeAddressesDataTable,
                    data: {
                        vendor_id: '{{ $vendor->id }}',
                    }
                },
                "columns": [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'street',
                        name: 'street',
                        render: function(data, type, full, meta) {
                            return `<a href="${full.routeEdit}">${data}</a> ${full.hoverComplement}`;
                        }
                    },
                    {
                        data: 'neighborhood',
                        name: 'neighborhood',
                    },
                    {
                        data: 'zip_code',
                        name: 'zip_code',
                    },
                    {
                        data: 'city',
                        name: 'city',
                        render: function(data, type, full, meta) {
                            return `${data}, ${translateUf(full.state)}`;
                        }
                    },
                    {
                        data: 'primary',
                        name: 'primary',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            const created_at = new Date(data);
                            const formatted_date = created_at.toLocaleString('pt-BR', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            return `<small class="text-secondary">registrado em: <span class="fw-bold">${formatted_date}</span></small>`;
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        width: '1%',
                        data: 'primaryButton',
                        name: 'primaryButton'
                    },
                ],
                "language": {
                    "paginate": {
                        "next": "Próxima",
                        "previous": "Anterior"
                    },
                    "search": "Buscar",
                    "info": "Mostrando de _START_ a _END_ de _TOTAL_ endereços",
                    "infoEmpty": "Não há registros disponíveis",
                    "infoFiltered": "(Filtrados de _MAX_ endereços)",
                    "lengthMenu": "Mostrar _MENU_ endereços",
                    "infoThousands": ".",
                    "emptyTable": "Nenhum registro encontrado",
                    "zeroRecords": "Nenhum registro correspondente encontrado",
                    "loadingRecords": "Carregando...",
                },
            });

            $('#searchZipCodeButton').on('click', function() {
                try {
                    const zipCode = $('#zipCodeInput').val().replace(/[^0-9]/, "");
                    if (zipCode.length != 8) {
                        throw new Error('CEP inválido');
                    }
                    const urlSearchZipCode = `https://viacep.com.br/ws/${zipCode}/json/`;
                    $.ajax({
                        url: urlSearchZipCode,
                        method: 'GET',
                        dataType: 'json',
                        crossDomain: true,
                        contentType: 'application/json',
                        success: function(response) {
                            try{
                                $('#clientMessages').empty();
                                $('#streetInput').val(response.logradouro);
                                $('#numberInput').val(response.siafi);
                                $('#complementInput').val(response.complemento);
                                $('#neighborhoodInput').val(response.bairro);
                                $('#cityInput').val(response.localidade);
                                $('#stateInput').val(translateStates(response.uf));
                            } catch(error) {
                                console.error(error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                        },
                    });

                    $('#hidePartForm').slideDown("slow");
                } catch (error) {
                    console.error(error);
                    $('#clientMessages').empty();
                    $('#clientMessages').html(`
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Erro ao buscar o CEP inserido.${' '}
                        <small class="fw-bold">(${error.message})</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`);
                    $('#spinnerAssing').addClass('visually-hidden');
                    $('#assingAddress').prop('disabled', false);
                }
            });

            $('#createAddressForm').submit(function (event) {
                event.preventDefault();
                $('#spinnerAssing').removeClass('visually-hidden');
                $('#assingAddress').prop('disabled', true);

                const data = new FormData($(this)[0]);
                data.append('get_vendor_id', '{{ $vendor->id }}');
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        tableAddresses.ajax.reload(null, false);
                        $('#cancelAddress').click();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                        $('#clientMessages').empty();
                        $('#clientMessages').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Erro ao atribuir novo endereço.${' '}
                            <small class="fw-bold">(${xhr.responseJSON.error || xhr.responseJSON.message})</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`);
                    }
                });
            });


            $('#cancelAddress').on('click', function() {
                $('#clientMessages').empty();
                $('#spinnerAssing').addClass('visually-hidden');
                $('#assingAddress').prop('disabled', false);
                $('#hidePartForm').slideUp("slow");
            });

            $(document).on('click', '.btn_primary', function() {
                const id = $(this).data('id');
                const route = "{{ route('addresses.primary', ':id') }}".replace(':id', id);
                $.ajax({
                    url: route,
                    method: 'PUT',
                    dataType: 'json',
                    success: function(response) {
                        tableAddresses.ajax.reload(null, false);
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
