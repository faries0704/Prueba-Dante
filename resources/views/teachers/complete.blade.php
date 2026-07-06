<x-layouts.app-layout title="Completar Perfil Docente">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Completar datos de Docente</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Asignar información profesional a: <b>{{ $user->username }}</b></h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Atención!</strong> Complete los datos profesionales para habilitar al docente en el sistema.
                    </div>

                    <form action="{{ route('teachers.storeComplete') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="iduser" value="{{ $user->iduser }}">

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del Docente <span class="text-danger">*</span></label>
                                    <input type="text" name="dni" maxlength="8" class="form-control" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" required placeholder="ejm: 45678912" value="{{ old('dni') }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-8">
                                <div class="form-group">
                                    <label>Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" required placeholder="ejm: Prof. Ricardo Palma" value="{{ old('full_name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Especialidad / Área <span class="text-danger">*</span></label>
                                    <input type="text" name="specialty" class="form-control" required placeholder="ejm: Matemáticas, Comunicación">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Teléfono de contacto</label>
                                    <input type="text" name="phone" class="form-control" placeholder="ejm: 987654321">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="birth_date" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary text-white">Guardar Perfil Docente</button>
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