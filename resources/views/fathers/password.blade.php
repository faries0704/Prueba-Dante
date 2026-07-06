<x-layouts.app-layout title="Actualizar Contraseña">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fathers.index') }}">Padres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar contraseña</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar contraseña del padre</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('fathers.update-password', $father->idfather) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
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
                                    <input type="text" readonly class="form-control" value="{{ $father->full_name }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nueva contraseña<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" required placeholder="ejm: *******">
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar</button>
                                <a class="btn btn-danger text-white" href="{{ route('fathers.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>