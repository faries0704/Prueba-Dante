<x-layouts.app-layout title="Semestres">

<div class="main-content">
    <div class="container-fluid">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Panel Control</a></li>
                <li class="breadcrumb-item active" aria-current="page">Semestres</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">calendar_today</i> Gestión de Semestres
                </h3>
                <p class="text-muted small mb-0">Listado de semestres distribuidos de manera clara por su periodo escolar.</p>
            </div>
            <a href="{{ route('semesters.create') }}" class="btn text-white px-4 shadow-sm d-flex align-items-center" style="background-color: #005187;">
                <i class='material-icons me-1'>add_circle</i> Nuevo Semestre
            </a>
        </div>

        @if($semesters->count() > 0)
            
            <div class="row g-4">
                
                @foreach($semesters->groupBy(function($item) {
                    return $item->period ? $item->period->period_name : 'Sin Periodo Relacionado';
                }) as $periodo => $semestresDelPeriodo)
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border border-light-subtle rounded-3 overflow-hidden">
                            
                            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-light border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="material-icons me-2 text-secondary fs-5">date_range</i>
                                    <h5 class="card-title mb-0 fw-bold text-dark">{{ $periodo }}</h5>
                                </div>
                                <span class="badge rounded-pill text-white small" style="background-color: #005187;">
                                    {{ $semestresDelPeriodo->count() }} {{ $semestresDelPeriodo->count() == 1 ? 'Semestre' : 'Semestres' }}
                                </span>
                            </div>

                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($semestresDelPeriodo as $semester)
                                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center bg-transparent border-bottom-0" 
                                             style="transition: background-color 0.2s;"
                                             onmouseover="this.style.backgroundColor='#f8f9fa'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            
                                            <div class="text-truncate pe-2">
                                                <div class="d-flex align-items-center mb-1">
                                                    @if($semester->status == 1)
                                                        <span class="p-1 bg-success rounded-circle me-2 d-inline-block" style="width: 8px; height: 8px;" title="Activo"></span>
                                                    @else
                                                        <span class="p-1 bg-danger rounded-circle me-2 d-inline-block" style="width: 8px; height: 8px;" title="Inactivo"></span>
                                                    @endif
                                                    <small class="text-muted fw-bold" style="font-size: 11px;">ID: #{{ $semester->idsemester }}</small>
                                                </div>
                                                <span class="fw-bold text-secondary text-truncate fs-6">{{ $semester->semester_name }}</span>
                                            </div>

                                            <div class="d-flex gap-1 grow">
                                                <a href="{{ route('semesters.edit', $semester->idsemester) }}" 
                                                   class="btn btn-sm btn-outline-warning p-1 d-flex align-items-center rounded-2" 
                                                   title="Editar">
                                                    <i class='material-icons fs-5'>edit</i>
                                                </a>
                                                <a href="{{ route('semesters.showDelete', $semester->idsemester) }}" 
                                                   class="btn btn-sm btn-outline-danger p-1 d-flex align-items-center rounded-2" 
                                                   title="Eliminar">
                                                    <i class='material-icons fs-5'>delete</i>
                                                </a>
                                            </div>

                                        </div>
                                        
                                        @if(!$loop->last)
                                            <hr class="mx-3 my-0 opacity-10">
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

        @else
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 bg-light" role="alert">
                <i class="material-icons text-info me-3 fs-2">info</i>
                <div>
                    <h5 class="fw-bold mb-1">No hay semestres disponibles</h5>
                    <p class="mb-0 text-muted small">Actualmente no existen semestres registrados en el sistema. Presione el botón de "Nuevo Semestre" para añadir uno.</p>
                </div>
            </div>
        @endif

    </div>
</div>

</x-layouts.app-layout>