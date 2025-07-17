@extends('layouts.app')

@section('title', 'products')

@section('sub-title', 'import')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('purchases.new') }}">
    <i class="fa-solid fa-plus"></i>
    <span class="d-none d-md-inline">Purchase</span>
</a>

<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card border-custom shadow-sm mb-4 overflow-hidden">
                <img src="{{ asset('assets/images/stock.png') }}" alt="" class="img-fluid">
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-custom shadow-sm mb-4">
                <form action="{{ route('products.save', $product->id) }}" method="POST" enctype="multipart/form-data"
                    class="form">
                    @csrf
                    <div class="card-head pb-0">
                        <h1 class="text-center text-primary">Import Product</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">Purchase</label>
                                    <select name="purchase_id" class="form-select" data-control="select2" required
                                        data-placeholder="Select an option">
                                        <option value=""></option>
                                        @foreach ($purchases as $purchase)
                                        <option value="{{ $purchase->id }}" {{ old('purchase_id')==$purchase->id ?
                                            'selected' :
                                            '' }}># {{ ucwords($purchase->number) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required form-label">Quantity</label>
                                    <input type="number" class="form-control" name="quantity"
                                        placeholder="Enter Quantity..." value="{{ old('quantity') }}" required min="0"
                                        step="any" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required form-label">Cost</label>
                                    <input type="number" class="form-control" name="cost" placeholder="Enter Cost..."
                                        value="{{ old('cost') }}" required min="0" step="any" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h2 class="text-primary my-3">Barcodes and SKU</h2>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Barcodes</label>
                                    <div id="barcode-wrapper">
                                    </div>
                                    <button type="button" id="add-barcode" class="btn btn-success mt-2"><i
                                            class="fa fa-plus"></i> Barcode</button>
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

<script>
    // Disable Barcode ENTER
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
            event.preventDefault();

            if (event.target.tagName === 'INPUT') {
                event.target.value.trim();
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var barcodeWrapper = document.getElementById('barcode-wrapper');
        var addBarcodeBtn = document.getElementById('add-barcode');

        function checkDuplicates() {
            var barcodeInputs = document.querySelectorAll('input[name="barcodes[]"]');
            var barcodeValues = [];

            barcodeInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    barcodeValues = [];
                    barcodeInputs.forEach(function(innerInput) {
                        if (barcodeValues.includes(innerInput.value.trim()) && innerInput.value.trim() !== '') {
                            innerInput.classList.add('is-invalid');
                        } else {
                            innerInput.classList.remove('is-invalid');
                            barcodeValues.push(innerInput.value.trim());
                        }
                    });
                });
            });
        }

        addBarcodeBtn.addEventListener('click', function() {
            var newBarcodeInput = document.createElement('div');
            newBarcodeInput.classList.add('input-group', 'mb-2');

            newBarcodeInput.innerHTML = `
                <input type="text" class="form-control" name="barcodes[]" placeholder="Enter Barcode" required />
                <button type="button" class="btn btn-danger btn-sm remove-barcode"><i class="fa fa-trash"></i></button>
            `;

            barcodeWrapper.appendChild(newBarcodeInput);
            checkDuplicates();
        });

        barcodeWrapper.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-barcode')) {
                event.target.closest('.input-group').remove();
            }
        });

        checkDuplicates();
    });
</script>
@endsection