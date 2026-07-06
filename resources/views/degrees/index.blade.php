<x-layouts.app-layout title="Grado Académico">

<div class="main-content">
    <div class="container-fluid">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Grado Académico</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">school</i> Grados Académicos
                </h3>
                <p class="text-muted small mb-0">Estructura organizada por periodo lectivo y semestres activos.</p>
            </div>
            <a href="{{ route('degrees.create') }}" class="btn text-white px-4 shadow-sm d-flex align-items-center" style="background-color: #005187;">
                <i class='material-icons me-1'>add_circle</i> Nuevo Grado
            </a>
        </div>

        @if($degrees->count() > 0)
            @php 
                // Agrupamos en el servidor: Primero por Periodo, luego por Semestre
                $groupedPeriods = $degrees->groupBy(function($item) {
                    return $item->semester->period->period_name ?? 'Sin Periodo';
                });
                $periodIndex = 0;
            @endphp

            <ul class="nav nav-tabs mb-4 border-bottom-0" id="periodTabs" role="tablist">
                @foreach($groupedPeriods as $periodo => $gradosDelPeriodo)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $periodIndex == 0 ? 'active fw-bold' : '' }} border" 
                                id="tab-{{ Str::slug($periodo) }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#panel-{{ Str::slug($periodo) }}" 
                                type="button" 
                                role="tab" 
                                style="{{ $periodIndex == 0 ? 'color: #fff; background-color: #005187;' : 'color: #005187;' }}"
                                onclick="let bs = 'background-color'; document.querySelectorAll('#periodTabs .nav-link').forEach(el => { el.style.color='#005187'; el.style.removeProperty(bs); el.classList.remove('fw-bold') }); this.style.color='#fff'; this.style.backgroundColor='#005187'; this.classList.add('fw-bold');">
                            {{ $periodo }}
                        </button>
                    </li>
                    @php $periodIndex++; @endphp
                @endforeach
            </ul>

            <div class="tab-content" id="periodTabsContent">
                @php $periodIndex = 0; @endphp
                @foreach($groupedPeriods as $periodo => $gradosDelPeriodo)
                    <div class="tab-pane fade {{ $periodIndex == 0 ? 'show active' : '' }}" 
                         id="panel-{{ Str::slug($periodo) }}" 
                         role="tabpanel">

                        <div class="accordion shadow-sm" id="accordion-{{ Str::slug($periodo) }}">
                            @php 
                                $semestres = $gradosDelPeriodo->groupBy(function($item) {
                                    return $item->semester->semester_name ?? 'Sin Semestre';
                                });
                                $semesterIndex = 0;
                            @endphp

                            @foreach($semestres as $semestre => $listaGrados)
                                @php 
                                    $accordionId = Str::slug($periodo) . '-' . Str::slug($semestre);
                                @endphp
                                <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                                    <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                        <button class="accordion-button {{ $semesterIndex == 0 ? '' : 'collapsed' }} fw-semibold text-dark bg-light" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse-{{ $accordionId }}" 
                                                aria-expanded="{{ $semesterIndex == 0 ? 'true' : 'false' }}" 
                                                aria-controls="collapse-{{ $accordionId }}">
                                            <i class="material-icons text-secondary me-2 fs-5">calendar_view_day</i>
                                            {{ $semestre }}
                                            <span class="badge ms-2 rounded-pill text-white bg-secondary small">{{ $listaGrados->count() }} Grados</span>
                                        </button>
                                    </h2>
                                    
                                    <div id="collapse-{{ $accordionId }}" 
                                         class="accordion-collapse collapse {{ $semesterIndex == 0 ? 'show' : '' }}" 
                                         aria-labelledby="heading-{{ $accordionId }}" 
                                         data-bs-parent="#accordion-{{ Str::slug($periodo) }}">
                                        
                                        <div class="accordion-body bg-white p-3">
                                            <div class="row g-3">
                                                @foreach($listaGrados as $degree)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center h-100 bg-light-subtle">
                                                            
                                                            <div class="text-truncate pe-2">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    @if($degree->status == 1)
                                                                        <span class="p-1 bg-success rounded-circle me-2" style="width: 8px; height: 8px;" title="Activo"></span>
                                                                    @else
                                                                        <span class="p-1 bg-danger rounded-circle me-2" style="width: 8px; height: 8px;" title="Inactivo"></span>
                                                                    @endif
                                                                    <small class="text-muted fw-bold text-uppercase" style="font-size: 10px;">ID: #{{ $degree->iddegree }}</small>
                                                                </div>
                                                                <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $degree->degree_name }}</h6>
                                                            </div>

                                                            <div class="d-flex gap-1 grow">
                                                                <a href="{{ route('degrees.edit', $degree->iddegree) }}" 
                                                                   class="btn btn-sm btn-outline-warning p-1 d-flex align-items-center rounded-2" 
                                                                   title="Editar">
                                                                    <i class='material-icons fs-5'>edit</i>
                                                                </a>
                                                                @if($degree->status == 1)
                                                                    <a href="{{ route('degrees.showDelete', $degree->iddegree) }}" 
                                                                       class="btn btn-sm btn-outline-danger p-1 d-flex align-items-center rounded-2" 
                                                                       title="Eliminar">
                                                                        <i class='material-icons fs-5'>delete</i>
                                                                    </a>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @php $semesterIndex++; @endphp
                            @endforeach
                        </div>

                    </div>
                    @php $periodIndex++; @endphp
                @endforeach
            </div>

        @else
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 bg-light" role="alert">
                <i class="material-icons text-info me-3 fs-2">info</i>
                <div>
                    <h5 class="fw-bold mb-1">No hay datos disponibles</h5>
                    <p class="mb-0 text-muted small">No se encontraron grados académicos configurados. Presiona "Nuevo Grado" para agregar uno.</p>
                </div>
            </div>
        @endif

    </div>
</div>

</x-layouts.app-layout>