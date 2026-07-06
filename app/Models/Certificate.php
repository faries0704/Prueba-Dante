<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * HU-12: Constancia de Estudios.
 * Representa un documento emitido por la administración que acredita
 * que un alumno cursa estudios en la institución.
 */
class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';
    protected $primaryKey = 'idcertificate';

    protected $fillable = [
        'certificate_code',
        'idstudent',
        'idenrollment',
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

    // Relación: matrícula que sustenta la constancia
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'idenrollment', 'idenrollment');
    }

    // Relación: usuario (administrador) que emitió la constancia
    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by', 'iduser');
    }
}
