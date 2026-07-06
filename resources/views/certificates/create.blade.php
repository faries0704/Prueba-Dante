<x-layouts.app-layout title="Nueva Constancia">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Constancias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nueva</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Generar Constancia de Estudios</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
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

                    <form action="{{ route('certificates.store') }}" method="POST" autocomplete="off">
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
                                    <small class="form-text text-muted"><span class="text-danger">Se validará que el alumno tenga matrícula activa.</span></small>
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
                                    <input type="text" class="form-control" name="purpose" value="{{ old('purpose') }}" required maxlength="255" placeholder="ejm: Trámites de beca escolar" list="purpose-list">
                                    <datalist id="purpose-list">
                                        <option value="Trámites diversos">
                                        <option value="Trámites de beca escolar">
                                        <option value="Traslado a otra institución educativa">
                                        <option value="Presentación ante entidad pública">
                                        <option value="Sustento de programas sociales">
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

                        {{-- HU-12.8: Visualización previa de los datos del alumno --}}
                        <div id="student-preview" class="d-none">
                            <hr>
                            <h5>Datos que llevará el documento:</h5>
                            <div id="student-preview-alert" class="alert alert-danger d-none"></div>
                            <div id="student-preview-data" class="table-responsive d-none">
                                <table class="table table-bordered" style="max-width: 700px;">
                                    <tbody>
                                        <tr><th style="width:220px;">Alumno</th><td id="pv-name"></td></tr>
                                        <tr><th>DNI</th><td id="pv-dni"></td></tr>
                                        <tr><th>Grado / Subgrado</th><td id="pv-degree"></td></tr>
                                        <tr><th>Sección</th><td id="pv-section"></td></tr>
                                        <tr><th>Periodo Escolar</th><td id="pv-period"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="btn-generate" class="btn btn-success text-white">Generar Constancia</button>
                                <a class="btn btn-danger text-white" href="{{ route('certificates.index') }}">Cancelar</a>
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
            var studentDataUrl = "{{ url('/constancias/datos-alumno') }}";

            $('#idstudent').on('change', function() {
                var id = $(this).val();
                var $preview = $('#student-preview');
                var $alert = $('#student-preview-alert');
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

                        if (!res.ok) {
                            $alert.text(res.message).removeClass('d-none');
                            $data.addClass('d-none');
                            $btn.prop('disabled', true);
                            return;
                        }

                        $alert.addClass('d-none');
                        $data.removeClass('d-none');
                        $btn.prop('disabled', false);

                        var degree = (res.academic.degree || '—');
                        if (res.academic.subgrade) degree += ' — ' + res.academic.subgrade;

                        $('#pv-name').text(res.student.full_name);
                        $('#pv-dni').text(res.student.dni);
                        $('#pv-degree').text(degree);
                        $('#pv-section').text(res.academic.section || '—');
                        $('#pv-period').text(res.academic.period || '—');
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
