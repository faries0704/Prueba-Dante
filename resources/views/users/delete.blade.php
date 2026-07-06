<x-layouts.app-layout title="Desactivar Usuario">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Desactivar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">¿Está seguro de desactivar al usuario: <b>{{ $user->username }}</b>?</h4>
                </div>

                <div class="card-content table-responsive">
                    <form action="{{ route('users.destroy', $user->iduser) }}" method="POST" id="form-desactivar">
                        @csrf
                        @method('DELETE')
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <hr>
                                        <button type="button" onclick="confirmarDesactivacion()" class="btn btn-success text-white">
                                            Desactivar
                                        </button>
                                        <a class="btn btn-danger text-white" href="{{ route('users.index') }}">Cancelar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmarDesactivacion() {
        Swal.fire({
            title: '¿Confirmar desactivación?',
            text: "El usuario ya no podrá ingresar al sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-desactivar').submit();
            }
        })
    }
</script>
@endpush

</x-layouts.app-layout>