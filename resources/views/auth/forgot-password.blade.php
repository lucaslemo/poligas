<x-layout>
    <x-slot:title>
        {{ config('app.name') }} - Recuperar Senha
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
                                        alt="{{ config('app.name') }}" style="max-height: 80px;">
                                </a>
                            </div>

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Esqueceu sua senha?</h5>
                                        <p class="text-center small">Você receberá um e-mail para redefini-la</p>
                                    </div>

                                    <form id="forgotPasswordForm" action="{{ route('password.email') }}"
                                        class="row g-3" method="POST" accept-charset="utf-8"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <x-alerts.messages />

                                        <div class="col-12">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                required>
                                        </div>

                                        <div class="col-12">
                                            <x-forms.button-with-spinner id="forgotPassword" type="submit"
                                                class="btn btn-primary w-100" formId="forgotPasswordForm">
                                                Enviar E-mail
                                            </x-forms.button-with-spinner>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">
                                                Já possui uma conta?
                                                <a href="{{ route('login') }}">Entrar</a>
                                            </p>
                                        </div>
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
