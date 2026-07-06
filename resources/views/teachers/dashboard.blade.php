<x-layouts.app-layout title="Dashboard Docente">

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
                        <span class="material-icons">group</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Alumnos Totales</strong></p>
                    <h3 class="card-title">{{ $totalAlumnos }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-rose">
                        <span class="material-icons">task_alt</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Asistencias Presenciales</strong></p>
                    <h3 class="card-title">{{ $asistencias }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-warning">
                        <span class="material-icons">running_with_errors</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Tardanzas Registradas</strong></p>
                    <h3 class="card-title">{{ $tardanzas }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Mi Horario de Dictado</h4>
                </div>
                <div class="card-content table-responsive p-3">
                    @if($horarioDocente->count() > 0)
                        <table class="table table-hover">
                            <thead class="text-primary">
                                <tr>
                                    <th>Día</th>
                                    <th>Curso / Unidad</th>
                                    <th>Sección</th>
                                    <th>Horario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horarioDocente as $clase)
                                    <tr>
                                        <td><span class="badge bg-primary text-white">{{ $clase->day_week }}</span></td>
                                        <td><strong>{{ $clase->course_name }}</strong></td>
                                        <td><span class="badge bg-light text-dark border">{{ $clase->section_name }}</span></td>
                                        <td>
                                            <small class="text-muted fw-bold">
                                                {{ date('g:i a', strtotime($clase->start_time)) }} - {{ date('g:i a', strtotime($clase->end_time)) }}
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning mt-3">
                            <strong>¡No tiene cargas horarias registradas en este periodo!</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Alumnos en Alerta Pedagógica (Promedio < 11)</h4>
                </div>
                <div class="card-content p-4 d-flex flex-column align-items-center justify-content-center">
                    @if($alumnosRiesgo->sum('total_desaprobados') > 0)
                        <div style="width: 100%; max-width: 450px;">
                            <canvas id="teacherAlertChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-success w-100">
                            <strong>¡Excelente! No registras alumnos desaprobados en tus asignaturas vigentes.</strong>
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
            const ctx = document.getElementById('teacherAlertChart');
            if (ctx) {
                const labelsX = {!! json_encode($alumnosRiesgo->pluck('course_name')) !!};
                const dataY = {!! json_encode($alumnosRiesgo->pluck('total_desaprobados')) !!};

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelsX,
                        datasets: [{
                            label: 'Cantidad de Alumnos Desaprobados',
                            data: dataY,
                            backgroundColor: 'rgba(244, 67, 54, 0.75)',
                            borderColor: '#f44336',
                            borderWidth: 1.5
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
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