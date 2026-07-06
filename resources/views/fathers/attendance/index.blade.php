<x-layouts.app-layout title="Resumen de Asistencias de mis Hijos">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Asistencias de mis Hijos</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #005187;">
                        <i class="material-icons align-middle fs-3 me-1">family_restroom</i> Seguimiento de Asistencias
                    </h3>
                    <p class="text-muted small mb-0">Supervise y consulte en tiempo real el registro diario de asistencia de sus estudiantes vinculados.</p>
                </div>
            </div>

            @if($attendances->count() > 0)
                
                <div class="card border-0 shadow-sm p-4 mb-4 bg-light rounded-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="material-icons me-2 text-secondary fs-5">supervised_user_circle</i>
                        <span class="text-secondary fw-bold small text-uppercase">Panel de Control Familiar</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label text-muted small fw-bold">Seleccionar Estudiante</label>
                            <select class="form-select border-secondary-subtle shadow-sm" id="filterStudent" onchange="filterFatherAttendance()">
                                <option value="">Todos tus hijos</option>
                                @foreach($attendances->unique('idstudent') as $row)
                                    <option value="{{ $row->idstudent }}">{{ $row->student_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-5">
                            <label class="form-label text-muted small fw-bold">Filtrar por Materia o Curso</label>
                            <select class="form-select border-secondary-subtle shadow-sm" id="filterCourse" onchange="filterFatherAttendance()">
                                <option value="">Todos los Cursos</option>
                                @foreach($attendances->unique('course_name') as $row)
                                    <option value="{{ Str::slug($row->course_name) }}">{{ $row->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label text-muted small fw-bold">Periodo Académico</label>
                            <select class="form-select border-secondary-subtle shadow-sm" id="filterPeriod" onchange="filterFatherAttendance()">
                                <option value="">Todos los Periodos</option>
                                @foreach($attendances->unique('period_name') as $row)
                                    <option value="{{ Str::slug($row->period_name) }}">{{ $row->period_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="fatherAttendanceGrid">
                    @foreach($attendances as $row)
                        @php
                            $totalClases = $row->total_clases_tomadas > 0 ? $row->total_clases_tomadas : 1;
                            $pctPresente = ($row->total_presentes / $totalClases) * 100;
                            $pctTardanza = ($row->total_tardanzas / $totalClases) * 100;
                            $pctAusente = ($row->total_ausentes / $totalClases) * 100;
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 father-attendance-card" 
                             data-student-id="{{ $row->idstudent }}"
                             data-course="{{ Str::slug($row->course_name) }}"
                             data-period="{{ Str::slug($row->period_name) }}">
                            
                            <div class="card h-100 shadow-sm border border-light-subtle rounded-3 overflow-hidden">
                                
                                <div class="card-header bg-white pt-3 pb-2 px-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-light text-secondary border small px-2">
                                            {{ $row->period_name }}
                                        </span>
                                        <span class="badge bg-dark-subtle text-dark small px-2">
                                            Aula: {{ $row->section_name }}
                                        </span>
                                    </div>
                                    <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                        <i class="material-icons align-middle text-secondary" style="font-size: 14px;">person</i> {{ $row->student_name }}
                                    </small>
                                    <h5 class="fw-bold text-dark mb-0 text-truncate" title="{{ $row->course_name }}">
                                        {{ $row->course_name }}
                                    </h5>
                                </div>

                                <div class="card-body py-2 px-3">
                                    <div class="p-3 rounded-3 bg-light border border-light-subtle">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small text-muted fw-bold">Progreso de Asistencias</span>
                                            <span class="badge bg-secondary rounded-1">{{ $row->total_clases_tomadas }} Evaluaciones</span>
                                        </div>

                                        <div class="progress mb-3" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pctPresente }}%" title="Presentes"></div>
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctTardanza }}%" title="Tardanzas"></div>
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pctAusente }}%" title="Ausentes"></div>
                                        </div>

                                        <div class="row g-1 text-center small">
                                            <div class="col-4 border-end">
                                                <span class="d-block text-success fw-bold fs-6">{{ (int)$row->total_presentes }}</span>
                                                <span class="text-muted" style="font-size: 11px;">Presentes</span>
                                            </div>
                                            <div class="col-4 border-end">
                                                <span class="d-block text-warning fw-bold fs-6">{{ (int)$row->total_tardanzas }}</span>
                                                <span class="text-muted" style="font-size: 11px;">Tardanzas</span>
                                            </div>
                                            <div class="col-4">
                                                <span class="d-block text-danger fw-bold fs-6">{{ (int)$row->total_ausentes }}</span>
                                                <span class="text-muted" style="font-size: 11px;">Ausentes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-light py-2 px-3 border-top-0">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-1 btn-view-dates rounded-2 py-1.5"
                                            data-section="{{ $row->idsection }}"
                                            data-student="{{ $row->idstudent }}"
                                            data-student-name="{{ $row->student_name }}"
                                            data-course-name="{{ $row->course_name }}">
                                        <i class="material-icons fs-5">calendar_month</i> <small class="fw-bold">Ver Historial por Fechas</small>
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
                        <h5 class="fw-bold mb-1">Sin estudiantes activos</h5>
                        <p class="mb-0 text-muted small">No posee alumnos o tutelados asociados a su cuenta o no se han cargado asistencias válidas.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade" id="studentDatesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons align-middle">calendar_month</i> 
                        HISTORIAL: <span id="modal_course_title" class="fw-bold"></span>
                        <br><small>Estudiante: <span id="modal_student_title" class="fw-bold text-warning"></span></small>
                        <br><small>Sección: <span id="modal_section_title"></span></small>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle" id="tablaFechasDetalle">
                            <thead class="table-light">
                                <tr>
                                    <th>FECHA DE CLASE</th>
                                    <th class="text-center">ESTADO DIARIO</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Se procesa dinámicamente vía AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script type="text/javascript">
        // Lógica de filtrado en tiempo real en la pantalla inicial
        function filterFatherAttendance() {
            let student = document.getElementById('filterStudent').value;
            let course = document.getElementById('filterCourse').value;
            let period = document.getElementById('filterPeriod').value;

            let cards = document.querySelectorAll('.father-attendance-card');

            cards.forEach(card => {
                let cardStudent = card.getAttribute('data-student-id');
                let cardCourse = card.getAttribute('data-course');
                let cardPeriod = card.getAttribute('data-period');

                let mStudent = (student === "" || cardStudent === student);
                let mCourse  = (course === ""  || cardCourse === course);
                let mPeriod  = (period === ""  || cardPeriod === period);

                if (mStudent && mCourse && mPeriod) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        }

    </script>
    @endpush
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            const infoModal = new bootstrap.Modal('#studentDatesModal');

            $('.btn-view-dates').on('click', function() {
                const idsection = $(this).data('section');
                const idstudent = $(this).data('student');
                const studentName = $(this).data('student-name');
                const courseName = $(this).data('course-name');
                const sectionName = $(this).data('section-name');

                $('#modal_course_title').text(courseName);
                $('#modal_student_title').text(studentName);
                $('#modal_section_title').text(sectionName);

                let tbody = $('#tablaFechasDetalle tbody');
                tbody.empty().append('<tr><td colspan="2" class="text-center py-3">Buscando el historial...</td></tr>');

                infoModal.show();

                // Llamada AJAX al nuevo controlador del apoderado pasando sección y estudiante
                $.get("{{ route('father.attendance.details') }}", { idsection: idsection, idstudent: idstudent }, function(data) {
                    tbody.empty();

                    if(data.length === 0) {
                        tbody.append('<tr><td colspan="2" class="text-center py-2 text-muted">No se registran firmas de asistencia en este curso todavía.</td></tr>');
                        return;
                    }

                    data.forEach(item => {
                        let fechaOriginal = new Date(item.attendance_date + 'T00:00:00');
                        let fechaFormateada = fechaOriginal.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });

                        let labelStyle = '';
                        if(item.status === 'PRESENTE') labelStyle = 'badge bg-success text-white px-3 py-2';
                        else if(item.status === 'AUSENTE') labelStyle = 'badge bg-danger text-white px-3 py-2';
                        else labelStyle = 'badge bg-warning text-dark px-3 py-2';

                        tbody.append(`
                            <tr>
                                <td class="py-2"><strong>${fechaFormateada}</strong></td>
                                <td class="text-center py-2">
                                    <span class="${labelStyle}">${item.status}</span>
                                </td>
                            </tr>
                        `);
                    });
                }).fail(function() {
                    tbody.html('<tr><td colspan="2" class="text-center text-danger py-2">Error al intentar recuperar la información del servidor.</td></tr>');
                });
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>