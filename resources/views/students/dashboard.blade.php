<x-layouts.app-layout title="Dashboard Estudiante">

<div class="main-content">
    <div class="row">

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-success">
                        <span class="material-icons">auto_stories</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Mis Cursos</strong></p>
                    <h3 class="card-title">{{ $totalCursos }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-info">
                        <span class="material-icons">task_alt</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Mis Asistencias</strong></p>
                    <h3 class="card-title">{{ $asistencias }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-rose">
                        <span class="material-icons">unpublished</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Mis Faltas</strong></p>
                    <h3 class="card-title">{{ $faltas }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-warning">
                        <span class="material-icons">star</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Promedio General</strong></p>
                    <h3 class="card-title">
                        <span class="badge {{ ($promedioGeneral < 11) ? 'badge-danger' : 'badge-success' }}">
                            {{ number_format($promedioGeneral, 2) }}
                        </span>
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Mi Horario de Clases</h4>
                </div>
                <div class="card-content table-responsive p-3">
                    @if($horario->count() > 0)
                        <table class="table table-hover">
                            <thead class="text-primary">
                                <tr>
                                    <th>Día</th>
                                    <th>Curso</th>
                                    <th>Sección</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horario as $clase)
                                    <tr>
                                        <td><span class="badge bg-secondary text-white">{{ $clase->day_week }}</span></td>
                                        <td><strong>{{ $clase->course_name }}</strong></td>
                                        <td>{{ $clase->section_name }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ date('g:i a', strtotime($clase->start_time)) }} - {{ date('g:i a', strtotime($clase->end_time)) }}
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning mt-3">
                            <strong>¡No tienes asignaturas con horarios programados para este periodo!</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Gráfico de Rendimiento Académico</h4>
                </div>
                <div class="card-content p-4 d-flex flex-column align-items-center justify-content-center">
                    @if($rendimientoCursos->count() > 0)
                        <div style="width: 100%; max-width: 450px;">
                            <canvas id="studentPerformanceChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-warning w-100">
                            <strong>Aún no cuentas con promedios registrados en tus asignaturas.</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('studentPerformanceChart');
            if (ctx) {
                // Inyección de colecciones PHP ordenadas a arreglos JS
                const labelsX = {!! json_encode($rendimientoCursos->pluck('course_name')) !!};
                const dataY = {!! json_encode($rendimientoCursos->pluck('promedio_curso')->map(fn($p) => $p ?? 0)) !!};

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelsX,
                        datasets: [{
                            label: 'Promedio Obtenido',
                            data: dataY,
                            backgroundColor: dataY.map(score => score < 11 ? 'rgba(244, 67, 54, 0.7)' : 'rgba(76, 175, 80, 0.7)'),
                            borderColor: dataY.map(score => score < 11 ? '#f44336' : '#4caf50'),
                            borderWidth: 1.5
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 20 // Modifica el máximo según el sistema de calificación (ej. 20 o 100)
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
@endpush

</x-layouts.app-layout>