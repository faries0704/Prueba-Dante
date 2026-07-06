<x-layouts.app-layout title="Resumen de Calificaciones">
    <div class="main-content">
        <div class="container-fluid">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
                <nav aria-label="breadcrumb" class="mb-0">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mis Calificaciones</li>
                    </ol>
                </nav>
                <a href="{{ route('teacher.grades.create') }}" class="btn d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2 rounded-2 text-white" style="background-color: #005187;">
                    <i class="material-icons">add_circle</i>
                    <span class="fw-bold small">Registrar Notas</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">assignment_turned_in</i> Registro y Control de Calificaciones (Docente)
                </h3>
                <p class="text-muted small mb-0">Gestión de promedios, actas de evaluación y métricas de rendimiento académico de sus aulas asignadas.</p>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4 bg-light rounded-3">
                <div class="d-flex align-items-center mb-3">
                    <i class="material-icons me-2 fs-5" style="color: #005187;">filter_list</i>
                    <span class="fw-bold small text-uppercase text-secondary" style="letter-spacing: 0.5px;">Criterios de Búsqueda y Filtrado</span>
                </div>
                
                <form method="GET" action="{{ route('teacher.grades.index') }}" id="formFiltrosCalificaciones" class="row g-3 mb-3 pb-3 border-bottom border-secondary-subtle">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <label class="form-label text-muted small fw-bold">Periodo Académico</label>
                        <select class="form-select border-secondary-subtle shadow-sm" name="idperiod" onchange="document.getElementById('formFiltrosCalificaciones').submit();">
                            <option value="">-- Todos los Periodos --</option>
                            @foreach($periods as $p)
                                <option value="{{ $p->idperiod }}" {{ $selectedPeriod == $p->idperiod ? 'selected' : '' }}>
                                    {{ $p->period_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <label class="form-label text-muted small fw-bold">Mis Cursos Asignados</label>
                        <select class="form-select border-secondary-subtle shadow-sm" name="idcourse" onchange="document.getElementById('formFiltrosCalificaciones').submit();">
                            <option value="">-- Todos mis Cursos --</option>
                            @foreach($teacherCourses as $tc)
                                <option value="{{ $tc->idcourse }}" {{ $selectedCourse == $tc->idcourse ? 'selected' : '' }}>
                                    {{ $tc->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($grades->count() > 0)
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Grado / Nivel</label>
                            <select class="form-select border-secondary-subtle shadow-sm bg-white" id="teacherFilterDegree" onchange="filterTeacherGrades()">
                                <option value="">Todos los Grados</option>
                                @foreach($grades->unique('degree_name') as $g)
                                    <option value="{{ Str::slug($g->degree_name) }}">{{ $g->degree_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Sub Grado</label>
                            <select class="form-select border-secondary-subtle shadow-sm bg-white" id="teacherFilterSubgrade" onchange="filterTeacherGrades()">
                                <option value="">Todos los Subgrados</option>
                                @foreach($grades->whereNotNull('subgrade_name')->unique('subgrade_name') as $g)
                                    <option value="{{ Str::slug($g->subgrade_name) }}">{{ $g->subgrade_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Sección</label>
                            <select class="form-select border-secondary-subtle shadow-sm bg-white" id="teacherFilterSection" onchange="filterTeacherGrades()">
                                <option value="">Todas las Secciones</option>
                                @foreach($grades->unique('section_name') as $g)
                                    <option value="{{ Str::slug($g->section_name) }}">{{ $g->section_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            @if($grades->count() > 0)
                
                <div class="row g-4" id="teacherGradesGrid">
                    @foreach ($grades as $g)
                        @php
                            $isDanger = $g->section_average < 11;
                            $badgeColor = $isDanger ? 'bg-danger-subtle text-danger border-danger' : 'bg-success-subtle text-success border-success';
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 teacher-grade-card-item"
                             data-degree="{{ Str::slug($g->degree_name) }}"
                             data-subgrade="{{ Str::slug($g->subgrade_name ?? 'na') }}"
                             data-section="{{ Str::slug($g->section_name) }}">
                            
                            <div class="card h-100 shadow-sm border border-light-subtle rounded-3 overflow-hidden"
                                 style="transition: transform 0.2s ease, box-shadow 0.2s ease;"
                                 onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.08)'"
                                 onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                                
                                <div class="card-header bg-white pt-3 pb-2 px-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-light text-dark border px-2 py-1 small fw-semibold">
                                            {{ $g->period_name }} 
                                            @if(!empty($g->semester_name))
                                                <span class="text-muted">| {{ $g->semester_name }}</span>
                                            @endif
                                        </span>
                                        <span class="badge text-white px-2 py-1 small" style="background-color: #0f766e;">
                                            Aula: {{ $g->section_name }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $g->course_name }}">
                                        {{ $g->course_name }}
                                    </h5>
                                    <p class="text-muted small mb-0 d-flex align-items-center gap-1" style="font-size: 13px;">
                                        <i class="material-icons text-secondary fs-6">school</i> 
                                        {{ $g->degree_name }} <span class="text-secondary-subtle">|</span> {{ $g->subgrade_name ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="card-body py-2 px-3">
                                    <div class="p-3 rounded-3 bg-light border border-light-subtle">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="small text-muted fw-bold">Promedios Consolidados</span>
                                            <span class="badge bg-dark rounded-1 d-flex align-items-center gap-1 px-2 py-0.5" style="font-size: 11px;">
                                                <i class="material-icons fs-6">groups</i> {{ $g->total_students }} Alumnos
                                            </span>
                                        </div>

                                        <div class="row g-2 text-center mb-3">
                                            <div class="col-6 border-end">
                                                <small class="text-muted d-block" style="font-size: 11px;">Prom. Prácticas</small>
                                                <span class="fw-bold text-dark fs-6">{{ $g->avg_practica ?? '0.00' }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block" style="font-size: 11px;">Prom. Exámenes</small>
                                                <span class="fw-bold text-dark fs-6">{{ $g->avg_examen ?? '0.00' }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border {{ $badgeColor }}">
                                            <span class="small fw-bold">Promedio General del Aula</span>
                                            <span class="fs-5 fw-bold px-2.5 rounded">{{ $g->section_average }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-light py-2.5 px-3 border-top-0">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-1 btn-edit-grades rounded-2 py-1.5 fw-bold"
                                            data-section="{{ $g->idsection }}"
                                            data-course="{{ $g->idcourse }}"
                                            data-section-name="{{ $g->section_name }}"
                                            data-course-name="{{ $g->course_name }}">
                                        <i class="material-icons fs-5">edit_note</i> Evaluar Estudiantes
                                    </button>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 rounded-3" role="alert">
                    <i class="material-icons text-info me-3 fs-2">info</i>
                    <div>
                        <h5 class="fw-bold mb-1">No se encontraron actas cargadas</h5>
                        <p class="mb-0 text-muted small">Modifique los criterios de búsqueda superiores o registre nuevas calificaciones.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade" id="editGradeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons align-middle">edit</i>
                        EDITAR CALIFICACIONES: <span id="edit_span_seccion" class="fw-bold"></span>
                        <br><small id="edit_span_curso"></small>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('teacher.grades.update') }}" method="POST" id="formEditGrades">
                    @csrf
                    <input type="hidden" name="idsection" id="edit_hidden_section">
                    <input type="hidden" name="idcourse" id="edit_hidden_course">

                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Evaluación</label>
                                <select name="idevaluation_type" id="edit_evaluation_type" class="form-select border-primary" required>
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaEditGradesModal">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">FOTO</th>
                                        <th>ALUMNO</th>
                                        <th width="180">CALIFICACIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Seleccione un tipo de evaluación para cargar el registro.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn text-white px-4" style="background-color: #005187;">ACTUALIZAR NOTAS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        // Lógica de filtrado en cliente para Grado, Subgrado y Sección
        function filterTeacherGrades() {
            let degree = document.getElementById('teacherFilterDegree').value;
            let subgrade = document.getElementById('teacherFilterSubgrade').value;
            let section = document.getElementById('teacherFilterSection').value;

            let cards = document.querySelectorAll('.teacher-grade-card-item');

            cards.forEach(card => {
                let cardDegree = card.getAttribute('data-degree');
                let cardSubgrade = card.getAttribute('data-subgrade');
                let cardSection = card.getAttribute('data-section');

                let matchDegree = (degree === "" || cardDegree === degree);
                let matchSubgrade = (subgrade === "" || cardSubgrade === subgrade);
                let matchSection = (section === "" || cardSection === section);

                if (matchDegree && matchSubgrade && matchSection) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        }

        let activeSection = null;
        let activeCourse = null;

    </script>
    @endpush

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                if ($.fn.DataTable.isDataTable('#example')) {
                    $('#example').DataTable().destroy();
                }
                $('#example').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    retrieve: true, 
                    paging: true
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                const editModal = new bootstrap.Modal('#editGradeModal');

                // Abrir e inicializar Modal
                $('.btn-edit-grades').on('click', function() {
                    const idsection = $(this).data('section');
                    const idcourse = $(this).data('course');
                    const sectionName = $(this).data('section-name');
                    const courseName = $(this).data('course-name');

                    $('#edit_hidden_section').val(idsection);
                    $('#edit_hidden_course').val(idcourse);
                    $('#edit_span_seccion').text(sectionName);
                    $('#edit_span_curso').text(courseName);

                    $('#tablaEditGradesModal tbody').html(`
                        <tr>
                            <td colspan="3" class="text-center text-muted">Seleccione un tipo de evaluación</td>
                        </tr>
                    `);

                    // Cargar Tipos de Evaluación por AJAX
                    $.get("{{ route('teacher.grades.getEvaluationTypes') }}", function(data) {
                        let select = $('#edit_evaluation_type');
                        select.empty().append('<option value="">-- Seleccione --</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.idevaluation_type}">${item.evaluation_name}</option>`);
                        });
                    });

                    editModal.show();
                });

                // Cargar Alumnos al cambiar el Tipo de Evaluación
                $('#edit_evaluation_type').on('change', function() {
                    const idevaluation_type = $(this).val();
                    const idsection = $('#edit_hidden_section').val();
                    const idcourse = $('#edit_hidden_course').val();
                    let tbody = $('#tablaEditGradesModal tbody');

                    if(!idevaluation_type) return;

                    tbody.html('<tr><td colspan="3" class="text-center py-3">Cargando alumnos inscritos...</td></tr>');

                    $.get("{{ route('teacher.grades.getEditData') }}", {
                        idsection: idsection,
                        idcourse: idcourse,
                        idevaluation_type: idevaluation_type
                    }, function(data) {
                        tbody.empty();

                        if(data.length === 0) {
                            tbody.html('<tr><td colspan="3" class="text-center">No se encontraron alumnos activos en la sección.</td></tr>');
                            return;
                        }

                        data.forEach(alumno => {
                            let foto = alumno.photo ? `/backend/img/subidas/${alumno.photo}` : `/backend/img/user-default.png`;
                            tbody.append(`
                                <tr>
                                    <td><img src="${foto}" width="45" height="45" class="rounded-circle shadow-sm" onerror="this.src='/backend/img/user-default.png'"></td>
                                    <td><strong>${alumno.full_name}</strong></td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="20" class="form-control border-primary" name="notas[${alumno.idstudent}]" value="${alumno.grade ?? ''}">
                                    </td>
                                </tr>
                            `);
                        });
                    });
                });

                // Enviar Calificaciones Editadas por AJAX
                $('#formEditGrades').on('submit', function(e) {
                    e.preventDefault();
                    const $form = $(this);

                    $.ajax({
                        url: $form.attr('action'),
                        method: 'POST',
                        data: $form.serialize(),
                        success: function(response) {
                            editModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Completado!',
                                text: 'Registro de calificaciones actualizado correctamente.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudieron almacenar los cambios en las notas.', 'error');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app-layout>