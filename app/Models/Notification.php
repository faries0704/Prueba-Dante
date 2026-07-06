<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'idnotification';
    
    // Desactivamos timestamps por defecto porque la tabla solo tiene created_at
    public $timestamps = false; 

    protected $fillable = [
        'iduser',
        'title',
        'description',
        'type',
        'is_read',
        'created_at'
    ];

    /**
     * Relación: Una notificación pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}