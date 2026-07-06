<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Father extends Model
{
    use HasFactory;

    protected $table = 'fathers';
    protected $primaryKey = 'idfather';

    const UPDATED_AT = null;

    protected $fillable = [
        'iduser',
        'dni',
        'full_name',
        'profession',
        'phone',
        'address'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class, 
            'father_student', 
            'idfather',     
            'idstudent'    
        );
    }
}