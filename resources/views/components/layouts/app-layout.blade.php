<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Panel' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css') }}">
    <link rel="shortcut icon" href="{{ asset('backend/img/favicon.png') }}" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/css/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/buttonsdataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/font.css') }}">
    @stack('styles')
</head>

<body>
<div class="wrapper">
    <div class="body-overlay"></div>

    <!-- SIDEBAR -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>
                <img src="{{ asset('backend/img/favicon.png') }}" class="img-fluid" />
                <span>I.E. La Inmaculada</span>
            </h3>
        </div>

        <ul class="list-unstyled components">

            {{-- ADMINISTRADOR --}}
            @if(Auth::user()->idrole == 1)

                <li class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="material-icons">dashboard</i> Panel Control
                    </a>
                </li>

                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="material-icons">person</i> Usuarios
                    </a>
                </li>

                <li class="{{ request()->routeIs('periods.*') ? 'active' : '' }}">
                    <a href="{{ route('periods.index') }}">
                        <i class="material-icons">calendar_month</i> Periodo Escolar
                    </a>
                </li>
                <li class="{{ request()->routeIs('semesters.*') ? 'active' : '' }}">
                    <a href="{{ route('semesters.index') }}">
                        <i class="material-icons">school</i> Semestres
                    </a>
                </li>
                <li class="{{ request()->routeIs('degrees.*') ? 'active' : '' }}">
                    <a href="{{ route('degrees.index') }}">
                        <i class="material-icons">square_foot</i> Grado
                    </a>
                </li>

                <li class="{{ request()->routeIs('subgrades.*') ? 'active' : '' }}">
                    <a href="{{ route('subgrades.index') }}">
                        <i class="material-icons">history_edu</i> Subgrado
                    </a>
                </li>

                
                <li class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <a href="{{ route('courses.index') }}">
                        <i class="material-icons">school</i> Cursos
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('sections.*') ? 'active' : '' }}">
                    <a href="{{ route('sections.index') }}">
                        <i class="material-icons">card_membership</i> Sección
                    </a>
                </li>
            
                <li class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <a href="{{ route('teachers.index') }}">
                        <i class="material-icons">badge</i> Docentes
                    </a>
                </li>

                <li class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <a href="{{ route('students.index') }}">
                        <i class="material-icons">face</i> Alumnos
                    </a>
                </li>
                <li class="{{ request()->routeIs('fathers.*') ? 'active' : '' }}">
                    <a href="{{ route('fathers.index') }}">
                        <i class="material-icons">group</i> Padres
                    </a>
                </li>
            @endif

            {{-- ADMINISTARDOR --}}
            @if(Auth::user()->idrole == 1)

                <li class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.index') }}">
                        <i class="material-icons">event_available</i> Asistencias
                    </a>
                </li>

                <li class="{{ request()->routeIs('grades.*') ? 'active' : '' }}">
                    <a href="{{ route('grades.index') }}">
                        <i class="material-icons">verified</i> Calificaciones
                    </a>
                </li>

                <li class="{{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                    <a href="{{ route('certificates.index') }}">
                     <i class="material-icons">description</i> Constancias
                    </a>
                </li>

                <li class="{{ request()->routeIs('gradeCertificates.*') ? 'active' : '' }}">
                    <a href="{{ route('gradeCertificates.index') }}">
                     <i class="material-icons">grading</i> Constancias de Notas
                    </a>
                </li>

            @endif

            {{-- DOCENTE --}}

            @if(Auth::user()->idrole == 2)

                <li class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('teacher.dashboard') }}">
                        <i class="material-icons">dashboard</i> Panel Control
                    </a>
                </li>
            
                <li class="{{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.attendance.index') }}">
                        <i class="material-icons">event_available</i>
                        Asistencias
                    </a>
                </li>

                <li class="{{ request()->routeIs('teacher.grades.*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.grades.index') }}">
                        <i class="material-icons">verified</i> Calificaciones
                    </a>
                </li>
            @endif


            {{-- ALUMNO --}}

            @if(Auth::user()->idrole == 3)

                <li class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('student.dashboard') }}">
                        <i class="material-icons">dashboard</i> Panel Control
                    </a>
                </li>
            
                <li class="{{ request()->routeIs('student.attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('student.attendance.index') }}">
                        <i class="material-icons">event_available</i>
                        Asistencias
                    </a>
                </li>

                <li class="{{ request()->routeIs('student.grades.*') ? 'active' : '' }}">
                    <a href="{{ route('student.grades.index') }}">
                        <i class="material-icons">verified</i> Calificaciones
                    </a>
                </li>
            @endif

            {{-- APODERADO --}}

            @if(Auth::user()->idrole == 4)

                <li class="{{ request()->routeIs('father.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('father.dashboard') }}">
                        <i class="material-icons">dashboard</i> Panel Control
                    </a>
                </li>
                        
                <li class="{{ request()->routeIs('father.attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('father.attendance.index') }}">
                        <i class="material-icons">event_available</i>
                        Asistencias
                    </a>
                </li>

                <li class="{{ request()->routeIs('father.grades.*') ? 'active' : '' }}">
                    <a href="{{ route('father.grades.index') }}">
                        <i class="material-icons">verified</i> Calificaciones
                    </a>
                </li>
            @endif

            <li class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <a href="{{ route('messages.index') }}">
                    <i class="material-icons">mail</i> Mensajes
                </a>
            </li>

            <li class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <a href="{{ route('notifications.index') }}">
                    <i class="material-icons">notifications</i> Notificaciones
                </a>
            </li>

        
        </ul>
    </nav>
    <!-- CONTENT -->
    <div id="content">

        <!-- NAVBAR -->
        <div class="top-navbar">
            <nav class="navbar navbar-expand-lg">
                <button type="button" id="sidebar-collapse" class="d-xl-block d-lg-block d-md-none d-none">
                    <span class="material-icons">arrow_back_ios</span>
                </button>

                <a class="navbar-brand" href="#">Panel Control</a>

                <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarcollapse" aria-controls="navbarcollapse" aria-expanded="false" aria-label="Toggle">
                    <span class="material-icons">more_vert</span>
                </button>

                <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none" id="navbarcollapse">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item dropdown active">
                            <a class="nav-link" href="#" data-bs-toggle="dropdown">
                                <span class="material-icons">person</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                                    href="{{ route('profile.edit') }}">
                                        Mi cuenta
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}#password-section">
                                        Contraseña
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Salir
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="material-icons">settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- CONTENIDO DINÁMICO -->
        <div class="container mt-4">
            {{ $slot }}
        </div>

    </div>
