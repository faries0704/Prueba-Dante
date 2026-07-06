<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * HU-13: Constancia de Notas.
 * Documento emitido por la administración que certifica las
 * calificaciones obtenidas por un alumno en un periodo escolar.
 */
class GradeCertificate extends Model
{
    use HasFactory;

    protected $table = 'grade_certificates';
    protected $primaryKey = 'idgradecertificate';

    protected $fillable = [
        'certificate_code',
        'idstudent',
        'idenrollment',
        'idperiod',
        'purpose',
        'observations',
        'issue_date',
        'issued_by',
        'status',
    ];

    // Relación: la constancia pertenece a un alumno
    public function student()
    {
        return $this->belongsTo(Student::class, 'idstudent', 'idstudent');
    }

    // Relación: matrícula de referencia
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'idenrollment', 'idenrollment');
    }

    // Relación: periodo escolar de las notas certificadas
    public function period()
    {
        return $this->belongsTo(Period::class, 'idperiod', 'idperiod');
    }

    // Relación: usuario (administrador) que emitió la constancia
    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by', 'iduser');
    }
}
