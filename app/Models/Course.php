<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'idcourse';
    public $timestamps = false;

    protected $fillable = [
        'course_name',
        'iddegree',
        'idsubgrade',
        'photo',
        'status',
        'idsemester'
    ];

    public function degree()
    {
        return $this->belongsTo(Degree::class, 'iddegree');
    }

    public function subgrade()
    {
        return $this->belongsTo(Subgrade::class, 'idsubgrade');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'idsemester');
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'course_teacher',
            'idcourse',
            'idteacher'
        );
    }
}