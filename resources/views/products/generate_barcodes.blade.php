@extends('layouts.app')

@section('title', 'products')

@section('sub-title', 'generate barcodes')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-5">
            <div class="card border-custom">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">Barcode Generator</h3>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="barcode-icon-container">
                            <i class="bi bi-upc-scan" style="font-size: 300px; opacity: 0.5; margin-bottom: 10px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-custom">
                <div class="card-body">
                    <div class="row my-5">
                        <div class="col-md-6">
                            <label class="form-label required">Product</label>
                            <select name="product_id" id="productSelect" class="form-select" data-control="select2"
                                required data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}">
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="alignmentSelect" class="form-label">Label Alignment:</label>
                            <select id="alignmentSelect" class="form-select">
                                <option value="center" selected>Center</option>
                                <option value="left">Left</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                    </div>

                    <div class="row my-5">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity of Labels:</label>
                            <input type="number" id="quantity" class="form-control" value="10" min="1" step="1">
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="showName" checked>
                                <label class="form-check-label" for="showName">
                                    Show Name
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="showPrice" checked>
                                <label class="form-check-label" for="showPrice">
                                    Show Price
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="my-5">
                        <button id="generateBtn" class="btn btn-primary me-2">Generate Labels</button>
                        <button id="printBtn" class="btn btn-success">Print Labels</button>
                    </div>

                    <div id="errorAlert" class="alert alert-danger d-none"></div>
                </div>
            </div>

            <div class="card border-custom mt-5">
                <div id="barcodePreview" class="d-flex flex-wrap gap-4 p-4">
                    <div class="w-100 text-center">Preview</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"
    integrity="sha512-k2wo/BkbloaRU7gc/RkCekHr4IOVe10kYxJ/Q8dRPl7u3YshAQmg3WfZtIcseEk+nGBdK03fHBeLgXTxRmWCLQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/generate_barcode.js') }}"></script>
@endsection