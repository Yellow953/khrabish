@extends('layouts.app')

@section('title', 'reports')

@section('sub-title', 'new')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card">
        <form action="{{ route('reports.create') }}" method="POST" enctype="multipart/form-data" class="form">
            @csrf
            <div class="card-head">
                <h1 class="text-center text-primary">New Report</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Date</label>
                            <input type="date" class="form-control" name="date" placeholder="Enter Date..."
                                value="{{ old('date') ?? date('Y-m-d') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Currency</label>
                            <select name="currency_id" class="form-select" data-control="select2" required
                                data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ auth()->user()->currency_id == $currency->id ?
                                    'selected' : '' }} {{ old('currency_id')==$currency->id ? 'selected' :
                                    '' }}>{{ ucwords($currency->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Start Cash</label>
                            <input type="number" step="any" min="0" class="form-control" name="start_cash"
                                placeholder="Enter Start Cash..." value="{{ old('start_cash') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">End Cash</label>
                            <input type="number" step="any" min="0" class="form-control" name="end_cash"
                                placeholder="Enter End Cash..." value="{{ old('end_cash') }}" required />
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