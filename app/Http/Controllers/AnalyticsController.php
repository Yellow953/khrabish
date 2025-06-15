<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Expense;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Fetch total debts for suppliers and clients
        $clientData = Debt::whereNotNull('client_id')->get();
        $totalClientDebt = 0;

        foreach ($clientData as $cd) {
            $totalClientDebt += ($cd->amount / $cd->currency->rate);
        }

        $supplierData = Debt::whereNotNull('supplier_id')->get();
        $totalSupplierDebt = 0;

        foreach ($supplierData as $cd) {
            $totalSupplierDebt += ($cd->amount / $cd->currency->rate);
        }

        $currency = auth()->user()->currency;

        // Fetch top products by quantity sold
        $topProductsByQuantity = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Fetch top products by total revenue generated
        $topProductsByRevenue = OrderItem::select('product_id', DB::raw('SUM(quantity * unit_price) as total_revenue'))
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // Get product names for the fetched product_ids
        $productNames = Product::whereIn('id', $topProductsByQuantity->pluck('product_id'))
            ->orWhereIn('id', $topProductsByRevenue->pluck('product_id'))
            ->pluck('name', 'id');

        // Prepare data for quantity pie chart
        $quantityData = $topProductsByQuantity->map(function ($product) use ($productNames) {
            return [$productNames[$product->product_id] ?? 'Unknown', $product->total_quantity];
        });

        // Prepare data for revenue pie chart
        $revenueData = $topProductsByRevenue->map(function ($product) use ($productNames) {
            return [$productNames[$product->product_id] ?? 'Unknown', $product->total_revenue];
        });

        // Sales breakdown by day, week, and month
        $salesByDay = $this->getSalesData('day');
        $salesByWeek = $this->getSalesData('week');
        $salesByMonth = $this->getSalesData('month');

        // Cash flow

        // Fetch products with category and calculate profit
        $products = Product::with('category')->get()->map(function ($product) {
            $profit = $product->price - $product->cost;
            return [
                'name' => $product->name,
                'category' => $product->category->name ?? 'Unknown',
                'image' => $product->image,
                'profit' => $profit,
            ];
        });

        $hourly_orders = Order::whereDate('created_at', Carbon::today())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => (int) $item->hour,
                    'count' => (int) $item->count
                ];
            });

        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $todays_orders = Order::whereBetween('created_at', [$startOfDay, $endOfDay])->get();
        $todays_orders_count = $todays_orders->count();
        $todays_sales = 0;
        $todays_profit = 0;

        foreach ($todays_orders as $order) {
            foreach ($order->items as $item) {
                $currency_rate = $order->currency->rate;

                $todays_sales += ($item->total / $currency_rate);
                $todays_profit += (($item->quantity * ($item->product->price - $item->product->cost)));
            }
        }
        $purchaseAnalytics = $this->getPurchaseAnalytics();

        // Fetch recent expenses
        $recentExpenses = Expense::orderBy('date', 'desc')
            ->limit(5)
            ->get();


        $data = compact(
            'totalClientDebt',
            'currency',
            'totalSupplierDebt',
            'hourly_orders',
            'quantityData',
            'revenueData',
            'salesByDay',
            'salesByWeek',
            'salesByMonth',
            'products',
            'todays_orders',
            'todays_orders_count',
            'todays_sales',
            'todays_profit',
            'recentExpenses'
        );


        $data = array_merge($data, $purchaseAnalytics);

        return view('analytics.index', $data);
    }

    public function getHourlyOrders(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = Carbon::parse($request->date);

        $hourly_orders = Order::whereDate('created_at', $date)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => (int) $item->hour,
                    'count' => (int) $item->count
                ];
            });

        return response()->json(['hourly_orders' => $hourly_orders]);
    }

    private function getSalesData($period)
    {
        switch ($period) {
            case 'day':
                $dateCondition = now()->subDay();
                break;
            case 'week':
                $dateCondition = now()->subWeek();
                break;
            case 'month':
                $dateCondition = now()->subMonth();
                break;
            default:
                $dateCondition = now()->subDay();
        }

        return OrderItem::select(DB::raw("DATE(created_at) as period"), DB::raw('SUM(quantity * unit_price) as total_sales'))
            ->where('created_at', '>=', $dateCondition)
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    public function dailyReport()
    {
        $currency = auth()->user()->currency;
        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        $dailyOrders = Order::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['cashier', 'currency'])
            ->withCount('items')
            ->paginate(10);

        $dailyTotalSales = $dailyOrders->sum(function ($order) {
            return $order->total;
        });
        $dailyTotalProfit = $dailyOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                return $item->quantity * ($item->product->price - $item->product->cost);
            });
        });

        $dailyOrderCount = $dailyOrders->count();

        $topSeller = OrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * unit_price) as total_sales'))
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->first();

        $topSellerDetails = $topSeller && $topSeller->product ? [
            'name' => $topSeller->product->name,
            'total_sales' => $topSeller->total_sales,
        ] : null;

        $hourlyOrders = Order::whereDate('created_at', now())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $hourlyLabels = $hourlyOrders->pluck('hour')->map(function ($hour) {
            $period = $hour >= 12 ? 'PM' : 'AM';
            $displayHour = $hour % 12 || 12;
            return "$displayHour $period";
        });

        $hourlyData = $hourlyOrders->pluck('count');

        return view('analytics.daily-report', [
            'currency' => $currency,
            'daily_total_sales' => $dailyTotalSales,
            'daily_total_profit' => $dailyTotalProfit,
            'daily_order_count' => $dailyOrderCount,
            'top_seller' => $topSellerDetails,
            'hourly_labels' => $hourlyLabels,
            'hourly_data' => $hourlyData,
            'daily_orders' => $dailyOrders,
            'report_date' => now()->format('Y-m-d'),
        ]);
    }

    public function weeklyReport()
    {
        $currency = auth()->user()->currency;
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $weeklyOrders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with(['cashier', 'currency'])
            ->withCount('items')
            ->paginate(10);

        $weeklyTotalSales = $weeklyOrders->sum(function ($order) {
            return $order->total;
        });
        $weeklyTotalProfit = $weeklyOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                return $item->quantity * ($item->product->price - $item->product->cost);
            });
        });

        $weeklyOrderCount = $weeklyOrders->count();

        $topSeller = OrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * unit_price) as total_sales'))
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->first();

        $topSellerDetails = $topSeller && $topSeller->product ? [
            'name' => $topSeller->product->name,
            'total_sales' => $topSeller->total_sales,
        ] : null;

        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_sales')
        )
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailySalesLabels = $dailySales->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('D, M d');
        });

        $dailySalesData = $dailySales->pluck('total_sales');

        return view('analytics.weekly-report', [
            'currency' => $currency,
            'weekly_total_sales' => $weeklyTotalSales,
            'weekly_total_profit' => $weeklyTotalProfit,
            'weekly_order_count' => $weeklyOrderCount,
            'top_seller' => $topSellerDetails,
            'daily_sales_labels' => $dailySalesLabels,
            'daily_sales_data' => $dailySalesData,
            'weekly_orders' => $weeklyOrders,
            'start_date' => $startOfWeek->format('Y-m-d'),
            'end_date' => $endOfWeek->format('Y-m-d'),
        ]);
    }

    public function monthlyReport()
    {
        $currency = auth()->user()->currency;
        $currentMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyOrders = Order::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->with(['cashier', 'currency'])
            ->withCount('items')
            ->paginate(10);

        $monthlyTotalSales = $monthlyOrders->sum(function ($order) {
            return $order->total;
        });
        $monthlyTotalProfit = $monthlyOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                return $item->quantity * ($item->product->price - $item->product->cost);
            });
        });

        $monthlyOrderCount = $monthlyOrders->count();

        $topSeller = OrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * unit_price) as total_sales'))
            ->whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->first();

        $topSellerDetails = $topSeller && $topSeller->product ? [
            'name' => $topSeller->product->name,
            'total_sales' => $topSeller->total_sales,
        ] : null;


        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_sales')
        )
            ->whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailySalesLabels = $dailySales->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('M d');
        });

        $dailySalesData = $dailySales->pluck('total_sales');

        return view('analytics.monthly-report', [
            'currency' => $currency,
            'monthly_total_sales' => $monthlyTotalSales,
            'monthly_total_profit' => $monthlyTotalProfit,
            'monthly_order_count' => $monthlyOrderCount,
            'top_seller' => $topSellerDetails,
            'daily_sales_labels' => $dailySalesLabels,
            'daily_sales_data' => $dailySalesData,
            'monthly_orders' => $monthlyOrders,
        ]);
    }

    public function customReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $currency = auth()->user()->currency;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['cashier', 'currency', 'items.product'])
            ->withCount('items')
            ->get();

        $totalSales = $orders->sum('total');
        $totalProfit = $orders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                return $item->quantity * ($item->product->price - $item->product->cost);
            });
        });
        $orderCount = $orders->count();

        $topSeller = DB::table('order_items')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->first();

        return view('analytics.custom-report', [
            'currency' => $currency,
            'total_sales' => $totalSales,
            'total_profit' => $totalProfit,
            'order_count' => $orderCount,
            'orders' => $orders,
            'top_seller' => $topSeller,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
    }

    private function getPurchaseAnalytics()
    {
        $currency = auth()->user()->currency;

        // Recent purchases (last 30 days)
        $recentPurchases = Purchase::where('purchase_date', '>=', now()->subDays(30))
            ->orderBy('purchase_date', 'desc')
            ->limit(10)
            ->get();

        // Total purchases value (last 30 days)
        $totalPurchasesValue = Purchase::where('purchase_date', '>=', now()->subDays(30))
            ->sum('total');

        // Most purchased products
        $topPurchasedProducts = PurchaseItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_cost'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->with('product:id,name,image')
            ->get();

        // Purchase trend by day (last 30 days)
        $purchaseTrend = Purchase::select(DB::raw('DATE(purchase_date) as date'), DB::raw('SUM(total) as daily_total'))
            ->where('purchase_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format for chart
        $purchaseDates = $purchaseTrend->pluck('date')->toArray();
        $purchaseAmounts = $purchaseTrend->pluck('daily_total')->toArray();

        // Supplier with most purchases
        $topSupplier = Purchase::select('supplier_id', DB::raw('COUNT(*) as purchase_count'), DB::raw('SUM(total) as total_amount'))
            ->with('supplier:id,name')
            ->groupBy('supplier_id')
            ->orderBy('total_amount', 'desc')
            ->first();

        return [
            'recentPurchases' => $recentPurchases,
            'totalPurchasesValue' => $totalPurchasesValue,
            'topPurchasedProducts' => $topPurchasedProducts,
            'purchaseDates' => $purchaseDates,
            'purchaseAmounts' => $purchaseAmounts,
            'topSupplier' => $topSupplier,
        ];
    }

    public function getSalesVsPurchases(Request $request)
    {
        $days = $request->input('days', 30);
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();

        // Get all dates in range
        $dateRange = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
        }

        // Get sales data
        $salesData = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as daily_total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('daily_total', 'date')
            ->toArray();

        // Get purchase data
        $purchaseData = Purchase::select(DB::raw('DATE(purchase_date) as date'), DB::raw('SUM(total) as daily_total'))
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('daily_total', 'date')
            ->toArray();

        // Format data for chart
        $formattedSalesData = [];
        $formattedPurchaseData = [];

        foreach ($dateRange as $date) {
            $formattedSalesData[] = $salesData[$date] ?? 0;
            $formattedPurchaseData[] = $purchaseData[$date] ?? 0;
        }

        return response()->json([
            'dates' => $dateRange->toArray(),
            'salesData' => $formattedSalesData,
            'purchaseData' => $formattedPurchaseData,
        ]);
    }
}
