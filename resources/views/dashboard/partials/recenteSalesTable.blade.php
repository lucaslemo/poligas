<div class="card recent-sales overflow-auto">

    <x-dashboards.filter id="tableFilter" />
    <div class="card-body">
        <h5 class="card-title">Vendas recentes <span id="labelFor-tableFilter-table" data-label="today">| Hoje</span></h5>
        <table id="salesDataTable" class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Vendedor</th>
                    <th scope="col">Valor total</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.filterFor_tableFilter', function() {
            const filter = $(this).data('filter');
            const html =$(this).html();
            $('#labelFor-tableFilter-table').data('label', filter);
            $('#labelFor-tableFilter-table').html(`| ${html}`);
            $('#labelFor-tableFilter-table').trigger('change');
            $('#salesDataTable').DataTable().ajax.reload(null, false);
        });

        const routeSalesDataTable = "{{ route('sales.load') }}";
        const tableSales = $('#salesDataTable').DataTable({
            searching: true,
            responsive: true,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: routeSalesDataTable,
                data: function(d) {
                    d.filter = $('#labelFor-tableFilter-table').data('label');
                }
            },
            "columns": [
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'customer.name',
                    name: 'customer.name',
                },
                {
                    data: 'user.first_name',
                    name: 'user.first_name',
                    render: function(data, type, full, meta) {
                        return `${data} ${full.user.last_name}`;
                    }
                },
                {
                    data: 'total_value',
                    name: 'total_value',
                    render: function(data, type, full, meta) {
                        return formatMoney(data);
                    }
                },
            ],
            "language": {
                "paginate": {
                    "next": "Próxima",
                    "previous": "Anterior"
                },
                "search": "Buscar",
                "info": "Mostrando de _START_ a _END_ de _TOTAL_ vendas",
                "infoEmpty": "Não há registros disponíveis",
                "infoFiltered": "(Filtrados de _MAX_ vendas)",
                "lengthMenu": "Mostrar _MENU_ vendas",
                "infoThousands": ".",
                "emptyTable": "Nenhum registro encontrado",
                "zeroRecords": "Nenhum registro correspondente encontrado",
                "loadingRecords": "Carregando...",
            },
        });
    });
</script>
@endpush
