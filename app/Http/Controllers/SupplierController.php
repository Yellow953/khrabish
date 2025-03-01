<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;


class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $suppliers = Supplier::select('id', 'name', 'email', 'phone', 'address')->filter()->orderBy('id', 'desc')->paginate(25);

        return view('suppliers.index', compact('suppliers'));
    }

    public function new()
    {
        return view('suppliers.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        $text = ucwords(auth()->user()->name) . " created new Supplier : " . $supplier->name . ", datetime :   " . now();
        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('suppliers')->with('success', 'Supplier created successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Supplier $supplier, Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
        ]);

        if ($supplier->name != trim($request->name)) {
            $text = ucwords(auth()->user()->name) . ' updated Supplier ' . $supplier->name . " to " . $request->name . ", datetime :   " . now();
        } else {
            $text = ucwords(auth()->user()->name) . ' updated Supplier ' . $supplier->name . ", datetime :   " . now();
        }

        $supplier->update(
            $request->all()
        );

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('suppliers')->with('warning', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted supplier : " . $supplier->name . ", datetime :   " . now();

            Log::create([
                'text' => $text,
            ]);
            $supplier->delete();

            return redirect()->back()->with('error', 'Supplier deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function export()
    {
        return Excel::download(new SuppliersExport, 'suppliers.xlsx');
    }
}
