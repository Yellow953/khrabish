<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'name', 'image', 'price', 'compare_price')->orderBy('created_at', 'DESC')->limit(10)->get();
        $categories = Category::select('id', 'name', 'image')->get();

        $data = compact('categories', 'products');
        return view('frontend.index', $data);
    }

    public function shop(Request $request)
    {
        $categories = Category::select('id', 'name', 'image')->get();

        if ($request->input('category')) {
            $category = Category::where('name', $request->input('category'))->firstOrFail();
            $products = Product::select('id', 'name', 'category_id', 'image', 'price', 'compare_price')->where('category_id', $category->id)->paginate(12);
        } else {
            $products = Product::select('id', 'name', 'category_id', 'image', 'price', 'compare_price')->paginate(12);
        }

        $data = compact('categories', 'products');
        return view('frontend.shop', $data);
    }

    public function product(Product $product)
    {
        $categories = Category::select('id', 'name', 'image')->get();
        $simillar_products = Product::select('id', 'name', 'image')->where('category_id', $product->category_id)->limit(10)->get();

        $data = compact('product', 'simillar_products', 'categories');
        return view('frontend.product', $data);
    }

    public function contact()
    {
        $categories = Category::select('id', 'name', 'image')->get();
        return view('frontend.contact', compact('categories'));
    }

    public function checkout()
    {
        $cities = Helper::get_cities();
        $categories = Category::select('id', 'name', 'image')->get();

        return view('frontend.checkout', compact('cities', 'categories'));
    }

    public function order(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'shipping' => 'required|numeric|min:0',
        ]);

        $cart = json_decode($request->cart, true);
        if (!$cart || empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty.');
        }

        $subTotal = 0;
        $productsCount = 0;
        foreach ($cart as $item) {
            $subTotal += $item['price'] * $item['quantity'];
            $productsCount += $item['quantity'];
        }
        $shippingFee = $request->shipping;
        $total = $subTotal + $shippingFee;

        DB::beginTransaction();
        try {
            $client = Client::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'city' => $request->city,
                    'country' => $request->country,
                    'address' => $request->address,
                ]
            );

            $currency = Currency::where('code', 'USD')->firstOrFail();

            $order = Order::create([
                'currency_id' => $currency->id,
                'client_id' => $client->id,
                'order_number' => Order::generate_number(),
                'payment_method' => $request->payment_method,
                'sub_total' => $subTotal,
                'total' => $total,
                'products_count' => $productsCount,
                'note' => $request->notes,
            ]);

            // Create order items
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            $this->sendOrderEmails($order, $client);

            setcookie('cart', '', time() - 3600, '/');

            return redirect()->back()->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', '%' . $query . '%')
            ->take(5)
            ->get(['id', 'name', 'image']);

        $products = $products->map(function ($product) {
            $product->url = route('product', $product->name);
            return $product;
        });

        return response()->json($products);
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:12',
            'message' => 'required|string',
            'spam' => 'required|numeric',
        ]);

        if ($request->spam == 19) {
            $data = $request->all();

            Mail::send('emails.contact', ['data' => $data,], function ($message) {
                $message->to('Khrabish.store@gmail.com')
                    ->subject('New Contact');
            });

            return redirect()->back()->with('success', 'Contact Form Submitted Successfully');
        } else {
            return redirect()->back()->with('error', 'Unable to Send...');
        }
    }

    private function sendOrderEmails(Order $order, Client $client)
    {
        if ($client->email) {
            Mail::send('emails.order-confirmation', ['order' => $order, 'client' => $client], function ($message) use ($client) {
                $message->to($client->email)
                    ->subject('Order Confirmation');
            });
        }

        Mail::send('emails.order-notification', ['order' => $order], function ($message) {
            $message->to('Khrabish.store@gmail.com')
                ->subject('New Order Notification');
        });
    }
}
