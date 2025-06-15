<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Log;
use App\Models\Order;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['create']);
    }

    public function index()
    {
        $reports = Report::select('id', 'user_id', 'start_datetime', 'end_datetime', 'total_sales', 'total_tax', 'total_discounts', 'cash_amount', 'transaction_count', 'currency_id')->with('currency', 'user')->filter()->orderBy('end_datetime', 'desc')->paginate(25);
        $users = User::select('id', 'name')->get();

        $data = compact('reports', 'users');
        return view('reports.index', $data);
    }

    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Get the last report or beginning of records
            $lastReport = Report::latest()->first();
            $startDate = $lastReport ? $lastReport->end_datetime : Order::oldest()->value('created_at') ?? now();

            // 2. Get orders in this period
            $orders = Order::with('items')
                ->whereBetween('created_at', [$startDate, now()])
                ->get();

            $total_sales = 0;
            $total_taxes = 0;
            $total_discounts = 0;
            $total_amount = 0;
            foreach ($orders as $order) {
                $rate = $order->currency->rate;
                $total_sales += (($order->sub_total + $order->tax) / $rate);
                $total_taxes += ($order->tax / $rate);
                $total_discounts += ($order->discount / $rate);
                $total_amount += ($order->amount_paid / $order->exchange_rate) - ($order->change_due / $order->exchange_rate);
            }

            // 4. Create the report
            $report = Report::create([
                'user_id' => auth()->id(),
                'start_datetime' => $startDate,
                'end_datetime' => now(),
                'total_sales' => $total_sales,
                'total_tax' => $total_taxes,
                'total_discounts' => $total_discounts,
                'cash_amount' => $total_amount,
                'transaction_count' => $orders->count(),
                'currency_id' => auth()->user()->currency_id
            ]);

            // 5. Save report items
            $itemsData = [];
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $itemsData[] = [
                        'report_id' => $report->id,
                        'product_id' => $item->product_id,
                        'quantity_sold' => $item->quantity,
                        'total_amount' => $item->total,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            ReportItem::insert($itemsData);

            DB::commit();

            return response()->json([
                'success' => true,
                'report' => $report->load('user', 'currency')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Z Report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Report $report)
    {
        $report->load(['items.product', 'user', 'currency']);
        return view('reports.show', compact('report'));
    }

    public function destroy(Report $report)
    {
        foreach ($report->items as $item) {
            $item->delete();
        }

        Log::create([
            'text' => "Z Report #{$report->id} deleted"
        ]);

        $report->delete();

        return back()->with('success', 'Report deleted successfully');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new ReportsExport($filters), 'Reports.xlsx');
    }

    protected function calculateOrderTotals($orders)
    {
        return [
            'total_sales' => $orders->sum('total'),
            'total_tax' => $orders->sum('tax'),
            'total_discount' => $orders->sum('discount'),
            'cash_amount' => $orders->where('payment_currency', 'USD')->sum('amount_paid')
        ];
    }

    protected function saveReportItems($report, $orders)
    {
        $itemsData = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $itemsData[] = [
                    'report_id' => $report->id,
                    'product_id' => $item->product_id,
                    'quantity_sold' => $item->quantity,
                    'total_amount' => $item->total,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        ReportItem::insert($itemsData);
    }
}
