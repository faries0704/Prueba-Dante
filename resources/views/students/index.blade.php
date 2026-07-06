<x-layouts.app-layout title="Mostrar Alumnos">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Alumnos</li>
                </ol>
            </nav>
            
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Alumnos Registrados</h4>
                    <a href="{{ route('students.create') }}" class="btn btn-danger text-white">
                        <i class='material-icons' data-toggle='tooltip' title='Add'>add</i>
                    </a>
                </div>

                <div class="card-content table-responsive">
                    @if($students->count() > 0)
                    <table class="table table-hover" id="example">
                        <thead class="text-primary">
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->idstudent }}</td>
                                <td>
                                    {{-- Traemos la foto desde el modelo User --}}
                                    @if($student->user && $student->user->photo)
                                        <img src="{{ asset('backend/img/subidas/' . $student->user->photo) }}" width='90'>
                                    @else
                                        <img src="{{ asset('backend/img/user-default.png') }}" width='90'>
                                    @endif
                                </td>
                                <td>{{ $student->dni }}</td>
                                <td>{{ $student->full_name }}</td>
                                <td>
                                    {{-- Accedemos al correo mediante la relación con el usuario --}}
                                    <a href="mailto:{{ $student->user->email ?? '#' }}">
                                        {{ $student->user->email ?? 'Sin correo' }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge" style="background:#198754; color:white;">Alumno</span>
                                </td>
                                <td>
                                    @if($student->user && $student->user->status == '1')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->user->status == '1')
                                        <a href="{{ route('students.edit', $student->idstudent) }}" class="btn btn-warning text-white">
                                            <i class='material-icons' data-toggle='tooltip' title='Editar'>warning</i>
                                        </a>
                                        
                                        <a href="{{ route('students.delete', $student->idstudent) }}" class="btn btn-danger text-white">
                                            <i class='material-icons' data-toggle='tooltip' title='Desactivar'>delete_forever</i>
                                        </a>

                                        <a href="{{ route('userss.photo', $student->idstudent) }}" class="btn btn-info text-white">
                                            <i class='material-icons' data-toggle='tooltip' title='Foto de Perfil'>image</i>
                                        </a>

                                        <a href="{{ route('students.password', $student->idstudent) }}" class="btn btn-success text-white" data-toggle="tooltip" title="Contraseña">
                                            <i class='material-icons'>password</i>
                                        </a>

                                    @else
                                        <a href="{{ route('students.edit', $student->idstudent) }}" class="btn btn-warning text-white">
                                            <i class='material-icons' data-toggle='tooltip' title='Editar'>warning</i>
                                        </a>
                                    @endif
                                        
                                        <a href="{{ route('students.show', $student->idstudent) }}" class="btn btn-primary text-white">
                                            <i class='material-icons' data-toggle='tooltip' title='Información'>info</i>
                                        </a>


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-warning">
                        <strong>No hay datos!</strong> No se encontraron alumnos registrados con perfil completo.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


</x-layouts.app-layout>