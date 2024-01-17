<li class="nav-item">
    <a class="nav-link {{ request()->routeIs(explode(',', $activeRoutes)) ? '' : 'collapsed' }}" data-bs-target="#{{ $uniqueId }}" data-bs-toggle="collapse" href="#">
        <i class="{{ $icon }}"></i>
        <span>{{ $title }}</span>
        <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="{{ $uniqueId }}" class="nav-content collapse {{ request()->routeIs(explode(',', $activeRoutes)) ? 'show' : '' }}" data-bs-parent="#{{ $parentId }}">
        <li>
            {{ $slot ?? '' }}
        </li>
    </ul>
</li>
