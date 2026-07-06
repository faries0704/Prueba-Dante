<x-layouts.app-layout title="Constancias de Notas">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gradeCertificates.index') }}">Constancias de Notas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mostrar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Constancias de Notas</h4>
                    <a href="{{ route('gradeCertificates.create') }}" class="btn btn-danger text-white">
                        <i class='material-icons' data-toggle='tooltip' title='Add'>add</i>
                    </a>
                </div>

                <div class="card-content table-responsive">
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    @if($certificates->count() > 0)
                        <table class="table table-hover" id="example">
                            <thead class="text-primary">
                                <tr>
                                    <th>Código</th>
                                    <th>Alumno</th>
                                    <th>DNI</th>
                                    <th>Periodo</th>
                                    <th>Motivo</th>
                                    <th>Fecha de Emisión</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($certificates as $certificate)
                                    <tr>
                                        <td>{{ $certificate->certificate_code }}</td>
                                        <td>{{ $certificate->student->full_name ?? '—' }}</td>
                                        <td>{{ $certificate->student->dni ?? '—' }}</td>
                                        <td><small>{{ $certificate->period->period_name ?? '—' }}</small></td>
                                        <td><small>{{ $certificate->purpose }}</small></td>
                                        <td><small>{{ $certificate->issue_date }}</small></td>
                                        <td>
                                            @if($certificate->status == 1)
                                                <span class="badge badge-success">Vigente</span>
                                            @else
                                                <span class="badge badge-danger">Anulada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('gradeCertificates.preview', $certificate->idgradecertificate) }}" class="btn btn-info text-white">
                                                <i class='material-icons' data-toggle='tooltip' title='Vista previa'>visibility</i>
                                            </a>

                                            @if($certificate->status == 1)
                                                <a href="{{ route('gradeCertificates.print', $certificate->idgradecertificate) }}" target="_blank" class="btn btn-success text-white">
                                                    <i class='material-icons' data-toggle='tooltip' title='Imprimir / PDF'>picture_as_pdf</i>
                                                </a>

                                                <a href="{{ route('gradeCertificates.showDelete', $certificate->idgradecertificate) }}" class="btn btn-danger text-white">
                                                    <i class='material-icons' data-toggle='tooltip' title='Anular'>delete_forever</i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning" style="position: relative; margin-top: 14px; margin-bottom: 0px;">
                            <strong>No hay constancias de notas emitidas!</strong>
                        </div>
                    @endif
                </div>
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

</x-layouts.app-layout>
