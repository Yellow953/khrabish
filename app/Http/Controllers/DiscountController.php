<?php

namespace App\Http\Controllers;

use App\Exports\DiscountsExport;
use App\Helpers\Helper;
use App\Models\Business;
use App\Models\Discount;
use App\Models\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['check']);
    }

    public function index()
    {
        $discounts = Discount::select('id', 'code', 'type', 'value', 'description')->filter()->orderBy('id', 'desc')->paginate(25);
        $types = Helper::get_discount_types();

        $data = compact('discounts', 'types');
        return view('discounts.index', $data);
    }

    public function new()
    {
        $types = Helper::get_discount_types();
        return view('discounts.new', compact('types'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'code' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        if ($request->type == 'Percentage' && $request->value > 100) {
            return redirect()->back()->with('error', 'Discount Value cannot be greater > than 100...');
        }

        Discount::create($data);

        $text = "Discount " . $request->code . " created in " . Carbon::now();

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('discounts')->with('success', 'Discount successfully created...');
    }

    public function edit(Discount $discount)
    {
        $types = Helper::get_discount_types();

        $data = compact('discount', 'types');
        return view('discounts.edit', $data);
    }

    public function update(Discount $discount, Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        if ($request->type == 'Percentage' && $request->value > 100) {
            return redirect()->back()->with('error', 'Discount Value cannot be greater > than 100...');
        }

        $discount->update($data);

        $text = "Discount " . $discount->code . " updated in " . Carbon::now();

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('discounts')->with('success', 'Discount successfully updated...');
    }

    public function destroy(Discount $discount)
    {
        if ($discount->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted Discount : " . $discount->code . ", datetime :   " . now();

            Log::create([
                'text' => $text,
            ]);
            $discount->delete();

            return redirect()->back()->with('error', 'Discount deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new DiscountsExport($filters), 'discounts.xlsx');
    }

    public function pdf(Request $request)
    {
        $discounts = Discount::select('type', 'code', 'value', 'description', 'created_at')->filter()->get();

        $pdf = Pdf::loadView('discounts.pdf', compact('discounts'));

        return $pdf->download('discounts.pdf');
    }

    public function check(Business $business, Request $request)
    {
        $discount = Discount::withoutGlobalScopes()->where('business_id', $business->id)->where('code', $request->discount)->first();

        if ($discount) {
            return response()->json(['exists' => true, 'type' => $discount->type, 'value' => $discount->value]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
}
