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

        <x-nav.aside-item title="Estoque" uniqueId="stock-nav" parentId="sidebar-nav"
        icon="bi bi-layout-text-window-reverse" activeRoutes="{{ '' }}">
            <x-nav.aside-sub-item title="Dados" url="#" class="{{ isActive('#') }}"/>
        </x-nav.aside-item>

        @hasanyrole('Administrador|Gerente')
            <x-nav.aside-item title="Clientes" uniqueId="customers-nav" parentId="sidebar-nav"
            icon="bi bi-person-lines-fill" activeRoutes="{{ '' }}">
                @can('Listar clientes')
                    <x-nav.aside-sub-item title="Listar" url="#" class="{{ isActive('#') }}"/>
                @endcan
            </x-nav.aside-item>

            <x-nav.aside-item title="Entregadores" uniqueId="delivary-men-nav" parentId="sidebar-nav"
            icon="ri ri-bike-line" activeRoutes="{{ 'users.deliveryMen' }}">
                @can('Listar entregadores')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('users.deliveryMen') }}" class="{{ isActive('users.deliveryMen') }}"/>
                @endcan
            </x-nav.aside-item>
        @endhasanyrole

        @hasrole('Administrador')
            <li class="nav-heading">Sistema</li>

            {{-- Nav usuários --}}
            <x-nav.aside-item title="Usuários" uniqueId="users-nav" parentId="sidebar-nav"
            icon="bi bi-people" activeRoutes="{{ 'users.index,users.create,users.edit' }}">
                @can('Listar usuários')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('users.index') }}" class="{{ isActive('users.index') }}"/>
                @endcan
                @can('Cadastrar usuário')
                    <x-nav.aside-sub-item title="Cadastrar" url="{{ route('users.create') }}" class="{{ isActive('users.create') }}"/>
                @endcan
            </x-nav.aside-item>

            {{-- Nav permissões --}}
            <x-nav.aside-item title="Permissões" uniqueId="permissions-nav" parentId="sidebar-nav"
            icon="bi bi-unlock" activeRoutes="{{ 'permissions.*' }}">
                @can('Listar permissões')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('permissions.index') }}" class="{{ isActive('permissions.index') }}"/>
                @endcan
            </x-nav.aside-item>
        @endhasrole

    </ul>
</aside>
