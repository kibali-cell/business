<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    // Display a list of companies
    public function index()
    {
        $companies = Company::paginate(15);
        return view('crm.companies.index', compact('companies'));
    }

    // Show the form for creating a new company
    public function create()
    {
        return view('crm.companies.create');
    }

    // Store a newly created company in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url',
        ]);

        Company::create($request->all());

        return redirect()->route('crm.companies.index')->with('success', 'Company created successfully.');
    }

    // Display the specified company
    public function show(Company $company)
    {
        return view('crm.companies.show', compact('company'));
    }

    // Show the form for editing the specified company
    public function edit(Company $company)
    {
        return view('crm.companies.edit', compact('company'));
    }

    // Update the specified company in the database
    public function update(Request $request, Company $company)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:companies,email,' . $company->id,
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'website' => 'nullable|url',
    ]);

    $company->update($request->all());

    return redirect()->route('crm.companies.index')->with('success', 'Company updated successfully.');
}

    // Remove the specified company from the database
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('crm.companies.index')->with('success', 'Company deleted successfully.');
    }
}