<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Exports\PurchasesExport;
use App\Helpers\Helper;
use App\Models\PurchaseItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $purchases = Purchase::select('id', 'number', 'supplier_id', 'currency_id', 'purchase_date', 'invoice_number', 'total', 'status')->filter()->orderBy('id', 'desc')->paginate(25);
        $suppliers = Supplier::select('id', 'name')->get();
        $statuses = Helper::get_purchase_statuses();

        $data = compact('purchases', 'suppliers', 'statuses');
        return view('purchases.index', $data);
    }

    public function new()
    {
        $suppliers = Supplier::select('id', 'name')->get();
        $products = Product::select('id', 'name')->get();
        $statuses = Helper::get_purchase_statuses();

        $data = compact('suppliers', 'products', 'statuses');
        return view('purchases.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
            'invoice_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|max:255',
            'paid_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'number' => Purchase::generate_number(),
                'supplier_id' => $request->supplier_id,
                'currency_id' => auth()->user()->currency_id,
                'purchase_date' => $request->purchase_date,
                'invoice_number' => $request->invoice_number,
                'notes' => $request->notes,
                'total' => 0,
                'status' => $request->status,
                'paid_amount' => $request->paid_amount,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['cost'];
                $total += $lineTotal;
                $product = Product::findOrFail($item['product_id']);

                $product->update([
                    'quantity' => ($product->quantity + $item['quantity']),
                ]);

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost' => $item['cost'],
                    'total' => $lineTotal,
                ]);
            }

            $purchase->update(['total' => $total]);

            Log::create([
                'text' => ucwords(auth()->user()->name) . " created new Purchase NO: {$purchase->number}, datetime: " . now(),
            ]);

            DB::commit();

            return redirect()->route('purchases')->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while creating the purchase.');
        }
    }

    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $products = Product::select('id', 'name')->get();
        $statuses = Helper::get_purchase_statuses();

        $data = compact('purchase', 'products', 'statuses');
        return view('purchases.edit', $data);
    }

    public function update(Purchase $purchase, Request $request)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'invoice_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|max:255',
            'paid_amount' => 'required|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.quantity' => 'nullable|numeric|min:0.01',
            'items.*.cost' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;

            if ($request->filled('items')) {
                foreach ($request->items as $item) {
                    $lineTotal = $item['quantity'] * $item['cost'];
                    $total += $lineTotal;

                    $product = Product::findOrFail($item['product_id']);
                    $product->update([
                        'quantity' => $product->quantity + $item['quantity'],
                    ]);

                    $purchase->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'cost' => $item['cost'],
                        'total' => $lineTotal,
                    ]);
                }
            } else {
                $total = $purchase->total;
            }

            $purchase->update([
                'purchase_date' => $request->purchase_date,
                'invoice_number' => $request->invoice_number,
                'notes' => $request->notes,
                'total' => $total,
                'status' => $request->status,
                'paid_amount' => $request->paid_amount,
            ]);

            Log::create([
                'text' => ucwords(auth()->user()->name) . " updated Purchase NO: {$purchase->number}, datetime: " . now(),
            ]);

            DB::commit();

            return redirect()->route('purchases')->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while updating the purchase. ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted Purchase NO: " . $purchase->number . ", datetime :   " . now();

            foreach ($purchase->items as $item) {
                $item->product->update([
                    'quantity' => ($item->product->quantity - $item->quantity),
                ]);

                $item->delete();
            }

            Log::create([
                'text' => $text,
            ]);
            $purchase->delete();

            return redirect()->back()->with('error', 'Purchase deleted successfully and Products returned!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function purchase_item_destroy(PurchaseItem $purchase_item)
    {
        $purchase_item->product->update([
            'quantity' => ($purchase_item->product->quantity - $purchase_item->quantity),
        ]);

        $text = ucwords(auth()->user()->name) . " returned " . $purchase_item->quantity . " of " . ucwords($purchase_item->product->name) . " from Purchase NO: " . $purchase_item->purchase->number . ", datetime :   " . now();

        Log::create([
            'text' => $text,
        ]);

        $purchase_item->delete();

        return redirect()->back()->with('error', 'Purchase Item returned successfully!');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new PurchasesExport($filters), 'Purchases.xlsx');
    }

    public function pdf(Request $request)
    {
        $purchases = Purchase::with('supplier')->filter()->get();

        $pdf = Pdf::loadView('purchases.pdf', compact('purchases'));

        return $pdf->download('Purchases.pdf');
    }
}
