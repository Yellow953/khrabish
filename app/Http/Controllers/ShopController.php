<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Discount;
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
        $categories = Category::select('id', 'name', 'image')->get();
        $booming_offers = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags')->whereRaw("JSON_SEARCH(tags, 'one', 'sale_%') IS NOT NULL")->with('variants')->orderBy('created_at', 'DESC')->limit(10)->get();

        $latest_additions = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags')->orderBy('created_at', 'desc')->limit(12)->get();
        $order_count = Order::count();
        if ($order_count < 10) {
            $best_sellers = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags')->inRandomOrder()->limit(12)->get();
        } else {
            $best_sellers = Product::select('products.id', 'products.name', 'products.image', 'products.price', 'products.compare_price', 'products.tags')->join('order_items', 'products.id', '=', 'order_items.product_id')->groupBy('products.id', 'products.name', 'products.image')->orderByRaw('COUNT(order_items.id) DESC')->with('variants')->limit(12)->get();
        }

        $data = compact('categories', 'booming_offers', 'latest_additions', 'best_sellers');
        return view('frontend.index', $data);
    }

    public function shop(Request $request)
    {
        $categories = Category::select('id', 'name', 'image')->get();

        if ($request->input('category')) {
            $category = Category::where('name', $request->input('category'))->firstOrFail();
            $products = Product::select('id', 'name', 'category_id', 'image', 'price', 'compare_price', 'tags')->where('category_id', $category->id)->with('variants')->paginate(12);
        } else {
            $products = Product::select('id', 'name', 'category_id', 'image', 'price', 'compare_price', 'tags')->with('variants')->paginate(12);
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
        $categories = Category::select('id', 'name', 'image')->get();
        $countries = Helper::get_countries();

        $data = compact('countries', 'categories');
        return view('frontend.checkout', $data);
    }

    public function order(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
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
            $subTotal += $item['finalPrice'] * $item['quantity'];
            $productsCount += $item['quantity'];
        }

        $shippingFee = $request->shipping;
        $discountAmount = $request->discount ?? 0;
        $total = $subTotal + $shippingFee - $discountAmount;

        DB::beginTransaction();
        try {
            $client = Client::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'address' => $request->address,
                    'status' => 'active',
                ]
            );

            $currency = Currency::where('code', 'USD')->firstOrFail();

            $order = Order::create([
                'currency_id' => $currency->id,
                'client_id' => $client->id,
                'order_number' => Order::generate_number(),
                'payment_method' => $request->payment_method,
                'sub_total' => $subTotal,
                'discount' => $discountAmount,
                'total' => $total,
                'products_count' => $productsCount,
                'note' => $request->notes,
            ]);

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);

                $variantDetails = [];
                if (!empty($item['variants'])) {
                    foreach ($item['variants'] as $variant) {
                        $variantDetails[] = [
                            'variant_id' => $variant['variant_id'],
                            'value' => $variant['value'],
                            'price_adjustment' => $variant['price_adjustment'],
                        ];
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['finalPrice'],
                    'total' => $item['finalPrice'] * $item['quantity'],
                    'variant_details' => json_encode($variantDetails),
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
