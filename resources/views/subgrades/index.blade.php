<x-layouts.app-layout title="Subgrados Académicos">
<div class="main-content">
    <div class="container-fluid">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subgrados Académicos</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-custom-blue mb-1">
                    <i class="material-icons align-middle fs-3 me-1">layers</i> Estructura de Subgrados
                </h3>
                <p class="text-muted small mb-0">Listado organizado por periodos escolares y grados académicos.</p>
            </div>
            <a href="{{ route('subgrades.create') }}" class="btn btn-success text-white px-4 shadow-sm d-flex align-items-center">
                <i class='material-icons me-1'>add_circle</i> Asignación Múltiple
            </a>
        </div>

        @if($subgrades->count() > 0)
            @foreach($subgrades->groupBy(function($item) {
                return $item->degree->semester->period->period_name ?? 'Sin Periodo';
            }) as $periodo => $subgradesPorPeriodo)
                
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                        <i class="material-icons text-custom-blue me-2 fs-4">date_range</i>
                        <h4 class="fw-bold mb-0 text-dark">Periodo Escolar: <span class="text-custom-blue">{{ $periodo }}</span></h4>
                        <span class="badge bg-secondary ms-3 rounded-pill">{{ $subgradesPorPeriodo->count() }} Subgrados</span>
                    </div>

                    <div class="row g-4">
                        @foreach($subgradesPorPeriodo->groupBy('degree.degree_name') as $grado => $listaSubgrados)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 degree-group-card shadow-sm">
                                    
                                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center border-bottom">
                                        <div class="d-flex align-items-center">
                                            <i class="material-icons text-secondary me-2 fs-5">school</i>
                                            <h5 class="card-title mb-0 fw-bold text-dark">{{ $grado }}</h5>
                                        </div>
                                        <span class="badge bg-custom-blue text-white rounded-pill">{{ $listaSubgrados->count() }}</span>
                                    </div>

                                    <div class="card-body p-3">
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($listaSubgrados as $sub)
                                                <div class="subgrade-item-box p-2 d-flex justify-content-between align-items-center">
                                                    
                                                    <div class="d-flex align-items-center text-truncate me-2">
                                                        @if($sub->status == 1)
                                                            <span class="p-1 bg-success rounded-circle me-2 d-inline-block" style="width: 8px; height: 8px;" title="Activo"></span>
                                                        @else
                                                            <span class="p-1 bg-danger rounded-circle me-2 d-inline-block" style="width: 8px; height: 8px;" title="Inactivo"></span>
                                                        @endif
                                                        <span class="fw-semibold text-secondary text-truncate fs-6">{{ $sub->subgrade_name }}</span>
                                                    </div>

                                                    <div class="d-flex gap-1 shrink-0">
                                                        <a href="{{ route('subgrades.edit', $sub->idsubgrade) }}" 
                                                           class="btn btn-sm btn-outline-warning p-1 d-flex align-items-center" 
                                                           style="border-radius: 6px;" title="Editar">
                                                            <i class='material-icons fs-6'>edit</i>
                                                        </a>
                                                        @if($sub->status == 1)
                                                            <a href="{{ route('subgrades.showDelete', $sub->idsubgrade) }}" 
                                                               class="btn btn-sm btn-outline-danger p-1 d-flex align-items-center" 
                                                               style="border-radius: 6px;" title="Desactivar / Eliminar">
                                                                <i class='material-icons fs-6'>delete</i>
                                                            </a>
                                                        @endif
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        @else
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 bg-light" role="alert">
                <i class="material-icons text-info me-3 fs-2">info</i>
                <div>
                    <h5 class="fw-bold mb-1">No se encontraron subgrados académicos</h5>
                    <p class="mb-0 text-muted small">Aún no has registrado ningún subgrado. Haz clic en el botón de "Asignación Múltiple" arriba para empezar.</p>
                </div>
            </div>
        @endif

    </div>
</div>

</x-layouts.app-layout>