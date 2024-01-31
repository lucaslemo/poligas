<x-dashboards.cardWithFilters class="{{ $class }}" title="{{ $title }}">
    <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
        <i class="{{ $icon }}"></i>
        </div>
        <div class="ps-3">
            <h6 id="{{ $id }}_primary_value"></h6>
            <span id="{{ $id }}_info_text" class="text-muted small pt-2 ps-1"></span>
            <span id="{{ $id }}_info_value"></span>
        </div>
    </div>
</x-dashboards.cardWithFilters>

@push('scripts')
<script type="text/javascript">

    const {{ $id }}UpdateAjax = () => {
        const filter = $('#current_filter').val();
        const route = "{{ route($dataUrl, ':filter') }}".replace(':filter', filter);
        $.get(route, function(response) {
            $('#{{ $id }}_primary_value').html(response.total);

            $('#{{ $id }}_info_value').attr('class', '');
            if (response.diference >= 0) {
                $('#{{ $id }}_info_value').addClass('text-success small pt-1 fw-bold');
                $('#{{ $id }}_info_text').html('Aumento de');
            } else {
                $('#{{ $id }}_info_value').addClass('text-danger small pt-1 fw-bold');
                $('#{{ $id }}_info_text').html('Redução de');
            }
            $('#{{ $id }}_info_value').html(`${response.diferencePercentage.toFixed(2)}%`);
        });
    }
    
</script>
@endpush
