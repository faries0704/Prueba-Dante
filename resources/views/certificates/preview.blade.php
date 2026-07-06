<x-layouts.app-layout title="Vista Previa de Constancia">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Constancias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vista Previa</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">
                        Vista Previa — {{ $certificate->certificate_code }}
                        @if($certificate->status == 1)
                            <span class="badge badge-success">Vigente</span>
                        @else
                            <span class="badge badge-danger">Anulada</span>
                        @endif
                    </h4>
                </div>

                <div class="card-content">
                    <div class="mb-3" style="margin-bottom: 20px;">
                        @if($certificate->status == 1)
                            <a href="{{ route('certificates.print', $certificate->idcertificate) }}" target="_blank" class="btn btn-success text-white">
                                <i class="material-icons" style="vertical-align: middle;">picture_as_pdf</i>
                                Imprimir / Guardar PDF
                            </a>
                        @endif
                        <a href="{{ route('certificates.index') }}" class="btn btn-danger text-white">Volver al listado</a>
                    </div>

                    {{-- HU-12.8: Visualización previa del documento --}}
                    <div style="background:#eee; padding: 25px 10px;">
                        @include('certificates._document')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>
