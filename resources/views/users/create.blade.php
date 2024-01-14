<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Usuários
    </x-slot>
    <main id="main" class="main">
        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Usuários</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Usuários</li>
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
                            <h5 class="card-title">Cadastrar novo usuário</h5>
                            <form id="createUserForm" action="{{ route('users.store') }}" class="row g-3" method="POST"
                            accept-charset="utf-8" enctype="multipart/form-data">
                            @csrf
                                <div class="row mb-3">
                                    <label for="firstNameInput" class="col-sm-2 col-form-label">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="firstNameInput" name="first_name" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="lastNameInput" class="col-sm-2 col-form-label">Sobrenome</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lastNameInput" name="last_name" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="usernameInput" class="col-sm-2 col-form-label">Usuário</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="usernameInput" name="username" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <small>
                                            <i class="bi bi-info-circle text-secondary"></i>
                                            O usuário precisará redefinir, certifique-se que este é um email válido.
                                        </small>

                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Tipo</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput1" value="Administrador" checked>
                                            <label class="form-check-label" for="typeInput1">
                                                Administrador
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput2" value="Gerente">
                                            <label class="form-check-label" for="typeInput2">
                                                Gerente
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="typeInput3" value="Entregador">
                                            <label class="form-check-label" for="typeInput3">
                                                Entregador
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="statusInput1" value="1" checked>
                                            <label class="form-check-label" for="statusInput1">
                                                Ativado
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="statusInput2" value="0">
                                            <label class="form-check-label" for="statusInput2">
                                                Desativado
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-center">
                                    <x-forms.button-with-spinner id="createUser" type="submit"
                                        class="btn btn-primary w-20" formId="createUserForm">
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
</x-app>
