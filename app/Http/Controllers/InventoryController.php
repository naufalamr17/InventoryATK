<?php

namespace App\Http\Controllers;

use App\Imports\YourDataImport;
use App\Models\Dataout;
use App\Models\employee;
use App\Models\inventory;
use App\Models\InventoryTotal;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Query inventaris berdasarkan status pengguna
            if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
                $inventory = InventoryTotal::orderBy('code', 'asc')->get();
            } else {
                $inventory = InventoryTotal::where('location', Auth::user()->location)
                    ->orderBy('code', 'asc')->get();
            }

            // Menghitung nilai terdepresiasi dan pesan sisa umur
            $inventory = $inventory->map(function ($inv) {
                // Menetapkan variabel action berdasarkan status pengguna
                if (Auth::check()) {
                    $inv->action = '<div class="d-flex align-items-center justify-content-center">
                        <div class="p-1">
                            <a href="' . route('in_inventory', ['id' => $inv->id]) . '" class="btn btn-success btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">input</i>
                            </a>
                        </div>
                        <div class="p-1">
                            <a href="' . route('out_inventory', ['id' => $inv->id]) . '" class="btn btn-warning btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">logout</i>
                            </a>
                        </div>
                    </div>';
                }

                return $inv;
            });

            // Mengembalikan DataTables dengan data inventaris yang sudah diproses
            return DataTables::of($inventory)
                ->addColumn('action', function ($inventory) {
                    return $inventory->action ?? '';
                })
                ->rawColumns(['action']) // Menggunakan rawColumns untuk memproses tag HTML
                ->make(true);
        }

        // Jika bukan request AJAX, kembalikan tampilan input inventaris
        return view('pages.asset.input');
    }

    public function addinventory()
    {
        $user = Auth::user();
        $userLocation = $user->location;

        // dd($userLocation);

        return view('pages.asset.inputasset', compact('userLocation'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'location' => 'required|string',
            'period' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'pic' => 'required|string',
            'qty' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
        ]);

        // Generate code based on category and iteration
        $category = $request->input('category');
        $lastCode = Inventory::where('category', $category)->orderBy('id', 'desc')->first();
        $nextId = $lastCode ? intval(substr($lastCode->code, -3)) + 1 : 1;
        $code = $category . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Assuming Inventory model is used for storing inventory data
        $inventory = new Inventory();
        $inventory->location = $validatedData['location'];
        $inventory->period = $validatedData['period'];
        $inventory->date = $validatedData['date'];
        $inventory->time = $validatedData['time'];
        $inventory->pic = $validatedData['pic'];
        $inventory->qty = $validatedData['qty'];
        $inventory->price = $validatedData['price'];
        $inventory->category = $validatedData['category'];
        $inventory->name = $validatedData['name'];
        $inventory->unit = $validatedData['unit'];
        $inventory->code = $code; // Assign the generated code

        $inventoryTotal = new InventoryTotal();
        $inventoryTotal->code = $code;
        $inventoryTotal->qty = $validatedData['qty'];
        $inventoryTotal->location = $validatedData['location'];
        $inventoryTotal->name = $validatedData['name'];
        $inventoryTotal->unit = $validatedData['unit'];

        // Save the inventory
        $inventory->save();
        $inventoryTotal->save();

        return redirect()->route('inventory')->with('success', 'Inventory created successfully.');
    }

    public function out($id)
    {
        $inventory = InventoryTotal::findOrFail($id);
        $inventory2 = Inventory::findOrFail($id);

        return view('pages.asset.inputout', compact('inventory', 'inventory2'));
    }

    public function storeout(Request $request, $id)
    {
        // dd($request);
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
            'period' => 'required|numeric',
            'date' => 'required|date',
            'time' => 'required',
            'pic' => 'required|string',
            'qty' => 'required|numeric',
            'location' => 'required|string',
            'category' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
        ]);

        // Ambil data employee berdasarkan nik yang dikirimkan
        $employee = Employee::where('nik', $request->nik)->first();

        // Periksa jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } elseif (!$employee) {
            return redirect()->back()->withErrors("Data employee tidak terdaftar");
        }

        // Ambil data dari InventoryTotal
        $inventoryTotal = InventoryTotal::findOrFail($id);

        // Simpan data baru ke dalam Inventory
        $inventory = new Dataout();
        $inventory->code = $inventoryTotal->code;
        $inventory->periode = $request->period;
        $inventory->date = $request->date;
        $inventory->time = $request->time;
        $inventory->pic = $request->pic;
        $inventory->qty = $request->qty;
        $inventory->nik = $request->nik;
        $inventory->save();

        // Update saldo
        $saldo = $inventoryTotal->qty - $request->qty;
        $inventoryTotal->qty = $saldo;
        $inventoryTotal->save();

        return redirect()->route('inventory')->with('success', 'Asset updated successfully.');
    }

    public function dataout(Request $request)
    {
        if ($request->ajax()) {
            // Query inventaris berdasarkan status pengguna
            if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
                $inventory = dataout::join('inventory_totals', 'dataouts.code', '=', 'inventory_totals.code')
                    ->join('employees', 'dataouts.nik', '=', 'employees.nik')
                    ->orderBy('dataouts.code', 'asc')
                    ->get(['dataouts.*', 'inventory_totals.*', 'employees.*']); // Menggunakan select() untuk memilih kolom yang tepat
            } else {
                $inventory = dataout::join('inventory_totals', 'dataouts.code', '=', 'inventory_totals.code')
                    ->join('employees', 'dataouts.nik', '=', 'employees.nik')
                    ->where('location', Auth::user()->location)
                    ->orderBy('dataouts.code', 'asc')
                    ->get(['dataouts.*', 'inventory_totals.*', 'employees.*']); // Menggunakan select() untuk memilih kolom yang tepat
            }

            // Mengubah nilai $inventory->nik menjadi string yang berisi informasi dari employees
            $inventory->transform(function ($item) {
                $nik = $item->nik;
                $nama = $item->nama;
                $area = $item->area;
                $dept = $item->dept;
                $jabatan = $item->jabatan;

                // Format string sesuai kebutuhan, misalnya:
                $item->nik = "$nik|$nama|$area|$dept|$jabatan";

                return $item;
            });

            // Mengembalikan DataTables dengan data inventaris yang sudah diproses
            return DataTables::of($inventory)
                ->make(true);
        }

        return view('pages.asset.dataout');
    }

    public function in($id)
    {
        $inventory = InventoryTotal::findOrFail($id);
        $in = Inventory::findOrFail($id);

        return view('pages.asset.editasset', compact('inventory', 'in'));
    }

    public function storein(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'period' => 'required|numeric',
            'date' => 'required|date',
            'time' => 'required',
            'pic' => 'required|string',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
            'location' => 'required|string',
            'category' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil data dari InventoryTotal
        $inventoryTotal = InventoryTotal::findOrFail($id);

        // Simpan data baru ke dalam Inventory
        $inventory = new Inventory();
        $inventory->code = $inventoryTotal->code;
        $inventory->period = $request->period;
        $inventory->date = $request->date;
        $inventory->time = $request->time;
        $inventory->pic = $request->pic;
        $inventory->qty = $request->qty;
        $inventory->price = $request->price;
        $inventory->location = $request->location;
        $inventory->category = $request->category;
        $inventory->name = $request->name;
        $inventory->unit = $request->unit;
        $inventory->save();

        // Update saldo
        $saldo = $inventoryTotal->qty + $request->qty;
        $inventoryTotal->qty = $saldo;
        $inventoryTotal->save();

        return redirect()->route('inventory')->with('success', 'Asset updated successfully.');
    }

    public function repair(Request $request)
    {
        if ($request->ajax()) {
            // Query inventaris berdasarkan status pengguna
            if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
                $inventory = Inventory::orderBy('code', 'asc')->get();
            } else {
                $inventory = Inventory::where('location', Auth::user()->location)
                    ->orderBy('code', 'asc')->get();
            }

            // Menghitung total price * qty
            $inventory->each(function ($item) {
                $item->total = $item->price * $item->qty;
            });

            // Mengembalikan DataTables dengan data inventaris yang sudah diproses
            return DataTables::of($inventory)
                ->addColumn('total', function ($item) {
                    return $item->total;
                })
                ->rawColumns(['total'])
                ->make(true);
        }

        return view('pages.asset.repair');
    }

    public function getInventoryData(Request $request)
    {
        $assetCode = $request->input('asset_code');
        $inventory = Inventory::where('asset_code', $assetCode)->first();

        if ($inventory) {
            $data = [
                'id' => $inventory->id,
                'location' => $inventory->location,
                'asset_category' => $inventory->asset_category,
                'asset_position_dept' => $inventory->asset_position_dept,
                'asset_type' => $inventory->asset_type,
                'description' => $inventory->description,
                'serial_number' => $inventory->serial_number,
                'acquisition_date' => $inventory->acquisition_date,
                'useful_life' => $inventory->useful_life,
                'acquisition_value' => $inventory->acquisition_value,
                'status' => $inventory->status,
                // Tambahkan data lain yang ingin Anda kembalikan
            ];

            return response()->json($data);
        } else {
            return response()->json(['error' => 'Inventaris tidak ditemukan.'], 404);
        }
    }

    public function report()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $inventoryData = Inventory::leftJoin('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->leftJoin('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->leftJoin('userhists', 'inventories.id', '=', 'userhists.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.old_asset_code',
                    'inventories.asset_category',
                    'inventories.asset_position_dept',
                    'inventories.asset_type',
                    'inventories.merk',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.acquisition_date',
                    'inventories.useful_life',
                    'inventories.user',
                    'inventories.dept',
                    'inventories.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'disposes.tanggal_penghapusan',
                    DB::raw('COALESCE(disposes.note, repairstatuses.note) as remarks')
                )
                ->orderBy('inventories.acquisition_date', 'desc')
                ->get()
                ->unique('asset_code');
        } else {
            $inventoryData = Inventory::leftJoin('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->leftJoin('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->leftJoin('userhists', 'inventories.id', '=', 'userhists.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.old_asset_code',
                    'inventories.asset_category',
                    'inventories.asset_position_dept',
                    'inventories.asset_type',
                    'inventories.merk',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.acquisition_date',
                    'inventories.useful_life',
                    'inventories.user',
                    'inventories.dept',
                    'inventories.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'disposes.tanggal_penghapusan',
                    DB::raw('COALESCE(disposes.note, repairstatuses.note) as remarks')
                )
                ->where('inventories.location', Auth::user()->location)
                ->orderBy('inventories.acquisition_date', 'desc')
                ->get()
                ->unique('asset_code');
        }

        return view('pages.report.list', compact('inventoryData'));
    }

    public function inputexcel()
    {
        return view('pages.asset.inputexcel');
    }

    public function storeexcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new YourDataImport, $request->file('file'));

            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to import data: ' . $e->getMessage()]);
        }
    }
}
