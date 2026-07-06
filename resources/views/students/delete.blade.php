<x-layouts.app-layout title="Desactivar Alumno">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar el registro de: <b>{{ $student->full_name }}</b>?</h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-danger">
                        <strong>Atención!</strong> Al desactivar al alumno, este ya no podrá acceder al sistema. Puede reactivarlo desde el módulo de edición.
                    </div>

                    <form action="{{ route('students.destroy', $student->idstudent) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success text-white">Sí, Desactivar</button>
                                <a class="btn btn-danger text-white" href="{{ route('students.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>