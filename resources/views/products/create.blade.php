<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Cadastrar produto
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Produtos</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Produtos</li>
                        <li class="breadcrumb-item active">Cadastrar</li>
                    </ol>
                </nav>
            </div>
        </div>
        <x-alerts.messages />
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cadastrar novo produto</h5>
                            <form id="createProductForm" action="{{ route('products.store') }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf

                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createProduct" type="submit"
                                        class="btn btn-primary w-20" formId="createProductForm">
                                        Cadastrar
                                    </x-forms.button-with-spinner>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @push('scripts')
    <script type="text/javascript">

    </script>
    @endpush
</x-app>
