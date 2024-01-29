<div class="col-xxl-4 col-md-6">
    <div class="{{ $customClass }}">

        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filtro</h6>
                </li>
                <li><button class="dropdown-item labelFor_{{ $id }}" data-label="today">Hoje</button></li>
                <li><button class="dropdown-item labelFor_{{ $id }}" data-label="month">Esse mês</button></li>
                <li><button class="dropdown-item labelFor_{{ $id }}" data-label="year">Esse ano</button></li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $title }} <span id="labelFor-{{ $id }}-card" data-label="today">| Hoje</span></h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="{{ $icon }}"></i>
                </div>
                <div class="ps-3">
                    <h6 id="totalFor-{{ $id }}-card"></h6>
                    <span id="diferenceLabelFor-{{ $id }}-card" class="text-muted small pt-2 ps-1"></span>
                    <span id="diferenceFor-{{ $id }}-card"></span>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script type="text/javascript">

    $(document).ready(function() {
        $(document).on('click', '.labelFor_{{ $id }}', function() {
            const label = $(this).data('label');
            const html =$(this).html();
            $('#labelFor-{{ $id }}-card').data('label', label);
            $('#labelFor-{{ $id }}-card').html(`| ${html}`);
            $('#labelFor-{{ $id }}-card').trigger('change');
        });

        $('#labelFor-{{ $id }}-card').on('change', function() {
            const filter = $(this).data('label');
            const route = "{{ route($dataUrl, ':filter') }}".replace(':filter', filter);
            $.get(route, function(response) {
                $('#totalFor-{{ $id }}-card').html(response.total);
                $('#diferenceFor-{{ $id }}-card').attr('class', '');
                if (response.diference >= 0) {
                    $('#diferenceFor-{{ $id }}-card').addClass('text-success small pt-1 fw-bold');
                    $('#diferenceLabelFor-{{ $id }}-card').html('Aumento de');
                } else {
                    $('#diferenceFor-{{ $id }}-card').addClass('text-danger small pt-1 fw-bold');
                    $('#diferenceLabelFor-{{ $id }}-card').html('Redução de');
                }
                $('#diferenceFor-{{ $id }}-card').html(`${response.diferencePercentage}%`);
            });
        }).trigger('change');
    });

</script>
@endpush
