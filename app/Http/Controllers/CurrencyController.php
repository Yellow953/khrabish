<?php

namespace App\Http\Controllers;

use App\Exports\CurrenciesExport;
use App\Models\Currency;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['switch']);
    }

    public function index()
    {
        $currencies = Currency::select('id', 'code', 'name', 'symbol', 'rate')->filter()->orderBy('id', 'desc')->paginate(25);
        return view('currencies.index', compact('currencies'));
    }

    public function new()
    {
        return view('currencies.new');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:currencies',
            'name' => 'required|unique:currencies',
            'symbol' => 'required|unique:currencies',
            'rate' => 'required|numeric|min:0',
        ]);

        Currency::create($data);

        $text = "Currency " . $request->name . " created in " . Carbon::now();

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('currencies')->with('success', 'Currency successfully created...');
    }

    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    public function update(Currency $currency, Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'symbol' => 'required',
            'rate' => 'required|numeric'
        ]);

        $currency->update($data);

        $text = "Currency " . $currency->name . " changed to " . $currency->rate . " in " . Carbon::now();

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('currencies')->with('success', 'Currency successfully updated...');
    }

    public function destroy(Currency $currency)
    {
        if ($currency->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted Currency : " . $currency->code . ", datetime :   " . now();

            Log::create([
                'text' => $text,
            ]);
            $currency->delete();

            return redirect()->back()->with('error', 'Currency deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function switch(Currency $currency)
    {
        auth()->user()->update([
            'currency_id' => $currency->id,
        ]);

        return redirect()->back()->with('success', 'Currency switched to ' . $currency->name);
    }

    public function export()
    {
        return Excel::download(new CurrenciesExport, 'currencies.xlsx');
    }
}
