<x-layouts.app-layout title="Nueva Asistencia Docente">
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Asistencia</a></li>
                        <li class="breadcrumb-item active">Nueva (Docente)</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Filtros de Asistencia - Mis Cursos</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Asistencia <span class="text-danger">*</span></label>
                                <input type="date" id="input_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Curso Asignado</label>
                                <select class="form-select" id="curso">
                                    <option value="">Seleccione un curso...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->idcourse }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Sección Disponible <span class="text-danger">*</span></label>
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
                <div class="modal-header" style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons">assignment_turned_in</i> 
                        REGISTRO DE ASISTENCIA: <span id="span_seccion"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
                </div>
                
                <form action="{{ route('teacher.attendance.store') }}" method="POST" id="formAsistencia">
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
                                    {{-- Carga dinámica vía AJAX --}}
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
            const attendanceModal = new bootstrap.Modal('#attendanceModal');

            // 1. Al cambiar el Curso, buscar sus Secciones asignadas
            $('#curso').change(function() {
                let idCourse = $(this).val();
                let seccionSelect = $('#seccion');
                
                seccionSelect.empty().append('<option value="">Seleccione Sección</option>').prop('disabled', true);
                
                if(idCourse) {
                    $.get('/teacher/attendance/get-sections/' + idCourse, function(data) {
                        seccionSelect.prop('disabled', false);
                        data.forEach(d => {
                            seccionSelect.append(`<option value="${d.idsection}">${d.section_name}</option>`);
                        });
                    });
                }
            });

            // 2. Al cambiar la Sección, abrir el Modal y cargar Alumnos matriculados
            $('#seccion').change(function() {
                let idSec = $(this).val();
                let nomSec = $("#seccion option:selected").text();
                let fecha = $('#input_date').val();

                if(!idSec) return;

                $('#hidden_date').val(fecha);
                $('#hidden_section').val(idSec);
                $('#span_seccion').text(nomSec);

                let tbody = $('#tablaAlumnosModal tbody');
                tbody.empty().append('<tr><td colspan="3" class="text-center">Cargando alumnos de la sección...</td></tr>');
                
                attendanceModal.show();

                // Petición AJAX al nuevo método del controlador de profesores
                $.get('/teacher/attendance/get-students/' + idSec, function(data) {
                    tbody.empty();
                    if(data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="text-center">No hay alumnos inscritos activos en esta sección</td></tr>');
                        return;
                    }

                    data.forEach(alumno => {
                        let foto = alumno.photo ? alumno.photo : 'student.png';
                        tbody.append(`
                            <tr>
                                <td><img src="/backend/img/subidas/${foto}" width="65" height="65" class="rounded shadow-sm" style="object-fit: cover;"></td>
                                <td class="align-middle"><strong>${alumno.full_name}</strong></td>
                                <td class="align-middle">
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

            // 3. Envío del formulario del Modal por AJAX
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
                            text: 'Asistencia de la sección guardada correctamente.',
                            timer: 2000
                        }).then(() => {
                            // Limpiar el combo de sección para permitir un nuevo registro si se requiere
                            window.location.href = "{{ route('teacher.attendance.index') }}";
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'No se pudo completar el registro de asistencias.', 'error');
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>