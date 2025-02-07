<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index()
    {
        $expenses = Expense::with(['submittedBy', 'approvedBy'])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('finance.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        // Ensure the view file exists: resources/views/finance/expenses/create.blade.php
        return view('finance.expenses.create');
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:0',
            'category'    => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Automatically set the submitting user
        $validated['submitted_by'] = Auth::id();

        Expense::create($validated);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense submitted successfully and is pending approval.');
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        return view('finance.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        return view('finance.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified expense in storage.
     *
     * This method handles both general detail updates and (if the user is a manager/admin) status changes.
     */
    public function update(Request $request, Expense $expense)
    {
        // Define rules for fields that everyone can update.
        $rules = [
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:0',
            'category'    => 'nullable|string',
            'description' => 'nullable|string',
        ];

        // Only allow managers or admins to update the status via this method.
        if (in_array(Auth::user()->role, ['manager', 'admin'])) {
            $rules['status'] = 'required|in:pending,approved,rejected';
        }

        $validated = $request->validate($rules);

        // If the user is not a manager/admin, ignore any submitted status value.
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            unset($validated['status']);
        } else {
            // For managers/admins, record who approved or changed the status.
            $validated['approved_by'] = Auth::id();
        }

        $expense->update($validated);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Update the status of an expense (approval/rejection).
     *
     * Alternatively, you can have dedicated methods for approve() and reject(), but this single method
     * lets you update the status based on the form input.
     */
    public function updateStatus(Request $request, Expense $expense)
    {
        // (Optional) Use a policy here if you have one:
        // $this->authorize('updateStatus', $expense);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Only managers or admins can update status.
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update([
            'status' => $validated['status'],
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense status updated successfully.');
    }

    /**
     * (Optional) Approve an expense.
     */
    public function approve(Expense $expense)
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense approved successfully.');
    }

    /**
     * (Optional) Reject an expense.
     */
    public function reject(Expense $expense)
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense rejected.');
    }
}
