<x-layouts.app-layout title="Registrar Calificaciones">
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('grades.index') }}">Calificaciones</a></li>
                        <li class="breadcrumb-item active">Nueva Calificación</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Filtros para Registro de Notas</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tipo de Evaluación <span class="text-danger">*</span></label>
                                <select class="form-select border-warning" id="tipo_evaluacion">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            
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
                                <select class="form-select" id="grado" disabled><option value="">Seleccione...</option></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Subgrado</label>
                                <select class="form-select" id="subgrado" disabled><option value="">Seleccione...</option></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Curso</label>
                                <select class="form-select" id="curso" disabled><option value="">Seleccione...</option></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sección <span class="text-danger">*</span></label>
                                <select class="form-select" id="seccion" disabled><option value="">Seleccione Sección</option></select>
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
                <div class="modal-header" style="background-color: #005187;" >
                    <h5 class="modal-title  text-white ">
                        <i class="material-icons">edit_note</i> 
                        NOTAS: <span id="span_curso"></span> - <span id="span_evaluacion"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form action="{{ route('grades.store') }}" method="POST" id="formGrades">
                    @csrf
                    <input type="hidden" name="idcourse" id="hidden_course">
                    <input type="hidden" name="idevaluation_type" id="hidden_eval_type">
                    <input type="hidden" name="idsection" id="hidden_section">

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaAlumnosGrades">
                                <thead class="table-light">
                                    <tr>
                                        <th>FOTO</th>
                                        <th>ALUMNO</th>
                                        <th width="150">NOTA (0-20)</th>
                                    </tr>
                                </thead>
                                <tbody>{{-- AJAX --}}</tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
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

            $.get('/get-evaluation-types', data => {
                data.forEach(d => $('#tipo_evaluacion').append(`<option value="${d.idevaluation_type}">${d.evaluation_name}</option>`));
            });
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

            $('#seccion').change(function() {
                let idSec = $(this).val();
                let idCur = $('#curso').val();
                let idEval = $('#tipo_evaluacion').val();

                if(!idEval) {
                    Swal.fire('Atención', 'Seleccione primero el tipo de evaluación', 'warning');
                    $(this).val('');
                    return;
                }
                
                $('#hidden_section').val(idSec);
                $('#hidden_course').val(idCur);
                $('#hidden_eval_type').val(idEval);
                $('#span_curso').text($("#curso option:selected").text());
                $('#span_evaluacion').text($("#tipo_evaluacion option:selected").text());

                let tbody = $('#tablaAlumnosGrades tbody');
                tbody.empty().append('<tr><td colspan="3" class="text-center">Cargando...</td></tr>');
                
                gradesModal.show();

                $.get('/get-students-section/' + idSec, function(data) {
                    tbody.empty();
                    data.forEach(alumno => {
                        let foto = alumno.user && alumno.user.photo ? alumno.user.photo : 'student.png';
                        tbody.append(`
                            <tr>
                                <td><img src="/backend/img/subidas/${foto}" width="50" height="50" class="rounded-circle"></td>
                                <td>${alumno.full_name}</td>
                                <td>
                                    <input type="number" name="notas[${alumno.idstudent}]" 
                                           class="form-control" min="0" max="20" step="0.1" required>
                                </td>
                            </tr>
                        `);
                    });
                });
            });

            $('#formGrades').on('submit', function(e) {

    e.preventDefault();

    $.ajax({

        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),

        success: function(response) {

            gradesModal.hide();

            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Notas registradas correctamente'
            }).then(() => {

                window.location.href = "{{ route('grades.index') }}";

            });

        },

        error: function(xhr) {

            console.log(xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un problema al guardar'
            });

        }

    });

});
        });
    </script>
    @endpush
</x-layouts.app-layout>