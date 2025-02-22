<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Expense;
use App\Services\CurrencyConversionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        // Base currency for reporting (default to USD)
        $baseCurrency = $request->get('base_currency', 'USD');
        
        // Instantiate the conversion service
        $conversionService = new CurrencyConversionService();
        
        // Get date range
        $endDate = Carbon::parse($request->get('end_date', now()));
        $startDate = Carbon::parse($request->get('start_date', now()->subMonth()));
        $previousPeriodStart = Carbon::parse($startDate)->subMonths($endDate->diffInMonths($startDate));

        // Calculate current period totals
        $currentPeriodInvoices = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        $totalRevenue = $currentPeriodInvoices->reduce(function ($carry, $invoice) use ($conversionService, $baseCurrency) {
            return $carry + $conversionService->convert($invoice->total, $invoice->currency, $baseCurrency);
        }, 0);

        // Calculate previous period totals for growth calculation
        $previousPeriodInvoices = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$previousPeriodStart, $startDate])
            ->get();
        
        $previousRevenue = $previousPeriodInvoices->reduce(function ($carry, $invoice) use ($conversionService, $baseCurrency) {
            return $carry + $conversionService->convert($invoice->total, $invoice->currency, $baseCurrency);
        }, 0);

        // Calculate revenue growth
        $revenueGrowth = $previousRevenue > 0 
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 
            : 0;

        // Calculate outstanding receivables and its growth
        $currentReceivables = Invoice::where('status', '<>', 'paid')
            ->where('created_at', '<=', $endDate)
            ->get();
        
        $outstandingReceivables = $currentReceivables->reduce(function ($carry, $invoice) use ($conversionService, $baseCurrency) {
            return $carry + $conversionService->convert($invoice->total, $invoice->currency, $baseCurrency);
        }, 0);

        $previousReceivables = Invoice::where('status', '<>', 'paid')
            ->where('created_at', '<=', $startDate)
            ->get()
            ->reduce(function ($carry, $invoice) use ($conversionService, $baseCurrency) {
                return $carry + $conversionService->convert($invoice->total, $invoice->currency, $baseCurrency);
            }, 0);

        $receivablesGrowth = $previousReceivables > 0 
            ? (($outstandingReceivables - $previousReceivables) / $previousReceivables) * 100 
            : 0;

        // Calculate expenses and growth
        $currentExpenses = Expense::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        $totalExpenses = $currentExpenses->reduce(function ($carry, $expense) use ($conversionService, $baseCurrency) {
            return $carry + $conversionService->convert($expense->amount, $expense->currency, $baseCurrency);
        }, 0);

        $previousExpenses = Expense::where('status', 'approved')
            ->whereBetween('created_at', [$previousPeriodStart, $startDate])
            ->get()
            ->reduce(function ($carry, $expense) use ($conversionService, $baseCurrency) {
                return $carry + $conversionService->convert($expense->amount, $expense->currency, $baseCurrency);
            }, 0);

        $expensesGrowth = $previousExpenses > 0 
            ? (($totalExpenses - $previousExpenses) / $previousExpenses) * 100 
            : 0;

        // Calculate profit and growth
        $profit = $totalRevenue - $totalExpenses;
        $previousProfit = $previousRevenue - $previousExpenses;
        $profitGrowth = $previousProfit > 0 
            ? (($profit - $previousProfit) / $previousProfit) * 100 
            : 0;

        // For monthly revenue chart
        $monthlyRevenue = Invoice::selectRaw('MONTH(created_at) as month, SUM(total) as revenue, currency')
            ->where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'currency')
            ->orderBy('month')
            ->get();

        // Convert monthly sums to base currency
        $monthlyData = $monthlyRevenue->map(function ($data) use ($conversionService, $baseCurrency) {
            return [
                'month' => $data->month,
                'revenue' => $conversionService->convert($data->revenue, $data->currency, $baseCurrency)
            ];
        });

        return view('home.body', compact(
            'totalRevenue',
            'revenueGrowth',
            'outstandingReceivables',
            'receivablesGrowth',
            'totalExpenses',
            'expensesGrowth',
            'profit',
            'profitGrowth',
            'monthlyData',
            'baseCurrency',
            'startDate',
            'endDate'
        ));
    }
}