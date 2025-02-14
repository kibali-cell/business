<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     */
    public function index()
    {
        // Retrieve all warehouses, ordered by name, with pagination.
        $warehouses = Warehouse::orderBy('name')->paginate(15);
        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create()
    {
        // Simply return the create view.
        return view('warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'location'   => 'nullable|string|max:255',
            'capacity'   => 'nullable|integer|min:0',
            'manager_id' => 'nullable|integer',
        ]);

        // Create the warehouse record.
        Warehouse::create($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified warehouse.
     */
    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        // Validate the incoming request.
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'location'   => 'nullable|string|max:255',
            'capacity'   => 'nullable|integer|min:0',
            'manager_id' => 'nullable|integer',
        ]);

        // Update the warehouse record.
        $warehouse->update($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
}
