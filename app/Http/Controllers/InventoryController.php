<?php

namespace App\Http\Controllers;

use App\Imports\YourDataImport;
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
                    if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor') {
                        $inv->action = '<div class="d-flex align-items-center justify-content-center">
                        <div class="p-1">
                            <a href="' . route('in_inventory', ['id' => $inv->id]) . '" class="btn btn-success btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">input</i>
                            </a>
                        </div>
                        <div class="p-1">
                            <a href="' . route('in_inventory', ['id' => $inv->id]) . '" class="btn btn-warning btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">logout</i>
                            </a>
                        </div>
                    </div>';
                    } elseif (Auth::user()->status == 'Modified') {
                        $inv->action = '<div class="d-flex align-items-center justify-content-center">
                        <div class="p-1">
                            <a href="' . route('edit_inventory', ['id' => $inv->id]) . '" class="btn btn-success btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">input</i>
                            </a>
                        </div>
                    </div>';
                    }
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
        $inventory = inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->back()->with('success', 'Inventory deleted successfully.');
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

    public function history()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $userhist = Userhist::join('inventories', 'userhists.inv_id', '=', 'inventories.id')
                ->select(
                    'inventories.asset_code as kode_asset',
                    'inventories.asset_category',
                    'inventories.asset_position_dept',
                    'inventories.asset_type',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.status',
                    'userhists.hand_over_date as serah_terima',
                    'userhists.user',
                    'userhists.dept',
                    'userhists.note'
                )
                ->get();
        } else {
            $userhist = Userhist::join('inventories', 'userhists.inv_id', '=', 'inventories.id')
                ->select(
                    'inventories.asset_code as kode_asset',
                    'inventories.asset_category',
                    'inventories.asset_position_dept',
                    'inventories.asset_type',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.status',
                    'userhists.hand_over_date as serah_terima',
                    'userhists.user',
                    'userhists.dept',
                    'userhists.note'
                )
                ->where('inventories.location', Auth::user()->location)
                ->get();
        }
        return view('pages.asset.history', compact('userhist'));
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

    public function inputrepair()
    {
        return view('pages.asset.inputrepair');
    }

    public function storerepair(Request $request)
    {
        // dd($request);
        // Validate the request data
        $request->validate([
            'tanggal_kerusakan' => 'nullable|date',
            'tanggal_pengembalian' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        // Find the inventory based on the asset code
        $inventory = inventory::where('asset_code', $request->asset_code)->first();

        // Update the status of the inventory
        $inventory->status = $request->status;
        $inventory->save();

        // dd($request->status);

        if ($request->status == "Breakdown") {
            // Create the RepairStatus record
            repairstatus::create([
                'inv_id' => $inventory->id,
                'status' => $request->status,
                'tanggal_kerusakan' => $request->tanggal_kerusakan_breakdown,
                'note' => $request->remarks_breakdown,
            ]);
        } else  if ($request->status == "Repair") {
            // Create the RepairStatus record
            repairstatus::create([
                'inv_id' => $inventory->id,
                'status' => $request->status,
                'tanggal_kerusakan' => $request->tanggal_kerusakan_repair,
                'tanggal_pengembalian' => $request->tanggal_pengembalian_repair,
                'note' => $request->remarks_repair,
            ]);
        } else if ($request->status == "Good") {
            // Check the latest RepairStatus record for the inventory
            $latestStatus = repairstatus::where('inv_id', $inventory->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestStatus) {
                // Update the tanggal_pengembalian to today
                $latestStatus->tanggal_pengembalian = Carbon::now();
                $latestStatus->save();
            }
        }

        return redirect()->route('repair_inventory')->with('success', 'Repair status updated successfully.');
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

    public function dispose()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->status == 'Auditor' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $inventory = inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.asset_type',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.acquisition_date',
                    'inventories.location',
                    'inventories.status',
                    'disposes.id',
                    'disposes.tanggal_penghapusan',
                    'disposes.note',
                    'disposes.approval',
                    'disposes.disposal_document',
                )->get();
        } else {
            $inventory = inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.asset_type',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.acquisition_date',
                    'inventories.location',
                    'inventories.status',
                    'disposes.id',
                    'disposes.tanggal_penghapusan',
                    'disposes.note',
                    'disposes.approval',
                    'disposes.disposal_document',
                )
                ->where('inventories.location', Auth::user()->location)
                ->get();
        }


        // dd($inventory);

        return view('pages.asset.dispose', compact('inventory'));
    }

    public function inputdispose()
    {
        return view('pages.asset.inputdispose');
    }

    public function storedispose(Request $request)
    {
        // dd($request);

        $assetCode = $request->input('asset_code');
        $inventory = Inventory::where('asset_code', $assetCode)->first();

        // Update the status of the inventory
        // Handle file upload
        if ($request->hasFile('disposal_document')) {
            $fileName = time() . '_' . $request->file('disposal_document')->getClientOriginalName();
            $filePath = $request->file('disposal_document')->storeAs('uploads', $fileName, 'public');
        }
        $inventory->status = 'Waiting Dispose';
        $inventory->disposal_date = $request->disposal_date;
        $inventory->save();

        dispose::create([
            'inv_id' => $inventory->id,
            'tanggal_penghapusan' => $request->disposal_date,
            'note' => $request->remarks_repair,
            'disposal_document' => $filePath
        ]);

        return redirect()->route('dispose_inventory')->with('success', 'Successfully.');
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
