<x-layouts.app-layout title="Calificaciones de mis Hijos">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Calificaciones Hijos</li>
                </ol>
            </nav>

            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">analytics</i> Reporte de Calificaciones Académicas
                </h3>
                <p class="text-muted small mb-0">Haga un seguimiento del progreso, promedio por criterios y rendimiento académico trimestral o semestral.</p>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4 bg-light rounded-3">
                <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <label class="form-label text-secondary fw-bold small text-uppercase" style="letter-spacing: 0.5px;">1. Seleccionar Estudiante / Hijo:</label>
                        <form method="GET" action="{{ route('father.grades.index') }}" id="formFiltroHijo">
                            <select class="form-select border-secondary-subtle shadow-sm" name="idstudent" onchange="document.getElementById('formFiltroHijo').submit();">
                                @if($students->isEmpty())
                                    <option value="">No hay hijos vinculados registrados</option>
                                @endif
                                @foreach($students as $student)
                                    <option value="{{ $student->idstudent }}" {{ $selectedStudent == $student->idstudent ? 'selected' : '' }}>
                                         {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            @if($grades->count() > 0)
                
                <div class="card border shadow-sm p-4 mb-4 bg-white rounded-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="material-icons me-2 fs-5" style="color: #005187;">tune</i>
                        <span class="text-dark fw-bold small text-uppercase" style="letter-spacing: 0.5px;">2. Filtrar Criterios del Alumno</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-muted small fw-bold">Periodo</label>
                            <select class="form-select border-secondary-subtle" id="fatherFilterPeriod" onchange="filterFatherGrades()">
                                <option value="">Todos los Periodos</option>
                                @foreach($grades->unique('period_name') as $g)
                                    <option value="{{ Str::slug($g->period_name) }}">{{ $g->period_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-muted small fw-bold">Semestre</label>
                            <select class="form-select border-secondary-subtle" id="fatherFilterSemester" onchange="filterFatherGrades()">
                                <option value="">Todos los Semestres</option>
                                @foreach($grades->whereNotNull('semester_name')->unique('semester_name') as $g)
                                    <option value="{{ Str::slug($g->semester_name) }}">{{ $g->semester_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6">
                            <label class="form-label text-muted small fw-bold">Curso / Materia</label>
                            <select class="form-select border-secondary-subtle" id="fatherFilterCourse" onchange="filterFatherGrades()">
                                <option value="">Todos los Cursos</option>
                                @foreach($grades->unique('course_name') as $g)
                                    <option value="{{ Str::slug($g->course_name) }}">{{ $g->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="fatherGradesGrid">
                    @foreach ($grades as $g)
                        @php
                            // Lógica de color según el promedio general (Menor a 11 desaprobado)
                            $isDanger = $g->promedio_general < 11;
                            $borderColor = $isDanger ? 'border-danger' : 'border-light-subtle';
                            $avgBadgeColor = $isDanger ? 'bg-danger-subtle text-danger border-danger' : 'bg-success-subtle text-success border-success';
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 father-grade-card-item"
                             data-period="{{ Str::slug($g->period_name) }}"
                             data-semester="{{ Str::slug($g->semester_name ?? 'na') }}"
                             data-course="{{ Str::slug($g->course_name) }}">
                            
                            <div class="card h-100 shadow-sm border rounded-3 overflow-hidden {{ $borderColor }}"
                                 style="transition: transform 0.2s ease, box-shadow 0.2s ease;"
                                 onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.08)'"
                                 onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                                
                                <div class="card-header bg-white pt-3 pb-2 px-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-light text-dark border px-2 py-1 small fw-semibold">
                                            {{ $g->period_name }} 
                                            @if($g->semester_name)
                                                <span class="text-muted">| {{ $g->semester_name }}</span>
                                            @endif
                                        </span>
                                        <span class="badge text-white px-2 py-1 small" style="background-color: #005187;">
                                            Sec: {{ $g->section_name }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $g->course_name }}">
                                        {{ $g->course_name }}
                                    </h5>
                                    <small class="text-muted d-block fs-6" style="font-size:12px;">
                                        Nivel: {{ $g->degree_name }} <span class="text-secondary">/</span> {{ $g->subgrade_name ?? 'N/A' }}
                                    </small>
                                </div>

                                <div class="card-body py-2 px-3">
                                    <div class="p-3 rounded-3 bg-light border border-light-subtle">
                                        
                                        <div class="row g-2 text-center mb-3 pb-2 border-bottom border-light-subtle">
                                            <div class="col-4 border-end">
                                                <small class="text-muted d-block text-truncate" style="font-size: 11px;">Prácticas</small>
                                                <span class="fw-bold text-dark small">{{ $g->nota_practica ?? '0.00' }}</span>
                                            </div>
                                            <div class="col-4 border-end">
                                                <small class="text-muted d-block text-truncate" style="font-size: 11px;">Exámenes</small>
                                                <span class="fw-bold text-dark small">{{ $g->nota_examen ?? '0.00' }}</span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted d-block text-truncate" style="font-size: 11px;">Trabajos</small>
                                                <span class="fw-bold text-dark small">{{ $g->nota_trabajo ?? '0.00' }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                                            <span class="small text-muted font-monospace" style="font-size: 11px;">Examen Final / Cierre</span>
                                            <span class="fw-bold text-dark fs-6">{{ $g->nota_final ?? '0.00' }}</span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border {{ $avgBadgeColor }}">
                                            <span class="small fw-bold">Promedio General Acumulado</span>
                                            <span class="fs-5 fw-bold px-2.5 rounded">{{ $g->promedio_general ?? '0.00' }}</span>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 rounded-3 mt-3" role="alert">
                    <i class="material-icons text-info me-3 fs-2">info</i>
                    <div>
                        <h5 class="fw-bold mb-1">Sin calificaciones cargadas</h5>
                        <p class="mb-0 text-muted small">No se registran notas publicadas ni actas vigentes para el estudiante seleccionado en este momento.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        // Función de filtrado interactivo instantáneo en la perspectiva del apoderado
        function filterFatherGrades() {
            let period = document.getElementById('fatherFilterPeriod').value;
            let semester = document.getElementById('fatherFilterSemester').value;
            let course = document.getElementById('fatherFilterCourse').value;

            let cards = document.querySelectorAll('.father-grade-card-item');

            cards.forEach(card => {
                let cardPeriod = card.getAttribute('data-period');
                let cardSemester = card.getAttribute('data-semester');
                let cardCourse = card.getAttribute('data-course');

                let matchPeriod = (period === "" || cardPeriod === period);
                let matchSemester = (semester === "" || cardSemester === semester);
                let matchCourse = (course === "" || cardCourse === course);

                if (matchPeriod && matchSemester && matchCourse) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
    @endpush
</x-layouts.app-layout>