<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\FatherAttendanceController;
use App\Http\Controllers\FatherController;
use App\Http\Controllers\FatherDashboardController;
use App\Http\Controllers\FatherGradeController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\GradeCertificateController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentGradeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\SubgradeController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherGradeController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/father/dashboard', [FatherDashboardController::class, 'index'])->name('father.dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/mi-cuenta', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mi-cuenta/actualizar', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/mi-cuenta/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

//USUARIOS
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');


Route::get('/usuarios/{id}/editar', [UserController::class, 'edit'])->name('users.edit');
Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('users.update');

Route::get('/usuarios/{id}/confirmar', [UserController::class, 'confirm'])->name('users.confirm');
Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('users.destroy');


Route::get('/usuarios/{id}/foto', [UserController::class, 'editPhoto'])->name('users.photo');
Route::put('/usuarios/{id}/foto-update', [UserController::class, 'updatePhoto'])->name('users.photo.update');

Route::get('/usuarios/nuevo', [UserController::class, 'create'])->name('users.create');
Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('users.store');

Route::get('/alumnos/completar/{iduser}', [StudentController::class, 'complete'])->name('students.complete');
Route::post('/alumnos/guardar-perfil', [StudentController::class, 'storeComplete'])->name('students.storeComplete');


Route::get('/docentes/completar/{iduser}', [TeacherController::class, 'complete'])->name('teachers.complete');
Route::post('/docentes/guardar-perfil', [TeacherController::class, 'storeComplete'])->name('teachers.storeComplete');


Route::get('/padres/completar/{iduser}', [FatherController::class, 'complete'])->name('fathers.complete');
Route::post('/padres/guardar-perfil', [FatherController::class, 'storeComplete'])->name('fathers.storeComplete');



//STUDENTS
Route::get('/alumnos/mostrar', [StudentController::class, 'index'])->name('students.index');


