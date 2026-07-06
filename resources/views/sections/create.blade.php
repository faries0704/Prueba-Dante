<x-layouts.app-layout title="Nueva Sección Múltiple">
<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sección</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header border-bottom pb-2">
                    <h4 class="card-title fw-bold text-custom-blue">Crear Secciones Múltiples</h4>
                </div>
                <div class="card-content p-4">
                    <div class="alert alert-info border-colegio" style="background-color: rgba(0, 81, 135, 0.1); color: #00365a;">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios. Marque las secciones para configurar su capacidad y cursos independientes.
                    </div>

                    <form action="{{ route('sections.store') }}" method="POST" autocomplete="off">
                        @csrf
                        
                        <!-- FILA 1: Filtros de Periodo, Grado y Subgrado -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Periodo<span class="text-danger">*</span></label>
                                    <select class="form-control" id="prd" name="period_id" required>
                                        <option value="">Seleccione Periodo</option>
                                        @foreach($periods as $p)
                                            <option value="{{ $p->idperiod }}">{{ $p->period_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Grado<span class="text-danger">*</span></label>
                                    <select class="form-control" id="grd" required>
                                        <option value="">Seleccione Grado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Subgrado<span class="text-danger">*</span></label>
                                    <select class="form-control" id="sub" required name="txtsgrd">
                                        <option value="">Seleccione Subgrado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- FILA 2: Selector de Letras de Sección -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">1. Active las Secciones a Generar<span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2 pt-1">
                                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $letra)
                                        <div style="min-width: 120px;">
                                            <input type="checkbox" class="btn-check section-toggle" value="{{ $letra }}" id="btn_sec_{{ $letra }}">
                                            <label class="btn btn-outline-colegio w-100 py-2 fw-bold" for="btn_sec_{{{ $letra }}}">
                                                Sección {{ $letra }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- FILA 3: Matriz Dinámica (Sección -> Capacidad y Cursos) -->
                        <div class="row mb-4 d-none" id="matrix-container">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">2. Configuración de Capacidad y Cursos por Sección<span class="text-danger">*</span></label>
                                <div class="table-responsive border rounded">
                                    <table class="table table-bordered align-middle mb-0">
                                        <thead class="bg-colegio">
                                            <tr>
                                                <th style="width: 15%;">Sección</th>
                                                <th style="width: 20%;">Capacidad Max.</th>
                                                <th style="width: 65%;">Cursos Asignados</th>
                                            </tr>
                                        </thead>
                                        <tbody id="matrix-body">
                                            <!-- Se rellena dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn bg-colegio px-4">Generar Secciones</button>
                            <a class="btn btn-danger text-white px-4" href="{{ route('sections.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let cursosDisponibles = []; // Cache local de los cursos devueltos por el subgrado

    // Al cambiar periodo
    $('#prd').on('change', function() {
        let id = $(this).val();
        $('#grd').empty().append('<option value="">Cargando...</option>');
        $('#sub').empty().append('<option value="">Seleccione Subgrado</option>');
        resetMatrix();

        if(id) {
            $.get(`/get-grades/${id}`, function(data) {
                $('#grd').empty().append('<option value="">Seleccione Grado</option>');
                data.forEach(grado => {
                    $('#grd').append(`<option value="${grado.iddegree}">${grado.degree_name}</option>`);
                });
            });
        }
    });

    // Al cambiar grado
    $('#grd').on('change', function() {
        let id = $(this).val();
        $('#sub').empty().append('<option value="">Cargando...</option>');
        resetMatrix();

        if(id) {
            $.get(`/get-subgrades/${id}`, function(data) {
                $('#sub').empty().append('<option value="">Seleccione Subgrado</option>');
                data.forEach(sub => {
                    $('#sub').append(`<option value="${sub.idsubgrade}">${sub.subgrade_name}</option>`);
                });
            });
        }
    });

    // Al cambiar subgrado: Guardamos los cursos en memoria
    $('#sub').on('change', function() {
        let id = $(this).val();
        resetMatrix();

        if(id) {
            $.get(`/get-courses/${id}`, function(data) {
                cursosDisponibles = data;
                rebuildMatrix(); // Redibuja si ya había un botón marcado
            });
        } else {
            cursosDisponibles = [];
        }
    });

    // Escuchar el evento de selección/deselección de secciones
    $('.section-toggle').on('change', function() {
        rebuildMatrix();
    });

    function resetMatrix() {
        $('.section-toggle').prop('checked', false);
        $('#matrix-body').empty();
        $('#matrix-container').addClass('d-none');
    }

    // Renderizar la tabla dinámica basada en lo seleccionado
    function rebuildMatrix() {
        let activeSections = [];
        $('.section-toggle:checked').each(function() {
            activeSections.push($(this).val());
        });

        if (activeSections.length === 0 || cursosDisponibles.length === 0) {
            $('#matrix-body').empty();
            $('#matrix-container').addClass('d-none');
            return;
        }

        $('#matrix-container').removeClass('d-none');
        $('#matrix-body').empty();

        activeSections.forEach(letra => {
            let cursoCheckboxes = '';
            
            cursosDisponibles.forEach(curso => {
                cursoCheckboxes += `
                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input" type="checkbox" 
                            name="section_data[${letra}][courses][]" 
                            value="${curso.idcourse}" 
                            id="cur_${letra}_${curso.idcourse}" checked>
                        <label class="form-check-label text-dark fw-medium" for="cur_${letra}_${curso.idcourse}">
                            ${curso.course_name}
                        </label>
                    </div>
                `;
            });

            $('#matrix-body').append(`
                <tr id="tr_sec_${letra}">
                    <td class="text-center fw-bold text-custom-blue fs-5 bg-light">
                        Sección ${letra}
                        <input type="hidden" name="sections[]" value="${letra}">
                    </td>
                    <td>
                        <input type="number" class="form-control border-colegio" 
                            name="section_data[${letra}][capacity]" 
                            required placeholder="Ej: 30" min="1" value="30">
                    </td>
                    <td>
                        <div class="d-flex flex-wrap p-2">
                            ${cursoCheckboxes}
                        </div>
                    </td>
                </tr>
            `);
        });
    }
});
</script>
@endpush
</x-layouts.app-layout>