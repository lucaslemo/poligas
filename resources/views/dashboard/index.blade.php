<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Dashboard
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Dashboard</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <x-dashboards.card id="salesCard" title="Vendas" icon="bi bi-cart"
                            customClass="card info-card sales-card" dataUrl="sales.loadCard" />
                        </div>

                        <div class="col-12">
                            @include('dashboard.partials.reportsChart')
                        </div>

                        <div class="col-12">
                            @include('dashboard.partials.recenteSalesTable')
                        </div>
                    </div>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </section>
    </main>
</x-app>
