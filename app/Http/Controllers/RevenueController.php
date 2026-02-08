<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\DailyExpense;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class RevenueController extends Controller
{
    public function index()
    {
        // Auto-generate current month revenue if it doesn't exist
        $month = now()->month;
        $year = now()->year;
        
        $exists = Revenue::where('year', $year)->where('month', $month)->exists();
        
        if (!$exists) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = Carbon::create($year, $month, 1)->endOfMonth();

            $totalSales = Sale::whereBetween('created_at', [$start, $end])->sum('payble');
            $totalPurchases = Purchase::whereBetween('created_at', [$start, $end])->sum('total_price');
            $totalExpenses = DailyExpense::whereBetween('created_at', [$start, $end])->sum('amount');

            Revenue::create([
                'year' => $year,
                'month' => $month,
                'total_sales' => $totalSales,
                'total_purchases' => $totalPurchases,
                'total_expenses' => $totalExpenses,
            ]);
        }
        
        $revenues = Revenue::orderByDesc('year')->orderByDesc('month')->get();
        return view('frontend.pages.revenue.index', compact('revenues'));
    }

    public function generate()
    {
        $month = now()->month;
        $year = now()->year;
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();

        $totalSales = Sale::whereBetween('created_at', [$start, $end])->sum('payble');
        $totalPurchases = Purchase::whereBetween('created_at', [$start, $end])->sum('total_price');
        $totalExpenses = DailyExpense::whereBetween('created_at', [$start, $end])->sum('amount');

        Revenue::updateOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'total_sales' => $totalSales,
                'total_purchases' => $totalPurchases,
                'total_expenses' => $totalExpenses,
            ]
        );

        return redirect()->route('revenues.index')
            ->with('success', 'Monthly revenue summary updated successfully!');
    }

     public function export($id)
    {
        $revenue = Revenue::findOrFail($id);

        $pdf = PDF::loadView('frontend.pages.revenue.pdf', compact('revenue'))
            ->setPaper('A4', 'portrait');

        $filename = "Revenue_Report_{$revenue->month_name}_{$revenue->year}.pdf";

        return $pdf->download($filename);
    }
}