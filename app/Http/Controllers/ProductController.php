<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Barcode;
use App\Models\Category;
use App\Models\Log;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $products = Product::select('id', 'name', 'quantity', 'cost', 'price', 'image', 'category_id', 'description')->filter()->orderBy('id', 'desc')->paginate(25);
        $categories = Category::select('id', 'name')->get();
        $currency = auth()->user()->currency;

        $data = compact('products', 'categories', 'currency');
        return view('products.index', $data);
    }

    public function new()
    {
        $categories = Category::select('id', 'name')->get();
        return view('products.new', compact('categories'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products',
            'quantity' => 'required|numeric|min:1',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required',
            'barcodes' => 'array',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = auth()->user()->id . '_' . time() . '.' . $ext;
            $image = Image::make($file);
            $image->fit(300, 300, function ($constraint) {
                $constraint->upsize();
            });
            $image->save(public_path('uploads/products/' . $filename));
            $path = '/uploads/products/' . $filename;
        } else {
            $path = "assets/images/no_img.png";
        }

        $product = Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'cost' => $request->cost,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $path,
        ]);

        if ($request->barcodes[0] != null) {
            foreach ($request->barcodes as $barcode) {
                $product->barcodes()->create(['barcode' => $barcode]);
            }
        }

        $text = ucwords(auth()->user()->name) .  " created Product: " . $product->name . ", datetime: " . now();
        Log::create(['text' => $text]);

        return redirect()->route('products')->with('success', 'Product was successfully created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::select('id', 'name')->get();
        $data = compact('categories', 'product');

        return view('products.edit', $data);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = auth()->user()->id . '_' . time() . '.' . $ext;
            $image = Image::make($file);
            $image->fit(300, 300, function ($constraint) {
                $constraint->upsize();
            });
            $image->save(public_path('uploads/products/' . $filename));
            $path = '/uploads/products/' . $filename;
        } else {
            $path = $product->image;
        }

        $product->update([
            'name' => $request->name,
            'cost' => $request->cost,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $path,
        ]);

        if ($request->barcodes[0] != null) {
            $barcodes = array_filter(array_map('trim', $request->barcodes));
            $product->barcodes()->delete();
            foreach ($barcodes as $barcode) {
                $product->barcodes()->create(['barcode' => $barcode]);
            }
        }

        $text = ucwords(auth()->user()->name) .  " updated Product: " . $request->name . ", datetime: " . now();
        Log::create(['text' => $text]);

        return redirect()->route('products')->with('success', 'Product was successfully updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->can_delete()) {
            $text = ucwords(auth()->user()->name) .  " deleted Product: " . $product->name . ", datetime: " . now();

            if ($product->image != 'assets/images/no_img.png') {
                $path = public_path($product->image);
                File::delete($path);
            }

            foreach ($product->barcodes as $barcode) {
                $barcode->delete();
            }

            $product->delete();
            Log::create(['text' => $text]);

            return redirect()->back()->with('danger', 'Product was successfully deleted...');
        } else {
            return redirect()->back()->with('danger', 'Unable to delete Product...');
        }
    }

    public function import(Product $product)
    {
        return view('products.import', compact('product'));
    }

    public function save(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        $product->update([
            'quantity' => $product->quantity + $request->quantity,
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) . ' imported ' . $request->quantity . ' pcs to Product: ' . $product->name . ', datetime: ' . now(),
        ]);

        return redirect()->route('products')->with('success', 'Stock Imported Successfully...');
    }

    public function barcode($barcode)
    {
        $barcodeEntry = Barcode::where('barcode', $barcode)->with('product')->first();

        if ($barcodeEntry) {
            $product = $barcodeEntry->product;

            $currencyRate = auth()->user()->currency->rate ?? 1;

            $product->price = $product->price * $currencyRate;

            return response()->json($product);
        }

        return response()->json(['message' => 'Product not found.'], 404);
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
