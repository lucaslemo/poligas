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
                                        <h5 class="card-title text-center pb-0 fs-4">Redefina sua senha</h5>
                                        <p class="text-center small">Sua nova senha deve possuir no mínimo 8 caracteres.</p>
                                    </div>

                                    <form id="resetPasswordForm" action="{{ route('password.store') }}" class="row g-3"
                                        method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                        @csrf

                                        <x-alerts.messages />

                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                        <input type="hidden" name="email"
                                            value="{{ old('email') ?? $request->email }}">
                                        <div class="col-12">
                                            <label for="password" class="form-label">Digite sua senha</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                        </div>
                                        <div class="col-12">
                                            <label for="password_confirmation" class="form-label">
                                                Confirme sua senha
                                            </label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" required>
                                        </div>

                                        <div class="col-12">
                                            <x-forms.button-with-spinner id="resetPassword" type="submit"
                                                class="btn btn-primary w-100" formId="resetPasswordForm">
                                                Redefinir
                                            </x-forms.button-with-spinner>
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
