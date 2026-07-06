<x-layouts.app-layout title="Listado de Cursos">
<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" class="text-decoration-none">Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Catálogo Estructurado</li>
                </ol>
            </nav>

            <div class="card shadow-sm" style="min-height:485px">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center pt-4 pb-3">
                    <h4 class="card-title mb-0 fw-bold text-custom-blue">
                        <i class="material-icons align-middle me-1">library_books</i> Catálogo de Cursos Académicos
                    </h4>
                    <a href="{{ route('courses.create') }}" class="btn btn-danger text-white shadow-sm">
                        <i class='material-icons align-middle'>add</i> Nuevo Bloque
                    </a>
                </div>

                <div class="card-body p-4 bg-light">
                    
                    @if($courses->count() > 0)
                        
                        @php
                            // 1. Agrupamos todos los cursos por el ID del Semestre
                            $groupedBySemester = $courses->groupBy('idsemester');
                        @endphp

                        @foreach($groupedBySemester as $semesterId => $coursesInSemester)
                            @php
                                // Obtenemos el nombre del semestre a partir del primer curso de este grupo
                                $firstCourse = $coursesInSemester->first();
                                $periodName = $firstCourse->semester->period->period_name ?? 'N/A';
                                $semesterName = $firstCourse->semester->semester_name;
                            @endphp

                            <div class="mb-5">
                                <h3 class="h4 fw-bold text-dark border-bottom border-2 border-dark pb-2 mb-4">
                                    <i class="material-icons align-middle text-custom">date_range</i> 
                                    Periodo Escolar: {{ $periodName }} - {{ $semesterName }}
                                </h3>

                                @php
                                    // 2. Dentro del semestre, agrupamos por ID de Grado
                                    $groupedByDegree = $coursesInSemester->groupBy('iddegree');
                                @endphp

                                @foreach($groupedByDegree as $degreeId => $coursesInDegree)
                                    @php
                                        // 3. Dentro del grado, agrupamos por ID de Subgrado
                                        $groupedBySubgrade = $coursesInDegree->groupBy('idsubgrade');
                                        $degreeName = $coursesInDegree->first()->degree->degree_name;
                                    @endphp

                                    @foreach($groupedBySubgrade as $subgradeId => $coursesInSubgrade)
                                        @php
                                            $subgradeName = $coursesInSubgrade->first()->subgrade->subgrade_name;
                                        @endphp

                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-white border-bottom py-3">
                                                <h5 class="mb-0 fw-bold text-secondary">
                                                    <span class="text-dark">Grado:</span> {{ $degreeName }} 
                                                    <span class="mx-2 text-muted">|</span> 
                                                    <span class="text-dark">Subgrado:</span> <span class="text-danger">{{ $subgradeName }}</span>
                                                </h5>
                                            </div>
                                            
                                            <div class="card-body bg-white">
                                                <div class="row g-4">
                                                    
                                                    @foreach($coursesInSubgrade as $course)
                                                    <div class="col-12 col-md-6 col-lg-4">
                                                        <div class="card h-100 border course-item-card {{ $course->status != 1 ? 'bg-light opacity-75' : '' }}">
                                                            
                                                            <div class="card-body p-3 d-flex align-items-center">
                                                                
                                                                <div class="course-img-wrapper me-3 border">
                                                                    @if($course->photo)
                                                                        <img src="{{ asset('backend/img/subidas/' . $course->photo) }}" alt="{{ $course->course_name }}">
                                                                    @else
                                                                        <img src="{{ asset('backend/img/no-image.png') }}" alt="Sin imagen" style="opacity: 0.5;">
                                                                    @endif
                                                                </div>

                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                                        <h6 class="mb-0 fw-bold text-dark text-truncate" style="max-width: 150px;" title="{{ $course->course_name }}">
                                                                            {{ $course->course_name }}
                                                                        </h6>
                                                                        <span class="badge {{ $course->status == 1 ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 0.7rem;">
                                                                            {{ $course->status == 1 ? 'Activo' : 'Inactivo' }}
                                                                        </span>
                                                                    </div>
                                                                    
                                                                    <div class="small mt-2">
                                                                        <span class="text-muted d-block mb-1"><i class="material-icons align-middle" style="font-size: 14px;">person</i> Docente(s):</span>
                                                                        @if($course->teachers->count() > 0)
                                                                            @foreach($course->teachers as $teacher)
                                                                                <span class="badge bg-secondary text-white fw-normal mb-1 text-wrap text-start">
                                                                                    {{ $teacher->full_name }}
                                                                                </span>
                                                                            @endforeach
                                                                        @else
                                                                            <span class="badge bg-light text-dark border fw-normal">Sin asignar</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="card-footer bg-transparent border-top p-2 d-flex justify-content-end gap-2">
                                                                <a href="{{ route('courses.editPhoto', $course->idcourse) }}" class="btn btn-sm btn-info text-white rounded px-2 py-1" title="Cambiar Foto">
                                                                    <i class='material-icons' style="font-size: 18px;">image</i>
                                                                </a>
                                                                <a href="{{ route('courses.edit', $course->idcourse) }}" class="btn btn-sm btn-warning text-white rounded px-2 py-1" title="Editar">
                                                                    <i class='material-icons' style="font-size: 18px;">edit</i>
                                                                </a>
                                                                @if($course->status == 1)
                                                                <a href="{{ route('courses.showDelete', $course->idcourse) }}" class="btn btn-sm btn-danger text-white rounded px-2 py-1" title="Eliminar">
                                                                    <i class='material-icons' style="font-size: 18px;">delete</i>
                                                                </a>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @endforeach

                    @else
                        <div class="text-center py-5">
                            <i class="material-icons text-muted" style="font-size: 64px;">inbox</i>
                            <h5 class="mt-3 text-muted">No hay cursos registrados aún.</h5>
                            <p class="text-muted">Comienza agregando un nuevo bloque de cursos.</p>
                            <a href="{{ route('courses.create') }}" class="btn btn-outline-primary mt-2">Agregar Cursos</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>