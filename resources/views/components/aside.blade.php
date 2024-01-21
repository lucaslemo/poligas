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
            {{-- Nav clientes --}}
            <x-nav.aside-item title="Clientes" uniqueId="customers-nav" parentId="sidebar-nav"
            icon="bi bi-person-lines-fill" activeRoutes="{{ 'customers.*' }}">
                @can('Listar clientes')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('customers.index') }}" class="{{ isActive('customers.index') }}"/>
                @endcan
                @can('Cadastrar cliente')
                    <x-nav.aside-sub-item title="Cadastrar" url="{{ route('customers.create') }}" class="{{ isActive('customers.create') }}"/>
                @endcan
            </x-nav.aside-item>

            {{-- Nav entregadores --}}
            <x-nav.aside-item title="Entregadores" uniqueId="delivary-men-nav" parentId="sidebar-nav"
            icon="ri ri-bike-line" activeRoutes="{{ 'users.deliveryMen' }}">
                @can('Listar entregadores')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('users.deliveryMen') }}" class="{{ isActive('users.deliveryMen') }}"/>
                @endcan
            </x-nav.aside-item>

            {{-- Nav produtos --}}
            <x-nav.aside-item title="Produtos" uniqueId="products-nav" parentId="sidebar-nav"
            icon="bx bx-barcode" activeRoutes="{{ 'products.*' }}">
                @can('Listar produtos')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('products.index') }}" class="{{ isActive('products.index') }}"/>
                @endcan
                @can('Cadastrar produto')
                    <x-nav.aside-sub-item title="Cadastrar" url="{{ route('products.create') }}" class="{{ isActive('products.create') }}"/>
                @endcan
            </x-nav.aside-item>

            {{-- Nav marcas --}}
            <x-nav.aside-item title="Marcas" uniqueId="brands-nav" parentId="sidebar-nav"
            icon="bx bx-copyright" activeRoutes="{{ 'brands.*' }}">
                @can('Listar marcas')
                    <x-nav.aside-sub-item title="Listar" url="{{ route('brands.index') }}" class="{{ isActive('brands.index') }}"/>
                @endcan
                @can('Cadastrar marca')
                    <x-nav.aside-sub-item title="Cadastrar" url="{{ route('brands.create') }}" class="{{ isActive('brands.create') }}"/>
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
