<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('frontend.index', compact('categories'));
    }

    public function shop()
    {
        $products = Product::filter()->get();
        return view('frontend.shop', compact('products'));
    }

    public function product(Product $product)
    {
        return view('frontend.product', compact('product'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }

    public function order(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30|unique:users,phone',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'zip' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'shipping' => 'required|numeric|min:1',
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
            $user = User::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => $request->country,
                    'zip' => $request->zip,
                    'password' => bcrypt('password'),
                ]
            );

            $order = Order::create([
                'client_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'payment_method' => $request->payment_method,
                'sub_total' => $subTotal,
                'total' => $total,
                'products_count' => $productsCount,
                'notes' => $request->notes,
                'status' => 'new',
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

            $this->sendOrderEmails($order, $user);

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
            ->take(10)
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
        ]);

        $data = $request->all();

        Mail::send('emails.contact', ['data' => $data,], function ($message) {
            $message->to('fatimakhansa97@gmail.com')
                ->subject('New Contact');
        });

        return redirect()->back()->with('success', 'Contact Form Submitted Successfully');
    }

    private function sendOrderEmails(Order $order, User $user)
    {
        if ($user->email) {
            Mail::send('emails.order-confirmation', ['order' => $order, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Order Confirmation');
            });
        }

        Mail::send('emails.order-notification', ['order' => $order], function ($message) {
            $message->to('Fatimakhansa97@gmail.com')
                ->subject('New Order Notification');
        });
    }
}
