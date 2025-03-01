@extends('layouts.app')

@section('title', 'debts')

@section('sub-title', 'edit')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card">
        <form action="{{ route('debts.update', $debt->id) }}" method="POST" enctype="multipart/form-data" class="form">
            @csrf
            <div class="card-head">
                <h1 class="text-center text-primary">Edit Debt</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter Amount..."
                                step="any" min="0" value="{{ $debt->amount }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">Currency</label>
                            <select name="currency_id" class="form-select" data-control="select2" required
                                data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ $debt->currency_id==$currency->id ? 'selected' :
                                    '' }}>{{ ucwords($currency->code) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Date</label>
                                <input type="date" class="form-control" name="date" placeholder="Enter Date..."
                                    value="{{ $debt->date }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3"
                                    placeholder="Enter Note...">{{ $debt->note }}</textarea>
                            </div>
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