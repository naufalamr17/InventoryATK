<?php

namespace App\Http\Controllers;

use App\Models\vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = vendor::select(['id', 'nama', 'alamat', 'no_tel', 'pic']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('edit_vendor', ['id' => $row->id]) . '" class="btn btn-success btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                    <i class="material-icons" style="font-size: 16px;">edit</i>
                </a>';
                    $deleteBtn = '<a href="' . route('destroy_vendor', ['id' => $row->id]) . '" class="btn btn-danger btn-sm p-0 mt-3 ms-2" style="width: 24px; height: 24px;" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                    <i class="material-icons" style="font-size: 16px;">delete</i>
                </a>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.vendor.index');
    }

    public function destroy(Request $request)
    {
        $vendor = Vendor::findOrFail($request->id);
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor deleted successfully.');
    }

    public function create()
    {
        return view('pages.vendor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_tel' => 'required|string',
            'pic' => 'required|string',
        ]);

        // Create a new vendor using the validated data
        Vendor::create($validated);

        // Redirect back with success message
        return redirect()->route('vendor')->with('success', 'Vendor added successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('pages.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_tel' => 'required|string',
            'pic' => 'required|string',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($validated);

        return redirect()->route('vendor')->with('success', 'Vendor updated successfully.');
    }
}
