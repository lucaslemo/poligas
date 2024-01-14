<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Permissões
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Permissões</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Permissões</li>
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
                            <h5 class="card-title">Permissões</h5>
                            <p>Lista das permissões cadastradas no sistema</p>

                            <table id="permissionsDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Permissão</th>
                                        <th scope="col">Grupo</th>
                                        <th scope="col">Registro</th>
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
                const routePermissionsDataTable = "{{ route('permissions.load') }}";
                const tablePermissions = $('#permissionsDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routePermissionsDataTable,
                    },
                    "columns": [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'group',
                            name: 'group',
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, full, meta) {
                                var created_at = new Date(data);
                                var formatted_date = created_at.toLocaleString('pt-BR', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                return `<small class="text-secondary">registrado em: <span class="fw-bold">${formatted_date}</span></small>`;
                            }
                        },
                    ],
                    "language": {
                        "paginate": {
                            "next": "Próxima",
                            "previous": "Anterior"
                        },
                        "search": "Buscar",
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ permissões",
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ permissões)",
                        "lengthMenu": "Mostrar _MENU_ permissões",
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
