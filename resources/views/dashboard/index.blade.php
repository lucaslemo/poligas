<x-layout>
    <x-slot:title>
        Poligás - Dashboard
    </x-slot>

    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <div class="d-flex align-items-center w-auto">
                                    <H1>Olá, {{'Usuário'}}. Você está logado!</H1>
                                    <form action="" class="row g-3 needs-validation" method="POST"
                                        accept-charset="utf-8" autocomplete="on" enctype="multipart/form-data">
                                        <button class="btn btn-primary-outiline w-100" type="submit">Sair</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layout>
