<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * Display a listing of budgets.
     */
    public function index()
    {
        $budgets = Budget::orderBy('start_date', 'desc')->paginate(10);
        return view('finance.budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new budget.
     * (This view will be embedded in a modal.)
     */
    public function create()
    {
        return view('finance.budgets.create');
    }

    /**
     * Store a newly created budget in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department' => 'nullable|string',
            'allocated'  => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // Set actual spending to 0 by default
        $validated['actual'] = 0;

        Budget::create($validated);
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    /**
     * Display the specified budget.
     */
    public function show(Budget $budget)
    {
        return view('finance.budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified budget.
     * (This view will be embedded in a modal.)
     */
    public function edit(Budget $budget)
    {
        return view('finance.budgets.edit', compact('budget'));
    }

    /**
     * Update the specified budget in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'department' => 'nullable|string',
            'allocated'  => 'required|numeric|min:0',
            'actual'     => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $budget->update($validated);
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified budget from storage.
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }
}
