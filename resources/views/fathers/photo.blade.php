<x-layouts.app-layout title="Actualizar Foto">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fathers.index') }}">Padres</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Foto</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar foto de {{ $father->full_name }}</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('fathers.update-photo', $father->idfather) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>DNI del padre</label>
                                    <input type="text" readonly class="form-control" value="{{ $father->dni }}">
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
                                    <label>Foto del padre<span class="text-danger">*</span></label>
                                    <input type="file" required name="photo" id="imagen" onchange="readURL(this);" class="form-control-file">
                                    <br>
                                    <img id="blah" src="{{ asset('backend/img/subidas/' . ($father->user->photo ?? 'default.png')) }}" 
                                         width="120" class="img-thumbnail" alt="Vista previa">
                                    <small class="form-text text-muted text-danger">Importante seleccionar una imagen válida.</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Actualizar Foto</button>
                            <a class="btn btn-danger text-white" href="{{ route('fathers.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

</x-layouts.app-layout>