<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('company_id', Auth::id())->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'capacity' => 'required|integer|min:100',
            'type' => 'required|in:compacto,mediano,grande,especial',
        ]);

        $data['company_id'] = Auth::id();
        $data['status'] = 'active';

        Vehicle::create($data);

        return redirect()->back()->with('success', 'Vehículo registrado');
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::where('company_id', Auth::id())->findOrFail($id);
        
        $data = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles,plate,'.$id,
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'capacity' => 'required|integer|min:100',
            'type' => 'required|in:compacto,mediano,grande,especial',
        ]);

        $vehicle->update($data);

        return redirect()->back()->with('success', 'Vehículo actualizado');
    }

    public function toggleStatus($id)
    {
        $vehicle = Vehicle::where('company_id', Auth::id())->findOrFail($id);
        
        $newStatus = $vehicle->status === 'active' ? 'inactive' : 'active';
        $vehicle->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Estado actualizado');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::where('company_id', Auth::id())->findOrFail($id);
        
        // Verificar si está asignado a alguna solicitud
        if ($vehicle->requests()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar un vehículo asignado a solicitudes');
        }

        $vehicle->delete();
        return redirect()->back()->with('success', 'Vehículo eliminado');
    }
}
