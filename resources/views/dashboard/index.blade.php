<x-layouts.app-layout title="Dashboard">

<div class="main-content">
    <div class="row">

        <!-- Padres -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-warning">
                        <span class="material-icons">group</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Padres</strong></p>
                    <h3 class="card-title">{{ $padres }}</h3>
                </div>
            </div>
        </div>

        <!-- Docentes -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-rose">
                        <span class="material-icons">badge</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Docentes</strong></p>
                    <h3 class="card-title">{{ $docentes }}</h3>
                </div>
            </div>
        </div>

        <!-- Alumnos -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-success">
                        <span class="material-icons">face</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Alumnos</strong></p>
                    <h3 class="card-title">{{ $alumnos }}</h3>
                </div>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-info">
                        <span class="material-icons">person</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Usuarios</strong></p>
                    <h3 class="card-title">{{ $usuarios }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Alumnos recientes</h4>
                </div>
                <div class="card-content table-responsive">

                    @if($alumnosRecientes->count() > 0)
                        <table class="table table-hover" id="example">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Alumnos</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alumnosRecientes as $alumno)
                                    <tr>
                                        <td>{{ $alumno->idstudent }}</td>
                                        <td>
                                            <img src="{{ asset('backend/img/subidas/' . ($alumno->photo ?? 'default.png')) }}" width="50">
                                        </td>
                                        <td>{{ $alumno->full_name }}</td>
                                        <td>{{ $alumno->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning mt-3">
                            <strong>No hay datos!</strong>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Docentes recientes</h4>
                </div>
                <div class="card-content table-responsive">

                    @if($docentesRecientes->count() > 0)
                        <table class="table table-hover" id="example1">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Docentes</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($docentesRecientes as $docente)
                                    <tr>
                                        <td>{{ $docente->idteacher }}</td>
                                        <td>{{ $docente->full_name }}</td>
                                        <td>
                                            @if ($docente->status == 1)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning mt-3">
                            <strong>No hay datos!</strong>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>


</x-layouts.app-layout>