@extends('layouts.app')

@section('title', 'expenses')

@section('sub-title', 'edit')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card border-custom">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data"
            class="form">
            @csrf
            <div class="card-head">
                <h1 class="text-center text-primary">Edit Expense</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">Category</label>
                            <select name="category" class="form-select" data-control="select2" required
                                data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ $expense->category==$category ? 'selected' : ''
                                    }}>{{
                                    $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Date</label>
                            <input type="date" class="form-control" name="date" value="{{ $expense->date }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" value="{{ $expense->amount }}"
                                min="0" step="any" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">Description</label>
                            <textarea class="form-control" name="description" rows="3"
                                placeholder="Description goes here..." required>{{ $expense->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer pt-0">
                <div class="d-flex align-items-center justify-content-around">
                    <button type="reset" class="btn btn-danger clear-btn py-2 px-4 ms-3">Clear</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection