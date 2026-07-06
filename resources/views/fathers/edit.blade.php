<x-layouts.app-layout title="Actualizar Padre">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fathers.index') }}">Padres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar padres</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('fathers.update', $father->idfather) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del padre<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" maxlength="8" id="documento" name="dni" value="{{ old('dni', $father->dni) }}" class="form-control" required>
                                        <div class="input-group-append">
                                            <button type="button" id="buscar" class="btn btn-dark btn-sm m-0"><i class="material-icons">search</i></button>
                                        </div>
                                    </div>
                                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="full_name" value="{{ old('full_name', $father->full_name) }}" id="nombres" required>
                                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Profesión del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="profession" value="{{ old('profession', $father->profession) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Correo del padre<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $father->user->email) }}" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Teléfono del padre<span class="text-danger">*</span></label>
                                    <input type="text" maxlength="9" class="form-control" name="phone" value="{{ old('phone', $father->phone) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Dirección del padre<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address', $father->address) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado<span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{ $father->user->status == '1' ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $father->user->status == '0' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success text-white">Actualizar</button>
                            <a class="btn btn-danger text-white" href="{{ route('fathers.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>