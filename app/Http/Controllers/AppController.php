<?php

namespace App\Http\Controllers;

use App\Models\BankNote;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SearchRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AppController extends Controller
{
    public function index()
    {
        $currency = auth()->user()->currency;
        $categories = Category::select('id', 'name', 'image')->with('products')->get();
        $currencies = Currency::select('id', 'code')->get();
        $bank_notes = BankNote::where('currency_code', auth()->user()->currency->code)->get();
        $last_order = Order::get()->last();

        $data = compact('categories', 'currency', 'currencies', 'bank_notes', 'last_order');
        return view('index', $data);
    }

    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $text = '';

            $order = Order::create([
                'cashier_id' => auth()->user()->id,
                'currency_id' => auth()->user()->currency_id,
                'order_number' => Order::generate_number(),
                'sub_total' => $request->total,
                'tax' => $request->tax,
                'discount' => $request->discount,
                'total' => $request->grand_total,
                'products_count' => count(json_decode($request->order_items, true)),
                'note' => $request->note ?? null,
            ]);

            $text .= 'User ' . ucwords(auth()->user()->name) . ' created Order NO: ' . $order->order_number . " of Sub Total: {$request->total}, tax: {$request->tax}, discount: {$request->discount}, Total: {$request->grand_total}";

            $orderItems = json_decode($request->order_items, true);

            $text .= " { ";
            foreach ($orderItems as $item) {
                $product = Product::find($item['id']);

                if ($product->quantity - $item['quantity'] < 0) {
                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                $product->update(['quantity' => ($product->quantity - $item['quantity'])]);

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
            return response()->json(['success' => false, 'message' => 'An error occurred while processing your order. $e:' . $e], 500);
        }
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'orderItems' => 'required|array',
                'total' => 'required|numeric',
                'amountPaid' => 'required|numeric',
                'changeDue' => 'required|numeric',
                'note' => 'nullable|string',
            ]);

            $text = '';
            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;

            $order = Order::create([
                'cashier_id' => auth()->user()->id,
                'currency_id' => auth()->user()->currency_id,
                'order_number' => Order::generate_number(),
                'sub_total' => $request->total - $tax + $discount,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $request->total,
                'products_count' => count($request->orderItems),
                'note' => $request->note,
            ]);
            $text .= 'User ' . ucwords(auth()->user()->name) . ' created Order NO: ' . $order->order_number . " of Sub Total: {$request->total}, tax: {$tax}, discount: {$discount}, Total: {$request->grand_total}";

            foreach ($request->orderItems as $item) {
                $product = Product::find($item['id']);

                if ($product->quantity - $item['quantity'] < 0) {
                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                $product->update(['quantity' => ($product->quantity - $item['quantity'])]);

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
