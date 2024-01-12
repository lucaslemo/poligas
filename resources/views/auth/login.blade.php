<x-layout>
    <x-slot:title>
        {{ config('app.name') }} - Login
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
                                        alt="{{ config('app.name') }}" style="max-height: 80px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="pt-4 pb-2">
                                                <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                                            </div>
                                            <form id="loginForm" action="{{ route('login') }}" class="row g-3" method="POST"
                                                accept-charset="utf-8" enctype="multipart/form-data">
                                                @csrf

                                                <x-alerts.messages />

                                                <div class="col-12">
                                                    <label for="login" class="form-label">
                                                        Usuário ou E-mail
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                        <input type="text" name="login" class="form-control"
                                                            id="login" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="password" class="form-label">Senha</label>
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" required>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="remember"
                                                            value="1" id="rememberMe">
                                                        <label class="form-check-label" for="rememberMe">
                                                            Manter-me conectado
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <x-forms.button-with-spinner id="login" type="submit"
                                                        class="btn btn-primary w-100" formId="loginForm">
                                                        Entrar
                                                    </x-forms.button-with-spinner>
                                                </div>
                                                <div class="col-12">
                                                    <p class="small mb-0">
                                                        Esqueceu sua senha?
                                                        <a href="{{ route('password.request') }}">Redefinir Senha</a>
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
