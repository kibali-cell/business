<?php

namespace App\Http\Controllers\CRM;
use App\Http\Controllers\Controller; 

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(15);
        return view('crm.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('crm.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable',
            'status' => 'required',
        ]);

        Customer::create($request->all());
        return redirect()->route('crm.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('crm.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('crm.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable',
            'status' => 'required',
        ]);

        $customer->update($request->all());
        return redirect()->route('crm.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('crm.customers.index')->with('success', 'Customer deleted successfully.');
    }
}