Route::get('/alumnos/editar/{id}', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/alumnos/actualizar/{id}', [StudentController::class, 'update'])->name('students.update');

Route::get('/alumnos/informacion/{id}', [StudentController::class, 'show'])->name('students.show');


Route::get('/alumnos/eliminar/{id}', [StudentController::class, 'delete'])->name('students.delete');

Route::delete('/alumnos/desactivar/{id}', [StudentController::class, 'destroy'])->name('students.destroy');


Route::get('/alumnos/foto/{id}', [StudentController::class, 'editPhoto'])->name('userss.photo');
Route::put('/alumnos/foto-actualizar/{id}', [StudentController::class, 'updatePhoto'])->name('userss.photo_update');

Route::get('/alumnos/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/alumnos/store', [StudentController::class, 'store'])->name('students.store');

Route::get('students/{student}/password', [StudentController::class, 'password'])->name('students.password');
Route::put('students/{student}/update-password', [StudentController::class, 'updatePassword'])->name('students.update-password');

Route::middleware(['auth'])->group(function () {
    Route::get('/student/attendance', [StudentAttendanceController::class, 'index'])->name('student.attendance.index');
    Route::get('/student/attendance/details', [StudentAttendanceController::class, 'getDetails'])->name('student.attendance.details');
    Route::get('/student/calificaciones', [StudentGradeController::class, 'index'])->name('student.grades.index');
});


//TEACHERS
Route::get('/docentes/mostrar', [TeacherController::class, 'index'])->name('teachers.index');

Route::get('/docentes/editar/{id}', [TeacherController::class, 'edit'])->name('teachers.edit');
Route::put('/docentes/actualizar/{id}', [TeacherController::class, 'update'])->name('teachers.update');

Route::get('/docentes/eliminar/{id}', [TeacherController::class, 'delete'])->name('teachers.delete');
Route::delete('/docentes/desactivar/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

Route::get('/docentes/foto/{id}', [TeacherController::class, 'editPhoto'])->name('teachers.photo');
Route::put('/docentes/foto-actualizar/{id}', [TeacherController::class, 'updatePhoto'])->name('teachers.photo_update');

Route::get('/docentes/informacion/{id}', [TeacherController::class, 'show'])->name('teachers.show');

Route::get('/docentes/create', [TeacherController::class, 'create'])->name('teachers.create');
Route::post('/docentes/store', [TeacherController::class, 'store'])->name('teachers.store');

Route::get('teachers/{teacher}/password', [TeacherController::class, 'password'])->name('teachers.password');
Route::put('teachers/{teacher}/update-password', [TeacherController::class, 'updatePassword'])->name('teachers.update-password');


Route::middleware(['auth'])->group(function () {
    // Rutas para la gestión de asistencia del rol TEACHER
    Route::get('/teacher/attendance', [TeacherAttendanceController::class, 'index'])->name('teacher.attendance.index');
    Route::get('/teacher/attendance/create', [TeacherAttendanceController::class, 'create'])->name('teacher.attendance.create');
    Route::post('/teacher/attendance/store', [TeacherAttendanceController::class, 'store'])->name('teacher.attendance.store');
    
    // Rutas AJAX exclusivas para los filtros encadenados del profesor
    Route::get('/teacher/attendance/show-details', [TeacherAttendanceController::class, 'showDetails'])->name('teacher.attendance.showDetails');
    Route::get('/teacher/attendance/get-sections/{idcourse}', [TeacherAttendanceController::class, 'getSections']);
    Route::get('/teacher/attendance/get-students/{idsection}', [TeacherAttendanceController::class, 'getStudentsBySection']);
});

Route::middleware(['auth'])->group(function () {
    // Vistas principales
    Route::get('/teacher/grades', [TeacherGradeController::class, 'index'])->name('teacher.grades.index');
    Route::get('/teacher/grades/create', [TeacherGradeController::class, 'create'])->name('teacher.grades.create');
    Route::post('/teacher/grades/update', [TeacherGradeController::class, 'update'])->name('teacher.grades.update');
    
    // Consultas AJAX asistidas
    Route::get('/teacher/grades/get-sections/{idcourse}', [TeacherGradeController::class, 'getSections']);
    Route::get('/teacher/grades/get-evaluation-types', [TeacherGradeController::class, 'getEvaluationTypes'])->name('teacher.grades.getEvaluationTypes');
    Route::get('/teacher/grades/get-edit-data', [TeacherGradeController::class, 'getEditData'])->name('teacher.grades.getEditData');
});

//FATHERS
Route::get('/padres', [FatherController::class, 'index'])->name('fathers.index');


Route::get('/padres/editar/{id}', [FatherController::class, 'edit'])->name('fathers.edit');
Route::put('/padres/actualizar/{id}', [FatherController::class, 'update'])->name('fathers.update');


Route::get('/padres/eliminar/{id}', [FatherController::class, 'confirmDelete'])->name('fathers.confirm-delete');
Route::delete('/padres/desactivar/{id}', [FatherController::class, 'destroy'])->name('fathers.destroy');


Route::get('/padres/foto/{id}', [FatherController::class, 'editPhoto'])->name('fathers.photo');
Route::put('/padres/foto-actualizar/{id}', [FatherController::class, 'updatePhoto'])->name('fathers.update-photo');


Route::get('/padres/informacion/{id}', [FatherController::class, 'show'])->name('fathers.show');


Route::get('/padres/contrasena/{id}', [FatherController::class, 'editPassword'])->name('fathers.edit-password');
Route::put('/padres/contrasena-actualizar/{id}', [FatherController::class, 'updatePassword'])->name('fathers.update-password');


Route::get('/padres/hijos/{id}', [FatherController::class, 'showAddHijo'])->name('fathers.hijos');
Route::post('/padres/hijos-guardar', [FatherController::class, 'storeHijo'])->name('fathers.store-hijo');

Route::get('/padres/create', [FatherController::class, 'create'])->name('fathers.create');
Route::post('/padres/store', [FatherController::class, 'store'])->name('fathers.store');

Route::middleware(['auth'])->prefix('father')->name('father.')->group(function () {
    // Ruta de la vista principal con filtros
    Route::get('/attendance', [FatherAttendanceController::class, 'index'])->name('attendance.index');
    
    // Ruta AJAX para los detalles del modal
    Route::get('/attendance/details', [FatherAttendanceController::class, 'getDetails'])->name('attendance.details');
});

Route::middleware(['auth'])->group(function () {
    // Calificaciones para Padres de Familia
    Route::get('/father/grades', [FatherGradeController::class, 'index'])->name('father.grades.index');
});


// PERIOD
Route::get('/periodos', [PeriodController::class, 'index'])->name('periods.index');

Route::get('/periodos/nuevo', [PeriodController::class, 'create'])->name('periods.create');
Route::post('/periodos/guardar', [PeriodController::class, 'store'])->name('periods.store');

Route::get('/periodos/{id}/editar', [PeriodController::class, 'edit'])->name('periods.edit');
Route::put('/periodos/{id}/actualizar', [PeriodController::class, 'update'])->name('periods.update');


Route::get('/periodos/{id}/eliminar', [PeriodController::class, 'showDelete'])->name('periods.showDelete');
Route::delete('/periodos/{id}/desactivar', [PeriodController::class, 'destroy'])->name('periods.destroy');


//SEMESTRE
Route::get('/semestres', [SemesterController::class, 'index'])->name('semesters.index');

Route::get('/semestres/nuevo', [SemesterController::class, 'create'])->name('semesters.create');
Route::post('/semestres/guardar', [SemesterController::class, 'store'])->name('semesters.store');

Route::get('/semestres/{id}/editar', [SemesterController::class, 'edit'])->name('semesters.edit');
Route::put('/semestres/{id}/actualizar', [SemesterController::class, 'update'])->name('semesters.update');

Route::get('/semestres/{id}/eliminar', [SemesterController::class, 'showDelete'])->name('semesters.showDelete');
Route::delete('/semestres/{id}/desactivar', [SemesterController::class, 'destroy'])->name('semesters.destroy');


//DEGREES
Route::get('/grados', [DegreeController::class, 'index'])->name('degrees.index');

Route::get('/grados/nuevo', [DegreeController::class, 'create'])->name('degrees.create');
Route::post('/grados/guardar', [DegreeController::class, 'store'])->name('degrees.store');

Route::get('/grados/{id}/editar', [DegreeController::class, 'edit'])->name('degrees.edit');
Route::put('/grados/{id}/actualizar', [DegreeController::class, 'update'])->name('degrees.update');

Route::get('/grados/{id}/eliminar', [DegreeController::class, 'showDelete'])->name('degrees.showDelete');
Route::delete('/grados/{id}/desactivar', [DegreeController::class, 'destroy'])->name('degrees.destroy');


//SUBGRADES
Route::get('/subgrados', [SubgradeController::class, 'index'])->name('subgrades.index');

Route::get('/subgrados/nuevo', [SubgradeController::class, 'create'])->name('subgrades.create');
Route::post('/subgrados/guardar', [SubgradeController::class, 'store'])->name('subgrades.store');

Route::get('/subgrados/{id}/editar', [SubgradeController::class, 'edit'])->name('subgrades.edit');
Route::put('/subgrados/{id}/actualizar', [SubgradeController::class, 'update'])->name('subgrades.update');

Route::get('/subgrados/{id}/eliminar', [SubgradeController::class, 'showDelete'])->name('subgrades.showDelete');
Route::delete('/subgrados/{id}/desactivar', [SubgradeController::class, 'destroy'])->name('subgrades.destroy');


//COURSES
Route::get('/cursos', [CourseController::class, 'index'])->name('courses.index');

Route::get('/cursos/nuevo', [CourseController::class, 'create'])->name('courses.create');
Route::post('/cursos/guardar', [CourseController::class, 'store'])->name('courses.store');

Route::get('/cursos/{id}/editar', [CourseController::class, 'edit'])->name('courses.edit');
Route::put('/cursos/{id}/actualizar', [CourseController::class, 'update'])->name('courses.update');

Route::get('/cursos/{id}/eliminar', [CourseController::class, 'showDelete'])->name('courses.showDelete');
Route::delete('/cursos/{id}/desactivar', [CourseController::class, 'destroy'])->name('courses.destroy');

Route::get('/cursos/{id}/foto', [CourseController::class, 'editPhoto'])->name('courses.editPhoto');
Route::put('/cursos/{id}/foto-actualizar', [CourseController::class, 'updatePhoto'])->name('courses.updatePhoto');

//SECTIONS
Route::get('/secciones', [SectionController::class, 'index'])->name('sections.index');

Route::get('/secciones/nuevo', [SectionController::class, 'create'])->name('sections.create');
Route::post('/secciones/guardar', [SectionController::class, 'store'])->name('sections.store');


Route::get('/secciones/{id}/editar', [SectionController::class, 'edit'])->name('sections.edit');
Route::put('/secciones/{id}', [SectionController::class, 'update'])->name('sections.update');

Route::get('/secciones/{id}/eliminar', [SectionController::class, 'showDelete'])->name('sections.showDelete');
Route::delete('/secciones/{id}/desactivar', [SectionController::class, 'destroy'])->name('sections.destroy');

// Ver detalles de la sección y lista de alumnos
Route::get('/secciones/{id}/gestionar', [SectionController::class, 'manage'])->name('sections.manage');


Route::post('/secciones/inscribir', [SectionController::class, 'enrollStudent'])->name('sections.enroll');
Route::delete('/secciones/retirar/{id}', [SectionController::class, 'unenrollStudent'])->name('sections.unenroll');




//NOTAS / GRADES
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');

Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
Route::post('/grades/store', [GradeController::class, 'store'])->name('grades.store');

// AJAX para cargar tipos de evaluación
Route::get('/get-evaluation-types', [GradeController::class, 'getEvaluationTypes'])->name('grades.getEvaluationTypes');;


Route::get('/grades/get-edit-data', [GradeController::class, 'getEditData'])->name('grades.getEditData');
Route::post('/grades/update', [GradeController::class, 'update'])->name('grades.update');


//ASISTENCIA / ATTENDANCE
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');

Route::get('/attendance/get-edit-data', [AttendanceController::class, 'getEditData'])->name('attendance.getEditData');
Route::post('/attendance/update', [AttendanceController::class, 'update'])->name('attendance.update');

Route::get('/attendance/show-details', [AttendanceController::class, 'showDetails'])->name('attendance.showDetails');


//mensajeria 

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/show-details', [MessageController::class, 'showDetails'])->name('messages.showDetails');
    Route::get('/messages/search-users', [MessageController::class, 'searchUsers'])->name('messages.searchUsers');
    // NUEVA RUTA PARA GUARDAR MENSAJE
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');

    // RUTAS PARA BANDEJA DE SALIDA
    Route::get('/messages/outbox', [MessageController::class, 'outbox'])->name('messages.outbox');
    Route::get('/messages/outbox/details', [MessageController::class, 'showOutboxDetails'])->name('messages.outboxDetails');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/show-details', [NotificationController::class, 'showDetails'])->name('notifications.showDetails');
});





