@extends('layouts.app')

@section('title', 'products')

@section('sub-title', 'import')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 overflow-hidden">
                <img src="{{ asset('assets/images/import.png') }}" alt="" class="img-fluid">
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <form action="{{ route('products.save', $product->id) }}" method="POST" enctype="multipart/form-data"
                    class="form">
                    @csrf
                    <div class="card-head pb-0">
                        <h1 class="text-center text-primary">Import Product</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required form-label">Quantity</label>
                                    <input type="number" class="form-control" name="quantity"
                                        placeholder="Enter Quantity..." value="{{ old('quantity') }}" required min="1"
                                        step="any" />
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
    </div>
</div>
@endsection