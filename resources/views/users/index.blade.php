<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Usuários
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Usuários</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Usuários</li>
                        <li class="breadcrumb-item active">Listar</li>
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
                            <h5 class="card-title">Usuários</h5>
                            <p>Lista dos usuários cadastrados no sistema</p>

                            <table id="usersDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome completo</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Tipo</th>
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
                const routeUsersDataTable = "{{ route('users.load') }}";
                const tableUsers = $('#usersDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routeUsersDataTable,
                    },
                    "columns": [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'full_name',
                            name: 'full_name',
                            render: function(data, type, full, meta) {
                                return `<a href="${full.routeEdit}">${data}</a>`;
                            }
                        },
                        {
                            data: 'email',
                            name: 'email',
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                const created_at = new Date(data);
                                const formatted_date = formatDateForDatatable(created_at);
                                return `<small class="text-secondary">registrado em: <span class="fw-bold">${formatted_date}</span></small>`;
                            }
                        },
                        {
                            data: 'statusButton',
                            name: 'statusButton',
                            orderable: false,
                            width: '1%'
                        },
                    ],
                    "language": {
                        "paginate": {
                            "next": "Próxima",
                            "previous": "Anterior"
                        },
                        "search": "Buscar",
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ usuários",
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ usuários)",
                        "lengthMenu": "Mostrar _MENU_ usuários",
                        "infoThousands": ".",
                        "emptyTable": "Nenhum registro encontrado",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "loadingRecords": "Carregando...",
                    },
                });

                $(document).on('click', '.btn_activate', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('users.activate', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: route,
                        method: 'PUT',
                        dataType: 'json',
                        success: function(response) {
                            tableUsers.ajax.reload(null, false);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                            alert(xhr.responseJSON.error || xhr.responseJSON.message);
                        }
                    });
                });

                $(document).on('click', '.btn_deactivate', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('users.deactivate', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: route,
                        method: 'PUT',
                        dataType: 'json',
                        success: function(response) {
                            tableUsers.ajax.reload(null, false);
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
