<x-layouts.app-layout title="Actualizar Sección">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sección</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar sección: <strong>{{ $section->section_name }}</strong></h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('sections.update', $section->idsection) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Periodo</label>
                                    <input type="text" readonly class="form-control" 
                                           value="{{ $section->course->semester->period->period_name ?? 'N/A' }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Grado</label>
                                    <input type="text" readonly class="form-control" 
                                           value="{{ $section->course->degree->degree_name ?? 'N/A' }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Subgrado</label>
                                    <input type="text" readonly class="form-control" 
                                           value="{{ $section->course->subgrade->subgrade_name ?? 'N/A' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre de la sección<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $section->section_name }}" class="form-control" name="txtnamsecc" required>
                                    <small class="form-text text-muted">Ejm: 101, A, Única</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Capacidad<span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $section->capacity }}" class="form-control" name="txtcapc" required>
                                    <small class="form-text text-muted">Máximo de alumnos permitidos.</small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" required name="txtstte">
                                        <option value="1" {{ $section->status == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $section->status == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    <small class="form-text text-muted">Estado actual de la sección.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Curso Asignado (Informativo)</label>
                                    <input type="text" readonly class="form-control bg-light" 
                                           value="{{ $section->course->course_name }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success text-white">Actualizar Sección</button>
                            <a class="btn btn-danger text-white" href="{{ route('sections.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>