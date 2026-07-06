<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Mostrar la bandeja de entrada
    public function index()
    {
        $messages = Message::with('sender')
            ->where('receiver_id', Auth::id())
            ->where('receiver_deleted', 0)
            ->orderBy('sent_at', 'desc')
            ->get();

        return view('messages.index', compact('messages'));
    }

    // 2. Agrega este nuevo método para la búsqueda AJAX
    public function searchUsers(Request $request)
    {
        $term = $request->term;

        if (empty($term)) {
            return response()->json([]);
        }

        $users = User::where('iduser', '!=', Auth::id())
            ->where(function($query) use ($term) {
                // Ajusta 'username' o 'name' según las columnas reales de tu tabla
                $query->where('username', 'LIKE', '%' . $term . '%')
                      ->orWhere('email', 'LIKE', '%' . $term . '%'); 
            })
            // Select2 necesita obligatoriamente que las columnas se llamen 'id' y 'text'
            ->select('iduser as id', 'username as text') 
            ->limit(20) // Limitamos a 20 resultados para no saturar
            ->get();

        return response()->json($users);
    }

    // Obtener detalles del mensaje vía AJAX y marcar como leído
    public function showDetails(Request $request)
    {
        $message = Message::with('sender')->find($request->idmessage);

        // Validamos que el mensaje exista y pertenezca al usuario logueado
        if ($message && $message->receiver_id == Auth::id()) {
            
            // Si no estaba leído, lo marcamos como leído
            if ($message->is_read == 0) {
                $message->update(['is_read' => 1]);
            }

            return response()->json([
                'success' => true,
                'subject' => $message->subject,
                'sender_name' => $message->sender->username, // Ajusta si prefieres mostrar full_name a través de roles
                'sender_photo' => $message->sender->photo,
                'date' => \Carbon\Carbon::parse($message->sent_at)->format('d/m/Y h:i A'),
                'message' => nl2br(htmlspecialchars($message->message)) // nl2br para respetar saltos de línea
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Mensaje no encontrado'], 404);
    }
    // Guardar el nuevo mensaje vía AJAX
    public function store(Request $request)
    {
        // Validar los datos que vienen del formulario
        $request->validate([
            'receiver_id' => 'required|exists:users,iduser',
            'subject' => 'required|string|max:150',
            'message' => 'required|string',
        ]);

        // Crear el mensaje en la base de datos
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => 0,
            'sender_deleted' => 0,
            'receiver_deleted' => 0,
            // 'sent_at' se llena automáticamente por el valor DEFAULT en la BD
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mensaje enviado correctamente.'
        ]);
    }

    // Mostrar la bandeja de salida (Mensajes Enviados)
    public function outbox()
    {
        // Obtenemos los mensajes donde el usuario logueado es el remitente (sender_id)
        $messages = Message::with('receiver') // Cargamos la relación del destinatario
            ->where('sender_id', Auth::id())
            ->where('sender_deleted', 0) // Que no estén eliminados por el remitente
            ->orderBy('sent_at', 'desc')
            ->get();

        return view('messages.outbox', compact('messages'));
    }

    // Obtener detalles del mensaje enviado vía AJAX
    public function showOutboxDetails(Request $request)
    {
        // Buscamos el mensaje y cargamos los datos del receptor
        $message = Message::with('receiver')->find($request->idmessage);

        // Validamos que el mensaje exista y pertenezca al remitente logueado
        if ($message && $message->sender_id == Auth::id()) {
            
            return response()->json([
                'success' => true,
                'subject' => $message->subject,
                'receiver_name' => $message->receiver->username, // El nombre de a quién se lo enviamos
                'receiver_photo' => $message->receiver->photo,
                'date' => \Carbon\Carbon::parse($message->sent_at)->format('d/m/Y h:i A'),
                'message' => nl2br(htmlspecialchars($message->message)),
                'status' => $message->is_read ? 'Leído por el destinatario' : 'Entregado (No leído)'
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Mensaje no encontrado'], 404);
    }
    
}