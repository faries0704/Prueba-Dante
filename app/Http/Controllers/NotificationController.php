<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Mostrar lista de notificaciones
    public function index()
    {
        $notifications = Notification::where('iduser', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    // Cargar detalles vía AJAX y marcar como leída
    public function showDetails(Request $request)
    {
        $notification = Notification::find($request->idnotification);

        // Validamos que exista y pertenezca al usuario logueado
        if ($notification && $notification->iduser == Auth::id()) {
            
            // Si es nueva, la marcamos como leída
            if ($notification->is_read == 0) {
                $notification->update(['is_read' => 1]);
            }

            // Asignar un icono/color según el tipo para el modal
            $icon = 'notifications';
            $color = '#005187';

            switch ($notification->type) {
                case 'MESSAGE': $icon = 'email'; $color = '#17a2b8'; break;
                case 'GRADE': $icon = 'star'; $color = '#ffc107'; break;
                case 'ATTENDANCE': $icon = 'fact_check'; $color = '#28a745'; break;
                case 'ENROLLMENT': $icon = 'person_add'; $color = '#6f42c1'; break;
                case 'SYSTEM': $icon = 'settings'; $color = '#6c757d'; break;
            }

            return response()->json([
                'success' => true,
                'title' => $notification->title,
                'description' => nl2br(htmlspecialchars($notification->description)),
                'date' => \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y h:i A'),
                'type' => $notification->type,
                'icon' => $icon,
                'color' => $color
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Notificación no encontrada'], 404);
    }
}