// Rutas para los selects dinámicos
Route::get('/get-grades/{id}', [FilterController::class, 'getGrades']);
Route::get('/get-subgrades/{id}', [FilterController::class, 'getSubgrades']);
Route::get('/get-courses/{id}', [FilterController::class, 'getCourses']);
//Route::get('/get-sections/{idcourse}', [FilterController::class, 'getSections']);

Route::get('/get-sections/{id}', [FilterController::class, 'getSections']);
Route::get('/get-students-section/{id}', [FilterController::class, 'getStudentsBySection']);



//CONSTANCIAS DE ESTUDIOS (HU-12)
Route::middleware(['auth'])->group(function () {
    Route::get('/constancias', [CertificateController::class, 'index'])->name('certificates.index');

    Route::get('/constancias/nuevo', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/constancias/guardar', [CertificateController::class, 'store'])->name('certificates.store');

    // AJAX para la visualizacion previa de datos del alumno
    Route::get('/constancias/datos-alumno/{id}', [CertificateController::class, 'getStudentData'])->name('certificates.studentData');

    Route::get('/constancias/{id}/vista-previa', [CertificateController::class, 'preview'])->name('certificates.preview');
    Route::get('/constancias/{id}/imprimir', [CertificateController::class, 'print'])->name('certificates.print');

    Route::get('/constancias/{id}/anular', [CertificateController::class, 'showDelete'])->name('certificates.showDelete');
    Route::delete('/constancias/{id}/desactivar', [CertificateController::class, 'destroy'])->name('certificates.destroy');
});


//CONSTANCIAS DE NOTAS (HU-13)
Route::middleware(['auth'])->group(function () {
    Route::get('/constancias-notas', [GradeCertificateController::class, 'index'])->name('gradeCertificates.index');

    Route::get('/constancias-notas/nuevo', [GradeCertificateController::class, 'create'])->name('gradeCertificates.create');
    Route::post('/constancias-notas/guardar', [GradeCertificateController::class, 'store'])->name('gradeCertificates.store');

    // AJAX para la consulta de notas del alumno
    Route::get('/constancias-notas/datos-alumno/{id}', [GradeCertificateController::class, 'getStudentData'])->name('gradeCertificates.studentData');

    Route::get('/constancias-notas/{id}/vista-previa', [GradeCertificateController::class, 'preview'])->name('gradeCertificates.preview');
    Route::get('/constancias-notas/{id}/imprimir', [GradeCertificateController::class, 'print'])->name('gradeCertificates.print');

    Route::get('/constancias-notas/{id}/anular', [GradeCertificateController::class, 'showDelete'])->name('gradeCertificates.showDelete');
    Route::delete('/constancias-notas/{id}/desactivar', [GradeCertificateController::class, 'destroy'])->name('gradeCertificates.destroy');
});
