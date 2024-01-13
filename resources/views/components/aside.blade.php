<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['dashboard']) ? '' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#stock-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i>
                <span>Estoque</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="stock-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ '#' }}">
                        <i class="bi bi-circle"></i><span>Dados</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-heading">Sistema</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['users.*']) ? '' : 'collapsed' }}" data-bs-target="#users-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-users"></i>
                <span>Usuários</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="users-nav" class="nav-content collapse {{ request()->routeIs(['users.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('users.index') }}" class="{{ isActive('users.index') }}">
                        <i class="bi bi-circle"></i><span>Listar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.create') }}" class="{{ isActive('users.create') }}">
                        <i class="bi bi-circle"></i><span>Cadastrar</span>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside>
