<div class="d-grid gap-2">
    <button class="btn btn-primary btn-sm btn_update" data-product="{{ $product->name }}"
    data-id="{{ $product->id }}" data-value="{{ isset($product->prices[0]) ? $product->prices[0]->value : null }}">
        <small class="fw-normal">Atualizar</small>
    </button>
</div>
