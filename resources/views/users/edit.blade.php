<x-layouts.app-layout title="Actualizar Usuario">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar usuario: {{ $user->username }}</h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('users.update', $user->iduser) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Nombre de Usuario <span class="text-danger">*</span></label>
                                    <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
                                    @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            {{-- NUEVO CAMPO DE CONTRASEÑA --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Nueva Contraseña</label>
                                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Rol del Usuario <span class="text-danger">*</span></label>
                                    <select class="form-control" name="idrole" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->idrole }}" {{ $user->idrole == $role->idrole ? 'selected' : '' }}>
                                                {{ $role->role_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- EL RESTO DE TU CÓDIGO (Estado de la cuenta / Botones) SE MANTIENE IGUAL --}}
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="mb-3 fw-bold">
                                            <i class="material-icons align-middle">lock</i>
                                            Estado de la Cuenta
                                        </h6>
                                        @if($user->locked_until && \Carbon\Carbon::now()->lt($user->locked_until))
                                            <div class="mb-3">
                                                <span class="badge bg-danger px-3 py-2">Cuenta Bloqueada</span>
                                            </div>
                                            <div class="mb-3 text-muted small">
                                                <div><strong>Bloqueado hasta:</strong></div>
                                                <div>{{ \Carbon\Carbon::parse($user->locked_until)->format('d/m/Y H:i') }}</div>
                                                <div class="mt-1">
                                                    <strong>Tiempo restante:</strong>
                                                    {{ \Carbon\Carbon::now()->diffForHumans($user->locked_until, true) }}
                                                </div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="unlock_user" name="unlock_user" value="1">
                                                <label class="form-check-label fw-semibold" for="unlock_user">Desbloquear usuario</label>
                                            </div>
                                        @else
                                            <div class="text-center py-3">
                                                <span class="badge bg-success px-3 py-2">Cuenta Activa</span>
                                                <p class="text-muted small mt-2 mb-0">El usuario puede iniciar sesión sin restricciones</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar Usuario</button>
                                <a class="btn btn-danger text-white" href="{{ route('users.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>