<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'idmessage';
    
    // Desactivamos los timestamps por defecto de Laravel ya que usas 'sent_at'
    public $timestamps = false; 

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'is_read',
        'sender_deleted',
        'receiver_deleted',
        'sent_at',
    ];

    // Relación: El usuario que envía el mensaje
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'iduser');
    }

    // Relación: El usuario que recibe el mensaje
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'iduser');
    }
}