<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $primaryKey = 'idsection';
    public $timestamps = false;

    protected $fillable = [
        'section_name',
        'idcourse',
        'capacity',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'idcourse');
    }
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'idsection');
    }
    
    public function students() {
        return $this->belongsToMany(Student::class, 'enrollments', 'idsection', 'idstudent')
                    ->withPivot('idenrollment', 'enrollment_date', 'status');
    }

}