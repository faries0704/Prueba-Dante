<x-layouts.app-layout title="Actualizar Subgrado Académico">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subgrades.index') }}">Subgrado Académico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar Subgrado: {{ $subgrade->subgrade_name }}</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('subgrades.update', $subgrade->idsubgrade) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Grado / Periodo <span class="text-danger">*</span></label>
                                    <select class="form-control" name="iddegree" required>
                                        @foreach($degrees as $degree)
                                            <option value="{{ $degree->iddegree }}" 
                                                {{ $subgrade->iddegree == $degree->iddegree ? 'selected' : '' }}>
                                                {{ $degree->semester->period->period_name }} - {{ $degree->degree_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted text-danger">Cambie el grado si es necesario.</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre del subgrado <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="subgrade_name" 
                                           value="{{ old('subgrade_name', $subgrade->subgrade_name) }}" required>
                                    <small class="form-text text-muted text-danger">Ejm: Sección B, Único, etc.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{ $subgrade->status == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $subgrade->status == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    <small class="form-text text-muted text-danger">Defina si el subgrado está disponible.</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar</button>
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