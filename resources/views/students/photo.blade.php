<x-layouts.app-layout title="Cambiar Foto de Alumno">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel Control</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cambiar Foto</li>
                </ol>
            </nav>
            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar Foto de Perfil</h4>
                </div>

                <div class="card-content">
                    <div class="alert alert-info">
                        Seleccione una imagen nueva para el usuario
                    </div>

                    <form action="{{ route('userss.photo_update', $student->idstudent) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Seleccionar Imagen</label>
                                    <input type="file" name="foto" class="form-control" onchange="readURL(this);" required>
                                    @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <label>Previsualización:</label><br>
                                @if($user->photo)
                                    <img id="blah" src="{{ asset('backend/img/subidas/' . $user->photo) }}" width="150" class="img-thumbnail shadow-sm">
                                @else
                                    <img id="blah" src="{{ asset('backend/img/ustudent-default.png') }}" width="150" class="img-thumbnail shadow-sm">
                                @endif
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white">Actualizar Foto</button>
                            <a href="{{ route('students.index') }}" class="btn btn-danger text-white">Cancelar</a>
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