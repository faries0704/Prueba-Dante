<x-layouts.app-layout title="Nuevo Grado Académico">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('degrees.index') }}">Grado Académico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Nuevo Grado Académico</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('degrees.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre del grado <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="degree_name" required placeholder="ejm: Inicial 5 años">
                                    <small class="form-text text-muted text-danger">Importante rellenar este campo.</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Semestre / Periodo <span class="text-danger">*</span></label>
                                    <select class="form-control" name="idsemester" required>
                                        <option value="">Seleccione un semestre...</option>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->idsemester }}">
                                                {{ $semester->period->period_name }} - {{ $semester->semester_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted text-danger">Asigne el grado a un ciclo académico.</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Añadir</button>
                                <a class="btn btn-danger text-white" href="{{ route('degrees.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>