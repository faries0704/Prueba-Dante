<x-layouts.app-layout title="Nuevo Semestre">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('semesters.index') }}">Semestres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Nuevo Semestre</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('semesters.store') }}" method="POST" autocomplete="off">
                        @csrf
                        
                        <div class="form-group">
                            <label>Nombre del Semestre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="semester_name" required placeholder="ejm: Primer Semestre">
                            <small class="form-text text-muted">Nombre descriptivo del semestre.</small>
                        </div>

                        <div class="form-group">
                            <label>Periodo Escolar <span class="text-danger">*</span></label>
                            <select name="idperiod" class="form-control" required>
                                <option value="">Seleccione un periodo...</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->idperiod }}">{{ $period->period_name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Asigne este semestre a un periodo escolar vigente.</small>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Añadir Semestre</button>
                                <a class="btn btn-danger text-white" href="{{ route('semesters.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>