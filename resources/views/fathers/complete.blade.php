<x-layouts.app-layout title="Completar Perfil Padre">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Completar datos de Padre</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Datos del Apoderado para: <b>{{ $user->username }}</b></h4>
                </div>

                <div class="card-content table-responsive">
                    <div class="alert alert-warning">
                        <strong>Importante!</strong> Estos datos son necesarios para la comunicación con la institución.
                    </div>

                    <form action="{{ route('fathers.storeComplete') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="iduser" value="{{ $user->iduser }}">

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>DNI del Padre/Madre <span class="text-danger">*</span></label>
                                    <input type="text" name="dni" maxlength="8" class="form-control" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" required placeholder="ejm: 09876543" value="{{ old('dni') }}">
                                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-8">
                                <div class="form-group">
                                    <label>Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" required placeholder="ejm: Pedro Alcántara" value="{{ old('full_name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Ocupación / Trabajo</label>
                                    <input type="text" name="occupation" class="form-control" placeholder="ejm: Ingeniero, Comerciante">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Teléfono de Emergencia <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" required placeholder="ejm: 900123456">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Guardar Perfil Familiar</button>
                                <a class="btn btn-danger text-white" href="{{ route('users.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>