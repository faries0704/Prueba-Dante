<x-layouts.app-layout title="Crear Usuario">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear perfil</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Crear nuevo perfil de acceso</h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    {{-- IMPORTANTE: Añadir enctype="multipart/form-data" para permitir subida de archivos --}}
                    <form action="{{ route('users.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre de usuario <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" required placeholder="ejm: jorge123" value="{{ old('username') }}">
                                    @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Correo electrónico <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required placeholder="ejm: usuario@escuela.com" value="{{ old('email') }}">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required placeholder="*******">
                                    <small class="form-text text-muted">Mínimo 6 caracteres.</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Asignar Rol <span class="text-danger">*</span></label>
                                    <select class="form-control" required name="idrole">
                                        <option value="">Seleccione un rol</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->idrole }}">{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Este rol definirá qué datos podrá llenar después.</small>
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN DE LA FOTO --}}
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Foto de perfil <span class="text-danger">*</span></label>
                                    <input type="file" name="photo" class="form-control-file" onchange="readURL(this);" required accept="image/*">
                                    @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="mt-2">
                                        <img id="preview" src="{{ asset('backend/img/noimage.png') }}" width="100" style="border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Crear perfil</button>
                                <a class="btn btn-danger text-white" href="{{ route('users.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Función para la vista previa de la imagen
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) { 
                    $('#preview').attr('src', e.target.result); 
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush

</x-layouts.app-layout>