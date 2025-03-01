<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Currency;
use App\Models\Log;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['new_report']);
    }

    public function index()
    {
        $reports = Report::select('id', 'start_cash', 'end_cash', 'date', 'currency_id')->filter()->orderBy('id', 'desc')->paginate(25);
        return view('reports.index', compact('reports'));
    }

    public function new()
    {
        $currencies = Currency::select('id', 'name')->get();
        return view('reports.new', compact('currencies'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'start_cash' => 'required|numeric|min:0',
            'end_cash' => 'required|numeric|min:0',
            'currency_id' => 'required',
            'date' => 'required|date',
        ]);

        $report = Report::create([
            'start_cash' => $request->start_cash,
            'end_cash' => $request->end_cash,
            'date' => $request->date,
            'currency_id' => $request->currency_id,
        ]);

        $text = "Report of " . $report->date . " created in " . Carbon::now();
        Log::create(['text' => $text]);

        return redirect()->route('reports')->with('success', 'Report was successfully created.');
    }

    public function edit(Report $report)
    {
        $currencies = Currency::select('id', 'name')->get();
        $data = compact('currencies', 'report');

        return view('reports.edit', $data);
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'start_cash' => 'required|numeric|min:0',
            'end_cash' => 'required|numeric|min:0',
            'date' => 'required|date',
            'currency_id' => 'required',
        ]);

        $report->update([
            'start_cash' => $request->start_cash,
            'end_cash' => $request->end_cash,
            'date' => $request->date,
            'currency_id' => $request->currency_id,
        ]);

        $text = "Report of " . $report->date . " updated in " . Carbon::now();
        Log::create(['text' => $text]);

        return redirect()->route('reports')->with('success', 'Report was successfully updated.');
    }

    public function destroy(Report $report)
    {
        $text = "Report of " . $report->date . " deleted in " . Carbon::now();

        $report->delete();

        Log::create(['text' => $text]);

        return redirect()->back()->with('danger', 'Report was successfully deleted');
    }

    public function export()
    {
        return Excel::download(new ReportsExport, 'reports.xlsx');
    }

    public function new_report(Request $request)
    {
        $request->validate([
            'start_cash' => 'required|numeric|min:0',
            'end_cash' => 'required|numeric|min:0',
            'currency_id' => 'required',
            'date' => 'required|date',
        ]);

        $report = Report::create([
            'start_cash' => $request->start_cash,
            'end_cash' => $request->end_cash,
            'date' => $request->date,
            'currency_id' => $request->currency_id,
        ]);

        $text = "Report of " . $report->date . " created in " . Carbon::now();
        Log::create(['text' => $text]);

        return response()->json(['success' => true, 'message' => 'Report created successfully!']);
    }
}
