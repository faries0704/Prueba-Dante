<x-layouts.app-layout title="Completar Perfil Estudiante">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Completar datos de Alumno</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Completar perfil para: <b>{{ $user->username }}</b></h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('students.storeComplete') }}" method="POST" autocomplete="off">
                        @csrf
                        {{-- ID del usuario vinculado --}}
                        <input type="hidden" name="iduser" value="{{ $user->iduser }}">

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del alumno <span class="text-danger">*</span></label>
                                    <input type="text" name="dni" maxlength="8" class="form-control" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" required placeholder="ejm: 76765654" value="{{ old('dni') }}">
                                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-8">
                                <div class="form-group">
                                    <label>Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" required placeholder="ejm: Juan Pérez García" value="{{ old('full_name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Género <span class="text-danger">*</span></label>
                                    <select class="form-control" name="gender" required>
                                        <option value="">Seleccione</option>
                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="birth_date" class="form-control" required value="{{ old('birth_date') }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" name="address" class="form-control" placeholder="ejm: Av. Las Flores 123" value="{{ old('address') }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Guardar Datos Académicos</button>
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