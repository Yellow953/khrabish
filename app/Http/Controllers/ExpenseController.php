<?php

namespace App\Http\Controllers;

use App\Exports\ExpensesExport;
use App\Helpers\Helper;
use App\Models\Expense;
use App\Models\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $expenses = Expense::select('id', 'number', 'date', 'currency_id', 'amount', 'description', 'category')->filter()->orderBy('id', 'desc')->paginate(25);
        $categories = Helper::get_expense_categories();

        $data = compact('expenses', 'categories');
        return view('expenses.index', $data);
    }

    public function new()
    {
        $categories = Helper::get_expense_categories();
        return view('expenses.new', compact('categories'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense = Expense::create([
            'number' => Expense::generate_number(),
            'date' => $request->date,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'currency_id' => auth()->user()->currency_id,
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) . " created new Expense NO: {$expense->number}, datetime: " . now(),
        ]);

        return redirect()->route('expenses')->with('success', 'Expense created successfully!');
    }

    public function edit(Expense $expense)
    {
        $categories = Helper::get_expense_categories();

        $data = compact('expense', 'categories');
        return view('expenses.edit', $data);
    }

    public function update(Expense $expense, Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense->update([
            'date' => $request->date,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) . " updated Expense NO: {$expense->number}, datetime: " . now(),
        ]);

        return redirect()->route('expenses')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted Expense NO: " . $expense->number . ", datetime :   " . now();

            Log::create([
                'text' => $text,
            ]);
            $expense->delete();

            return redirect()->back()->with('error', 'Expense deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new ExpensesExport($filters), 'Expenses.xlsx');
    }

    public function pdf(Request $request)
    {
        $expenses = Expense::select('number', 'date', 'category', 'description', 'amount', 'created_at')->filter()->get();

        $pdf = Pdf::loadView('expenses.pdf', compact('expenses'));

        return $pdf->download('Expenses.pdf');
    }
}
