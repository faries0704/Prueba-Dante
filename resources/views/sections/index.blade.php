<x-layouts.app-layout title="Mostrar Secciones">

<div class="main-content">
    <div class="container-fluid">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sections.index') }}" class="text-decoration-none">Sección</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mostrar</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">groups</i> Gestión de Secciones
                </h3>
                <p class="text-muted small mb-0">Distribución de cursos, docentes asignados y control de capacidad por aulas.</p>
            </div>
            <a href="{{ route('sections.create') }}" class="btn text-white px-4 shadow-sm d-flex align-items-center" style="background-color: #005187;">
                <i class='material-icons me-1'>add_circle</i> Nueva Sección
            </a>
        </div>

        @if($sections->count() > 0)
            @php 
                // Agrupamos en cascada usando las colecciones de Laravel
                $periodsGrouped = $sections->groupBy(function($item) {
                    return $item->course->semester->period->period_name ?? 'Sin Periodo';
                });
                $periodIndex = 0;
            @endphp

            <ul class="nav nav-tabs mb-4 border-bottom-0" id="sectionPeriodTabs" role="tablist">
                @foreach($periodsGrouped as $periodo => $sectionsDelPeriodo)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $periodIndex == 0 ? 'active fw-bold' : '' }} border" 
                                id="tab-sec-{{ Str::slug($periodo) }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#panel-sec-{{ Str::slug($periodo) }}" 
                                type="button" 
                                role="tab" 
                                style="{{ $periodIndex == 0 ? 'color: #fff; background-color: #005187;' : 'color: #005187;' }}"
                                onclick="let bs = 'background-color'; document.querySelectorAll('#sectionPeriodTabs .nav-link').forEach(el => { el.style.color='#005187'; el.style.removeProperty(bs); el.classList.remove('fw-bold') }); this.style.color='#fff'; this.style.backgroundColor='#005187'; this.classList.add('fw-bold');">
                            {{ $periodo }}
                        </button>
                    </li>
                    @php $periodIndex++; @endphp
                @endforeach
            </ul>

            <div class="tab-content" id="sectionPeriodTabsContent">
                @php $periodIndex = 0; @endphp
                @foreach($periodsGrouped as $periodo => $sectionsDelPeriodo)
                    <div class="tab-pane fade {{ $periodIndex == 0 ? 'show active' : '' }}" 
                         id="panel-sec-{{ Str::slug($periodo) }}" 
                         role="tabpanel">

                        <div class="accordion shadow-sm" id="accordionSec-{{ Str::slug($periodo) }}">
                            @php
                                $subgrouping = $sectionsDelPeriodo->groupBy(function($item) {
                                    $semestre = $item->course->semester->semester_name ?? 'N/A';
                                    $grado = $item->course->degree->degree_name ?? 'N/A';
                                    return $semestre . ' — ' . $grado;
                                });
                                $accordionIndex = 0;
                            @endphp

                            @foreach($subgrouping as $identificador => $listaSecciones)
                                @php 
                                    $accordionId = Str::slug($periodo) . '-' . Str::slug($identificador);
                                @endphp
                                <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                                    <h2 class="accordion-header" id="headingSec-{{ $accordionId }}">
                                        <button class="accordion-button {{ $accordionIndex == 0 ? '' : 'collapsed' }} fw-semibold text-dark bg-light" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapseSec-{{ $accordionId }}" 
                                                aria-expanded="{{ $accordionIndex == 0 ? 'true' : 'false' }}" 
                                                aria-controls="collapseSec-{{ $accordionId }}">
                                            <i class="material-icons text-secondary me-2 fs-5">assignment</i>
                                            {{ $identificador }}
                                            <span class="badge ms-2 rounded-pill text-white bg-secondary small">{{ $listaSecciones->count() }} Clases</span>
                                        </button>
                                    </h2>
                                    
                                    <div id="collapseSec-{{ $accordionId }}" 
                                         class="accordion-collapse collapse {{ $accordionIndex == 0 ? 'show' : '' }}" 
                                         aria-labelledby="headingSec-{{ $accordionId }}" 
                                         data-bs-parent="#accordionSec-{{ Str::slug($periodo) }}">
                                        
                                        <div class="accordion-body bg-white p-3">
                                            
                                            <div class="row g-3">
                                                @foreach($listaSecciones as $section)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="card h-100 border rounded-3 bg-light-subtle shadow-sm"
                                                             style="transition: transform 0.2s;"
                                                             onmouseover="this.style.transform='translateY(-2px)'"
                                                             onmouseout="this.style.transform='none'">
                                                            
                                                            <div class="card-body p-3">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <div>
                                                                        <span class="badge bg-white text-dark border mb-1 fs-6 fw-bold">
                                                                            Sec: {{ $section->section_name }}
                                                                        </span>
                                                                        <small class="text-muted d-block">
                                                                            Subgrado: <strong>{{ $section->course->subgrade->subgrade_name ?? 'N/A' }}</strong>
                                                                        </small>
                                                                    </div>
                                                                    
                                                                    @if($section->status == 1)
                                                                        <span class="badge bg-success-subtle text-success rounded-pill px-2">Activo</span>
                                                                    @else
                                                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Inactivo</span>
                                                                    @endif
                                                                </div>

                                                                <h5 class="fw-bold text-dark mb-2 text-truncate" title="{{ $section->course->course_name }}">
                                                                    {{ $section->course->course_name }}
                                                                </h5>

                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block mb-1" style="font-size: 11px; font-weight:700;">Docente(s):</small>
                                                                    <div class="d-flex flex-wrap gap-1">
                                                                        @forelse($section->course->teachers as $teacher)
                                                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-1 text-truncate" style="max-width: 100%;">
                                                                                {{ $teacher->full_name }}
                                                                            </span>
                                                                        @empty
                                                                            <span class="text-muted small italic">Sin docente asignado</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex align-items-center bg-white p-2 rounded-2 border">
                                                                    <i class="material-icons text-muted me-2 fs-5">airline_seat_recline_normal</i>
                                                                    <div>
                                                                        <small class="text-muted d-block small" style="font-size: 10px;">Capacidad del Aula</small>
                                                                        <span class="fw-bold text-dark">{{ $section->capacity }} Alumnos</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="card-footer bg-light py-2 px-3 border-top d-flex justify-content-end gap-1">
                                                                <a href="{{ route('sections.edit', $section->idsection) }}" 
                                                                   class="btn btn-sm btn-outline-warning p-1 d-flex align-items-center rounded-2" 
                                                                   title="Editar sección">
                                                                    <i class='material-icons fs-5'>edit</i>
                                                                </a>
                                                                
                                                                @if($section->status == 1)
                                                                    <a href="{{ route('sections.showDelete', $section->idsection) }}" 
                                                                       class="btn btn-sm btn-outline-danger p-1 d-flex align-items-center rounded-2" 
                                                                       title="Eliminar / Desactivar">
                                                                        <i class='material-icons fs-5'>delete_forever</i>
                                                                    </a>
                                                                    <a href="{{ route('sections.manage', $section->idsection) }}" 
                                                                       class="btn btn-sm text-white p-1 d-flex align-items-center rounded-2" 
                                                                       style="background-color: #005187;"
                                                                       title="Administrar alumnos e indicadores">
                                                                        <i class='material-icons fs-5'>logout</i>
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
                                @php $accordionIndex++; @endphp
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
                    <h5 class="fw-bold mb-1">No se encontraron secciones</h5>
                    <p class="mb-0 text-muted small">No hay aulas ni secciones estructuradas para mostrar. Crea una nueva sección académica para comenzar a asignar docentes y cursos.</p>
                </div>
            </div>
        @endif

    </div>
</div>

</x-layouts.app-layout>