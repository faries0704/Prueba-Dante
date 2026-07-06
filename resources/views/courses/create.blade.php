<x-layouts.app-layout title="Nuevos Cursos">
    <div class="main-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" class="text-decoration-none">Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nuevo Bloque de Cursos</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h4 class="card-title mb-0 fw-bold">
                        <i class="fas fa-layer-group me-2"></i> Asignar Cursos por Grado
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <h5 class="mb-3 fw-bold text-secondary">1. Seleccione el nivel académico</h5>
                        <div class="row g-3 bg-light p-3 rounded mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Semestre / Periodo <span class="text-danger">*</span></label>
                                <select class="form-select" name="idsemester" id="select-semester" required>
                                    <option value="">Seleccione Semestre...</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->idsemester }}">
                                            {{ $semester->period->period_name }} - {{ $semester->semester_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Grado <span class="text-danger">*</span></label>
                                <select class="form-select" name="iddegree" id="select-degree" required disabled>
                                    <option value="">Seleccione Grado...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Subgrado <span class="text-danger">*</span></label>
                                <select class="form-select" name="idsubgrade" id="select-subgrade" required disabled>
                                    <option value="">Seleccione Subgrado...</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="mb-3 fw-bold text-secondary">2. Selección de Cursos, Docentes y Foto</h5>
                        
                        <div class="row g-4">
                            @foreach($cursosDefault as $index => $curso)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 course-card inactive-card shadow-sm" id="card_{{ $index }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                            <h6 class="card-title mb-0 fw-bold text-dark">{{ $curso }}</h6>
                                            <div class="form-check form-switch fs-5 mb-0">
                                                <input class="form-check-input course-toggle" type="checkbox" role="switch" 
                                                       name="courses[{{ $index }}][selected]" value="1" 
                                                       id="toggle_{{ $index }}" data-index="{{ $index }}">
                                                <input type="hidden" name="courses[{{ $index }}][nombre]" value="{{ $curso }}">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label text-muted small mb-1">Docente Asignado <span class="text-danger">*</span></label>
                                            <select class="form-select teacher-select" name="courses[{{ $index }}][teacher]" id="teacher_{{ $index }}" disabled required>
                                                <option value="">-- Asignar Docente --</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->idteacher }}">{{ $teacher->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="form-label text-muted small mb-1">Foto del Curso <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="courses[{{ $index }}][photo]" id="photo_{{ $index }}" accept="image/jpeg, image/png, image/jpg" disabled required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-5 pt-3 border-top text-end">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-1"></i> Guardar Cursos Seleccionados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Lógica para efectos visuales y activar/desactivar inputs
            $('.course-toggle').on('change', function() {
                let index = $(this).data('index');
                let card = $('#card_' + index);
                let selectTeacher = $('#teacher_' + index);
                let inputPhoto = $('#photo_' + index); // Referencia al input file

                if ($(this).is(':checked')) {
                    // Activar tarjeta
                    card.removeClass('inactive-card').addClass('active-card');
                    selectTeacher.prop('disabled', false); 
                    inputPhoto.prop('disabled', false); // Habilitar archivo
                } else {
                    // Desactivar tarjeta
                    card.removeClass('active-card').addClass('inactive-card');
                    selectTeacher.prop('disabled', true); 
                    selectTeacher.val(''); 
                    inputPhoto.prop('disabled', true); // Deshabilitar archivo
                    inputPhoto.val(''); // Limpiar el archivo si se desmarca
                }
            });

            // Lógica existente para Semestre -> Grado (AJAX)
            $('#select-semester').on('change', function() {
                var semesterId = $(this).val();
                $('#select-degree').empty().append('<option value="">Cargando...</option>').prop('disabled', true);
                $('#select-subgrade').empty().append('<option value="">Seleccione Subgrado...</option>').prop('disabled', true);

                if (semesterId) {
                    $.get('/get-grades/' + semesterId, function(data) {
                        $('#select-degree').empty().append('<option value="">Seleccione Grado...</option>').prop('disabled', false);
                        $.each(data, function(index, value) {
                            $('#select-degree').append('<option value="'+value.iddegree+'">'+value.degree_name+'</option>');
                        });
                    });
                }
            });

            // Lógica existente para Grado -> Subgrado (AJAX)
            $('#select-degree').on('change', function() {
                var degreeId = $(this).val();
                $('#select-subgrade').empty().append('<option value="">Cargando...</option>').prop('disabled', true);

                if (degreeId) {
                    $.get('/get-subgrades/' + degreeId, function(data) {
                        $('#select-subgrade').empty().append('<option value="">Seleccione Subgrado...</option>').prop('disabled', false);
                        $.each(data, function(index, value) {
                            $('#select-subgrade').append('<option value="'+value.idsubgrade+'">'+value.subgrade_name+'</option>');
                        });
                    });
                }
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>