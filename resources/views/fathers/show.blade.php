<x-layouts.app-layout title="Información del Padre">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fathers.index') }}">Padres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Informacion</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Informacion de los padres</h4>
                </div>

                <div class="card-content table-responsive">
                    <form>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del padre<span class="text-danger">*</span></label>
                                    <input type="text" readonly value="{{ $father->dni }}" class="form-control">
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $father->full_name }}" readonly>
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Profesion del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $father->profession }}" readonly>
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Correo del padre<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" value="{{ $father->user->email }}" readonly>
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Telefono del padre<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $father->phone }}" class="form-control" readonly>
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Direccion del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $father->address }}" readonly>
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Foto del padre<span class="text-danger">*</span></label>
                                    <br>
                                    <img src="{{ asset('backend/img/subidas/' . ($father->user->photo ?? 'default.png')) }}" 
                                         width="100" style="max-width:90px;" class="img-thumbnail" />
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <br>
                                    @if($father->user->status == '1')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Usuario del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $father->user->username }}" readonly>
                                    <small class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Rol</label>
                                    <br>
                                    @if($father->user->idrole == '4')
                                        <span class="badge badge-success">Padre</span>
                                    @else
                                        <span class="badge badge-danger">Error</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <a class="btn btn-danger text-white" href="{{ route('fathers.index') }}">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>