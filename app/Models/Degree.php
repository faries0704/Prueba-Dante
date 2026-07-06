<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Degree extends Model
{
    use HasFactory;

    protected $table = 'degrees';
    protected $primaryKey = 'iddegree';
    public $timestamps = false;

    protected $fillable = [
        'degree_name',
        'status',
        'idsemester'
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'idsemester', 'idsemester');
    }
}