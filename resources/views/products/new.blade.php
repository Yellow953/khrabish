@extends('layouts.app')

@section('title', 'products')

@section('sub-title', 'new')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('categories.new') }}">
    <i class="fa-solid fa-plus"></i>
    New Category
</a>
<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary px-4 d-flex align-items-center">
    <i class="bi bi-caret-left-fill"></i>
    Back
</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card border-custom">
        <form action="{{ route('products.create') }}" method="POST" enctype="multipart/form-data" class="form">
            @csrf
            <div class="card-head">
                <h1 class="text-center text-primary">New Product</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name..."
                                value="{{ old('name') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">Category</label>
                            <select name="category_id" class="form-select" data-control="select2" required
                                data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' :
                                    '' }}>{{ ucwords($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" step="any" min="0"
                                placeholder="Enter Quantity..." value="{{ old('quantity') }}" required />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label">Cost</label>
                            <input type="number" class="form-control" name="cost" step="any" min="0"
                                placeholder="Enter Cost..." value="{{ old('cost') }}" required />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label">Price</label>
                            <input type="number" class="form-control" name="price" step="any" min="0"
                                placeholder="Enter Price..." value="{{ old('price') }}" required />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Compare Price</label>
                            <input type="number" class="form-control" name="compare_price" step="any" min="0"
                                placeholder="Enter Compare Price..." value="{{ old('compare_price') }}" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Enter Description...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <label class="col-4 form-label">Image</label>
                            <div class="col-8">
                                <!--begin::Image input-->
                                <div class="image-input image-input-empty" data-kt-image-input="true">
                                    <!--begin::Image preview wrapper-->
                                    <div class="image-input-wrapper w-100px h-100px"
                                        style="background-image: url({{ asset('assets/images/no_img.png') }})"></div>
                                    <!--end::Image preview wrapper-->

                                    <!--begin::Edit button-->
                                    <label
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        data-bs-dismiss="click" title="Change image">
                                        <i class="fa fa-pen"></i>

                                        <!--begin::Inputs-->
                                        <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Edit button-->

                                    <!--begin::Cancel button-->
                                    <span
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        data-bs-dismiss="click" title="Remove image">
                                        <i class="fa fa-close"></i>
                                    </span>
                                    <!--end::Cancel button-->

                                    <!--begin::Remove button-->
                                    <span
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        data-bs-dismiss="click" title="Remove image">
                                        <i class="fa fa-close"></i>
                                    </span>
                                    <!--end::Remove button-->
                                </div>
                                <!--end::Image input-->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group my-5">
                            <div class="fw-semibold mb-4">Booming Offers</div>
                            <div class="d-flex align-items-center mx-3">
                                <span class="me-3">Booming</span>
                                <label class="switch">
                                    <input type="checkbox" name="booming" id="booming" value="1" {{ old('booming', 1)
                                        ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group my-5">
                            <div class="fw-semibold mb-4">Visibility</div>
                            <div class="d-flex align-items-center mx-3">
                                <span class="me-3">Public</span>
                                <label class="switch">
                                    <input type="checkbox" name="public" id="public" value="1" {{ old('public', 1)
                                        ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text-primary my-3">Tags</h2>
                        <div class="mx-1 mb-3 fs-6 text-tertary">
                            Products can be grouped by Tags. Enter SPACE to separate tags...
                        </div>

                        <div class="col-md-12">
                            <input type="text" id="tagInput" class="form-control" placeholder="Enter Tags...">

                            <div class="tag-input-wrapper p-2" id="tagContainer"
                                style="display: flex; flex-wrap: wrap; gap: 5px;">
                            </div>

                            <input type="hidden" name="tags" id="tags">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2 class="text-primary my-3">Barcodes and SKU</h2>
                        <div class="mx-1 mb-3 fs-6 text-tertary">
                            Barcodes that are entered here can be generated from products -> generate barcodes page...
                        </div>
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

                <hr>

                <div class="row">
                    <h2 class="text-primary my-3">Secondary Images</h2>
                    <div class="mx-1 mb-3 fs-6 text-tertary">
                        Secondary Images can also be used as variant images...
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Secondary Images</label>
                            <input type="file" name="secondary_images[]" class="form-control" id="secondaryImagesInput"
                                multiple accept=".png, .jpg, .jpeg">
                        </div>

                        <div id="imagePreviewContainer" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h2 class="text-primary my-3">Variants</h2>
                    <div class="mx-1 mb-3 fs-6 text-tertary">
                        Variants are variations of the same product ex: size, color, remove, ... <br>
                        Options are the values
                        of the variant ex: Small, Medium, Large, Blue, Red... <br>
                        Option Quantity Total should be the same as main quantity field above, please leave empty if
                        options have no stock...<br>
                        Option Price should be 0 if the price is
                        the same otherwise its added to the product price above... <br>
                    </div>
                    <div class="col-md-12">
                        <div id="variant-wrapper">
                        </div>
                        <button type="button" id="add-variant" class="btn btn-success mt-2"><i class="fa fa-plus"></i>
                            Add Variant</button>
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

    // Barcodes
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

    // Secondary Images
    document.addEventListener("DOMContentLoaded", function () {
        let secondaryImagesInput = document.getElementById("secondaryImagesInput");
        let previewContainer = document.getElementById("imagePreviewContainer");

        secondaryImagesInput.addEventListener("change", function () {
            previewContainer.innerHTML = "";

            let dt = new DataTransfer();

            Array.from(this.files).forEach((file, index) => {
                dt.items.add(file);

                let reader = new FileReader();
                reader.onload = function (e) {
                    let imagePreview = document.createElement("div");
                    imagePreview.classList.add("position-relative", "m-1");
                    imagePreview.dataset.index = index;

                    imagePreview.innerHTML = `
                        <img src="${e.target.result}" class="rounded border" width="100" height="100">
                        <button type="button" class="btn bg-danger btn-sm position-absolute top-0 end-0 remove-image"
                            data-index="${index}">&times;</button>
                    `;
                    previewContainer.appendChild(imagePreview);
                };
                reader.readAsDataURL(file);
            });

            secondaryImagesInput.files = dt.files;
        });

        previewContainer.addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-image")) {
                let indexToRemove = event.target.dataset.index;

                let dt = new DataTransfer();
                Array.from(secondaryImagesInput.files).forEach((file, index) => {
                    if (index != indexToRemove) {
                        dt.items.add(file);
                    }
                });

                secondaryImagesInput.files = dt.files;
                event.target.parentElement.remove();
            }
        });
    });

    //Variants
    document.addEventListener('DOMContentLoaded', function() {
        var variantWrapper = document.getElementById('variant-wrapper');
        var addVariantBtn = document.getElementById('add-variant');
        var variantIndex = 1;

        addVariantBtn.addEventListener('click', function() {
            var newVariantGroup = document.createElement('div');
            newVariantGroup.classList.add('variant-group', 'mb-3');
            newVariantGroup.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control w-25" name="variants[${variantIndex}][title]" placeholder="Variant Title (e.g., Size, Color)" required>
                    <select name="variants[${variantIndex}][type]" class="form-select w-25" required>
                        <option value="single">Single</option>
                        <option value="multiple">Multiple</option>
                    </select>
                    <button type="button" class="btn btn-danger remove-variant"><i class="fa fa-trash"></i></button>
                </div>
                <div class="variant-options mt-2">
                    <div class="input-group mb-2 flex-wrap">
                        <input type="text" class="form-control w-25" name="variants[${variantIndex}][options][0][value]" placeholder="Option (e.g., S, Red)" required>
                        <input type="number" class="form-control w-15" name="variants[${variantIndex}][options][0][quantity]" placeholder="Qty" min="0" step="0.01">
                        <input type="number" class="form-control w-15" name="variants[${variantIndex}][options][0][price]" placeholder="Price" required min="0" value="0" step="0.01">
                        <input type="file" class="form-control w-20" name="variants[${variantIndex}][options][0][image]" accept=".png, .jpg, .jpeg">
                        <button type="button" class="btn btn-danger remove-option"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm add-option"><i class="fa fa-plus"></i> Add Option</button>
            `;
            variantWrapper.appendChild(newVariantGroup);
            variantIndex++;
        });

        variantWrapper.addEventListener('click', function(event) {
            if (event.target.closest('.remove-variant')) {
                event.target.closest('.variant-group').remove();
            }
        });

        variantWrapper.addEventListener('click', function(event) {
            if (event.target.closest('.add-option')) {
                var variantGroup = event.target.closest('.variant-group');
                var optionsContainer = variantGroup.querySelector('.variant-options');
                var variantInput = variantGroup.querySelector('input[name^="variants["]');
                var variantId = variantInput.name.match(/\d+/)[0];
                var optionIndex = optionsContainer.querySelectorAll('.input-group').length;

                var newOption = document.createElement('div');
                newOption.classList.add('input-group', 'mb-2', 'flex-wrap');
                newOption.innerHTML = `
                    <input type="text" class="form-control w-25" name="variants[${variantId}][options][${optionIndex}][value]" placeholder="Option (e.g., M, Blue)" required>
                    <input type="number" class="form-control w-15 option-qty" name="variants[${variantId}][options][${optionIndex}][quantity]" placeholder="Qty" min="0">
                    <input type="number" class="form-control w-15" name="variants[${variantId}][options][${optionIndex}][price]" placeholder="Price" required value="0" min="0" step="0.01">
                    <input type="file" class="form-control w-20" name="variants[${variantId}][options][${optionIndex}][image]" accept=".png, .jpg, .jpeg">
                    <button type="button" class="btn btn-danger remove-option"><i class="fa fa-trash"></i></button>
                `;
                optionsContainer.appendChild(newOption);
            }
        });

        variantWrapper.addEventListener('click', function(event) {
            if (event.target.closest('.remove-option')) {
                event.target.closest('.input-group').remove();
            }
        });
    });

    //Tags
    document.addEventListener('DOMContentLoaded', function () {
        const tagInput = document.getElementById('tagInput');
        const tagContainer = document.getElementById('tagContainer');
        const hiddenInput = document.getElementById('tags');

        let tags = [];

        tagInput.addEventListener('keydown', function (e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                const newTag = tagInput.value.trim();
                if (newTag && !tags.includes(newTag)) {
                    tags.push(newTag);
                    tagInput.value = '';
                    renderTags();
                }
            } else if (e.key === 'Backspace' && tagInput.value === '') {
                tags.pop();
                renderTags();
            }
        });

        function renderTags() {
            tagContainer.innerHTML = '';

            tags.forEach((tag, index) => {
                const tagEl = document.createElement('span');
                tagEl.className = 'badge bg-light d-flex align-items-center border rounded';
                tagEl.style.padding = '6px 10px';
                tagEl.style.gap = '8px';
                tagEl.innerHTML = `
                    <span>${tag}</span>
                    <button type="button" class="btn-close btn-close-dark btn-sm ms-1" data-index="${index}" aria-label="Remove tag"></button>
                `;
                tagContainer.appendChild(tagEl);
            });

            hiddenInput.value = tags.join(',');
        }

        tagContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-close')) {
                const index = e.target.dataset.index;
                tags.splice(index, 1);
                renderTags();
            }
        });
    });
</script>

@endsection