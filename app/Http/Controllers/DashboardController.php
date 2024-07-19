<?php

namespace App\Http\Controllers;

use App\Models\Dataout;
use App\Models\inventory;
use App\Models\InventoryTotal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the selected months from the request, default to the current month if not provided
        $selectedYearMasuk = $request->input('yearMasuk');
        $selectedMonthMasuk = null;

        // dd($selectedYearSisa);

        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            // Fetch data for Pie Chart (Data Masuk per Kategori)
            $inventoryData = InventoryTotal::select(DB::raw('SUBSTRING_INDEX(code, "-", 1) as category'), DB::raw('SUM(qty) as total'))
                ->groupBy('category')
                ->get();

            // Fetch data for Line Chart (Data Masuk per Month for the last 12 months)
            $monthlyInventoryData = Inventory::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(qty) as total_qty'))
                ->where('date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
                ->get();

            // Fetch data for Line Chart (Data Keluar per Month for the last 12 months)
            $monthlyDataoutData = Dataout::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(qty) as total_qty'))
                ->where('date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
                ->get();

            // Fetch data for Inventory Table
            $inventoryTableData = InventoryTotal::select('code', 'name', 'qty', 'location', 'unit')
                ->orderBy('code', 'asc')
                ->where('qty', '<=', 5) // Ubah kondisi where
                ->get();

            if (empty($selectedYearMasuk)) {
                $selectedMonthMasuk = $request->input('monthMasuk', now()->format('Y-m'));
                $totalPriceMasuk = Inventory::where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $selectedMonthMasuk)->sum(DB::raw('price * qty'));
            } else {
                $totalPriceMasuk = Inventory::where(DB::raw('DATE_FORMAT(date, "%Y")'), $selectedYearMasuk)
                    ->sum(DB::raw('price * qty'));
            }
        } else {
            // Fetch data for Pie Chart (Data Masuk per Kategori with location condition)
            $inventoryData = InventoryTotal::select(DB::raw('SUBSTRING_INDEX(code, "-", 1) as category'), DB::raw('SUM(qty) as total'))
                ->where('location', Auth::user()->location)
                ->groupBy('category')
                ->get();

            // Fetch data for Line Chart (Data Masuk per Month for the last 12 months with location condition)
            $monthlyInventoryData = Inventory::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(qty) as total_qty'))
                ->where('location', Auth::user()->location)
                ->where('date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
                ->get();

            // Fetch data for Line Chart (Data Keluar per Month for the last 12 months with location condition)
            $monthlyDataoutData = Dataout::select(DB::raw('DATE_FORMAT(dataouts.date, "%Y-%m") as month'), DB::raw('SUM(dataouts.qty) as total_qty'))
                ->join('inventory_totals', 'dataouts.code', '=', 'inventory_totals.code')
                ->where('dataouts.date', '>=', now()->subMonths(12))
                ->where('inventory_totals.location', Auth::user()->location)
                ->groupBy(DB::raw('DATE_FORMAT(dataouts.date, "%Y-%m")'))
                ->get();

            // Fetch data for Inventory Table
            $inventoryTableData = InventoryTotal::select('code', 'name', 'qty', 'location', 'unit')
                ->where('location', Auth::user()->location)
                ->orderBy('code', 'asc')
                ->where('qty', '<=', 5) // Ubah kondisi where
                ->get();

            if ($selectedYearMasuk == null) {
                $selectedMonthMasuk = $request->input('monthMasuk', now()->format('Y-m'));
                $totalPriceMasuk = Inventory::where('location', Auth::user()->location)->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $selectedMonthMasuk)->sum(DB::raw('price * qty'));
            } else {
                $totalPriceMasuk = Inventory::where(DB::raw('DATE_FORMAT(date, "%Y")'), $selectedYearMasuk)
                    ->sum(DB::raw('price * qty'));

                $totalPriceMasuk = Inventory::where('location', Auth::user()->location)->where(DB::raw('DATE_FORMAT(date, "%Y")'), $selectedYearMasuk)->sum(DB::raw('price * qty'));
            }
        }

        return view('dashboard.index', compact(
            'inventoryData',
            'monthlyInventoryData',
            'monthlyDataoutData',
            'inventoryTableData',
            'totalPriceMasuk',
            'selectedMonthMasuk',
            'selectedYearMasuk',
        ));
    }
}
