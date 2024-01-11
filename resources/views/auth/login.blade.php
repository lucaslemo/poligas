<x-layout>
    <x-slot:title>
        Poligás - Login
    </x-slot>

    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <div class="d-flex align-items-center w-auto">
                                    <img src="{{ Vite::asset('resources/assets/img/logos/MARCA_DAGUA_2.png') }}"
                                        alt="Logo Poligas" style="max-height: 80px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="pt-4 pb-2">
                                                <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                                            </div>
                                            <form action="{{ route('login') }}" class="row g-3 needs-validation"
                                                method="POST" accept-charset="utf-8" autocomplete="on"
                                                enctype="multipart/form-data">
                                                {{-- Mensagem login --}}
                                                @if ($errors->all())
                                                    @foreach ($errors->all() as $error)
                                                        <div class="alert alert-warning alert-dismissible fade show"
                                                            role="alert">
                                                            {{ $error }}
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                @csrf
                                                <div class="col-12">
                                                    <label for="username" class="form-label">Nome de Usuário</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                        <input type="text" name="username" class="form-control"
                                                            id="username" required>
                                                        <div class="invalid-feedback">Digite o seu nome de Usuário!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="password" class="form-label">Senha</label>
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" required>
                                                    <div class="invalid-feedback">Digite sua senha!</div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="remember"
                                                            value="true" id="rememberMe">
                                                        <label class="form-check-label" for="rememberMe">Manter-me
                                                            Conectado</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-primary w-100" type="submit">Entrar</button>
                                                </div>
                                                <div class="col-12">
                                                    <p class="small mb-0">Esqueceu sua senha? <a
                                                            href="{{ route('password.request') }}">Redefinir Senha</a>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layout>
