<x-layouts.app-layout title="Actualizar Docente">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Docentes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar docente</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('teachers.update', $teacher->idteacher) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del docente<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" maxlength="8" value="{{ old('dni', $teacher->dni) }}" id="documento" class="form-control" name="dni" required>
                                        <div class="input-group-append">
                                            <button type="button" id="buscar" class="btn btn-dark btn-xs m-0">
                                                <i class="material-icons">search</i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre del docente<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombres" value="{{ old('full_name', $teacher->full_name) }}" name="full_name" required>
                                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Sexo del docente<span class="text-danger">*</span></label>
                                    <select class="form-control" name="gender" required>
                                        <option value="M" {{ $teacher->gender == 'M' ? 'selected' : '' }}>
                                            Masculino
                                        </option>

                                        <option value="F" {{ $teacher->gender == 'F' ? 'selected' : '' }}>
                                            Femenino
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Correo del docente<span class="text-danger">*</span></label>
                                    <input type="email" value="{{ old('email', $teacher->user->email) }}" class="form-control" name="email" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Teléfono del docente<span class="text-danger">*</span></label>
                                    <input type="text" maxlength="9" class="form-control" value="{{ old('phone', $teacher->phone) }}" name="phone" required>
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado del docente<span class="text-danger">*</span></label>
                                    <select class="form-control" required name="status">
                                        <option value="1" {{ $teacher->user->status == '1' ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $teacher->user->status == '0' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Actualizar</button>
                            <a class="btn btn-danger text-white" href="{{ route('teachers.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
        <script src="{{ asset('backend/js/letra.js') }}"></script>
    @endpush

</x-layouts.app-layout>