<x-layouts.app-layout title="Desactivar Sección">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sección</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar el registro?</h4>
                    <p class="text-muted">Esta acción cambiará el estado de la sección a "Inactivo".</p>
                </div>
                <div class="card-content">
                    <div class="alert alert-danger">
                        <strong>Atención:</strong> Vas a desactivar la sección <b>{{ $section->section_name }}</b> del curso <b>{{ $section->course->course_name }}</b>.
                    </div>

                    <form action="{{ route('sections.destroy', $section->idsection) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="row">
                            <div class="col-md-4">
                                <label>Periodo:</label>
                                <p><strong>{{ $section->course->semester->period->period_name ?? 'N/A' }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <label>Grado/Subgrado:</label>
                                <p><strong>{{ $section->course->degree->degree_name }} - {{ $section->course->subgrade->subgrade_name }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <label>Capacidad Actual:</label>
                                <p><strong>{{ $section->capacity }} alumnos</strong></p>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Confirmar Desactivación</button>
                            <a class="btn btn-danger text-white" href="{{ route('sections.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>