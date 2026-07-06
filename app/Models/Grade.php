<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;
    protected $table = 'grades';
    protected $primaryKey = 'idgrade';
    public $timestamps = false;

    protected $fillable = ['idstudent', 'idcourse', 'idsection', 'idevaluation_type', 'grade', 'created_at'];

    public function student() {
        return $this->belongsTo(Student::class, 'idstudent');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'idcourse');
    }
}