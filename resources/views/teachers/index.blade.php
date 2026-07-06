<x-layouts.app-layout title="Listado de Docentes">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Docentes</li>
                </ol>
            </nav>
            
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Docentes</h4>
                    <a href="{{ route('teachers.create') }}" class="btn btn-danger text-white">
                        <i class='material-icons' data-toggle='tooltip' title='Add'>add</i>
                    </a>
                </div>

                <div class="card-content table-responsive">
                    @if($teachers->count() > 0)
                        <table class="table table-hover" id="example">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->idteacher }}</td>
                                        <td>
                                            @if($teacher->user && $teacher->user->photo)
                                                <img src="{{ asset('backend/img/subidas/' . $teacher->user->photo) }}" width='90'>
                                            @else
                                                <img src="{{ asset('backend/img/user-default.png') }}" width='90'>
                                            @endif
                                        </td>
                                        <td>{{ $teacher->dni }}</td>
                                        <td>{{ $teacher->full_name }}</td>
                                        <td>
                                            <a href="mailto:{{ $teacher->user->email ?? '' }}">
                                                {{ $teacher->user->email ?? 'Sin correo' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $teacher->phone }}">
                                                {{ $teacher->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($teacher->user && $teacher->user->idrole == '2')
                                                <span class="badge" style="background:#0dcaf0;">Docente</span>
                                            @else
                                                <span class="badge badge-danger">Sin Rol</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($teacher->user && $teacher->user->status == '1')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($teacher->user->status == '1')

                                            <a href="{{ route('teachers.edit', $teacher->idteacher) }}" class="btn btn-warning text-white">
                                                <i class='material-icons' title='Editar'>warning</i>
                                            </a>

                                            <a href="{{ route('teachers.delete', $teacher->idteacher) }}" class="btn btn-danger text-white">
                                                <i class='material-icons' title='Eliminar'>delete_forever</i>
                                            </a>

                                            <a href="{{ route('teachers.photo', $teacher->idteacher) }}" class="btn btn-info text-white">
                                                <i class='material-icons' title='Foto'>image</i>
                                            </a>

                                            <a href="{{ route('teachers.password', $teacher->idteacher) }}" class="btn btn-success text-white">
                                                <i class='material-icons' title='Contraseña'>password</i>
                                            </a>
                                            @else
                                                <a href="{{ route('teachers.edit', $teacher->idteacher) }}" class="btn btn-warning text-white">
                                                    <i class='material-icons' title='Editar'>warning</i>
                                                </a>
                                            @endif
                                                <a href="{{ route('teachers.show', $teacher->idteacher) }}" class="btn btn-primary text-white">
                                                    <i class='material-icons' title='Info'>info</i>
                                                </a>




                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning mt-3">
                            <strong>No hay datos de docentes disponibles!</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>