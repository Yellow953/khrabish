@extends('layouts.app')

@section('title', 'debts')

@section('sub-title', 'new')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <form action="{{ route('debts.create') }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        <div class="card">
            <div class="card-head">
                <h1 class="text-center text-primary">New Debt</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">Type</label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="">Select An Option</option>
                                @foreach ($types as $type)
                                <option value="{{ $type }}" {{ old('type')==$type ? 'selected' : '' }}>{{ ucwords($type)
                                    }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="creditor" class="form-group">
                            <!-- Dynamic select will be appended here -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter Amount..."
                                step="any" min="0" value="{{ old('amount') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">Currency</label>
                            <select name="currency_id" class="form-select" data-control="select2" required
                                data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ auth()->user()->currency_id == $currency->id ?
                                    'selected' : '' }} {{ old('currency_id')==$currency->id ? 'selected' :
                                    '' }}>{{ ucwords($currency->code) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

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
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="3"
                                placeholder="Enter Note...">{{ old('note') }}</textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const suppliers = @json($suppliers);
        const clients = @json($clients);
        const typeSelect = document.getElementById('type');
        const creditorContainer = document.getElementById('creditor');

        typeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            creditorContainer.innerHTML = '';

            let data = [];
            let name = '';
            let label = '';

            if (selectedType === 'supplier') {
                data = suppliers;
                name = 'supplier_id';
                label = 'Supplier';
            } else if (selectedType === 'client') {
                data = clients;
                name = 'client_id';
                label = 'Client';
            }

            if (data.length > 0) {
                const labelElement = document.createElement('label');
                labelElement.classList.add('form-label', 'required');
                labelElement.textContent = label;

                const selectElement = document.createElement('select');
                selectElement.name = name;
                selectElement.classList.add('form-select');
                selectElement.required = true;
                selectElement.setAttribute('data-control', 'select2');

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select an option';
                selectElement.appendChild(defaultOption);

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    selectElement.appendChild(option);
                });

                creditorContainer.appendChild(labelElement);
                creditorContainer.appendChild(selectElement);

                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(selectElement).select2();
                }
            }
        });
    });
</script>

@endsection