<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 'users';

    protected $primaryKey = 'iduser';

    protected $fillable = [
        'username',
        'email',
        'password',
        'idrole',
        'photo',
        'status',
        'login_attempts',
        'locked_until',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'iduser', 'iduser');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'iduser', 'iduser');
    }

    public function father()
    {
        return $this->hasOne(Father::class, 'iduser', 'iduser');
    }
    
}
