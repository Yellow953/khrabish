@extends('layouts.app')

@section('title', 'purchases')

@section('sub-title', 'new')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('products.new') }}">
    <i class="fa-solid fa-plus"></i>
    <span class="d-none d-md-inline">New Product</span>
</a>
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 order-2 order-md-1">
            <div class="card border-custom my-4 mt-md-0 shadow-sm radius-10px">
                <img src="{{ asset('assets/images/sales_order.png') }}" alt="Sales Order" class="img-fluid">
            </div>

            <div class="card border-custom p-4 my-4 mt-md-0 shadow-sm products-container">
                <h2 class="text-center text-primary my-4">Products</h2>

                <div class="d-flex p-4 mb-4">
                    <input type="text" id="searchInput" name="search" placeholder="Search Product ..."
                        class="form-control me-2">
                    <button type="submit" id="searchBtn" class="btn btn-sm btn-primary px-4 ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <div id="no-results" class="text-muted text-center d-none">No matching products found.</div>
                <div id="products" class="px-5 max-h-400px">
                    @foreach ($products as $product)
                    <div class="product-row row" data-name="{{ strtolower($product->name) }}">
                        <div class="col-9 pb-2 my-auto">
                            {{ $product->name }}
                        </div>
                        <div class="col-3 pb-2 my-auto text-end">
                            <button class="btn btn-sm btn-success px-4"
                                onclick="addProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->price ?? 0 }})">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <hr>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-8 order-1 order-md-2">
            <form action="{{ route('purchases.create') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card border-custom p-4 my-4 mt-md-0 shadow-sm">
                    <h2 class="text-center text-primary my-4">New Purchase</h2>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id" class="col-form-label">Supplier *</label>

                                <select name="supplier_id" id="supplier_id" required class="form-select"
                                    data-control="select2" required data-placeholder="Select an option">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $supplier->id==old('supplier_id') ?
                                        'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_number" class="col-form-label">Invoice Number
                                    *</label>

                                <input id="invoice_number" type="text" placeholder="Enter Invoice Number" required
                                    class="form-control" name="invoice_number" value="{{ old('invoice_number') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_date" class="col-form-label">Purchase Date *</label>

                                <input id="purchase_date" type="date" required class="form-control" name="purchase_date"
                                    value="{{ old('purchase_date') ?? date('Y-m-d') }}">

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="col-form-label">Status *</label>

                                <select name="status" id="status" required class="form-select" data-control="select2"
                                    required data-placeholder="Select an option">
                                    <option value="">Select Status</option>
                                    @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ $status==old('status') ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paid_amount" class="col-form-label">Paid Amount
                                    *</label>

                                <input id="paid_amount" type="number" placeholder="Enter Paid Amount" required min="0"
                                    step="any" class="form-control" name="paid_amount"
                                    value="{{ old('paid_amount') ?? 0 }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="col-form-label">Notes</label>

                        <textarea id="notes" placeholder="Enter any Notes" rows="3" class="form-control"
                            name="notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="card border-custom p-4 mb-4 shadow-sm">
                    <h2 class="text-center text-primary my-4">Items</h2>

                    <div class="py-4 px-5">
                        <div class="row">
                            <div class="col-4 fs-5 fw-bold">Product</div>
                            <div class="col-3 fs-5 fw-bold">Quantity</div>
                            <div class="col-3 fs-5 fw-bold">Cost</div>
                            <div class="col-2 fs-5 fw-bold">Remove</div>
                        </div>
                        <div id="product-items" class="my-4"></div>
                    </div>
                </div>

                <div class="card border-custom mb-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-around py-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const productItems = [];

    function addProduct(id, name) {
        productItems.push({ id, name });

        updateproductItemsUI();
    }

    function removeProduct(index) {
        productItems.splice(index, 1);

        updateproductItemsUI();
    }

    function updateproductItemsUI() {
        const productItemsContainer = document.getElementById('product-items');
        productItemsContainer.innerHTML = '';

        if (productItems.length === 0) {
            productItemsContainer.innerHTML = '<p class="text-center text-muted">No products added yet...</p>';
            return;
        }

        productItems.forEach((product, index) => {
            const itemRow = document.createElement('div');
            itemRow.className = 'row mb-2';

            itemRow.innerHTML = `
                <input type="hidden" name="items[${index}][product_id]" value="${product.id}">
                <div class="col-4 my-auto">
                    ${product.name}
                </div>
                <div class="col-3 my-auto">
                    <input type="number" name="items[${index}][quantity]" value="1" min="0" step="any" class="form-control" placeholder="Quantity" required>
                </div>
                <div class="col-3 my-auto">
                    <input type="number" min="0" step="any" name="items[${index}][cost]" class="form-control" required>
                </div>
                <div class="col-2 my-auto text-center">
                    <button class="btn btn-sm btn-danger px-4" onclick="removeProduct(${index})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;

            productItemsContainer.appendChild(itemRow);
        });
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();
        const productRows = document.querySelectorAll('.product-row');
        let visibleCount = 0;

        productRows.forEach(row => {
            const name = row.getAttribute('data-name');
            const match = name.includes(query);
            row.style.display = match ? 'flex' : 'none';
            if (match) visibleCount++;
        });

        document.getElementById('no-results').classList.toggle('d-none', visibleCount > 0);
    });
</script>
@endsection