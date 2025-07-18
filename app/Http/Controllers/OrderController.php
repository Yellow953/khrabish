<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Log;
use App\Models\Order;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $orders = Order::select('id', 'order_number', 'cashier_id', 'client_id', 'status', 'currency_id', 'sub_total', 'tax', 'discount', 'total', 'products_count')->filter()->orderBy('id', 'desc')->paginate(25);
        $users = User::select('id', 'name')->get();
        $clients = Client::select('id', 'name')->get();

        $data = compact('orders', 'users', 'clients');
        return view('orders.index', $data);
    }

    public function show(Order $order)
    {
        $currency = $order->currency;

        $data = compact('order', 'currency');
        return view('orders.show', $data);
    }

    public function destroy(Order $order)
    {
        if ($order->can_delete()) {
            $text = ucwords(auth()->user()->name) .  " deleted Order " . $order->id . ", datetime: " . now();

            foreach ($order->items() as $item) {
                $item->product->update([
                    'quantity' => ($item->product->quantity + $item->quantity),
                ]);
                $item->delete();
            }

            $order->delete();
            Log::create(['text' => $text]);

            return redirect()->back()->with('success', "Order successfully deleted and Products returned!");
        } else {
            return redirect()->back()->with('danger', 'Unable to delete');
        }
    } //end of order

    public function pay(Order $order)
    {
        if ($order->status == 'paid') {
            return redirect()->back()->with('warning', 'Order already paid...');
        }

        $rate = Currency::where('code', 'LBP')->firstOrFail()->rate;
        $currencies = Currency::select('name', 'code')->get();

        $data = compact('order', 'rate', 'currencies');
        return view('orders.pay', $data);
    }

    public function update(Order $order, Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_currency' => 'required|string|max:3',
            'amount_paid' => 'required|numeric|min:0',
            'exchange_rate' => 'required|numeric|min:1',
        ]);

        $amountPaid = $request->amount_paid;
        $paymentCurrency = $request->payment_currency;
        $exchangeRate = $request->exchange_rate;

        $systemCurrency = auth()->user()->currency->code;
        $amountPaidInSystemCurrency = ($paymentCurrency === $systemCurrency)
            ? $amountPaid
            : ($paymentCurrency === 'USD'
                ? $amountPaid * $exchangeRate
                : $amountPaid / $exchangeRate);

        $order->update([
            'payment_method' => $request->payment_method,
            'payment_currency' => $paymentCurrency,
            'amount_paid' => $order->amount_paid + $amountPaidInSystemCurrency,
            'change_due' => max(0, $order->change_due - $amountPaidInSystemCurrency),
            'exchange_rate' => $exchangeRate,
            'status' => ($order->change_due - $amountPaidInSystemCurrency) <= 0 ? 'paid' : 'partially_paid',
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) . ' payyed Order: ' . $order->order_number . ', datetime: ' . now(),
        ]);


        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Payment processed successfully!');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new OrdersExport($filters), 'Orders.xlsx');
    }

    public function pdf(Request $request)
    {
        $orders = Order::with('cashier', 'client')->filter()->get();

        $pdf = Pdf::loadView('orders.pdf', compact('orders'));

        return $pdf->download('Orders.pdf');
    }
}
