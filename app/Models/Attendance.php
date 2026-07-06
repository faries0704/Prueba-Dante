<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';
    protected $primaryKey = 'idattendance';


    public $timestamps = false; 

    protected $fillable = [
        'idstudent',
        'idsection',
        'attendance_date',
        'status',
        'created_at'
    ];


    protected $casts = [
        'attendance_date' => 'date',
    ];
    

    public function student()
    {
        return $this->belongsTo(Student::class, 'idstudent', 'idstudent');
    }


    public function section()
    {
        return $this->belongsTo(Section::class, 'idsection', 'idsection');
    }

    public function scopeOfDate($query, $date)
    {
        return $query->where('attendance_date', $date);
    }

  
    public function scopeOfSection($query, $idsection)
    {
        return $query->where('idsection', $idsection);
    }

}