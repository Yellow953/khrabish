<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Exports\LogsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::select('text', 'created_at')->filter()->orderBy('created_at', 'desc')->paginate(25);

        return view('logs.index', compact('logs'));
    }

    public function fetch()
    {
        $logs = Log::orderBy('created_at', 'desc')->take(10)->get();

        $logs = $logs->map(function ($log) {
            return [
                'message' => $log->text,
                'timestamp' => $log->created_at->diffForHumans(),
            ];
        });

        return response()->json(['logs' => $logs]);
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new LogsExport($filters), 'Logs.xlsx');
    }

    public function pdf(Request $request)
    {
        $logs = Log::select('text', 'created_at')->filter()->get();

        $pdf = Pdf::loadView('logs.pdf', compact('logs'));

        return $pdf->download('Logs.pdf');
    }
}
