<x-layouts.app-layout title="Anular Constancia de Notas">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gradeCertificates.index') }}">Constancias de Notas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Anular</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de anular la constancia de notas?</h4>
                    <p class="text-muted">
                        Constancia: <strong>{{ $certificate->certificate_code }}</strong> —
                        Alumno: <strong>{{ $certificate->student->full_name ?? '—' }}</strong>
                    </p>
                </div>

                <div class="card-content table-responsive">
                    <form action="{{ route('gradeCertificates.destroy', $certificate->idgradecertificate) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="alert alert-danger">
                            <strong>Atención!</strong> Esta acción cambiará el estado de la constancia a Anulada y ya no podrá imprimirse.
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Anular Ahora</button>
                                <a class="btn btn-danger text-white" href="{{ route('gradeCertificates.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>
