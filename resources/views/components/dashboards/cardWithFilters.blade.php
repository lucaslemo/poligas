<div class="{{ $class ?? 'card' }}">

    <x-dashboards.filter />

    <div class="card-body pb-0">
      <h5 class="card-title">{{ $title }} <span class="filter_label">| Hoje</span></h5>

        {{ $slot }}
    </div>
</div>
