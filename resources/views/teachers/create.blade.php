<x-layouts.app-layout title="Registrar Nuevo Docente">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Docentes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registrar Nuevo Docente</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Registrar Docente (Usuario + Perfil)</h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    {{-- Añadido enctype="multipart/form-data" para permitir subida de archivos --}}
                    <form action="{{ route('teachers.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        
                        <h5 class="mt-2 mb-3" style="color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            <strong>Datos de Acceso</strong>
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre de usuario <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" required placeholder="ejm: docente123" value="{{ old('username') }}">
                                    @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Correo electrónico <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required placeholder="ejm: docente@escuela.com" value="{{ old('email') }}">
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
                        </div>

                        <h5 class="mt-4 mb-3" style="color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            <strong>Información del Docente</strong>
                        </h5>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>DNI del Docente <span class="text-danger">*</span></label>
                                    <input type="text" name="dni" maxlength="8" class="form-control" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" required placeholder="ejm: 45678912" value="{{ old('dni') }}">
                                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" required placeholder="ejm: Prof. Ricardo Palma" value="{{ old('full_name') }}">
                                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Género <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Teléfono de contacto</label>
                                    <input type="text" name="phone" class="form-control" placeholder="ejm: 987654321" value="{{ old('phone') }}">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Seleccionar imagen (Opcional)</label>
                                    <input type="file" name="photo" class="form-control-file" onchange="readURL(this);" accept="image/*">
                                    @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="mt-2">
                                        <img id="preview" src="{{ asset('backend/img/noimage.png') }}" width="100" style="border: 1px solid #ddd; padding: 5px; border-radius: 5px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Mostrar error de transacción si falla la BD --}}
                        @if($errors->has('error'))
                            <div class="alert alert-danger mt-3">
                                {{ $errors->first('error') }}
                            </div>
                        @endif

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Crear Perfil Completo</button>
                                <a class="btn btn-danger text-white" href="{{ route('teachers.index') }}">Cancelar</a>
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