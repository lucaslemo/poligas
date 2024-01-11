<x-layout>
    <x-slot:title>
        Poligás - Recuperar Senha
    </x-slot>
    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ Vite::asset('resources/assets/img/logos/MARCA_DAGUA_2.png') }}"
                                        alt="Logo Poligas" style="max-height: 80px;">
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Esqueceu sua senha?</h5>
                                        <p class="text-center small">Você receberá um e-mail para redefini-la</p>
                                    </div>

                                    <form action="{{ route('password.email') }}" class="row g-3 needs-validation"
                                        method="POST" accept-charset="utf-8" autocomplete="on"
                                        enctype="multipart/form-data">
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <div class="alert alert-warning alert-dismissible fade show"
                                                    role="alert">
                                                    {{ $error }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        @csrf

                                        <div class="col-12">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                required>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Enviar E-mail</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Já possui uma conta? <a
                                                    href="{{ route('login') }}">Entrar</a></p>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->
</x-layout>
