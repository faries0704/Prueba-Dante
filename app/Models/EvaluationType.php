<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationType extends Model
{
    protected $table = 'evaluation_types';
    protected $primaryKey = 'idevaluation_type';
    public $timestamps = false;
}