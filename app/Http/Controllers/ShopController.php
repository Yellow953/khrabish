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
        $categories = Category::select('id', 'name', 'image')->get();
        $booming_offers = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags')->where('booming', true)->where('public', true)->with('variants')->orderBy('created_at', 'DESC')->limit(10)->get();

        $latest_additions = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags')->where('public', true)->orderBy('created_at', 'desc')->limit(12)->get();
        $order_count = Order::count();
        if ($order_count < 10) {
            $best_sellers = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags')->where('public', true)->inRandomOrder()->limit(12)->get();
        } else {
            $best_sellers = Product::select('products.id', 'products.name', 'products.image', 'products.price', 'products.compare_price', 'products.tags')->where('public', true)->join('order_items', 'products.id', '=', 'order_items.product_id')->groupBy('products.id', 'products.name', 'products.image')->orderByRaw('COUNT(order_items.id) DESC')->with('variants')->limit(12)->get();
        }

        $data = compact('categories', 'booming_offers', 'latest_additions', 'best_sellers');
        return view('frontend.index', $data);
    }

    public function shop(Request $request)
    {
        $categories = Category::select('id', 'name', 'image')->get();
        
        // Start building query
        $query = Product::select('id', 'name', 'category_id', 'image', 'price', 'compare_price', 'tags', 'created_at')
            ->where('public', true)
            ->with('variants', 'category');

        // Filter by category (support both category name and category_id)
        if ($request->input('category')) {
            $category = Category::where('name', $request->input('category'))->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        } elseif ($request->input('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by price range
        if ($request->input('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->input('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Filter by on sale (products with compare_price)
        if ($request->input('on_sale') == '1') {
            $query->whereNotNull('compare_price');
        }

        // Sort by
        $sortBy = $request->input('sort_by', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get min and max prices for filter range
        $minPrice = Product::where('public', true)->min('price') ?? 0;
        $maxPrice = Product::where('public', true)->max('price') ?? 1000;

        $products = $query->paginate(12)->withQueryString();

        $data = compact('categories', 'products', 'minPrice', 'maxPrice');
        return view('frontend.shop', $data);
    }

    public function product(Product $product)
    {
        if (!$product->public) return redirect()->back()->with('warning', 'Product Not Public!');

        $categories = Category::select('id', 'name', 'image')->get();
        $simillar_products = Product::select('id', 'name', 'image')->where('public', true)->where('category_id', $product->category_id)->limit(10)->get();

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

        $products = Product::where('name', 'like', '%' . $query . '%')->where('public', true)
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

    public function booming()
    {
        $categories = Category::select('id', 'name', 'image')->get();
        $booming_offers = Product::select('id', 'name', 'image', 'price', 'compare_price', 'tags', 'category_id')->where('booming', true)->where('public', true)->with(['variants', 'category'])->orderBy('created_at', 'DESC')->paginate();

        $data = compact('categories', 'booming_offers');
        return view('frontend.booming', $data);
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
