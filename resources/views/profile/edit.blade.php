<x-app>
    <x-slot:title>
        {{ config('app.name') }} - Perfil
    </x-slot>
    <main id="main" class="main">

        <div class="row d-flex justify-content-start mb-3">
            <div class="col-md-auto pagetitle align-self-center mb-0 py-3">
                <h1>Meu Perfil</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Meu Perfil</li>
                    </ol>
                </nav>
            </div>
        </div>

        <x-alerts.messages />
        <div id="clientMessages"></div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            @if ($user->image_profile)
                                <img src="{{ Storage::url($user->image_profile) }}" alt="Profile"
                                    class="rounded-circle">
                            @else
                                <img src="{{ Vite::asset('resources/assets/img/perfis/user.png') }}" alt="Profile"
                                    class="rounded-circle">
                            @endif
                            <h2>{{ $user->fullname() }}</h2>
                            <h3>{{ 'Administrador' }}</h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Informações</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">
                                        Editar perfil
                                    </button>
                                </li>


                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">Detalhes do perfil</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Nome completo</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->fullname() }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Tipo</div>
                                        <div class="col-lg-9 col-md-8">{{ 'Administrador' }}</div>
                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                    <!-- Profile Edit Form -->
                                    <form id="loginForm" action="{{ route('profile.update') }}" class="row g-3"
                                        method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-3">
                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">
                                                Imagem de perfil
                                            </label>
                                            <input id="shouldDelete" type="hidden" value="0" name="should_delete">
                                            <input id="imageProfile" type="file" name="image" placeholder="Photo"
                                                accept="image/png, image/gif, image/jpeg, image/bmp, image/gif, image/svg, image/webp"
                                                class="visually-hidden" data-old="{{ $user->image_profile }}">
                                            <div class="col-md-8 col-lg-9">
                                                @if ($user->image_profile)
                                                    <img id="imageProfilePreview"
                                                        src="{{ Storage::url($user->image_profile) }}" alt="Profile">
                                                @else
                                                    <img id="imageProfilePreview"
                                                        src="{{ Vite::asset('resources/assets/img/perfis/user.png') }}"
                                                        alt="Profile">
                                                @endif
                                                <div class="pt-2">
                                                    <a id="uploadImageProfile" class="btn btn-primary btn-sm"
                                                        title="Insirir nova imagem de perfil">
                                                        <i class="bi bi-upload"></i>
                                                    </a>
                                                    <a id="deleteImageProfile"
                                                        class="btn btn-danger btn-sm {{ $user->image_profile ? '' : 'visually-hidden' }}"
                                                        title="Remover imagem de perfil">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="firstName" class="col-md-4 col-lg-3 col-form-label">
                                                Nome
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="first_name" type="text" class="form-control"
                                                    id="firstName" value="{{ $user->first_name }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="lastname" class="col-md-4 col-lg-3 col-form-label">
                                                Sobrenome
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="last_name" type="text" class="form-control"
                                                    id="lastname" value="{{ $user->last_name }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Email"
                                                class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="email" class="form-control"
                                                    id="Email" value="{{ $user->email }}">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                                        </div>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
    @push('scripts')
        <script type="application/javascript">
            $(document).ready(function() {
                $("#uploadImageProfile").click(function() {
                    $("#imageProfile").click();
                });

                $('#deleteImageProfile').click(function() {
                    $('#imageProfilePreview').attr('src', "{{ Vite::asset('resources/assets/img/perfis/user.png') }}");
                    $('#deleteImageProfile').addClass('visually-hidden');
                    $('#imageProfile').val('');
                    $('#shouldDelete').val(1);
                });

                $("#imageProfile").on('change', async function() {
                    try {
                        if ($(this).val() != '') {
                            const image = $(this)[0].files[0];

                            if(! await checkLimitHightAndWidthFromInputImages(image, 640, 640)) {
                                throw new Error(`File video dimensions (width/height) too large. (${640}x${640})`);
                            };

                            $('#imageProfilePreview').attr('src', window.URL.createObjectURL(image));
                            $('#deleteImageProfile').removeClass('visually-hidden');
                        }
                    } catch (error) {
                        $('#imageProfile').val('');
                        $('#clientMessages').empty();
                        $('#clientMessages').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Erro ao carregar imagem.${' '}
                            <small class="fw-bold">(${error.message})</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`);
                    }
                });
            });
        </script>
    @endpush
</x-app>
