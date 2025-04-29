<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'plate' => 'required|unique:vehicles',
            'brand' => 'required',
            'model' => 'required',
            'capacity' => 'required|integer|min:100',
            'type' => 'required|in:compacto,mediano,grande,especial',
        ]);

        Vehicle::create([
            'company_id' => Auth::id(),
            'plate' => $request->plate,
            'brand' => $request->brand,
            'model' => $request->model,
            'capacity' => $request->capacity,
            'type' => $request->type,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'Vehículo registrado correctamente.');
    }

    public function toggleStatus(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403);
        }

        $vehicle->status = $vehicle->status === 'active' ? 'inactive' : 'active';
        $vehicle->save();

        return back()->with('success', 'Estado del vehículo actualizado.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403);
        }

        $vehicle->delete();
        return back()->with('success', 'Vehículo eliminado.');
    }
}

