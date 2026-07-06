<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';
    protected $primaryKey = 'idenrollment';
    public $timestamps = false;

    protected $fillable = [
        'idstudent',
        'idsection',
        'enrollment_date',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'idstudent');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'idsection');
    }
}