</div>

<div class="modal fade" id="offlineModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white justify-content-center py-3">
                <h5 class="modal-title text-white d-flex align-items-center gap-2 fw-bold">
                    <span class="material-icons animate-pulse">cloud_off</span> 
                    CONEXIÓN INTERRUMPIDA
                </h5>
            </div>
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <span class="material-icons" style="font-size: 70px;">wifi_off</span>
                </div>
                <h4 class="fw-bold text-dark mb-2">Te has quedado sin internet</h4>
                <p class="text-muted px-3">
                    Hemos pausado tus acciones para proteger tus datos escolares. El sistema se desbloqueará automáticamente tan pronto como se recupere el acceso a la red.
                </p>
                <div class="d-flex align-items-center justify-content-center gap-2 text-primary mt-4 fw-bold">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <span>Intentando restablecer la conexión...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (solo una vez y antes de todo lo que lo use) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap (incluye Popper, no necesitas popper aparte) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="{{ asset('backend/js/datatable.js') }}"></script>
<script src="{{ asset('backend/js/datatablebuttons.js') }}"></script>
<script src="{{ asset('backend/js/jszip.js') }}"></script>
<script src="{{ asset('backend/js/pdfmake.js') }}"></script>
<script src="{{ asset('backend/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend/js/buttonshtml5.js') }}"></script>
<script src="{{ asset('backend/js/buttonsprint.js') }}"></script>


