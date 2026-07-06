<x-layouts.app-layout title="Nuevo Periodo">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('periods.index') }}">Periodo escolar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Nuevo Periodo Escolar</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('periods.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre del Periodo Escolar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="period_name" required placeholder="ejm: Año Académico 2026">
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar este campo.</span></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-output form-control" name="start_date" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Fecha de Finalización <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Añadir Periodo</button>
                                <a class="btn btn-danger text-white" href="{{ route('periods.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>