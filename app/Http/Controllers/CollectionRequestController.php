<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;

class CollectionRequestController extends Controller
{
    //
    public function create()
    {
        return view('collection-requests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:organico,inorganico,reciclable,peligroso',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $points = $this->calculatePoints($data['type'], $data['weight']);

        $request = CollectionRequest::create([
            'user_id' => Auth::id(),
            'date' => $data['date'],
            'type' => $data['type'],
            'weight' => $data['weight'],
            'points' => $points,
            'status' => 'pending',
        ]);

        // Actualizar puntos del usuario
        Auth::user()->increment('points', $points);

        // Notificación
        if (Auth::user()->whatsapp_notification) {
            $this->sendWhatsAppNotification(
                Auth::user()->phone,
                "Solicitud creada para {$data['date']}. Puntos: {$points}"
            );
        }

        return redirect()->route('dashboard')->with('success', 'Solicitud creada');
    }

    public function accept(Request $request, $id)
    {
        $collectionRequest = CollectionRequest::findOrFail($id);
        
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $collectionRequest->update([
            'status' => 'accepted',
            'company_id' => Auth::id(),
            'vehicle_id' => $data['vehicle_id'],
        ]);

        // Notificar al usuario
        if ($collectionRequest->user->whatsapp_notification) {
            $vehicle = Vehicle::find($data['vehicle_id']);
            $this->sendWhatsAppNotification(
                $collectionRequest->user->phone,
                "Solicitud aceptada por {Auth::user()->name}. Vehículo: {$vehicle->plate}"
            );
        }

        return redirect()->back()->with('success', 'Solicitud aceptada');
    }

    public function complete(Request $request, $id)
    {
        $collectionRequest = CollectionRequest::findOrFail($id);
        
        $data = $request->validate([
            'weight' => 'required|numeric|min:0.1',
        ]);

        // Recalcular puntos
        $newPoints = $this->calculatePoints($collectionRequest->type, $data['weight']);
        $pointsDifference = $newPoints - $collectionRequest->points;

        $collectionRequest->update([
            'status' => 'completed',
            'weight' => $data['weight'],
            'points' => $newPoints,
        ]);

        // Actualizar puntos del usuario
        $collectionRequest->user->increment('points', $pointsDifference);

        // Notificación
        if ($collectionRequest->user->whatsapp_notification) {
            $this->sendWhatsAppNotification(
                $collectionRequest->user->phone,
                "Recolección completada. Puntos obtenidos: {$newPoints}"
            );
        }

        return redirect()->back()->with('success', 'Recolección registrada');
    }

    protected function calculatePoints($type, $weight)
    {
        // Implementar la lógica de cálculo de puntos
        $strategies = [
            'organico' => 0.8,
            'inorganico' => 0.5,
            'reciclable' => 1,
            'peligroso' => 2,
        ];

        return floor($weight * $strategies[$type]);
    }

    protected function sendWhatsAppNotification($phone, $message)
    {
        // Implementar integración con API de WhatsApp
        // Por ahora solo lo registramos en el log
        \Log::info("WhatsApp a {$phone}: {$message}");
    }
}
