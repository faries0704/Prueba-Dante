<x-layouts.app-layout title="Desactivar Periodo">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('periods.index') }}">Periodo escolar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar el registro?</h4>
                    <p class="text-muted">Periodo: <strong>{{ $period->period_name }}</strong></p>
                </div>

                <div class="card-content table-responsive">
                    <form action="{{ route('periods.destroy', $period->idperiod) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="alert alert-danger">
                            <strong>Atención!</strong> Esta acción cambiará el estado del periodo a Inactivo.
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Desactivar Ahora</button>
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