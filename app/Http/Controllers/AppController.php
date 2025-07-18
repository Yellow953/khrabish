<?php

namespace App\Http\Controllers;

use App\Models\BankNote;
use App\Models\Category;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SearchRoute;
use App\Models\VariantOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AppController extends Controller
{
    public function index()
    {
        $currency = auth()->user()->currency;
        $exchange_rate = Currency::where('code', 'LBP')->firstOrFail()->rate;
        $currencies = Currency::select('id', 'code')->get();
        $bank_notes = BankNote::get();
        $last_order = Order::whereNotNull('cashier_id')->latest()->first();
        $clients = Client::select('id', 'name')->orderBy('created_at', 'DESC')->get();
        $categories = Category::select('id', 'name', 'image')
            ->with([
                'products' => function ($query) {
                    $query->where('quantity', '>', 0)
                        ->with(['variants.options', 'barcodes']);
                }
            ])
            ->get();

        $data = compact('categories', 'currency', 'currencies', 'exchange_rate', 'bank_notes', 'last_order', 'clients');
        return view('index', $data);
    }

    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $text = '';

            $order = Order::create([
                'cashier_id' => auth()->id(),
                'client_id' => $request->client_id,
                'currency_id' => auth()->user()->currency_id,
                'order_number' => Order::generate_number(),
                'sub_total' => $request->total,
                'tax' => $request->tax,
                'discount' => $request->discount,
                'delivery' => $request->delivery,
                'total' => $request->grand_total,
                'products_count' => count(json_decode($request->order_items, true)),
                'note' => $request->note ?? null,
                'exchange_rate' => $request->exchange_rate,
                'payment_currency' => $request->payment_currency,
                'amount_paid' => $request->amount_paid ?? 0,
                'change_due' => $request->change_due ?? $request->grand_total,
                'status' => $request->status ?? ($request->amount_paid > 0 ? 'completed' : 'unpaid'),
            ]);

            $text .= 'User ' . ucwords(auth()->user()->name) . ' created Order NO: ' . $order->order_number . " of Sub Total: {$request->total}, tax: {$request->tax}, discount: {$request->discount}, delivery: {$request->delivery}, Total: {$request->grand_total}";

            $orderItems = json_decode($request->order_items, true);

            $text .= " { ";
            foreach ($orderItems as $item) {
                $product = Product::find($item['id']);

                if ($product->quantity - $item['quantity'] < 0) {
                    continue;
                }

                $variantTotalPrice = $item['price'];
                $variantDetails = [];

                if (isset($item['options']) && is_array($item['options'])) {
                    foreach ($item['options'] as $option) {
                        $optionPrice = $option['optionPrice'] === null ? $item['price'] : $option['optionPrice'];
                        $variantTotalPrice += ($optionPrice * $option['quantity']);

                        $variantDetails[] = [
                            'variant_id' => $option['variantId'],
                            'option_id' => $option['optionId'],
                            'value' => $option['value'],
                            'price' => $optionPrice,
                            'quantity' => $option['quantity']
                        ];
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $variantTotalPrice * $item['quantity'],
                    'variant_details' => !empty($variantDetails) ? json_encode($variantDetails) : null,
                ]);

                $product->update(['quantity' => ($product->quantity - $item['quantity'])]);

                if (!empty($variantDetails)) {
                    foreach ($variantDetails as $variantDetail) {
                        $variantOption = VariantOption::find($variantDetail['option_id']);
                        if ($variantOption && $variantOption->quantity !== null) {
                            $variantOption->decrement('quantity', $variantDetail['quantity'] * $item['quantity']);
                        }
                    }
                }

                $text .= "Product ID: {$item['id']}, Product Name: {$item['name']}, Price: {$item['price']}, Quantity: {$item['quantity']} | ";
            }

            $text .= " } , datetime: " . now();

            Log::create([
                'text' => $text,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order successfully created.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred while processing your order. Error: ' . $e->getMessage()], 500);
        }
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();

            $text = '';
            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $delivery = $request->delivery ?? 0;

            $order = Order::create([
                'cashier_id' => auth()->id(),
                'client_id' => $request->client_id,
                'currency_id' => auth()->user()->currency_id,
                'order_number' => Order::generate_number(),
                'sub_total' => $request->total,
                'tax' => $request->tax,
                'discount' => $request->discount,
                'delivery' => $request->delivery,
                'total' => $request->grand_total,
                'products_count' => count($request->orderItems),
                'note' => $request->note,
                'exchange_rate' => $request->exchangeRate,
                'payment_currency' => $request->paymentCurrency,
                'amount_paid' => $request->amountPaid ?? 0,
                'change_due' => $request->changeDue ?? $request->grand_total,
                'status' => $request->status ?? ($request->amountPaid > 0 ? 'completed' : 'unpaid'),
            ]);

            $text .= 'User ' . ucwords(auth()->user()->name) . ' created Order NO: ' . $order->order_number . " of Sub Total: {$request->total}, tax: {$tax}, discount: {$discount}, delivery: {$delivery}, Total: {$request->grand_total}";

            foreach ($request->orderItems as $item) {
                $product = Product::find($item['id']);

                if ($product->quantity - $item['quantity'] < 0) {
                    continue;
                }

                $variantTotalPrice = $item['price'];
                $variantDetails = [];

                if (isset($item['options']) && is_array($item['options'])) {
                    foreach ($item['options'] as $option) {
                        $optionPrice = $option['optionPrice'] === null ? $item['price'] : $option['optionPrice'];
                        $variantTotalPrice += ($optionPrice * $option['quantity']);

                        $variantDetails[] = [
                            'variant_id' => $option['variantId'],
                            'option_id' => $option['optionId'],
                            'value' => $option['value'],
                            'price' => $optionPrice,
                            'quantity' => $option['quantity']
                        ];
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $variantTotalPrice * $item['quantity'],
                    'variant_details' => !empty($variantDetails) ? json_encode($variantDetails) : null,
                ]);

                $product->update(['quantity' => ($product->quantity - $item['quantity'])]);

                if (!empty($variantDetails)) {
                    foreach ($variantDetails as $variantDetail) {
                        $variantOption = VariantOption::find($variantDetail['option_id']);
                        if ($variantOption && $variantOption->quantity !== null) {
                            $variantOption->decrement('quantity', $variantDetail['quantity'] * $item['quantity']);
                        }
                    }
                }

                $text .= "Product ID: {$item['id']}, Product Name: {$item['name']}, Price: {$item['price']}, Quantity: {$item['quantity']} | ";
            }

            $text .= " } , datetime: " . now();
            Log::create(['text' => $text]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order synced successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error syncing order: ' . $e->getMessage()], 500);
        }
    }

    public function custom_logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('login');
    }

    public function navigate(Request $request)
    {
        $res = SearchRoute::where('name', $request->route)->first();

        if (!$res) {
            return response()->json(['error' => 'Route not found'], 404);
        } else {
            return redirect()->route($res->link);
        }
    }
}
