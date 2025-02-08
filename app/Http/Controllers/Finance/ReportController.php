<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dashboard()
    {
        // Total revenue: sum of all paid invoices.
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        
        // Outstanding receivables: sum of unpaid invoices.
        $outstandingReceivables = Invoice::where('status', '<>', 'paid')->sum('total');
        
        // Total expenses: sum of approved expenses.
        $totalExpenses = Expense::where('status', 'approved')->sum('amount');
        
        // Profit: revenue minus expenses.
        $profit = $totalRevenue - $totalExpenses;
        
        // Monthly revenue for the last 6 months (example)
        $monthlyRevenue = Invoice::selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('finance.reports.dashboard', compact(
            'totalRevenue',
            'outstandingReceivables',
            'totalExpenses',
            'profit',
            'monthlyRevenue'
        ));
    }
    
    // (Optional) Additional report methods for exporting data.
}
