<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Index: List all invoices 
    public function index()
    {
        $invoices = Invoice::with('client')->paginate(10);
        $clients = Customer::all();
        return view('finance.invoices.index', compact('invoices', 'clients'));
    }

    // Create: Show the form to create a new invoice
    public function create()
    {
        $clients = Customer::all();
        return view('finance.invoices.create', compact('clients'));
    }

    // Store: Save a new invoice
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:invoices',
            'client_id' => 'required|exists:customers,id',
            'due_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|string|max:255',
            'payment_terms' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Invoice::create($request->all());
        return redirect()->route('financial.invoices.index')->with('success', 'Invoice created successfully.');
    }

    // Show: Display a specific invoice
    public function show(Invoice $invoice)
    {
        return view('finance.invoices.show', compact('invoice'));
    }

    // Edit: Show the form to edit an invoice
    public function edit(Invoice $invoice)
    {
        $clients = Customer::all();
        return view('finance.invoices.edit', compact('invoice', 'clients'));
    }

    // Update: Update an invoice
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'number' => 'required|string|unique:invoices,number,' . $invoice->id,
            'client_id' => 'required|exists:customers,id',
            'due_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|string|max:255',
            'payment_terms' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($request->all());
        return redirect()->route('financial.invoices.index')->with('success', 'Invoice updated successfully.');
    }

    // Destroy: Delete an invoice
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('financial.invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}