<!-- Scripts personalizados -->
<script src="{{ asset('backend/js/letra.js') }}"></script>
<script src="{{ asset('backend/js/condn.js') }}"></script>

    <script>
        $(document).ready(function () {

            // DataTables
            if ($('#example').length) {
                $('#example').DataTable();
            }

            if ($('#example1').length) {
                $('#example1').DataTable();
            }

            // Sidebar toggle
            $("#sidebar-collapse").on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            $(".more-button, .body-overlay").on('click', function() {
                $('#sidebar, .body-overlay').toggleClass('show-nav');
            });

        

        // -----------------------------------------------------------------
        // CONTROL AUTOMÁTICO DE INTERNET (MODAL OFFLINE)
        // -----------------------------------------------------------------
        const offlineModal = new bootstrap.Modal(document.getElementById('offlineModal'));
        let isModalOpen = false;

        function handleOffline() {
            if (!isModalOpen) {
                offlineModal.show();
                isModalOpen = true;
            }
        }

        function handleOnline() {
            if (isModalOpen) {
                offlineModal.hide();
                isModalOpen = false;

                // Alerta flotante usando SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: '¡Conexión Restaurada!',
                    text: 'Se ha restablecido el acceso a internet. Puedes continuar operando.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            }
        }

        // Listeners del navegador
        window.addEventListener('offline', handleOffline);
        window.addEventListener('online', handleOnline);

        // Verificación en el arranque de la app
        if (!navigator.onLine) {
            handleOffline();
        }

    });

    </script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    @if(session('desactivado') == 'OK')
    <script>
        Swal.fire(
            '¡Desactivado!',
            'El registro ha sido desactivado con éxito.',
            'success'
        )
    </script>
    @endif


    @if(session('actualizado') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'El registro se modificó correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif


    @if(session('foto_actualizada') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Imagen Actualizada!',
            text: 'La foto de perfil se cambió correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('creado') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Perfil Creado!',
            text: 'El usuario ha sido registrado y vinculado con éxito.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('perfil_completado') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Perfil Vinculado!',
            text: 'Los datos del alumno se registraron correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('perfil_docente') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Docente Registrado!',
            text: 'Los datos profesionales se vincularon correctamente.',
            confirmButtonColor: '#007bff',
        })
    </script>
    @endif

    @if(session('perfil_padre') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Perfil Familiar Listo!',
            text: 'Los datos del padre/apoderado han sido vinculados.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif
    @if(session('update_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'Los datos del alumno se actualizaron correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('delete_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Registro Desactivado',
            text: 'El alumno ha sido inhabilitado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif
    @if(session('photo_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Foto Actualizada',
            text: 'La imagen de perfil se cambió correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('deletee_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Desactivado',
            text: 'El docente ha sido desactivado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif


    @if(session('updatee_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'Los datos del apoderado se actualizaron correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('delete_successApo') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Desactivado',
            text: 'El apoderado ha sido desactivado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif


    @if(session('dpassword_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Contraseña Actualizada!',
            text: 'La clave de acceso ha sido cambiada correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('add_hijo_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Alumno Agregado!',
            text: 'El alumno se ha vinculado correctamente al Apoderado.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('add_successSemes') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Logrado!',
            text: 'El semestre ha sido registrado exitosamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('update_successSemes') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'Los datos del semestre se han actualizado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('delete_successSemes') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Desactivado!',
            text: 'El semestre ha sido desactivado con éxito.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('add_successDegre') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Registrado!',
            text: 'El grado académico se ha creado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('update_successDegre') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'Los datos del grado académico se han modificado con éxito.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('delete_successDegree') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Desactivado!',
            text: 'El grado académico ahora se encuentra en estado inactivo.',
            confirmButtonColor: '#dc3545',
        })
    </script>
    @endif

    @if(session('add_successSubgrade') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Registrado!',
            text: 'El subgrado académico se ha creado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('update_successSubgrade') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'El subgrado se ha modificado correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('delete_successSubgrade') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Desactivado!',
            text: 'El subgrado académico ahora se encuentra en estado inactivo.',
            confirmButtonColor: '#dc3545',
        })
    </script>
    @endif

    @if(session('add_successCourse') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Curso Registrado!',
            text: 'El curso y el docente han sido asignados correctamente.',
            confirmButtonColor: '#28a745',
        })
    </script>
    @endif

    @if(session('delete_successCourse') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Desactivado!',
            text: 'El curso ahora se encuentra en estado inactivo.',
            confirmButtonColor: '#dc3545'
        });
    </script>
    @endif

    @if(session('add_successSection') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Secciones Registradadas!',
            text: 'La sección se ha registrado correctamente.',
            confirmButtonColor: '#dc3545'
        });
    </script>
    @endif

    @if(session('update_successSection') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Actualizado!',
            text: 'La sección se ha actualizado correctamente.',
            confirmButtonColor: '#dc3545'
        });
    </script>
    @endif

    @if(session('delete_successSection') == 'OK')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Desactivado!',
            text: 'El registro se ha desactivado correctamente.',
            confirmButtonColor: '#dc3545'
        });
    </script>
    @endif

    @if(session('attendance_success') == 'OK')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Registrado!',
            text: 'La asistencia se registró correctamente.',
            confirmButtonColor: '#28a745',
        });
    </script>
    @endif

    @if(session('attendance_error') == 'OK')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al registrar la asistencia.',
            confirmButtonColor: '#dc3545',
        });
    </script>
    @endif



</body>
</html>