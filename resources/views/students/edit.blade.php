<x-layouts.app-layout title="Actualizar Alumno">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar datos de: <b>{{ $student->full_name }}</b></h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('students.update', $student->idstudent) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del alumno <span class="text-danger">*</span></label>
                                    <input type="text" maxlength="8" value="{{ old('dni', $student->dni) }}" class="form-control" name="dni" required>
                                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-8">
                                <div class="form-group">
                                    <label>Nombre del alumno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ old('full_name', $student->full_name) }}" name="full_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Dirección <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ old('address', $student->address) }}" name="address" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Correo del alumno (Usuario) <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" value="{{ old('email', $student->user->email) }}" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Sexo <span class="text-danger">*</span></label>
                                    <select class="form-control" name="gender" required>
                                        <option value="M" {{ $student->gender == 'M' ? 'selected' : '' }}>
                                            Masculino
                                        </option>

                                        <option value="F" {{ $student->gender == 'F' ? 'selected' : '' }}>
                                            Femenino
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" value="{{ $student->birth_date }}" class="form-control" name="birth_date" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Estado de Cuenta <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{ $student->user->status == '1' ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $student->user->status == '0' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Actualizar Datos</button>
                            <a class="btn btn-danger text-white" href="{{ route('students.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>