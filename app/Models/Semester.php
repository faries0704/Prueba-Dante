<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';
    protected $primaryKey = 'idsemester';
    public $timestamps = false;

    protected $fillable = [
        'semester_name',
        'idperiod',
        'status'
    ];

    public function period()
    {
        return $this->belongsTo(Period::class, 'idperiod', 'idperiod');
    }
}