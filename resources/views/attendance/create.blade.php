<x-layouts.app-layout title="Nueva Asistencia">
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Asistencia</a></li>
                        <li class="breadcrumb-item active">Nueva</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Filtros de Asistencia</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Asistencia <span class="text-danger">*</span></label>
                                <input type="date" id="input_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Periodo</label>
                                <select class="form-select" id="periodo">
                                    <option value="">Seleccione Periodo</option>
                                    @foreach($periods as $p)
                                        <option value="{{ $p->idperiod }}">{{ $p->period_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Grado</label>
                                <select class="form-select" id="grado" disabled>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Subgrado</label>
                                <select class="form-select" id="subgrado" disabled>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Curso</label>
                                <select class="form-select" id="curso" disabled>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sección <span class="text-danger">*</span></label>
                                <select class="form-select" id="seccion" disabled>
                                    <option value="">Seleccione Sección</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header"style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons">assignment_turned_in</i> 
                        REGISTRO DE ASISTENCIA: <span id="span_seccion"></span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="{{ route('attendance.store') }}" method="POST" id="formAsistencia">
                    @csrf
                    <input type="hidden" name="attendance_date" id="hidden_date">
                    <input type="hidden" name="seccion" id="hidden_section">

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaAlumnosModal">
                                <thead class="table-light">
                                    <tr>
                                        <th>FOTO</th>
                                        <th>NOMBRE DEL ALUMNO</th>
                                        <th width="200">ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Carga via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn text-white px-4" style="background-color: #005187;">GUARDAR ASISTENCIAS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Instancia del modal de Bootstrap 5
            const attendanceModal = new bootstrap.Modal('#attendanceModal');

            // --- Lógica de Selects Encadenados ---
            function resetSelects(ids) {
                ids.forEach(id => $(id).empty().append('<option value="">Seleccione...</option>').prop('disabled', true));
            }

            $('#periodo').change(function() {
                let id = $(this).val();
                resetSelects(['#grado', '#subgrado', '#curso', '#seccion']);
                if(id) $.get('/get-grades/' + id, data => {
                    $('#grado').prop('disabled', false);
                    data.forEach(d => $('#grado').append(`<option value="${d.iddegree}">${d.degree_name}</option>`));
                });
            });

            $('#grado').change(function() {
                let id = $(this).val();
                resetSelects(['#subgrado', '#curso', '#seccion']);
                if(id) $.get('/get-subgrades/' + id, data => {
                    $('#subgrado').prop('disabled', false);
                    data.forEach(d => $('#subgrado').append(`<option value="${d.idsubgrade}">${d.subgrade_name}</option>`));
                });
            });

            $('#subgrado').change(function() {
                let id = $(this).val();
                resetSelects(['#curso', '#seccion']);
                if(id) $.get('/get-courses/' + id, data => {
                    $('#curso').prop('disabled', false);
                    data.forEach(d => $('#curso').append(`<option value="${d.idcourse}">${d.course_name}</option>`));
                });
            });

            $('#curso').change(function() {
                let id = $(this).val();
                resetSelects(['#seccion']);
                if(id) $.get('/get-sections/' + id, data => {
                    $('#seccion').prop('disabled', false);
                    data.forEach(d => $('#seccion').append(`<option value="${d.idsection}">${d.section_name}</option>`));
                });
            });

            // --- Abrir Modal y Cargar Alumnos ---
            $('#seccion').change(function() {
                let idSec = $(this).val();
                let nomSec = $("#seccion option:selected").text();
                let fecha = $('#input_date').val();

                if(!idSec) return;

                $('#hidden_date').val(fecha);
                $('#hidden_section').val(idSec);
                $('#span_seccion').text(nomSec);

                let tbody = $('#tablaAlumnosModal tbody');
                tbody.empty().append('<tr><td colspan="3" class="text-center">Cargando alumnos...</td></tr>');
                
                attendanceModal.show();

                $.get('/get-students-section/' + idSec, function(data) {
                    tbody.empty();
                    if(data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="text-center">No hay alumnos inscritos en esta sección</td></tr>');
                        return;
                    }

                    data.forEach(alumno => {
                        let foto = alumno.user && alumno.user.photo ? alumno.user.photo : 'student.png';
                        tbody.append(`
                            <tr>
                                <td><img src="/backend/img/subidas/${foto}" width="90" height="90" class="rounded shadow-sm"></td>
                                <td>${alumno.full_name}</td>
                                <td>
                                    <select name="asistencias[${alumno.idstudent}]" class="form-select border-primary shadow-sm" required>
                                        <option value="PRESENTE">Presente</option>
                                        <option value="AUSENTE">Ausente</option>
                                        <option value="TARDANZA">Tardanza</option>
                                    </select>
                                </td>
                            </tr>
                        `);
                    });
                });
            });

            $('#formAsistencia').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function() {
                        attendanceModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registrado!',
                            text: 'Asistencia procesada correctamente.',
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('attendance.index') }}";
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'No se pudo completar el registro.', 'error');
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>