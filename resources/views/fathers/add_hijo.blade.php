<x-layouts.app-layout title="Gestionar Hijos">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fathers.index') }}">Padres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hijos</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Añadir los hijos de los padres</h4>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>DNI del padre</label>
                                <input type="text" readonly value="{{ $father->dni }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Nombre del padre</label>
                                <input type="text" class="form-control" value="{{ $father->full_name }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Listado general de Alumnos</h4>
                </div>
                <div class="card-content table-responsive">
                    <table class="table table-hover" id="example">
                        <thead class="text-primary">
                            <tr>
                                <th>Foto</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td><img height="50" src="{{ asset('backend/img/subidas/'.($student->user->photo ?? 'default.png')) }}" width="50"></td>
                                <td>{{ $student->dni }}</td>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->user->email }}</td>
                                <td><span class="badge badge-success">Alumno</span></td>
                                <td>
                                    <form action="{{ route('fathers.store-hijo') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="idfather" value="{{ $father->idfather }}">
                                        <input type="hidden" name="idstudent" value="{{ $student->idstudent }}">
                                        <button type="submit" class="btn btn-danger text-white">
                                            <i class='material-icons'>add</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Listado de tus hijos</h4>
                </div>
                <div class="card-content table-responsive">
                    <table class="table table-hover" id="example1">
                        <thead class="text-primary">
                            <tr>
                                <th>Foto</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myChildren as $hijo)
                            <tr>
                                <td><img height="50" src="{{ asset('backend/img/subidas/'.($hijo->user->photo ?? 'default.png')) }}" width="50"></td>
                                <td>{{ $hijo->dni }}</td>
                                <td>{{ $hijo->full_name }}</td>
                                <td>{{ $hijo->user->email }}</td>
                                <td>
                                    @if($hijo->user->status == '1')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</x-layouts.app-layout>