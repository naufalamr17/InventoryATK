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
    public function index()
    {
        // Fetch data for Pie Chart (Data Masuk per Kategori)
        $inventoryData = InventoryTotal::select(DB::raw('SUBSTRING_INDEX(code, "-", 1) as category'), DB::raw('count(*) as total'))
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

        // dd($monthlyDataoutData);

        return view('dashboard.index', [
            'inventoryData' => $inventoryData,
            'monthlyInventoryData' => $monthlyInventoryData,
            'monthlyDataoutData' => $monthlyDataoutData,
            'inventoryTableData' => $inventoryTableData,
        ]);
    }
}
