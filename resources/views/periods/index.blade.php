<x-layouts.app-layout title="Periodo Escolar">

<div class="main-content">
    <div class="container-fluid">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Panel Control</a></li>
                <li class="breadcrumb-item active" aria-current="page">Periodo escolar</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">date_range</i> Periodos Escolares
                </h3>
                <p class="text-muted small mb-0">Configuración y administración de los ciclos lectivos de la institución.</p>
            </div>
            <a href="{{ route('periods.create') }}" class="btn text-white px-4 shadow-sm d-flex align-items-center" style="background-color: #005187;">
                <i class='material-icons me-1'>add_circle</i> Nuevo Periodo
            </a>
        </div>

        @if($periods->count() > 0)
            
            <div class="row g-4">
                @foreach($periods as $period)
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border border-light-subtle rounded-3 overflow-hidden" 
                             style="transition: transform 0.2s, box-shadow 0.2s;"
                             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.08)'" 
                             onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                            
                            <div class="card-header py-3 d-flex justify-content-between align-items-start bg-light border-bottom">
                                <div class="text-truncate me-2">
                                    <span class="badge bg-secondary mb-1 opacity-75 small">ID: #{{ $period->idperiod }}</span>
                                    <h5 class="card-title fw-bold text-dark mb-0 text-truncate">{{ $period->period_name }}</h5>
                                </div>
                                
                                @if($period->status == 1)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">Activo</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2">Inactivo</span>
                                @endif
                            </div>

                            <div class="card-body p-3 bg-white">
                                <div class="p-2 rounded-2 bg-light-subtle border d-flex align-items-center">
                                    <i class="material-icons text-muted me-2 fs-4">event</i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 11px; text-uppercase; font-weight: 700;">Vigencia del Ciclo</small>
                                        <span class="text-secondary fw-semibold small">
                                            {{ $period->start_date }} <span class="text-muted mx-1">al</span> {{ $period->end_date }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light py-2 px-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('periods.edit', $period->idperiod) }}" 
                                   class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1 px-3 rounded-2" 
                                   title="Editar configuración">
                                    <i class='material-icons fs-5'>edit</i> <small class="fw-bold">Editar</small>
                                </a>

                                <a href="{{ route('periods.showDelete', $period->idperiod) }}" 
                                   class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 px-3 rounded-2" 
                                   title="Desactivar periodo">
                                    <i class='material-icons fs-5'>delete_forever</i> <small class="fw-bold">Eliminar</small>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 bg-light" role="alert">
                <i class="material-icons text-info me-3 fs-2">info</i>
                <div>
                    <h5 class="fw-bold mb-1">No se encontraron periodos escolares</h5>
                    <p class="mb-0 text-muted small">Actualmente no hay ciclos activos registrados. Crea un nuevo periodo lectivo usando el botón superior.</p>
                </div>
            </div>
        @endif

    </div>
</div>

</x-layouts.app-layout>