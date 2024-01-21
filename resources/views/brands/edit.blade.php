<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Editar marca
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Marcas</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Marcas</li>
                        <li class="breadcrumb-item active">Editar</li>
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
                            <h5 class="card-title mb-5">Editando marca: {{ $brand->name }}</h5>
                            <form id="editBrandForm" action="{{ route('brands.update', $brand->id) }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="nameInput" class="col-sm-2 col-form-label">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control toggle-focus" id="nameInput" value="{{ $brand->name }}" name="name" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="editBrand" type="submit"
                                        class="btn btn-primary w-20" formId="editBrandForm">
                                        Atualizar
                                    </x-forms.button-with-spinner>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app>
