<x-layouts.app-layout title="Dashboard Apoderado">

<div class="main-content">
    <div class="row">

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-warning">
                        <span class="material-icons">family_restroom</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Hijos Registrados</strong></p>
                    <h3 class="card-title">{{ $totalHijos }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-success">
                        <span class="material-icons">auto_stories</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Total Cursos (Fam.)</strong></p>
                    <h3 class="card-title">{{ $totalCursosHijos }}</h3>
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
                    <p class="category"><strong>Asistencias Totales</strong></p>
                    <h3 class="card-title">{{ $asistenciasTotales }}</h3>
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
                    <p class="category"><strong>Inasistencias Totales</strong></p>
                    <h3 class="card-title">{{ $faltasTotales }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Mis Hijos Registrados</h4>
                </div>
                <div class="card-content table-responsive">
                    @if($misHijos->count() > 0)
                        <table class="table table-hover">
                            <thead class="text-primary">
                                <tr>
                                    <th># ID</th>
                                    <th>Foto</th>
                                    <th>Estudiante</th>
                                    <th>Correo Institucional</th>
                                    <th class="text-center">Promedio Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($misHijos as $hijo)
                                    <tr>
                                        <td>{{ $hijo->idstudent }}</td>
                                        <td>
                                            <img src="{{ asset('backend/img/subidas/' . ($hijo->photo ?? 'default.png')) }}" width="45" class="rounded-circle">
                                        </td>
                                        <td><strong>{{ $hijo->full_name }}</strong></td>
                                        <td>{{ $hijo->email ?? 'Sin correo' }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ ($hijo->promedio_hijo < 11) ? 'badge-danger' : 'badge-success' }}">
                                                {{ $hijo->promedio_hijo ?? '0.00' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning mt-3 m-3">
                            <strong>No cuenta con estudiantes vinculados a su perfil de apoderado.</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Rendimiento Académico por Hijo</h4>
                </div>
                <div class="card-content p-4 d-flex flex-column align-items-center justify-content-center">
                    @if($misHijos->count() > 0)
                        <div style="width: 100%; max-width: 400px;">
                            <canvas id="familyPerformanceChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-warning w-100">
                            <strong>No hay datos disponibles para graficar.</strong>
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
            const ctx = document.getElementById('familyPerformanceChart');
            if (ctx) {
                const labelsX = {!! json_encode($misHijos->pluck('full_name')) !!};
                const dataY = {!! json_encode($misHijos->pluck('promedio_hijo')->map(fn($p) => $p ?? 0)) !!};

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelsX,
                        datasets: [{
                            label: 'Promedio General',
                            data: dataY,
                            backgroundColor: dataY.map(score => score < 11 ? 'rgba(244, 67, 54, 0.7)' : 'rgba(33, 150, 243, 0.7)'),
                            borderColor: dataY.map(score => score < 11 ? '#f44336' : '#2196f3'),
                            borderWidth: 1.5
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 20
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