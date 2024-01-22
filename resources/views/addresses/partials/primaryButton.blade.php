@if($address->primary)
<div class="d-grid gap-2">
    <button class="btn btn-success btn-sm" disabled><small class="fw-normal">Principal</small></button>
</div>
@else
<div class="d-grid gap-2">
    <button class="btn btn-primary btn-sm btn_primary" data-id="{{ $address->id }}"><small class="fw-normal">Principal</small></button>
</div>
@endif
