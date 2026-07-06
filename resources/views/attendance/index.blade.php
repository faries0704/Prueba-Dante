<x-layouts.app-layout title="Resumen de Asistencias">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Asistencias</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #005187;">
                        <i class="material-icons align-middle fs-3 me-1">fact_check</i> Resumen de Asistencias
                    </h3>
                    <p class="text-muted small mb-0">Panel de control administrativo para supervisar el ausentismo y puntualidad por aula.</p>
                </div>
                <a href="{{ route('attendance.create') }}" class="btn text-white px-4 shadow-sm d-flex align-items-center" style="background-color: #005187;">
                    <i class="material-icons me-1">add_circle</i> Tomar Asistencia
                </a>
            </div>

            @if($attendances->count() > 0)
                
                <div class="card border-0 shadow-sm p-4 mb-4 bg-light rounded-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="material-icons me-2 text-secondary fs-5">filter_list</i>
                        <span class="text-secondary fw-bold small text-uppercase">Buscador Avanzado y Filtros</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label text-muted small fw-bold">Periodo</label>
                            <select class="form-select form-select-sm border-secondary-subtle" id="filterPeriod" onchange="filterAttendanceCards()">
                                <option value="">Todos</option>
                                @foreach($attendances->unique('period_name') as $p)
                                    <option value="{{ Str::slug($p->period_name) }}">{{ $p->period_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label text-muted small fw-bold">Grado</label>
                            <select class="form-select form-select-sm border-secondary-subtle" id="filterDegree" onchange="filterAttendanceCards()">
                                <option value="">Todos</option>
                                @foreach($attendances->unique('degree_name') as $d)
                                    <option value="{{ Str::slug($d->degree_name) }}">{{ $d->degree_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label text-muted small fw-bold">Subgrado</label>
                            <select class="form-select form-select-sm border-secondary-subtle" id="filterSubgrade" onchange="filterAttendanceCards()">
                                <option value="">Todos</option>
                                @foreach($attendances->unique('subgrade_name') as $sub)
                                    <option value="{{ Str::slug($sub->subgrade_name) }}">{{ $sub->subgrade_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Curso</label>
                            <select class="form-select form-select-sm border-secondary-subtle" id="filterCourse" onchange="filterAttendanceCards()">
                                <option value="">Todos los Cursos</option>
                                @foreach($attendances->unique('course_name') as $c)
                                    <option value="{{ Str::slug($c->course_name) }}">{{ $c->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label text-muted small fw-bold">Búsqueda Manual (Sección)</label>
                            <input type="text" class="form-control form-control-sm border-secondary-subtle" id="searchSection" placeholder="Escribe el nombre de sección..." onkeyup="filterAttendanceCards()">
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="attendanceGrid">
                    @foreach($attendances as $row)
                        @php
                            $total = $row->total_students > 0 ? $row->total_students : 1;
                            $pctPresente = ($row->total_presentes / $total) * 100;
                            $pctTardanza = ($row->total_tardanzas / $total) * 100;
                            $pctAusente = ($row->total_ausentes / $total) * 100;
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 attendance-card-item" 
                             data-period="{{ Str::slug($row->period_name) }}"
                             data-degree="{{ Str::slug($row->degree_name) }}"
                             data-subgrade="{{ Str::slug($row->subgrade_name) }}"
                             data-course="{{ Str::slug($row->course_name) }}"
                             data-section="{{ strtolower($row->section_name) }}">
                            
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
                                        <span class="badge bg-light text-secondary border rounded-1" style="font-size: 10px;">{{ $row->degree_name }}</span>
                                        <span class="badge bg-light text-dark border rounded-1" style="font-size: 10px;">{{ $row->subgrade_name }}</span>
                                    </div>
                                </div>

                                <div class="card-body py-2 px-3">
                                    <div class="p-3 rounded-3 bg-light border border-light-subtle">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small text-muted fw-bold">Distribución del Aula</span>
                                            <span class="badge bg-dark rounded-1">{{ $row->total_students }} alumnos</span>
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

                                <div class="card-footer bg-light py-2 px-3 border-top-0 d-flex gap-2">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning w-50 d-flex align-items-center justify-content-center gap-1 btn-edit-attendance rounded-2 py-1.5"
                                            data-date="{{ $row->attendance_date }}" 
                                            data-section="{{ $row->idsection }}"
                                            data-section-name="{{ $row->section_name }}">
                                        <i class="material-icons fs-5">edit</i> <small class="fw-bold">Modificar</small>
                                    </button>

                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info w-50 d-flex align-items-center justify-content-center gap-1 btn-show-attendance rounded-2 py-1.5"
                                            data-date="{{ $row->attendance_date }}" 
                                            data-section="{{ $row->idsection }}"
                                            data-section-name="{{ $row->section_name }}">
                                        <i class="material-icons fs-5">visibility</i> <small class="fw-bold">Ver Alumnos</small>
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
                        <h5 class="fw-bold mb-1">Sin registros de asistencia</h5>
                        <p class="mb-0 text-muted small">No se han guardado asistencias en el sistema.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons align-middle">edit</i> 
                        EDITAR ASISTENCIA: <span id="edit_span_seccion" class="fw-bold"></span>
                        <br><small>Fecha: <span id="edit_span_fecha"></span></small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('attendance.update') }}" method="POST" id="formEditAsistencia">
                    @csrf
                    <input type="hidden" name="attendance_date" id="edit_hidden_date">
                    <input type="hidden" name="seccion" id="edit_hidden_section">

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaEditAlumnosModal">
                                <thead class="table-light">
                                    <tr>
                                        <th>FOTO</th>
                                        <th>NOMBRE DEL ALUMNO</th>
                                        <th width="200">ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn text-white px-4" style="background-color: #005187;">ACTUALIZAR</button>
                    </div>
                </form>
            </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span><strong>Fecha:</strong> <span id="info_span_fecha"></span></span>
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
            paging: true
        });
    });
    </script>
    @endpush
    @push('scripts')
    <script type="text/javascript">
        function filterAttendanceCards() {
            // Captura de los valores seleccionados de los 5 filtros
            let periodVal = document.getElementById('filterPeriod').value;
            let degreeVal = document.getElementById('filterDegree').value;
            let subgradeVal = document.getElementById('filterSubgrade').value;
            let courseVal = document.getElementById('filterCourse').value;
            let sectionVal = document.getElementById('searchSection').value.toLowerCase().trim();
            
            let cards = document.querySelectorAll('.attendance-card-item');

            cards.forEach(card => {
                // Obtención de los valores reales asignados en la tarjeta
                let cardPeriod = card.getAttribute('data-period');
                let cardDegree = card.getAttribute('data-degree');
                let cardSubgrade = card.getAttribute('data-subgrade');
                let cardCourse = card.getAttribute('data-course');
                let cardSection = card.getAttribute('data-section');

                // Verificaciones lógicas condicionales
                let matchesPeriod   = (periodVal === ""   || cardPeriod === periodVal);
                let matchesDegree   = (degreeVal === ""   || cardDegree === degreeVal);
                let matchesSubgrade = (subgradeVal === "" || cardSubgrade === subgradeVal);
                let matchesCourse   = (courseVal === ""   || cardCourse === courseVal);
                let matchesSection  = (sectionVal === ""  || cardSection.includes(sectionVal));

                // Evaluación global: Muestra u oculta la tarjeta correspondiente
                if (matchesPeriod && matchesDegree && matchesSubgrade && matchesCourse && matchesSection) {
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
            const editModal = new bootstrap.Modal('#editAttendanceModal');

            $('.btn-edit-attendance').on('click', function() {
                const idsection = $(this).data('section');
                const date = $(this).data('date');
                const sectionName = $(this).data('section-name');


                $('#edit_hidden_date').val(date);
                $('#edit_hidden_section').val(idsection);
                $('#edit_span_seccion').text(sectionName);
                $('#edit_span_fecha').text(date);

                let tbody = $('#tablaEditAlumnosModal tbody');
                tbody.empty().append('<tr><td colspan="3" class="text-center py-4">Cargando datos...</td></tr>');

                editModal.show();

                $.get("{{ route('attendance.getEditData') }}", { 
                    idsection: idsection, 
                    attendance_date: date 
                }, function(data) {
                    tbody.empty();

                    if(data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="text-center">No se encontraron alumnos.</td></tr>');
                        return;
                    }

                    data.forEach(alumno => {
                        let fotoPath = alumno.photo 
                            ? `/backend/img/subidas/${alumno.photo}` 
                            : `/backend/img/user-default.png`;

                        tbody.append(`
                            <tr>
                                <td>
                                    <img src="${fotoPath}" width="60" height="60" class="rounded-circle shadow-sm" 
                                        onerror="this.src='/backend/img/user-default.png'">
                                </td>
                                <td>${alumno.full_name}</td>
                                <td>
                                    <select name="asistencias[${alumno.idstudent}]" class="form-select border-warning">
                                        <option value="PRESENTE" ${alumno.status === 'PRESENTE' ? 'selected' : ''}>Presente</option>
                                        <option value="AUSENTE" ${alumno.status === 'AUSENTE' ? 'selected' : ''}>Ausente</option>
                                        <option value="TARDANZA" ${alumno.status === 'TARDANZA' ? 'selected' : ''}>Tardanza</option>
                                    </select>
                                </td>
                            </tr>
                        `);
                    });
                }).fail(function() {
                    tbody.html('<tr><td colspan="3" class="text-center text-danger">Error al cargar los datos.</td></tr>');
                });
            });

            $('#formEditAsistencia').on('submit', function(e) {
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
                            title: '¡Actualizado!',
                            text: 'Los registros se actualizaron correctamente.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al guardar los cambios.'
                        });
                    }
                });
            });
        });
    </script>

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
                tbody.empty().append('<tr><td colspan="3" class="text-center">Cargando detalles...</td></tr>');

                infoModal.show();

                $.get("{{ route('attendance.showDetails') }}", { 
                    idsection: idsection, 
                    attendance_date: date 
                }, function(data) {
                    tbody.empty();

                    data.forEach(item => {
                        let foto = item.photo ? `/backend/img/subidas/${item.photo}` : `/backend/img/user-default.png`;
                        
                        // Determinar color del badge
                        let badgeClass = '';
                        if(item.status === 'PRESENTE') badgeClass = 'badge bg-success';
                        else if(item.status === 'AUSENTE') badgeClass = 'badge bg-danger';
                        else badgeClass = 'badge bg-warning text-dark';

                        tbody.append(`
                            <tr>
                                <td>
                                    <img src="${foto}" width="40" height="40" class="rounded-circle" onerror="this.src='/backend/img/user-default.png'">
                                </td>
                                <td>${item.full_name}</td>
                                <td class="text-center">
                                    <span class="${badgeClass}">${item.status}</span>
                                </td>
                            </tr>
                        `);
                    });
                });
            });
        });
    </script>

    @endpush

</x-layouts.app-layout>