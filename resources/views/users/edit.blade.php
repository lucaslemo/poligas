<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Editar usuário
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Usuários</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Usuários</li>
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
                            <h5 class="card-title">Editando Usuário: {{ $user->fullname() }}</h5>
                            <form id="createUserForm" action="{{ route('users.update', $user->id) }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                <div class="row mb-3">
                                    <label for="firstNameInput" class="col-sm-2 col-form-label">Nome completo</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="firstNameInput" value="{{ $user->fullname() }}" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="usernameInput" class="col-sm-2 col-form-label">Usuário</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="usernameInput" name="username" value="{{ $user->username }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                        <small>
                                            <i class="bi bi-info-circle text-secondary"></i>
                                            As mudanças de usuário e email devem ser informadas ao usuário, visto que são suas credenciais para se autenticar no sistema.
                                        </small>
                                    </div>
                                </div>
                                <fieldset class="row mb-3" {{ $user->id == 1 ? 'disabled' : '' }}>
                                    <legend class="col-form-label col-sm-2 pt-0">Tipo</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput1" value="Administrador" {{ $user->type == 'Administrador' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="typeInput1">
                                                Administrador
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput2" value="Gerente" {{ $user->type == 'Gerente' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="typeInput2">
                                                Gerente
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput3" value="Entregador" {{ $user->type == 'Entregador' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="typeInput3">
                                                Entregador
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createUser" type="submit"
                                        class="btn btn-primary w-20" formId="createUserForm">
                                        Atualizar
                                    </x-forms.button-with-spinner>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if($user->hasRole('Gerente'))

            <div id="clientMessages"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Atribuir Entregadores</h5>
                            <div class="row mb-3">
                                <label for="usersSelect" class="col-sm-2 col-form-label">Entregador</label>
                                <div class="col-sm-4">
                                    <select id="usersSelect" class="form-select select2"></select>
                                </div>
                                <div class="col-sm-1">
                                    <button id="insertNewDelivery" type="button" class="btn btn-outline-primary w-100">Atribuir</button>
                                </div>
                            </div>

                            <h5 class="card-title fs-6">Entregadores</h5>
                            <p>Lista dos entregadores atribuídos a {{ $user->fullname() }}</p>

                            <table id="usersDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome completo</th>
                                        <th scope="col">Email</th>
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
            @endif
        </section>
    </main>

    @if($user->hasAnyRole(['Gerente', 'Entregador']))
    @if($user->hasRole('Gerente'))
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                const routeUsersDataTable = "{{ route('users.load', ['role' => 'Entregador']) }}";
                const tableUsers = $('#usersDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routeUsersDataTable,
                        data: {
                            delivery_men_from_manger_id: '{{ $user->id }}',
                        }
                    },
                    "columns": [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'full_name',
                            name: 'full_name',
                        },
                        {
                            data: 'email',
                            name: 'email',
                        },
                        {
                            data: 'detachButton',
                            name: 'detachButton',
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
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ entregadores",
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ entregadores)",
                        "lengthMenu": "Mostrar _MENU_ entregadores",
                        "infoThousands": ".",
                        "emptyTable": "Nenhum registro encontrado",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "loadingRecords": "Carregando...",
                    },
                });

                const routeUsers = "{{route('users.getUsers', ['role' => 'Entregador'])}}";
                const selectUsers = $("#usersSelect").select2({
                    placeholder: 'Selecione...',
                    theme: "bootstrap-5",
                    language: {
                        noResults: function(){
                            return 'Nenhum registro encontrado';
                        }
                    },
                    allowClear: true,
                    multiple: false,
                    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
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

                $(document).on('click', '.btn_detach', function() {
                    const id = $(this).data('id');
                    const routeUnassignDeliveryman = "{{ route('users.unassignDeliveryman', $user->id) }}";
                    $.ajax({
                        url: routeUnassignDeliveryman,
                        method: 'PUT',
                        dataType: 'json',
                        data: {
                            deliveryman_id: id,
                        },
                        success: function(response) {
                            tableUsers.ajax.reload(null, false);
                        },
                        error: function(xhr, status, error) {
                            alert(xhr.responseJSON.error);
                        }
                    });
                });

                $('#insertNewDelivery').on('click', function() {
                    const routeAssignDeliveryman = "{{ route('users.assignDeliveryman', $user->id) }}";
                    $.ajax({
                        url: routeAssignDeliveryman,
                        method: 'PUT',
                        dataType: 'json',
                        data: {
                            deliveryman_id: $("#usersSelect").val(),
                        },
                        success: function(response) {
                            $("#usersSelect").val(null).trigger('change');
                            tableUsers.ajax.reload(null, false);
                        },
                        error: function(xhr, status, error) {
                            $('#clientMessages').empty();
                            $('#clientMessages').html(`
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                Erro ao inserir o entregador a esse usuário.${' '}
                                <small class="fw-bold">(${xhr.responseJSON.error})</small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`);
                        }
                    });
                });
            });
        </script>
    @endpush
    @endif
    @endif
</x-app>
