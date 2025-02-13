<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // List products
    public function index()
    {
        $products = Product::orderBy('name')->paginate(15);
        return view('inventory.index', compact('products'));
    }

    // Show form for creating a new product
    public function create()
    {
        return view('inventory.create');
    }

    // Store a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string',
            'sku'           => 'required|string|unique:products,sku',
            'barcode'       => 'nullable|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'cost'          => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'category_id'   => 'nullable|integer',
            'supplier_id'   => 'nullable|integer',
            'reorder_point' => 'required|integer|min:0',
            'status'        => 'required|in:active,inactive',
        ]);

        Product::create($validated);
        return redirect()->route('inventory.index')->with('success', 'Product created successfully.');
    }

    // Display a product and its transactions
    public function show(Product $product)
    {
        $transactions = $product->transactions()->orderBy('date', 'desc')->get();
        return view('inventory.show', compact('product', 'transactions'));
    }

    // Show form for editing a product
    public function edit(Product $product)
    {
        return view('inventory.edit', compact('product'));
    }

    // Update a product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'required|string',
            'sku'           => 'required|string|unique:products,sku,' . $product->id,
            'barcode'       => 'nullable|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'cost'          => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'category_id'   => 'nullable|integer',
            'supplier_id'   => 'nullable|integer',
            'reorder_point' => 'required|integer|min:0',
            'status'        => 'required|in:active,inactive',
        ]);

        $product->update($validated);
        return redirect()->route('inventory.index')->with('success', 'Product updated successfully.');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully.');
    }

    // Record an inventory transaction (stock in/out)
    public function recordTransaction(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type'             => 'required|in:in,out',
            'quantity'         => 'required|integer|min:1',
            'unit_price'       => 'nullable|numeric|min:0',
            'reference_number' => 'nullable|string',
            'date'             => 'required|date',
            'warehouse_id'     => 'nullable|integer',
        ]);

        InventoryTransaction::create([
            'product_id'       => $product->id,
            'warehouse_id'     => $validated['warehouse_id'] ?? null,
            'type'             => $validated['type'],
            'quantity'         => $validated['quantity'],
            'unit_price'       => $validated['unit_price'] ?? $product->price,
            'reference_number' => $validated['reference_number'] ?? null,
            'date'             => $validated['date'],
        ]);

        // Update product quantity accordingly.
        if ($validated['type'] === 'in') {
            $product->increment('quantity', $validated['quantity']);
        } else {
            $product->decrement('quantity', $validated['quantity']);
        }

        return redirect()->route('inventory.show', $product->id)->with('success', 'Transaction recorded successfully.');
    }
}
