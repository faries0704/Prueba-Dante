<x-layouts.app-layout title="Actualizar Curso">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Actualizar</li>
                </ol>
            </nav>

            <div class="card" style="min-height:485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Actualizar curso: {{ $course->course_name }}</h4>
                </div>
                <div class="card-content">
                    <div class="alert alert-warning">
                        <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
                    </div>

                    <form action="{{ route('courses.update', $course->idcourse) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">

                            {{-- PERIODO --}}
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">

                                    <label>
                                        Periodo
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-control"
                                            name="idsemester"
                                            required>

                                        @foreach($periods as $semester)

                                            <option value="{{ $semester->idsemester }}"
                                                {{ $course->idsemester == $semester->idsemester ? 'selected' : '' }}>

                                                {{ $semester->period->period_name ?? 'Sin periodo' }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>
                            </div>

                            {{-- GRADO --}}
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">

                                    <label>
                                        Grado
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-control"
                                            name="iddegree"
                                            required>

                                        @foreach($degrees as $degree)

                                            <option value="{{ $degree->iddegree }}"
                                                {{ $course->iddegree == $degree->iddegree ? 'selected' : '' }}>

                                                {{ $degree->degree_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>
                            </div>

                            {{-- SUBGRADO --}}
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">

                                    <label>
                                        Subgrado
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-control"
                                            name="idsubgrade"
                                            required>

                                        @foreach($subgrades as $subgrade)

                                            <option value="{{ $subgrade->idsubgrade }}"
                                                {{ $course->idsubgrade == $subgrade->idsubgrade ? 'selected' : '' }}>

                                                {{ $subgrade->subgrade_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Nombre del curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="course_name" value="{{ $course->course_name }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Docente <span class="text-danger">*</span></label>
                                    <select class="form-control" name="idteacher" required>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->idteacher }}" 
                                                {{ $course->teachers->contains('idteacher', $teacher->idteacher) ? 'selected' : '' }}>
                                                {{ $teacher->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{ $course->status == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $course->status == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success text-white">Actualizar</button>
                                <a class="btn btn-danger text-white" href="{{ route('courses.index') }}">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app-layout>