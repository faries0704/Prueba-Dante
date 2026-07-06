<x-layouts.app-layout title="Información del Docente">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Docentes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Información</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Información detallada del docente</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-info">
                        <strong>Perfil del docente:</strong> Visualización de datos registrados en el sistema.
                    </div>

                    <form>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del docente</label>
                                    <input type="text" value="{{ $teacher->dni }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre del docente</label>
                                    <input type="text" class="form-control" value="{{ $teacher->full_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Sexo del docente</label>
                                    <input type="text" class="form-control" value="{{ $teacher->gender == 'M' ? 'Masculino' : 'Femenino' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Correo del docente</label>
                                    <input type="email" value="{{ $teacher->user->email }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Teléfono del docente</label>
                                    <input type="text" class="form-control" value="{{ $teacher->phone }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Usuario del docente</label>
                                    <input type="text" class="form-control" value="{{ $teacher->user->username }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="d-block">Estado del docente</label>
                                    @if($teacher->user->status == '1')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="d-block">Rol en el sistema</label>
                                    @if($teacher->user->idrole == '2')
                                        <span class="badge" style="background:#0dcaf0; ">Docente</span>
                                    @else
                                        <span class="badge badge-warning">Otro</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <a class="btn btn-danger text-white" href="{{ route('teachers.index') }}">Volver al listado</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>