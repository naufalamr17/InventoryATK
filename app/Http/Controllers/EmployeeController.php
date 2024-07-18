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
                            <a href="' . route('employee.edit', ['id' => $inv->id]) . '" class="btn btn-success btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">edit</i>
                            </a>
                        </div>
                        <div class="p-1">
                            <form action="' . route('employee.destroy', ['id' => $inv->id]) . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                    <i class="material-icons" style="font-size: 16px;">delete</i>
                                </button>
                            </form>
                        </div>
                    </div>';
                    } elseif (Auth::user()->status == 'Modified') {
                        $inv->action = '<div class="d-flex align-items-center justify-content-center">
                        <div class="p-1">
                            <a href="' . route('employee.edit', ['id' => $inv->id]) . '" class="btn btn-success btn-sm p-0 mt-3" style="width: 24px; height: 24px;">
                                <i class="material-icons" style="font-size: 16px;">edit</i>
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
        $departments = Employee::select('dept')->distinct()->orderBy('dept', 'asc')->pluck('dept');
        // dd($userLocation);

        return view('pages.employees.inputemp', compact('userLocation', 'departments'));
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

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Employee::select('dept')->distinct()->orderBy('dept', 'asc')->pluck('dept');
        return view('pages.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'area' => 'required',
            'dept' => 'required',
            'jabatan' => 'required',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($request->all());

        return redirect()->route('employee')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employee')
            ->with('success', 'Employee deleted successfully.');
    }
}
