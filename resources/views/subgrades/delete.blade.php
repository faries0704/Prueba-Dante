<x-layouts.app-layout title="Desactivar Subgrado Académico">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subgrades.index') }}">Subgrado Académico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar el registro?</h4>
                    <p class="text-muted">Subgrado: <strong>{{ $subgrade->subgrade_name }}</strong></p>
                </div>
                <div class="card-content">
                    <form action="{{ route('subgrades.destroy', $subgrade->idsubgrade) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="alert alert-danger">
                            <i class="material-icons" style="vertical-align: middle;">report_problem</i>
                            <strong>Atención:</strong> Esta acción marcará el subgrado como <strong>Inactivo</strong>. 
                            Pertenece al grado: {{ $subgrade->degree->degree_name }} 
                            ({{ $subgrade->degree->semester->period->period_name }}).
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Desactivar</button>
                                <a class="btn btn-danger text-white" href="{{ route('subgrades.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>