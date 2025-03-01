<?php

namespace App\Http\Controllers;

use App\Exports\DebtsExport;
use App\Helpers\Helper;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Debt;
use App\Models\Log;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DebtController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['new_debt']);
    }

    public function index()
    {
        $debts = Debt::select('id', 'currency_id', 'client_id', 'supplier_id', 'amount', 'date', 'type')->filter()->orderBy('id', 'desc')->paginate(25);
        $clients = Client::select('id', 'name')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $types = Helper::get_debt_types();

        $data = compact('debts', 'suppliers', 'clients', 'types');
        return view('debts.index', $data);
    }

    public function new()
    {
        $suppliers = Supplier::select('id', 'name')->get();
        $clients = Client::select('id', 'name')->get();
        $currencies = Currency::select('id', 'code')->get();
        $types = Helper::get_debt_types();

        $data = compact('suppliers', 'clients', 'currencies', 'types');
        return view('debts.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'currency_id' => 'required',
            'date' => 'required|date',
            'amount' => 'required|min:0|numeric',
        ]);

        if ($request->supplier_id) {
            $debt = Debt::create($request->all());

            $text = "Debt for Debt: " . ucwords($debt->supplier->name) . " of " . $debt->amount . " in " . now();

            Log::create([
                'text' => $text,
            ]);

            return redirect()->route('debts')->with('success', 'Debt successfully created...');
        } else if ($request->client_id) {
            $debt = Debt::create($request->all());

            $text = "Debt for Client: " . ucwords($debt->client->name) . " of " . $debt->amount . " in " . now();

            Log::create([
                'text' => $text,
            ]);

            return redirect()->route('debts')->with('success', 'Debt successfully created...');
        } else {
            return redirect()->back()->with('danger', 'Please select supplier or client...');
        }
    }

    public function edit(Debt $debt)
    {
        $currencies = Currency::select('id', 'code')->get();
        $types = Helper::get_debt_types();

        $data = compact('debt', 'currencies', 'types');
        return view('debts.edit', $data);
    }

    public function update(Debt $debt, Request $request)
    {
        $request->validate([
            'currency_id' => 'required',
            'date' => 'required|date',
            'amount' => 'required|min:0|numeric',
        ]);

        $debt->update($request->all());

        $text = ucwords(auth()->user()->name) . " updated Debt: " . $debt->id . " in " . now();

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('debts')->with('success', 'Debt successfully updated...');
    }

    public function destroy(Debt $debt)
    {
        if ($debt->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted debt : " . $debt->id . ", datetime :   " . now();

            Log::create([
                'text' => $text,
            ]);
            $debt->delete();

            return redirect()->back()->with('error', 'Debt deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function export()
    {
        return Excel::download(new DebtsExport, 'debts.xlsx');
    }

    public function new_debt(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'currency_id' => 'required',
            'date' => 'required|date',
            'amount' => 'required|min:0|numeric',
        ]);

        $debt = Debt::create([
            'type' => 'client',
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'currency_id' => $request->currency_id,
            'date' => $request->date,
            'note' => $request->note
        ]);

        $text = "Debt for Client: " . ucwords($debt->client->name) . " of " . $debt->amount . " in " . now();

        Log::create([
            'text' => $text,
        ]);

        return response()->json(['success' => true, 'message' => 'Debt created successfully!']);
    }
}
