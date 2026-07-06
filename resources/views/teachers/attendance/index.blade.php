<x-layouts.app-layout title="Resumen de Asistencias">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mis Asistencias</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #005187;">
                        <i class="material-icons align-middle fs-3 me-1">assignment_turned_in</i> Control de Asistencias
                    </h3>
                    <p class="text-muted small mb-0">Vista de docente para gestionar y revisar las bitácoras de asistencia de tus cursos a cargo.</p>
                </div>
                <a href="{{ route('teacher.attendance.create') }}" class="btn text-white px-4 shadow-sm d-flex align-items-center" style="background-color: #005187;">
                    <i class="material-icons me-1">add_box</i> Registrar Nueva Asistencia
                </a>
            </div>

            @if($attendances->count() > 0)
                
                <div class="card border-0 shadow-sm p-4 mb-4 bg-light rounded-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="material-icons me-2 text-secondary fs-5">tune</i>
                        <span class="text-secondary fw-bold small text-uppercase">Filtrar Cursos y Aulas Asignadas</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-muted small fw-bold">Periodo</label>
                            <select class="form-select form-select-sm border-secondary-subtle shadow-sm" id="filterPeriod" onchange="filterTeacherAttendance()">
                                <option value="">Todos</option>
                                @foreach($attendances->unique('period_name') as $row)
                                    <option value="{{ Str::slug($row->period_name) }}">{{ $row->period_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-muted small fw-bold">Grado</label>
                            <select class="form-select form-select-sm border-secondary-subtle shadow-sm" id="filterDegree" onchange="filterTeacherAttendance()">
                                <option value="">Todos</option>
                                @foreach($attendances->unique('degree_name') as $row)
                                    <option value="{{ Str::slug($row->degree_name) }}">{{ $row->degree_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-muted small fw-bold">Subgrado</label>
                            <select class="form-select form-select-sm border-secondary-subtle shadow-sm" id="filterSubgrade" onchange="filterTeacherAttendance()">
                                <option value="">Todos</option>
                                @foreach($attendances->unique('subgrade_name') as $row)
                                    <option value="{{ Str::slug($row->subgrade_name) }}">{{ $row->subgrade_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-md-6 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Curso</label>
                            <select class="form-select form-select-sm border-secondary-subtle shadow-sm" id="filterCourse" onchange="filterTeacherAttendance()">
                                <option value="">Todos tus cursos</option>
                                @foreach($attendances->unique('course_name') as $row)
                                    <option value="{{ Str::slug($row->course_name) }}">{{ $row->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Sección</label>
                            <select class="form-select form-select-sm border-secondary-subtle shadow-sm" id="filterSection" onchange="filterTeacherAttendance()">
                                <option value="">Todas tus secciones</option>
                                @foreach($attendances->unique('section_name') as $row)
                                    <option value="{{ Str::slug($row->section_name) }}">{{ $row->section_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="teacherAttendanceGrid">
                    @foreach($attendances as $row)
                        @php
                            $total = $row->total_students > 0 ? $row->total_students : 1;
                            $pctPresente = ($row->total_presentes / $total) * 100;
                            $pctTardanza = ($row->total_tardanzas / $total) * 100;
                            $pctAusente = ($row->total_ausentes / $total) * 100;
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 teacher-attendance-card" 
                             data-period="{{ Str::slug($row->period_name) }}"
                             data-degree="{{ Str::slug($row->degree_name) }}"
                             data-subgrade="{{ Str::slug($row->subgrade_name) }}"
                             data-course="{{ Str::slug($row->course_name) }}"
                             data-section="{{ Str::slug($row->section_name) }}">
                            
                            <div class="card h-100 shadow-sm border border-light-subtle rounded-3 overflow-hidden">
                                
                                <div class="card-header bg-white pt-3 pb-2 px-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-secondary-subtle text-secondary small px-2">
                                            {{ $row->period_name }}
                                        </span>
                                        <span class="text-muted fw-medium d-flex align-items-center" style="font-size: 13px;">
                                            <i class="material-icons fs-6 me-1 text-secondary">calendar_month</i>
                                            {{ \Carbon\Carbon::parse($row->attendance_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0 text-truncate" title="{{ $row->section_name }}">
                                        Sección: {{ $row->section_name }}
                                    </h5>
                                    <small class="text-primary fw-semibold d-block text-truncate mt-1" style="color: #005187 !important;">
                                        {{ $row->course_name }}
                                    </small>

                                    <div class="mt-2 d-flex gap-1 flex-wrap">
                                        <span class="badge bg-light text-secondary border rounded-1" style="font-size: 10px; font-weight: 500;">
                                            {{ $row->degree_name }}
                                        </span>
                                        <span class="badge bg-light text-dark border rounded-1" style="font-size: 10px; font-weight: 500;">
                                            {{ $row->subgrade_name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body py-2 px-3">
                                    <div class="p-3 rounded-3 bg-light border border-light-subtle">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small text-muted fw-bold">Métricas de Alumnos</span>
                                            <span class="badge bg-dark rounded-1">{{ $row->total_students }} matriculados</span>
                                        </div>

                                        <div class="progress mb-3" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pctPresente }}%" title="Presentes"></div>
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctTardanza }}%" title="Tardanzas"></div>
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pctAusente }}%" title="Ausentes"></div>
                                        </div>

                                        <div class="row g-1 text-center small">
                                            <div class="col-4 border-end">
                                                <span class="d-block text-success fw-bold fs-6">{{ $row->total_presentes }}</span>
                                                <span class="text-muted" style="font-size: 11px;">Presentes</span>
                                            </div>
                                            <div class="col-4 border-end">
                                                <span class="d-block text-warning fw-bold fs-6">{{ $row->total_tardanzas }}</span>
                                                <span class="text-muted" style="font-size: 11px;">Tardanzas</span>
                                            </div>
                                            <div class="col-4">
                                                <span class="d-block text-danger fw-bold fs-6">{{ $row->total_ausentes }}</span>
                                                <span class="text-muted" style="font-size: 11px;">Ausentes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-light py-2 px-3 border-top-0">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info w-100 d-flex align-items-center justify-content-center gap-1 btn-show-attendance rounded-2 py-1.5"
                                            data-date="{{ $row->attendance_date }}" 
                                            data-section="{{ $row->idsection }}"
                                            data-section-name="{{ $row->section_name }}">
                                        <i class="material-icons fs-5">visibility</i> <small class="fw-bold">Visualizar Detalle de Aula</small>
                                    </button>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 bg-light" role="alert">
                    <i class="material-icons text-info me-3 fs-2">info</i>
                    <div>
                        <h5 class="fw-bold mb-1">Sin asistencias registradas</h5>
                        <p class="mb-0 text-muted small">Aún no registras asistencias para tus materias asignadas en este ciclo académico.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade" id="infoAttendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons align-middle">info</i> 
                        DETALLES DE ASISTENCIA: <span id="info_span_seccion" class="fw-bold"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span><strong>Fecha evaluada:</strong> <span id="info_span_fecha"></span></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle" id="tablaInfoAlumnos">
                            <thead class="table-light">
                                <tr>
                                    <th>FOTO</th>
                                    <th>ALUMNO</th>
                                    <th class="text-center">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Carga dinámica mediante AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                retrieve: true, 
                paging: true,
                order: [[1, 'desc']] // Ordenar por fecha por defecto
            });
        });
    </script>
    @endpush

    @push('scripts')
    <script type="text/javascript">
        function filterTeacherAttendance() {
            let period = document.getElementById('filterPeriod').value;
            let degree = document.getElementById('filterDegree').value;
            let subgrade = document.getElementById('filterSubgrade').value;
            let course = document.getElementById('filterCourse').value;
            let section = document.getElementById('filterSection').value;

            let cards = document.querySelectorAll('.teacher-attendance-card');

            cards.forEach(card => {
                let cardPeriod = card.getAttribute('data-period');
                let cardDegree = card.getAttribute('data-degree');
                let cardSubgrade = card.getAttribute('data-subgrade');
                let cardCourse = card.getAttribute('data-course');
                let cardSection = card.getAttribute('data-section');

                let mPeriod   = (period === ""   || cardPeriod === period);
                let mDegree   = (degree === ""   || cardDegree === degree);
                let mSubgrade = (subgrade === "" || cardSubgrade === subgrade);
                let mCourse   = (course === ""   || cardCourse === course);
                let mSection  = (section === ""  || cardSection === section);

                if (mPeriod && mDegree && mSubgrade && mCourse && mSection) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const infoModal = new bootstrap.Modal('#infoAttendanceModal');

            $('.btn-show-attendance').on('click', function() {
                const idsection = $(this).data('section');
                const date = $(this).data('date');
                const sectionName = $(this).data('section-name');

                $('#info_span_seccion').text(sectionName);
                $('#info_span_fecha').text(date);

                let tbody = $('#tablaInfoAlumnos tbody');
                tbody.empty().append('<tr><td colspan="3" class="text-center py-3">Cargando detalles de alumnos...</td></tr>');

                infoModal.show();

                // Llamada AJAX al método del docente para recuperar la información del aula
                $.get("/teacher/attendance/show-details", { 
                    idsection: idsection, 
                    attendance_date: date 
                }, function(data) {
                    tbody.empty();

                    if(data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="text-center">No se encontraron registros diarios.</td></tr>');
                        return;
                    }

                    data.forEach(item => {
                        let foto = item.photo ? `/backend/img/subidas/${item.photo}` : `/backend/img/user-default.png`;
                        
                        let badgeClass = '';
                        if(item.status === 'PRESENTE') badgeClass = 'badge bg-success text-white px-3 py-2';
                        else if(item.status === 'AUSENTE') badgeClass = 'badge bg-danger text-white px-3 py-2';
                        else badgeClass = 'badge bg-warning text-dark px-3 py-2';

                        tbody.append(`
                            <tr>
                                <td>
                                    <img src="${foto}" width="50" height="50" class="rounded-circle shadow-sm" onerror="this.src='/backend/img/user-default.png'">
                                </td>
                                <td><strong>${item.full_name}</strong></td>
                                <td class="text-center">
                                    <span class="${badgeClass}">${item.status}</span>
                                </td>
                            </tr>
                        `);
                    });
                }).fail(function() {
                    tbody.html('<tr><td colspan="3" class="text-center text-danger">Error al cargar el reporte de alumnos.</td></tr>');
                });
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>