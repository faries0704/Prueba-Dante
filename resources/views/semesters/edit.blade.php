<x-layouts.app-layout title="Actualizar Semestre">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('semesters.index') }}">Semestres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar Semestre: {{ $semester->semester_name }}</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('semesters.update', $semester->idsemester) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Nombre del Semestre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="semester_name" 
                                   value="{{ old('semester_name', $semester->semester_name) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Periodo Escolar <span class="text-danger">*</span></label>
                            <select name="idperiod" class="form-control" required>
                                @foreach($periods as $period)
                                    <option value="{{ $period->idperiod }}" 
                                        {{ $semester->idperiod == $period->idperiod ? 'selected' : '' }}>
                                        {{ $period->period_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ $semester->status == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $semester->status == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar</button>
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