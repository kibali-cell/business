<?php

namespace App\Http\Controllers\CRM;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // Display a paginated list of companies
    public function index()
    {
        $companies = Company::paginate(15);
        return view('crm.companies.index', compact('companies'));
    }

    // Show the form to create a new company
    public function create()
    {
        return view('crm.companies.create');
    }

    // Store a newly created company in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:companies',
            'phone' => 'nullable',
            'status' => 'required',
        ]);

        Company::create($request->all());
        return redirect()->route('crm.companies.index')->with('success', 'Company created successfully.');
    }

    // Display a specific company
    public function show(Company $company)
    {
        return view('crm.companies.show', compact('company'));
    }

    // Show the form to edit a specific company
    public function edit(Company $company)
    {
        return view('crm.companies.edit', compact('company'));
    }

    // Update a specific company in the database
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable',
            'status' => 'required',
        ]);

        $company->update($request->all());
        return redirect()->route('crm.companies.index')->with('success', 'Company updated successfully.');
    }

    // Delete a specific company from the database
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('crm.companies.index')->with('success', 'Company deleted successfully.');
    }
}
