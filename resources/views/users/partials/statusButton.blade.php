<div class="d-grid gap-2">
@if($user->hasRole('Administrador'))
    <button class="btn btn-danger btn-sm" disabled>
        <small class="fw-normal">Desativar</small>
    </button>
@else
@if ($user->status)
    <button class="btn btn-danger btn-sm btn_deactivate" data-id="{{ $user->id }}"><small class="fw-normal">Desativar</small></button>
@else
    <button class="btn btn-primary btn-sm btn_activate" data-id="{{ $user->id }}"><small class="fw-normal">Ativar</small></button>
@endif
@endif
</div>
