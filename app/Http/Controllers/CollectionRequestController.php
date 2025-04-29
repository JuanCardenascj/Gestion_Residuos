<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use Illuminate\Support\Facades\Auth;

class CollectionRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:organico,inorganico,reciclable,peligroso',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $points = $this->calculatePoints($request->type, $request->weight);

        CollectionRequest::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'type' => $request->type,
            'weight' => $request->weight,
            'points' => $points,
            'status' => 'pending',
        ]);

        $user = Auth::user();
        $user->increment('points', $points);

        return redirect()->route('dashboard')->with('success', 'Solicitud creada exitosamente.');
    }

    private function calculatePoints($type, $weight)
    {
        $strategies = [
            'organico' => fn($w) => floor($w * 0.8),
            'inorganico' => fn($w) => floor($w * 0.5),
            'reciclable' => fn($w) => floor($w * 1),
            'peligroso' => fn($w) => floor($w * 2),
        ];

        return $strategies[$type]($weight) ?? 0;
    }
}
