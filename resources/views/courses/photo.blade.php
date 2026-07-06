<x-layouts.app-layout title="Actualizar Foto">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar Foto</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar foto del curso: {{ $course->course_name }}</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('courses.updatePhoto', $course->idcourse) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre del curso</label>
                                    <input type="text" disabled class="form-control" value="{{ $course->course_name }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Foto del curso <span class="text-danger">*</span></label>
                                    <input type="file" required name="photo" class="form-control-file" onchange="readURL(this);">
                                    <div class="mt-3">
                                        <img id="blah" src="{{ asset('backend/img/subidas/' . $course->photo) }}" 
                                             alt="Vista previa" style="max-width:150px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                    <small class="form-text text-muted">Seleccione una imagen nueva para reemplazar la actual.</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Actualizar Foto</button>
                            <a class="btn btn-danger text-white" href="{{ route('courses.index') }}">Cancelar</a>
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