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
                                        <h5 class="card-title text-center pb-0 fs-4">Redefina sua senha</h5>
                                        {{-- <p class="text-center small">Você receberá um e-mail para redefini-la</p> --}}
                                    </div>

                                    <form action="{{ route('password.store') }}" class="row g-3 needs-validation"
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
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                        <input type="hidden" name="email"
                                            value="{{ old('email') ?? $request->email }}">
                                        <div class="col-12">
                                            <label for="password" class="form-label">Digite sua senha!</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                        </div>
                                        <div class="col-12">
                                            <label for="password_confirmation" class="form-label">Confirme sua
                                                senha!</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" required>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Redefinir</button>
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
