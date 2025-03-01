<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //

    public function index()
    {
        return view('shop.index');
    }

    public function product(Product $product)
    {
        return view('shop.product', compact('product'));
    }

    public function checkout()
    {
        return view('shop.checkout');
    }

    public function about()
    {
        return view('shop.about');
    }

    public function contact()
    {
        return view('shop.contact');
    }

    public function shop()
    {
        $products = Product::filter()->get();
        return view('shop.shop', compact('products'));
    }

}
