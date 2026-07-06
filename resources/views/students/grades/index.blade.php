<x-layouts.app-layout title="Mis Calificaciones">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mis Calificaciones</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #005187;">
                        <i class="material-icons align-middle fs-3 me-1">school</i> Mi Progreso Académico
                    </h3>
                    <p class="text-muted small mb-0">Revisa tus promedios calculados por tipo de evaluación y estado de aprobación.</p>
                </div>
            </div>

            @if($grades->count() > 0)
                
                <div class="card border shadow-sm p-4 mb-4 bg-white rounded-3 ">
                    <div class="d-flex align-items-center mb-3">
                        <i class="material-icons me-2 fs-5" style="color: #005187;">filter_list</i>
                        <span class="text-dark fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Filtros de Búsqueda Rápida</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-muted small fw-bold">Periodo</label>
                            <select class="form-select border-secondary-subtle shadow-sm" id="studentFilterPeriod" onchange="filterStudentGrades()">
                                <option value="">Todos los Periodos</option>
                                @foreach($grades->unique('period_name') as $g)
                                    <option value="{{ Str::slug($g->period_name) }}">{{ $g->period_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-muted small fw-bold">Semestre / Ciclo</label>
                            <select class="form-select border-secondary-subtle shadow-sm" id="studentFilterSemester" onchange="filterStudentGrades()">
                                <option value="">Todos los Semestres</option>
                                @foreach($grades->whereNotNull('semester_name')->unique('semester_name') as $g)
                                    <option value="{{ Str::slug($g->semester_name) }}">{{ $g->semester_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6">
                            <label class="form-label text-muted small fw-bold">Filtrar por Curso o Asignatura</label>
                            <select class="form-select border-secondary-subtle shadow-sm" id="studentFilterCourse" onchange="filterStudentGrades()">
                                <option value="">Todos los Cursos</option>
                                @foreach($grades->unique('course_name') as $g)
                                    <option value="{{ Str::slug($g->course_name) }}">{{ $g->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="studentGradesGrid">
                    @foreach ($grades as $g)
                        @php
                            // Lógica condicional de colores (Rojo si el promedio es menor a 11)
                            $isDesaprobado = $g->promedio_general < 11;
                            $cardBorder = $isDesaprobado ? 'border-danger' : 'border-light-subtle';
                            $badgeScoreClass = $isDesaprobado ? 'bg-danger-subtle text-danger border-danger' : 'bg-success-subtle text-success border-success';
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 student-grade-card-item"
                             data-period="{{ Str::slug($g->period_name) }}"
                             data-semester="{{ Str::slug($g->semester_name ?? 'na') }}"
                             data-course="{{ Str::slug($g->course_name) }}">
                            
                            <div class="card h-100 shadow-sm border rounded-3 overflow-hidden {{ $cardBorder }}"
                                 style="transition: transform 0.2s, box-shadow 0.2s;"
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
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 small">
                                            Sección: {{ $g->section_name }}
                                        </span>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0 text-truncate" title="{{ $g->course_name }}">
                                        {{ $g->course_name }}
                                    </h5>
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
                                            <span class="small text-muted" style="font-size: 11px;">Examen de Cierre / Final</span>
                                            <span class="fw-bold text-dark">{{ $g->nota_final ?? '0.00' }}</span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center p-2 rounded-2 border {{ $badgeScoreClass }}">
                                            <span class="small fw-bold">Promedio General</span>
                                            <span class="fs-5 fw-bold px-2 rounded">{{ $g->promedio_general ?? '0.00' }}</span>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center p-4 rounded-3 mt-2" role="alert">
                    <i class="material-icons text-warning me-3 fs-2">warning</i>
                    <div>
                        <h5 class="fw-bold mb-1">¡Sin asignaturas activas!</h5>
                        <p class="mb-0 text-muted small">No te encuentras registrado en ningún curso o tus docentes aún no han publicado calificaciones oficiales en este periodo.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        // Lógica limpia en vanilla JS para filtrar las tarjetas al instante
        function filterStudentGrades() {
            let period = document.getElementById('studentFilterPeriod').value;
            let semester = document.getElementById('studentFilterSemester').value;
            let course = document.getElementById('studentFilterCourse').value;

            let items = document.querySelectorAll('.student-grade-card-item');

            items.forEach(card => {
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