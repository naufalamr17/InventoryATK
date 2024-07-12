<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = employee::all();

            $employees = $employees->map(function ($inv) {
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

            return DataTables::of($employees)
                ->addColumn('action', function ($employees) {
                    return $employees->action ?? '';
                })
                ->rawColumns(['action']) // Menggunakan rawColumns untuk memproses tag HTML
                ->make(true);
        }
        return view('pages.employees.index');
    }

    public function addemployee()
    {
        $user = Auth::user();
        $userLocation = $user->location;

        // dd($userLocation);

        return view('pages.employees.inputemp', compact('userLocation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:employees',
            'nama' => 'required',
            'area' => 'required',
            'dept' => 'required',
            'jabatan' => 'required',
        ]);

        Employee::create($request->all());

        return redirect()->route('employee')
            ->with('success', 'Employee created successfully.');
    }
}
