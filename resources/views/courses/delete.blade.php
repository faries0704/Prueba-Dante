<x-layouts.app-layout title="Desactivar Curso">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar el registro?</h4>
                </div>
                <div class="card-content">
                    <form action="{{ route('courses.destroy', $course->idcourse) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="alert alert-danger">
                            <i class="material-icons" style="vertical-align: middle;">warning</i>
                            Usted está a punto de desactivar el curso: <strong>{{ $course->course_name }}</strong> <br>
                            <small>Grado: {{ $course->degree->degree_name }} | Subgrado: {{ $course->subgrade->subgrade_name }}</small>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Desactivar ahora</button>
                                <a class="btn btn-danger text-white" href="{{ route('courses.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>