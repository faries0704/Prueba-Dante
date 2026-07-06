<x-layouts.app-layout title="Usuarios">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
                </ol>
            </nav>
            
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Usuarios del Sistema</h4>
                    <a href="{{ route('users.create') }}" class="btn btn-danger text-white">
                        <i class='material-icons' data-toggle='tooltip' title='Add'>add</i>
                    </a>
                </div>

                <div class="card-content table-responsive">
                    @if($users->count() > 0)
                        <table class="table table-hover" id="example">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->iduser }}</td>
                                        <td>
                                            <img src="{{ asset('backend/img/subidas/' . ($user->photo ?? 'default.png')) }}" width='90'>
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                        <td>
                                            @if($user->idrole == 1)
                                                <span class="badge" style="background:#dc3545; color:white;">
                                                    ADMIN
                                                </span>

                                            @elseif($user->idrole == 2)
                                                <span class="badge" style="background:#0dcaf0; color:black;">
                                                    TEACHER
                                                </span>

                                            @elseif($user->idrole == 3)
                                                <span class="badge" style="background:#198754; color:white;">
                                                    STUDENT
                                                </span>

                                            @else
                                                <span class="badge" style="background:#ffc107; color:black;">
                                                    FATHER
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status == 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Botones de acción estándar (Editar, Foto, Confirmar Desactivar) --}}
                                            <a href="{{ route('users.edit', $user->iduser) }}" class="btn btn-warning text-white">
                                                <i class='material-icons' data-toggle='tooltip' title='Editar Usuario'>warning</i>
                                            </a>
                                            <a href="{{ route('users.photo', $user->iduser) }}" class="btn btn-info text-white">
                                                <i class='material-icons' data-toggle='tooltip' title='Cambiar Foto'>image</i>
                                            </a>
                                            <a href="{{ route('users.confirm', $user->iduser) }}" class="btn btn-danger text-white">
                                                <i class='material-icons' data-toggle='tooltip' title='Desactivar'>delete_forever</i>
                                            </a>
                                            @if($user->idrole == 3 && !$user->student)
                                                <a href="{{ route('students.complete', $user->iduser) }}" class="btn btn-dark text-white">
                                                    <i class='material-icons' data-toggle='tooltip' title='Completar Alumno'>person_add</i>
                                                </a>
                                            @endif

                                            {{-- Si es TEACHER (Rol 2) y NO tiene datos en la tabla teachers --}}
                                            @if($user->idrole == 2 && !$user->teacher)
                                                <a href="{{ route('teachers.complete', $user->iduser) }}" class="btn btn-primary text-white">
                                                    <i class='material-icons' data-toggle='tooltip' title='Completar Docente'>school</i>
                                                </a>
                                            @endif

                                            {{-- Si es FATHER (Rol 4) y NO tiene datos en la tabla fathers --}}
                                            @if($user->idrole == 4 && !$user->father)
                                                <a href="{{ route('fathers.complete', $user->iduser) }}" class="btn btn-success text-white">
                                                    <i class='material-icons' data-toggle='tooltip' title='Completar Padre'>family_restroom</i>
                                                </a>
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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
        // Si ya existe una instancia, la destruimos para evitar el warning
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }

        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            // Esto permite que si se intenta inicializar de nuevo, no explote
            retrieve: true, 
            paging: true
        });
    });
    </script>
@endpush

</x-layouts.app-layout>