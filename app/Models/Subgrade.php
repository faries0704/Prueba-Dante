<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subgrade extends Model
{
    use HasFactory;

    protected $table = 'subgrades';
    protected $primaryKey = 'idsubgrade';
    public $timestamps = false;

    protected $fillable = ['subgrade_name', 'iddegree', 'status'];

    // Relación: Un Subgrado pertenece a un Grado
    public function degree()
    {
        return $this->belongsTo(Degree::class, 'iddegree', 'iddegree');
    }
}