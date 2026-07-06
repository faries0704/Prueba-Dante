<x-layouts.app-layout title="Nueva Calificación Docente">
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.grades.index') }}">Calificaciones</a></li>
                        <li class="breadcrumb-item active">Nueva</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Filtros de Calificaciones - Mis Cursos</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de Evaluación <span class="text-danger">*</span></label>
                                <select class="form-select " id="evaluation_type">
                                    <option value="">Seleccione tipo...</option>
                                    @foreach($evaluationTypes as $type)
                                        <option value="{{ $type->idevaluation_type }}">{{ $type->evaluation_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Curso Asignado <span class="text-danger">*</span></label>
                                <select class="form-select " id="curso">
                                    <option value="">Seleccione un curso...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->idcourse }}">{{ $course->course_name }} ({{ $course->semester_name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Sección Disponible <span class="text-danger">*</span></label>
                                <select class="form-select " id="seccion" disabled>
                                    <option value="">Seleccione Sección</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gradesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #005187;">
                    <h5 class="modal-title text-white">
                        <i class="material-icons align-middle">edit_note</i> 
                        REGISTRO DE CALIFICACIONES: <span id="span_seccion" class="fw-bold"></span>
                        <br><small>Evaluación: <span id="span_evaluacion" class="text-warning fw-bold"></span></small>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form action="{{ route('teacher.grades.update') }}" method="POST" id="formCalificaciones">
                    @csrf
                    <input type="hidden" name="idcourse" id="hidden_course">
                    <input type="hidden" name="idsection" id="hidden_section">
                    <input type="hidden" name="idevaluation_type" id="hidden_evaluation_type">

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaAlumnosModal">
                                <thead class="table-light">
                                    <tr>
                                        <th width="90">FOTO</th>
                                        <th>NOMBRE DEL ALUMNO</th>
                                        <th width="200">CALIFICACIÓN (0 - 20)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Carga dinámica vía AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn text-white px-4" style="background-color: #005187;">GUARDAR CALIFICACIONES</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const gradesModal = new bootstrap.Modal('#gradesModal');

            // 1. Al cambiar el Curso, buscar sus Secciones asignadas
            $('#curso').change(function() {
                let idCourse = $(this).val();
                let seccionSelect = $('#seccion');
                
                seccionSelect.empty().append('<option value="">Seleccione Sección</option>').prop('disabled', true);
                
                if(idCourse) {
                    $.get('/teacher/grades/get-sections/' + idCourse, function(data) {
                        seccionSelect.prop('disabled', false);
                        data.forEach(d => {
                            seccionSelect.append(`<option value="${d.idsection}">${d.section_name}</option>`);
                        });
                    });
                }
            });

            // 2. Al cambiar la Sección, validar Filtros completos y abrir el Modal
            $('#seccion').change(function() {
                let idSec = $(this).val();
                let idCourse = $('#curso').val();
                let idEvalType = $('#evaluation_type').val();
                
                let nomSec = $("#seccion option:selected").text();
                let nomEval = $("#evaluation_type option:selected").text();

                // Validación preventiva
                if (!idEvalType) {
                    Swal.fire('Atención', 'Por favor, seleccione primero el Tipo de Evaluación.', 'warning');
                    $(this).val(''); // Resetear combo
                    return;
                }

                if(!idSec || !idCourse) return;

                // Seteamos los inputs ocultos del formulario
                $('#hidden_course').val(idCourse);
                $('#hidden_section').val(idSec);
                $('#hidden_evaluation_type').val(idEvalType);
                
                // Textos informativos del header del modal
                $('#span_seccion').text(nomSec);
                $('#span_evaluacion').text(nomEval);

                let tbody = $('#tablaAlumnosModal tbody');
                tbody.empty().append('<tr><td colspan="3" class="text-center py-3">Cargando lista de alumnos y registros previos...</td></tr>');
                
                gradesModal.show();

                // Petición AJAX al método getEditData que ya busca alumnos inscritos y sus notas si existen
                $.get("{{ route('teacher.grades.getEditData') }}", {
                    idsection: idSec,
                    idcourse: idCourse,
                    idevaluation_type: idEvalType
                }, function(data) {
                    tbody.empty();
                    if(data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="text-center">No hay alumnos inscritos activos en esta sección</td></tr>');
                        return;
                    }

                    data.forEach(alumno => {
                        let foto = alumno.photo ? `/backend/img/subidas/${alumno.photo}` : '/backend/img/user-default.png';
                        // Validar si ya tiene nota o llega nulo
                        let notaActual = alumno.grade !== null ? alumno.grade : '';

                        tbody.append(`
                            <tr>
                                <td>
                                    <img src="${foto}" width="50" height="50" class="rounded-circle shadow-sm" onerror="this.src='/backend/img/user-default.png'">
                                </td>
                                <td><strong>${alumno.full_name}</strong></td>
                                <td>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="20" 
                                           class="form-control border-primary shadow-sm input-nota" 
                                           name="notas[${alumno.idstudent}]" 
                                           value="${notaActual}" 
                                           placeholder="0.00">
                                </td>
                            </tr>
                        `);
                    });
                }).fail(function() {
                    tbody.html('<tr><td colspan="3" class="text-center text-danger">Error al sincronizar con el servidor.</td></tr>');
                });
            });

            // 3. Envío del formulario masivo por AJAX
            $('#formCalificaciones').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if(res.success) {
                            gradesModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: 'Las calificaciones se procesaron correctamente.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Redireccionar al panel principal de notas del docente
                                window.location.href = "{{ route('teacher.grades.index') }}";
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Ocurrió un problema al guardar las notas.', 'error');
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>