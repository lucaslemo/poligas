<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Entregadores
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Entregadores</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Entregadores</li>
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
                            <h5 class="card-title">Entregadores</h5>
                            <p>Lista dos entregadores atribuídos ao seu usuário</p>

                            <table id="usersDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome completo</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Atribuído</th>
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
                            delivery_men_from_manger_id: '{{ Auth::user()->id }}',
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
                            data: 'assign_at',
                            name: 'assign_at',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                const created_at = new Date(data);
                                const formatted_date = formatDateForDatatable(created_at);
                                return data
                                    ? `<small class="text-secondary">atribuído em: <span class="fw-bold">${formatted_date}</span></small>`
                                    : '-';
                            }
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
            });
        </script>
    @endpush
</x-app>
