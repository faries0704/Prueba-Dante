<x-layouts.app-layout title="Actualizar Foto Docente">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Docentes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar Foto</li>
                </ol>
            </nav>
            
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar foto del Docente: <b>{{ $teacher->full_name }}</b></h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('teachers.photo_update', $teacher->idteacher) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>DNI del docente</label>
                                    <input type="text" value="{{ $teacher->dni }}" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre del docente</label>
                                    <input type="text" class="form-control" value="{{ $teacher->full_name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nueva Foto del docente<span class="text-danger">*</span></label>
                                    <input type="file" required name="foto" id="imagen" onchange="readURL(this);" class="form-control-file">
                                    
                                    <div class="mt-3">
                                        <label>Vista previa:</label><br>
                                        @if($teacher->user->photo)
                                            <img id="blah" src="{{ asset('backend/img/subidas/' . $teacher->user->photo) }}" width="150" class="img-thumbnail" alt="Foto actual" />
                                        @else
                                            <img id="blah" src="{{ asset('backend/img/user-default.png') }}" width="150" class="img-thumbnail" alt="Sin foto" />
                                        @endif
                                    </div>
                                    <small class="form-text text-muted"><span class="text-danger">Formatos permitidos: jpeg, png, jpg, gif. Máx 2MB.</span></small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Actualizar Foto</button>
                            <a class="btn btn-danger text-white" href="{{ route('teachers.index') }}">Cancelar</a>
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