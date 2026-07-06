<x-layouts.app-layout title="Lista de Padres">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Padres</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Padres de Familia</h4>
                    <a href="{{ route('fathers.create') }}" class="btn btn-danger text-white">
                        <i class='material-icons' data-toggle='tooltip' title='Add'>add</i>
                    </a>
                </div>

                <div class="card-content table-responsive">
                    @if($fathers->count() > 0)
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
                                @foreach($fathers as $father)
                                    <tr>
                                        <td>{{ $father->idfather }}</td>
                                        <td>
                                            @if($father->user && $father->user->photo)
                                                <img src="{{ asset('backend/img/subidas/' . $father->user->photo) }}" width='90'>
                                            @else
                                                <img src="{{ asset('backend/img/user-default.png') }}" width='90'>
                                            @endif
                                        </td>
                                        <td>{{ $father->dni }}</td>
                                        <td>{{ $father->full_name }}</td>
                                        <td>
                                            <a href="mailto:{{ $father->user->email }}">{{ $father->user->email }}</a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $father->phone }}">{{ $father->phone }}</a>
                                        </td>
                                        <td>
                                            @if($father->user->idrole == '4')
                                                <span class="badge" class="badge" style="background:#ffc107; color:black;">Padre</span>
                                            @else
                                                <span class="badge badge-danger">Error</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($father->user->status == '1')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                        
                                            @if($father->user->status == '1')
                                                <a href="{{ route('fathers.edit', $father->idfather) }}" class="btn btn-warning text-white" data-toggle="tooltip" title="Editar">
                                                    <i class='material-icons'>warning</i>
                                                </a>

                                                <a href="{{ route('fathers.confirm-delete', $father->idfather) }}" class="btn btn-danger text-white" data-toggle="tooltip" title="Desactivar">
                                                    <i class='material-icons'>delete_forever</i>
                                                </a>
                                                <a href="{{ route('fathers.hijos', $father->idfather) }}" class="btn btn-secondary text-white" data-toggle="tooltip" title="Hijos">
                                                    <i class='material-icons'>face</i>
                                                </a>
                                                <a href="{{ route('fathers.photo', $father->idfather) }}" class="btn btn-info text-white" data-toggle="tooltip" title="Cambiar Foto">
                                                    <i class='material-icons'>image</i>
                                                </a>
                                                <a href="{{ route('fathers.edit-password', $father->idfather) }}" class="btn btn-success text-white" data-toggle="tooltip" title="Contraseña">
                                                    <i class='material-icons'>password</i>
                                                </a>
                                            @else
                                                <a href="{{ route('fathers.edit', $father->idfather) }}" class="btn btn-warning text-white" data-toggle="tooltip" title="Editar">
                                                    <i class='material-icons'>warning</i>
                                                </a>
                                            @endif

                                            <a href="{{ route('fathers.show', $father->idfather) }}" class="btn btn-primary text-white" data-toggle="tooltip" title="Info">
                                                <i class='material-icons'>info</i>
                                            </a>
                                            
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
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }

        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],

            retrieve: true, 
            paging: true
        });
    });
    </script>
    @endpush

</x-layouts.app-layout>