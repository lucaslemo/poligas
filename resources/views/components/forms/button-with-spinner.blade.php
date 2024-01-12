<button id="myButton-{{ $id }}" class="{{ $class }}" type="{{ $type }}">
    <span id="spinner-{{ $id }}" class="spinner-border spinner-border-sm visually-hidden" role="status"
        aria-hidden="true"></span>
    <span>{{ $slot }}</span>
</button>

@push('scripts')
    <script type="text/javascript">
        $('#{{ $formId }}').on('submit', function() {
            $('#myButton-{{ $id }}').prop('disabled', true);
            $('#spinner-{{ $id }}').removeClass('visually-hidden');
        });
    </script>
@endpush
