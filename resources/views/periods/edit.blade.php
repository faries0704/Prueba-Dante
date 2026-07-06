<x-layouts.app-layout title="Actualizar Periodo">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('periods.index') }}">Periodo escolar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar periodo escolar</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('periods.update', $period->idperiod) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Nombre del Periodo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="period_name" value="{{ $period->period_name }}" required placeholder="ejm: 2022-1">
                            <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                        </div>

                        <div class="form-group">
                            <label>Comienza <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechad" name="start_date" value="{{ $period->start_date }}" required>
                        </div>

                        <div class="form-group">
                            <label>Termina <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechade" name="end_date" value="{{ $period->end_date }}" required>
                        </div>

                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select class="form-control" required name="status">
                                <option value="1" {{ $period->status == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $period->status == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar</button>
                                <a class="btn btn-danger text-white" href="{{ route('periods.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let fecha = new Date().toISOString().split('T')[0];
    document.querySelector('#fechad').min = fecha;
    document.querySelector('#fechade').min = fecha;
</script>
@endpush

</x-layouts.app-layout>