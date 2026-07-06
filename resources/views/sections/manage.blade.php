<x-layouts.app-layout title="Gestionar Sección">

<div class="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">Sección</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Entrar a la sección</li>
                </ol>
            </nav>

            <div class="card mb-4">
                <div class="row gutters-sm p-3">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex flex-column align-items-center text-center">
                            @php $teacher = $section->course->teachers->first(); @endphp
                            <img src="{{ asset('backend/img/subidas/' . ($teacher->user->photo ?? 'default.png')) }}" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4>{{ $teacher->full_name ?? 'Sin Docente' }}</h4>
                                <p class="text-secondary mb-1"><strong>Docente</strong></p>
                                <p class="text-secondary mb-1">Curso: <span class="badge bg-danger text-white">{{ $section->course->course_name }}</span></p>
                                <p class="text-muted font-size-sm">Grado: <span class="badge bg-dark text-white">{{ $section->course->degree->degree_name }}</span></p>
                                <p class="text-muted font-size-sm">Subgrado: <span class="badge bg-success text-white">{{ $section->course->subgrade->subgrade_name }}</span></p>
                                <p class="text-muted font-size-sm">Sección: <span class="badge bg-warning text-white">{{ $section->section_name }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3"><h6 class="mb-0">DNI</h6></div>
                                <div class="col-sm-9 text-secondary">{{ $teacher->dni ?? 'N/A' }}</div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-3"><h6 class="mb-0">Correo</h6></div>
                                <div class="col-sm-9 text-secondary">{{ $teacher->user->email ?? 'N/A' }}</div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-3"><h6 class="mb-0">Teléfono</h6></div>
                                <div class="col-sm-9 text-secondary">{{ $teacher->phone ?? 'N/A' }}</div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-3"><h6 class="mb-0">Capacidad</h6></div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $section->students->count() }} / {{ $section->capacity }} Alumnos
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                <i class="material-icons">&#xE147;</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Mis alumnos de esta sección</h4>
                </div>
                <div class="card-content p-3">
                    @forelse($section->students as $student)
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-12">
                            <div class="card shadow-0 border rounded-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img src="{{ asset('backend/img/subidas/' . ($student->user->photo ?? 'student.png')) }}" class="w-100 rounded" />
                                        </div>
                                        <div class="col-md-6">
                                            <h5>{{ $student->full_name }}</h5>
                                            <p class="text-muted small">DNI: {{ $student->dni }}</p>
                                            <p class="text-muted small">Correo: {{ $student->user->email ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-3 border-start text-center">
                                            <form action="{{ route('sections.unenroll', $student->pivot->idenrollment) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm mt-4">RETIRAR</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-warning">No hay alumnos inscritos en esta sección.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Student -->

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="material-icons">person</i> REGISTRO DE ALUMNOS
                </h5>
            </div>

            <div class="modal-body">

                {{-- ID SECCIÓN --}}
                <input type="hidden" name="idsection" value="{{ $section->idsection }}">

                @if($availableStudents->count() > 0)

                    <div class="table-responsive">
                        <table class="table table-hover" id="studentsTable">

                            <thead class="text-primary">
                                <tr>
                                    <th>FOTO</th>
                                    <th>DNI</th>
                                    <th>NOMBRE</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($availableStudents as $student)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('backend/img/subidas/' . ($student->user->photo ?? 'student.png')) }}"
                                                 width="50" style="border-radius:5px;">
                                        </td>

                                        <td>{{ $student->dni }}</td>

                                        <td>{{ $student->full_name }}</td>

                                        <td>
                                            <form action="{{ route('sections.enroll') }}" method="POST">
                                                @csrf

                                                <input type="hidden" name="idsection" value="{{ $section->idsection }}">
                                                <input type="hidden" name="idstudent" value="{{ $student->idstudent }}">

                                                <button type="submit" class="btn btn-success btn-sm">
                                                    AÑADIR
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                @else
                    <div class="alert alert-warning">
                        <strong>No hay alumnos disponibles!</strong>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>

    @push('scripts')
        <script>
        $(document).ready(function () {

            let table = $('#studentsTable').DataTable({
                paging: true,
                searching: true,
                lengthChange: true,
                pageLength: 5,
                responsive: true,
                autoWidth: false,
                language: {
                    search: "Buscar alumno:",
                    lengthMenu: "Mostrar _MENU_ alumnos",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ alumnos",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    zeroRecords: "No se encontraron alumnos"
                }
            });

            $('#addStudentModal').on('shown.bs.modal', function () {
                table.columns.adjust().draw();
            });

        });
        </script>
    @endpush


</x-layouts.app-layout>