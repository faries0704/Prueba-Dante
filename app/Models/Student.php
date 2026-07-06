<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'idstudent';
    
    const UPDATED_AT = null;

    protected $fillable = [
        'iduser',
        'dni',
        'full_name',
        'gender',
        'birth_date',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function fathers()
    {
        return $this->belongsToMany(Father::class, 'father_student', 'idstudent', 'idfather');
    }
}