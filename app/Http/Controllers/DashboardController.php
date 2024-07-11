<?php

namespace App\Http\Controllers;

use App\Models\inventory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // dd(Auth::user());

        // if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
        //     $assets = Inventory::all();

        //     // Aggregate data for asset growth per year and location
        //     $yearlyGrowth = $assets->filter(function ($item) {
        //         // Filter hanya data dengan acquisition_date tidak kosong
        //         return $item->acquisition_date !== '-';
        //     })->groupBy(function ($item) {
        //         // Menggunakan Carbon untuk mengurai acquisition_date yang valid dan lokasi
        //         return Carbon::parse($item->acquisition_date)->format('Y') . '_' . $item->location;
        //     })->map->count();

        //     // Convert to a format suitable for charts (if necessary)
        //     $yearlyGrowthFormatted = $yearlyGrowth->sortKeys()->map(function ($count, $year_location) {
        //         // Memisahkan tahun dan lokasi dari kunci yang digunakan untuk pengelompokan
        //         list($year, $location) = explode('_', $year_location);
        //         return ['year' => $year, 'location' => $location, 'count' => $count];
        //     })->values();

        //     // Aggregate data for the charts
        //     $statusCounts = $assets->groupBy('status')->map->count();
        //     $categoryStatusCounts = $assets->groupBy('asset_category')->map(function ($category) {
        //         return $category->groupBy('status')->map->count();
        //     });

        //     // Aggregate data for asset growth per month in the last year
        //     $oneYearAgo = Carbon::now()->subYear();
        //     $locations = ['Head Office', 'Office Kendari', 'Site Molore']; // Tambahkan lokasi yang Anda inginkan

        //     $monthlyGrowth = $assets->filter(function ($item) use ($oneYearAgo) {
        //         // Filter hanya data dengan acquisition_date tidak sama dengan '-'
        //         return $item->acquisition_date !== '-' && Carbon::parse($item->acquisition_date)->greaterThanOrEqualTo($oneYearAgo);
        //     })->groupBy(function ($item) {
        //         // Mengelompokkan data berdasarkan bulan dan lokasi
        //         return Carbon::parse($item->acquisition_date)->format('Y-m') . '|' . $item->location;
        //     })->map->count();

        //     // Ensure every month in the last year is represented, even if the count is zero
        //     $monthlyGrowthFormatted = collect();
        //     for ($i = 0; $i < 12; $i++) {
        //         $date = Carbon::now()->subMonths($i)->format('Y-m');
        //         foreach ($locations as $location) {
        //             $key = $date . '|' . $location;
        //             $monthlyGrowthFormatted->push([
        //                 'month' => $date,
        //                 'location' => $location,
        //                 'count' => $monthlyGrowth->get($key, 0)
        //             ]);
        //         }
        //     }
        //     $monthlyGrowthFormatted = $monthlyGrowthFormatted->sortBy('month')->values();

        //     $inventory = inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
        //         ->select(
        //             'inventories.asset_code',
        //             'inventories.asset_type',
        //             'inventories.serial_number',
        //             'inventories.useful_life',
        //             'inventories.location',
        //             'inventories.status',
        //             'disposes.tanggal_penghapusan',
        //             'disposes.note'
        //         )
        //         ->orderBy('disposes.tanggal_penghapusan', 'desc')
        //         ->take(5)
        //         ->get();

        //     $repair = inventory::join('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
        //         ->select(
        //             'inventories.asset_code',
        //             'inventories.asset_type',
        //             'inventories.serial_number',
        //             'inventories.useful_life',
        //             'inventories.location',
        //             'repairstatuses.status',
        //             'repairstatuses.tanggal_kerusakan',
        //             'repairstatuses.tanggal_pengembalian',
        //             'repairstatuses.note'
        //         )
        //         ->orderBy('repairstatuses.tanggal_kerusakan', 'desc')
        //         ->take(5)
        //         ->get();
        // } else {
        //     $assets = Inventory::where('location', Auth::user()->location)->get();

        //     // Aggregate data for asset growth per year
        //     $yearlyGrowth = $assets->filter(function ($item) {
        //         // Filter hanya data dengan acquisition_date tidak kosong
        //         return $item->acquisition_date !== '-';
        //     })->groupBy(function ($item) {
        //         // Menggunakan Carbon untuk mengurai acquisition_date yang valid
        //         return Carbon::parse($item->acquisition_date)->format('Y');
        //     })->map->count();

        //     // Convert to a format suitable for charts (if necessary)
        //     $yearlyGrowthFormatted = $yearlyGrowth->sortKeys()->map(function ($count, $year) {
        //         return ['year' => $year, 'count' => $count];
        //     })->values();

        //     // Aggregate data for the charts
        //     $statusCounts = $assets->groupBy('status')->map->count();
        //     $categoryStatusCounts = $assets->groupBy('asset_category')->map(function ($category) {
        //         return $category->groupBy('status')->map->count();
        //     });

        //     // Aggregate data for asset growth per month in the last year
        //     $oneYearAgo = Carbon::now()->subYear();
        //     $monthlyGrowth = $assets->filter(function ($item) use ($oneYearAgo) {
        //         // Filter hanya data dengan acquisition_date tidak sama dengan '-'
        //         return $item->acquisition_date !== '-' && Carbon::parse($item->acquisition_date)->greaterThanOrEqualTo($oneYearAgo);
        //     })->groupBy(function ($item) {
        //         // Menggunakan Carbon untuk mengurai acquisition_date yang valid
        //         return Carbon::parse($item->acquisition_date)->format('Y-m');
        //     })->map->count();

        //     // Ensure every month in the last year is represented, even if the count is zero
        //     $monthlyGrowthFormatted = collect();
        //     for ($i = 0; $i < 12; $i++) {
        //         $date = Carbon::now()->subMonths($i)->format('Y-m');
        //         $monthlyGrowthFormatted->push([
        //             'month' => $date,
        //             'count' => $monthlyGrowth->get($date, 0)
        //         ]);
        //     }
        //     $monthlyGrowthFormatted = $monthlyGrowthFormatted->sortBy('month')->values();

        //     // Query untuk mengambil data inventory yang telah dipindahkan
        //     $inventory = Inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
        //         ->select(
        //             'inventories.asset_code',
        //             'inventories.asset_type',
        //             'inventories.serial_number',
        //             'inventories.useful_life',
        //             'inventories.location',
        //             'inventories.status',
        //             'disposes.tanggal_penghapusan',
        //             'disposes.note'
        //         )
        //         ->where('inventories.location', '=', Auth::user()->location)
        //         ->orderBy('disposes.tanggal_penghapusan', 'desc')
        //         ->take(5)
        //         ->get();

        //     // Query untuk mengambil data inventory yang perlu direparasi
        //     $repair = Inventory::join('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
        //         ->select(
        //             'inventories.asset_code',
        //             'inventories.asset_type',
        //             'inventories.serial_number',
        //             'inventories.useful_life',
        //             'inventories.location',
        //             'repairstatuses.status',
        //             'repairstatuses.tanggal_kerusakan',
        //             'repairstatuses.tanggal_pengembalian',
        //             'repairstatuses.note'
        //         )
        //         ->where('inventories.location', '=', Auth::user()->location)
        //         ->orderBy('repairstatuses.tanggal_kerusakan', 'desc')
        //         ->take(5)
        //         ->get();
        // }

        // dd($monthlyGrowthFormatted);

        // return view('dashboard.index', [
        //     'statusCounts' => $statusCounts,
        //     'categoryStatusCounts' => $categoryStatusCounts,
        //     'yearlyGrowth' => $yearlyGrowthFormatted,
        //     'monthlyGrowth' => $monthlyGrowthFormatted,
        //     'inventory' => $inventory,
        //     'repair' => $repair
        // ]);

        return view('dashboard.index');
    }
}
