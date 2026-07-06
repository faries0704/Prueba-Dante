<x-layouts.app-layout title="Desactivar Grado Académico">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('degrees.index') }}">Grado Académico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar el registro?</h4>
                    <p class="text-muted">Vas a desactivar el grado: <strong>{{ $degree->degree_name }}</strong></p>
                </div>
                <div class="card-content">
                    <form action="{{ route('degrees.destroy', $degree->iddegree) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="alert alert-danger">
                            <i class="material-icons" style="vertical-align: middle;">warning</i>
                            Esta acción marcará el grado como <strong>Inactivo</strong> dentro del periodo 
                            {{ $degree->semester->period->period_name }}.
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Desactivar</button>
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