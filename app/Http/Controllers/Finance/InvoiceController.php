<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // List all invoices with pagination and load all clients (for dropdowns or filtering)
    public function index()
    {
        $invoices = Invoice::with('client')->paginate(10);
        $clients  = Customer::all();
        
        // Calculate the predicted invoice number:
        $lastId = Invoice::max('id');
        $nextId = $lastId ? $lastId + 1 : 1;
        $predictedNumber = 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        
        return view('finance.invoices.index', compact('invoices', 'clients', 'predictedNumber'));
    }


    // Show the form to create a new invoice
    public function create()
    {
        $clients = Customer::all();
        
        // Calculate the predicted invoice number
        $lastId = Invoice::max('id');  // Gets the current maximum invoice ID
        $nextId = $lastId ? $lastId + 1 : 1; // If no invoice exists, start with 1
        $predictedNumber = 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        
        return view('finance.invoices.index', compact('clients', 'predictedNumber'));
    }


    // Store a new invoice in the database
    public function store(Request $request)
    {
        // Validate without 'number'
        $validated = $request->validate([
            // 'number' rule removed since we auto‑generate it
            'client_id'      => 'required|exists:customers,id',
            'due_date'       => 'required|date',
            'subtotal'       => 'required|numeric',
            'tax'            => 'required|numeric',
            'total'          => 'required|numeric',
            'status'         => 'required|string|max:255',
            'payment_terms'  => 'required|string|max:255',
            'notes'          => 'nullable|string',
            'currency'      => 'required|string|size:3', // e.g., USD, EUR, GBP
        ]);

        // Create the invoice (number is null or default at this point)
        $invoice = Invoice::create($validated);

        // Now generate the invoice number based on the new invoice's id.
        $invoice->number = 'INV-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT);
        $invoice->save();

        return redirect()->route('finance.invoices.index')
                        ->with('success', 'Invoice created successfully.');
    }



    // Display a specific invoice
    public function show(Invoice $invoice)
    {
        return view('finance.invoices.show', compact('invoice'));
    }

    // Show the form to edit an invoice
    public function edit(Invoice $invoice)
    {
        $clients = Customer::all();
        return view('finance.invoices.edit', compact('invoice', 'clients'));
    }

    // Update an existing invoice
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'number'         => 'required|string|unique:invoices,number,' . $invoice->id,
            'client_id'      => 'required|exists:customers,id',
            'due_date'       => 'required|date',
            'subtotal'       => 'required|numeric',
            'tax'            => 'required|numeric',
            'total'          => 'required|numeric',
            'status'         => 'required|string|max:255',
            'payment_terms'  => 'required|string|max:255',
            'notes'          => 'nullable|string',
            'currency'      => 'required|string|size:3', // e.g., USD, EUR, GBP
        ]);

        $invoice->update($validated);
        return redirect()->route('finance.invoices.index')
                         ->with('success', 'Invoice updated successfully.');
    }

    public function download(Invoice $invoice)
    {
        // Load the PDF view and pass the invoice variable
        $pdf = PDF::loadView('finance.invoices.pdf', compact('invoice'));

        // Optionally, you can set the paper size and orientation:
        // $pdf->setPaper('A4', 'portrait');

        // Return the generated PDF as a download
        return $pdf->download("invoice_{$invoice->number}.pdf");
    }


    // Delete an invoice
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('finance.invoices.index')
                         ->with('success', 'Invoice deleted successfully.');
    }
}
