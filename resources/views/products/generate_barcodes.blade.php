@extends('layouts.app')

@section('title', 'products')

@section('sub-title', 'generate_barcodes')

@section('actions')
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/barcodes.css') }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
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

                    <button class="btn btn-primary w-100 py-3" style="margin-top: 30px;" id="generate_barcodes">
                        <i class="bi bi-upc me-2"></i>Generate Barcodes
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Barcode Configuration</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode_prefix">Prefix (Optional)</label>
                                <input type="text" class="form-control" id="barcode_prefix" placeholder="Enter prefix">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode_quantity">Quantity</label>
                                <input type="number" class="form-control" id="barcode_quantity" value="10" min="1"
                                    max="100">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode_type">Barcode Type</label>
                                <select class="form-control" id="barcode_type">
                                    <option value="CODE128">Code 128</option>
                                    <option value="CODE39">Code 39</option>
                                    <option value="EAN13">EAN-13</option>
                                    <option value="UPC">UPC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button class="btn btn-success w-100" id="print_barcodes" disabled>
                                    <i class="bi bi-printer"></i> <span class="ms-1">Print Barcodes</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="barcodes_container" class="d-none">
                        <div class="mb-3 border-top pt-3">
                            <h4>Generated Barcodes</h4>
                        </div>
                        <div class="row" id="barcode_list">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="print_container" class="d-none">
    <div id="stacked_barcodes"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const generateBtn = document.getElementById('generate_barcodes');
            const printBtn = document.getElementById('print_barcodes');
            const barcodesContainer = document.getElementById('barcodes_container');
            const barcodeList = document.getElementById('barcode_list');
            const barcodeIcon = document.querySelector('.barcode-icon-container');
            const stackedBarcodes = document.getElementById('stacked_barcodes');
            const printContainer = document.getElementById('print_container');

            barcodeIcon.addEventListener('click', function() {
                generateBtn.click();
            });

            generateBtn.addEventListener('click', generateBarcodes);
            printBtn.addEventListener('click', printBarcodes);

            function generateBarcodes() {
                const prefix = document.getElementById('barcode_prefix').value;
                const quantity = parseInt(document.getElementById('barcode_quantity').value) || 10;
                const barcodeType = document.getElementById('barcode_type').value;

                barcodeList.innerHTML = '';
                stackedBarcodes.innerHTML = '';

                for (let i = 0; i < quantity; i++) {
                    const randomPart = Math.floor(Math.random() * 10000000000).toString().padStart(10, '0');
                    const barcodeValue = prefix + randomPart;

                    createDisplayBarcode(barcodeValue, barcodeType);

                    createStackedBarcode(barcodeValue, barcodeType);
                }

                barcodesContainer.classList.remove('d-none');
                printBtn.disabled = false;
            }

            function createDisplayBarcode(barcodeValue, barcodeType) {
                const barcodeCol = document.createElement('div');
                barcodeCol.className = 'col-md-4 col-sm-6 col-6';

                const barcodeItem = document.createElement('div');
                barcodeItem.className = 'barcode-item';

                const barcodeCanvas = document.createElement('canvas');
                barcodeCanvas.className = 'barcode-canvas';

                const barcodeText = document.createElement('div');
                barcodeText.className = 'barcode-value';
                barcodeText.textContent = barcodeValue;

                barcodeItem.appendChild(barcodeCanvas);
                barcodeItem.appendChild(barcodeText);
                barcodeCol.appendChild(barcodeItem);
                barcodeList.appendChild(barcodeCol);

                try {
                    JsBarcode(barcodeCanvas, barcodeValue, {
                        format: barcodeType,
                        width: 2,
                        height: 80,
                        displayValue: false,
                        lineColor: "#000000",
                        background: "#ffffff",
                        margin: 10
                    });
                } catch (e) {
                    console.error("Error generating barcode:", e);
                    barcodeText.textContent = "Error: " + barcodeValue + " - " + e.message;
                    barcodeCanvas.style.display = "none";
                }
            }

            function createStackedBarcode(barcodeValue, barcodeType) {
                const barcodeItem = document.createElement('div');
                barcodeItem.className = 'stacked-barcode-item';

                const barcodeCanvas = document.createElement('canvas');
                barcodeCanvas.className = 'stacked-barcode-canvas';

                const barcodeText = document.createElement('div');
                barcodeText.className = 'stacked-barcode-value';
                barcodeText.textContent = barcodeValue;

                barcodeItem.appendChild(barcodeCanvas);
                barcodeItem.appendChild(barcodeText);
                stackedBarcodes.appendChild(barcodeItem);

                try {
                    JsBarcode(barcodeCanvas, barcodeValue, {
                        format: barcodeType,
                        width: 2,
                        height: 80,
                        displayValue: false,
                        lineColor: "#000000",
                        background: "#ffffff",
                        margin: 10
                    });
                } catch (e) {
                    console.error("Error generating stacked barcode:", e);
                    barcodeText.textContent = "Error: " + barcodeValue + " - " + e.message;
                    barcodeCanvas.style.display = "none";
                }
            }

            function printBarcodes() {
                printContainer.classList.remove('d-none');
                window.print();
                setTimeout(function() {
                    printContainer.classList.add('d-none');
                }, 1000);
            }
        });
</script>
@endsection