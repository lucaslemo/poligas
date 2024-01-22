<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Clientes
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Clientes</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Clientes</li>
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
                            <h5 class="card-title">Clientes</h5>
                            <p>Lista dos clientes cadastrados no sistema</p>

                            <table id="customersDataTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">CPF/CNPJ</th>
                                        <th scope="col">Telefone</th>
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
                const routeCustomersDataTable = "{{ route('customers.load') }}";
                const tableCustomers = $('#customersDataTable').DataTable({
                    searching: true,
                    responsive: true,
                    "pageLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: routeCustomersDataTable,
                    },
                    "columns": [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function(data, type, full, meta) {
                                return `<a href="${full.routeEdit}">${data}</a>`;
                            }
                        },
                        {
                            data: 'type',
                            name: 'type',
                        },
                        {
                            data: 'code',
                            name: 'code',
                        },
                        {
                            data: 'phone_number',
                            name: 'phone_number',
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: false,
                            searchable: false,
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
                        "info": "Mostrando de _START_ a _END_ de _TOTAL_ clientes",
                        "infoEmpty": "Não há registros disponíveis",
                        "infoFiltered": "(Filtrados de _MAX_ clientes)",
                        "lengthMenu": "Mostrar _MENU_ clientes",
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
