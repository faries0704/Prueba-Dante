<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'idrole';
    public $timestamps = false;

    protected $fillable = ['role_name'];

    public function users()
    {
        return $this->hasMany(User::class, 'idrole', 'idrole');
    }
}