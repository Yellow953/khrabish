<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Barcode;
use App\Models\Category;
use App\Models\Log;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SecondaryImage;
use App\Models\Variant;
use App\Models\VariantOption;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('barcode');
    }

    public function index()
    {
        $products = Product::select('id', 'name', 'quantity', 'cost', 'price', 'image', 'category_id', 'description', 'booming', 'public')->filter()->orderBy('id', 'desc')->paginate(25);
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
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'category_id' => 'required',
            'barcodes' => 'array',
            'tags' => 'nullable|string',
            'booming' => 'nullable|boolean',
            'public' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = auth()->id() . '_' . time() . '.' . $ext;
            $image = Image::make($file);
            $image->fit(560, 560, function ($constraint) {
                $constraint->upsize();
            });
            $image->save(public_path('uploads/products/' . $filename));
            $path = '/uploads/products/' . $filename;
        } else {
            $path = "assets/images/no_img.png";
        }

        $tags = $request->input('tags');
        $tags = $tags
            ? collect(explode(',', $tags))->map(fn($tag) => trim($tag))->filter()->values()->toArray()
            : [];

        $product = Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'cost' => $request->cost,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $path,
            'tags' => $tags,
            'booming' => $request->has('booming') ? 1 : 0,
            'public' => $request->has('public') ? 1 : 0,
        ]);

        if ($request->barcodes) {
            foreach ($request->barcodes as $barcode) {
                $product->barcodes()->create(['barcode' => $barcode]);
            }
        }

        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $image) {
                $ext = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $ext;
                $picture = Image::make($image);

                $picture->fit(560, 560, function ($constraint) {
                    $constraint->upsize();
                });

                $path = 'uploads/products/' . $filename;
                $picture->save(public_path($path));

                SecondaryImage::create([
                    'product_id' => $product->id,
                    'path' => '/' . $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if ($request->variants) {
            foreach ($request->variants as $variant) {
                $variantModel = $product->variants()->create([
                    'title' => $variant['title'],
                    'type' => $variant['type'],
                ]);

                foreach ($variant['options'] as $option) {
                    $variantModel->options()->create([
                        'value' => $option['value'],
                        'quantity' => $option['quantity'],
                        'price' => $option['price'],
                    ]);
                }
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
            'compare_price' => 'nullable|numeric|min:0',
            'category_id' => 'required',
            'tags' => 'nullable|string',
            'booming' => 'nullable|boolean',
            'public' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = auth()->id() . '_' . time() . '.' . $ext;
            $image = Image::make($file);
            $image->fit(560, 560, function ($constraint) {
                $constraint->upsize();
            });
            $image->save(public_path('uploads/products/' . $filename));
            $path = '/uploads/products/' . $filename;
        } else {
            $path = $product->image;
        }

        $tags = $request->input('tags');
        $tags = $tags
            ? collect(explode(',', $tags))->map(fn($tag) => trim($tag))->filter()->values()->toArray()
            : [];

        $product->update([
            'name' => $request->name,
            'cost' => $request->cost,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $path,
            'tags' => $tags,
            'booming' => $request->has('booming') ? 1 : 0,
            'public' => $request->has('public') ? 1 : 0,
        ]);

        if ($request->barcodes) {
            $barcodes = array_filter(array_map('trim', $request->barcodes));
            $product->barcodes()->delete();
            foreach ($barcodes as $barcode) {
                $product->barcodes()->create(['barcode' => $barcode]);
            }
        }

        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $image) {
                $ext = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $ext;
                $picture = Image::make($image);

                $picture->fit(560, 560, function ($constraint) {
                    $constraint->upsize();
                });

                $path = 'uploads/products/' . $filename;
                $picture->save(public_path($path));

                SecondaryImage::create([
                    'product_id' => $product->id,
                    'path' => '/' . $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if ($request->variants) {
            foreach ($request->variants as $variant) {
                $variantModel = $product->variants()->create([
                    'title' => $variant['title'],
                    'type' => $variant['type'],
                ]);

                foreach ($variant['options'] as $option) {
                    $variantModel->options()->create([
                        'value' => $option['value'],
                        'quantity' => $option['quantity'],
                        'price' => $option['price'],
                    ]);
                }
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

            foreach ($product->secondary_images as $image) {
                $path = public_path($image->path);
                File::delete($path);
                $image->delete();
            }

            foreach ($product->variants as $variant) {
                foreach ($variant->options as $option) {
                    $option->delete();
                }
                $variant->delete();
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
        $purchases = Purchase::select('id', 'number')->get();

        $data = compact('product', 'purchases');
        return view('products.import', $data);
    }

    public function save(Product $product, Request $request)
    {
        $request->validate([
            'purchase_id' => 'required',
            'quantity' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
        ]);

        if ($request->barcodes) {
            $barcodes = array_filter(array_map('trim', $request->barcodes));
            $product->barcodes()->delete();
            foreach ($barcodes as $barcode) {
                $product->barcodes()->create(['barcode' => $barcode]);
            }
        }

        $purchase = Purchase::findOrFail($request->purchase_id);

        $purchase->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'cost' => $request->cost,
            'total' => $request->quantity * $request->cost,
        ]);

        $product->update([
            'quantity' => $product->quantity + $request->quantity,
        ]);

        $purchase->update([
            'total' => $purchase->total + ($request->quantity + $request->cost),
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) . ' imported ' . $request->quantity . ' pcs to Product: ' . ucwords($product->name) . ', datetime: ' . now(),
        ]);

        return redirect()->route('products')->with('success', 'Stock Imported Successfully...');
    }

    public function barcode($barcode)
    {
        $barcodeEntry = Barcode::where('barcode', $barcode)->with('product.variants.options')->first();

        if ($barcodeEntry) {
            $product = $barcodeEntry->product;

            $currencyRate = auth()->user()->currency->rate ?? 1;

            $product->price = $product->price * $currencyRate;

            return response()->json($product);
        }

        return response()->json(['message' => 'Product not found.'], 404);
    }

    public function generate_barcodes()
    {
        $products = Product::select('id', 'name', 'price')->get();
        return view('products.generate_barcodes', compact('products'));
    }

    public function save_barcodes(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'barcodes' => 'required|array',
            'barcodes.*' => 'required|string|max:255'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);

            foreach ($request->barcodes as $barcode) {
                $product->barcodes()->create(['barcode' => $barcode]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Barcodes saved successfully',
                'count' => count($request->barcodes)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save barcodes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function secondary_image_delete(SecondaryImage $secondary_image)
    {
        $path = public_path($secondary_image->path);
        File::delete($path);
        $secondary_image->delete();

        return redirect()->back()->with('danger', 'Image deleted successfully...');
    }

    public function variant_delete(Variant $variant)
    {
        foreach ($variant->options as $option) {
            $option->delete();
        }

        $variant->delete();

        return redirect()->back()->with('danger', 'Variant deleted successfully...');
    }

    public function variant_option_delete(VariantOption $variant_option)
    {
        $variant_option->delete();

        return redirect()->back()->with('danger', 'Variant option deleted successfully...');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new ProductsExport($filters), 'Products.xlsx');
    }

    public function pdf(Request $request)
    {
        $products = Product::with('category')->filter()->get();

        $pdf = Pdf::loadView('products.pdf', compact('products'));

        return $pdf->download('Products.pdf');
    }
}
