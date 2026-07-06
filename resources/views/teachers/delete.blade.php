<x-layouts.app-layout title="Desactivar Docente">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Docentes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar al docente?</h4>
                    <p class="category">Registro: <b>{{ $teacher->full_name }}</b></p>
                </div>

                <div class="card-content">
                    <form action="{{ route('teachers.destroy', $teacher->idteacher) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <strong>Atención!</strong> Esta acción cambiará el estado del docente a "Inactivo" y no podrá acceder al sistema hasta que sea reactivado.
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="idteacher" value="{{ $teacher->idteacher }}">
                                    <hr>
                                    <button type="submit" class="btn btn-success text-white">Confirmar Desactivación</button>
                                    <a class="btn btn-danger text-white" href="{{ route('teachers.index') }}">Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>