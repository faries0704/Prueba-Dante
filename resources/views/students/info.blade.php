<x-layouts.app-layout title="Información del Alumno">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Información</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Información Detallada: <b>{{ $student->full_name }}</b></h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>DNI del alumno</label>
                                <input readonly type="text" value="{{ $student->dni }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Nombre del alumno</label>
                                <input readonly type="text" value="{{ $student->full_name }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <input readonly type="text" value="{{ $student->birth_date }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input readonly type="text" value="{{ $student->address }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input readonly type="text" value="{{ $student->user->email ?? 'No asignado' }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Sexo</label><br>
                                <span class="badge badge-success">{{ $student->gender }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Estado de Cuenta</label><br>
                                @if($student->user && $student->user->status == '1')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Nombre de Usuario</label>
                                <input readonly type="text" value="{{ $student->user->username ?? 'N/A' }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Foto de Perfil</label><br>
                                @if($student->user && $student->user->photo)
                                    <img src="{{ asset('backend/img/subidas/' . $student->user->photo) }}" width="100" class="img-thumbnail shadow-sm">
                                @else
                                    <img src="{{ asset('backend/img/user-default.png') }}" width="100" class="img-thumbnail shadow-sm">
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <a class="btn btn-danger text-white" href="{{ route('students.index') }}">Regresar a la lista</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>