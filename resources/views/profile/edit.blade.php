<x-layouts.app-layout title="Mi Cuenta">

<div class="main-content">
    <div class="row">
        
        <div class="col-lg-4 col-md-5">
            <div class="card card-profile text-center py-4 px-3" style="min-height: 485px;">
                <div class="card-content">
                    <div class="mb-3">
                        <img src="{{ asset('backend/img/subidas/' . ($user->photo ?? 'default.png')) }}" 
                             alt="Foto Perfil" 
                             class="rounded-circle shadow-sm border-light" 
                             style="width: 130px; height: 130px; object-fit: cover;">
                    </div>
                    <h4 class="fw-bold mb-1">{{ $profile->full_name ?? $user->username }}</h4>
                    
                    <span class="badge rounded-pill bg-primary px-3 py-2 mb-3">
                        @if($user->idrole == 1) ADMINISTRADOR 
                        @elseif($user->idrole == 2) DOCENTE 
                        @elseif($user->idrole == 3) ALUMNO 
                        @elseif($user->idrole == 4) APODERADO @endif
                    </span>

                    <hr class="my-4 text-muted">

                    <div class="text-start px-2">
                        <p class="mb-2"><i class="material-icons align-middle text-secondary me-2">badge</i> 
                            <strong>DNI:</strong> {{ $profile->dni ?? 'N/A' }}
                        </p>
                        <p class="mb-2"><i class="material-icons align-middle text-secondary me-2">account_circle</i> 
                            <strong>Usuario:</strong> {{ $user->username }}
                        </p>
                        <p class="mb-0"><i class="material-icons align-middle text-secondary me-2">alternate_email</i> 
                            <strong>Correo:</strong> {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            <div class="card" style="min-height: 485px;">
                <div class="card-header card-header-text" style="background-color: #005187; border-radius: 4px 4px 0 0;">
                    <h4 class="card-title text-white p-2 m-0 d-flex align-items-center gap-2">
                        <i class="material-icons">manage_accounts</i> DATOS DE LA CUENTA
                    </h4>
                </div>
                
                <div class="card-content p-4">
                    
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mb-5">
                        @csrf
                        @method('PUT')
                        
                        <h5 class="fw-bold text-primary mb-3 pb-2 border-bottom">Información Personal</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre Completo</label>
                                <input type="text" name="full_name" class="form-control" value="{{ $profile->full_name ?? '' }}" {{ $user->idrole == 1 ? 'disabled' : 'required' }}>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            @if($user->idrole == 2 || $user->idrole == 4)
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Teléfono / Celular</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $profile->phone ?? '' }}" maxlength="9">
                                </div>
                            @endif

                            @if($user->idrole == 4)
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Profesión / Ocupación</label>
                                    <input type="text" name="profession" class="form-control" value="{{ $profile->profession ?? '' }}">
                                </div>
                            @endif

                            @if($user->idrole == 3 || $user->idrole == 4)
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Dirección de Domicilio</label>
                                    <input type="text" name="address" class="form-control" value="{{ $profile->address ?? '' }}">
                                </div>
                            @endif

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Cambiar Foto de Perfil</label>
                                <input type="file" name="photo" class="form-control">
                                <div class="form-text text-muted">Formatos permitidos: JPG, PNG. Máximo 2MB.</div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn text-white px-4" style="background-color: #005187;">
                                <i class="material-icons align-middle me-1">save</i> Guardar Cambios
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <h5 id="password-section" class="fw-bold text-danger mb-3 pb-2 border-bottom">Seguridad y Contraseña</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Contraseña Actual</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="material-icons align-middle me-1">lock_reset</i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

</x-layouts.app-layout>