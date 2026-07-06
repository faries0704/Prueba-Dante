<x-layouts.app-layout title="Nueva Constancia de Notas">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gradeCertificates.index') }}">Constancias de Notas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nueva</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Emitir Constancia de Notas</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios. La constancia se genera con las notas del periodo más reciente del alumno.
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0" style="padding-left: 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('gradeCertificates.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Alumno <span class="text-danger">*</span></label>
                                    <select name="idstudent" id="idstudent" class="form-control" required>
                                        <option value="">-- Seleccione un alumno --</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->idstudent }}" {{ old('idstudent') == $student->idstudent ? 'selected' : '' }}>
                                                {{ $student->full_name }} (DNI: {{ $student->dni }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted"><span class="text-danger">Se validará que el alumno tenga calificaciones registradas.</span></small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Fecha de Emisión <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Motivo / Fines de la constancia <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="purpose" value="{{ old('purpose') }}" required maxlength="255" placeholder="ejm: Postulación a beca de estudios" list="purpose-list">
                                    <datalist id="purpose-list">
                                        <option value="Trámites diversos">
                                        <option value="Postulación a beca de estudios">
                                        <option value="Traslado a otra institución educativa">
                                        <option value="Presentación ante entidad pública">
                                    </datalist>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Observaciones (opcional)</label>
                                    <textarea class="form-control" name="observations" rows="2" maxlength="1000" placeholder="Información adicional para el documento">{{ old('observations') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Vista previa de las notas del alumno (13.4) --}}
                        <div id="student-preview" class="d-none">
                            <hr>
                            <h5>Notas que llevará el documento:</h5>
                            <div id="student-preview-alert" class="alert alert-danger d-none"></div>
                            <div id="student-preview-warnings" class="alert alert-warning d-none"></div>
                            <div id="student-preview-data" class="table-responsive d-none">
                                <p><strong>Alumno:</strong> <span id="pv-name"></span> — <strong>DNI:</strong> <span id="pv-dni"></span> — <strong>Periodo:</strong> <span id="pv-period"></span></p>
                                <table class="table table-bordered" style="max-width: 600px;">
                                    <thead class="text-primary">
                                        <tr><th>Curso</th><th>Promedio</th></tr>
                                    </thead>
                                    <tbody id="pv-courses"></tbody>
                                    <tfoot>
                                        <tr><th style="text-align:right;">Promedio general:</th><th id="pv-general"></th></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="btn-generate" class="btn btn-success text-white">Emitir Constancia</button>
                                <a class="btn btn-danger text-white" href="{{ route('gradeCertificates.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var studentDataUrl = "{{ url('/constancias-notas/datos-alumno') }}";

            $('#idstudent').on('change', function() {
                var id = $(this).val();
                var $preview = $('#student-preview');
                var $alert = $('#student-preview-alert');
                var $warnings = $('#student-preview-warnings');
                var $data = $('#student-preview-data');
                var $btn = $('#btn-generate');

                if (!id) {
                    $preview.addClass('d-none');
                    $btn.prop('disabled', false);
                    return;
                }

                $.get(studentDataUrl + '/' + id)
                    .done(function(res) {
                        $preview.removeClass('d-none');
                        $warnings.addClass('d-none');

                        if (!res.ok) {
                            $alert.text(res.message).removeClass('d-none');
                            $data.addClass('d-none');
                            $btn.prop('disabled', true);
                            return;
                        }

                        $alert.addClass('d-none');
                        $data.removeClass('d-none');
                        $btn.prop('disabled', false);

                        $('#pv-name').text(res.student.full_name);
                        $('#pv-dni').text(res.student.dni);
                        $('#pv-period').text(res.report.period_name || '—');

                        var rows = '';
                        res.report.courses.forEach(function(c) {
                            var avg = (c.average !== null && c.average !== undefined) ? Number(c.average).toFixed(2) : '—';
                            rows += '<tr><td>' + c.course + '</td><td>' + avg + '</td></tr>';
                        });
                        $('#pv-courses').html(rows);

                        var general = (res.report.general_average !== null && res.report.general_average !== undefined)
                            ? Number(res.report.general_average).toFixed(2) : '—';
                        $('#pv-general').text(general);

                        // 13.7: advertencias de integridad de la información
                        if (res.warnings && res.warnings.length > 0) {
                            $warnings.html('<strong>Advertencia:</strong> ' + res.warnings.join(' ')).removeClass('d-none');
                        }
                    })
                    .fail(function() {
                        $preview.removeClass('d-none');
                        $alert.text('No se pudo obtener la información del alumno.').removeClass('d-none');
                        $data.addClass('d-none');
                        $btn.prop('disabled', true);
                    });
            });

            // Si viene de una validación fallida con alumno seleccionado
            if ($('#idstudent').val()) {
                $('#idstudent').trigger('change');
            }
        });
    </script>
    @endpush

</x-layouts.app-layout>
