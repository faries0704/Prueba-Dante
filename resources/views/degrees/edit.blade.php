<x-layouts.app-layout title="Actualizar Grado Académico">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('degrees.index') }}">Grado Académico</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar Grado: {{ $degree->degree_name }}</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('degrees.update', $degree->iddegree) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre del grado <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="degree_name" 
                                           value="{{ old('degree_name', $degree->degree_name) }}" required>
                                    <small class="form-text text-muted text-danger">Importante rellenar los campos.</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Periodo / Semestre <span class="text-danger">*</span></label>
                                    <select class="form-control" name="idsemester" required>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->idsemester }}" 
                                                {{ $degree->idsemester == $semester->idsemester ? 'selected' : '' }}>
                                                {{ $semester->period->period_name }} - {{ $semester->semester_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted text-danger">Seleccione el ciclo académico.</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{ $degree->status == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $degree->status == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    <small class="form-text text-muted text-danger">Defina la disponibilidad del grado.</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar</button>
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