@extends('layouts.app')

@section('title', 'products')

@section('sub-title', 'new')

@section('actions')
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
                    <div class="col-md-6">
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

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Tags <small class="text-muted">(Type and press
                                    Space)</small></label>
                            <input type="text" id="tag-input" class="form-control"
                                placeholder="Type a tag and press space" />
                            <div id="tag-container" class="d-flex flex-wrap mt-2 gap-2"></div>
                            <input type="hidden" name="tags" id="tags-hidden-input" value="">
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

                <div class="row">
                    <h2 class="text-primary my-3">Secondary Images</h2>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Secondary Images</label>
                            <input type="file" name="secondary_images[]" class="form-control" id="secondaryImagesInput"
                                multiple accept=".png, .jpg, .jpeg">
                        </div>

                        <div id="imagePreviewContainer" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>

                <div class="row">
                    <h2 class="text-primary my-3">Product Variants</h2>
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
                    <button type="button" class="btn btn-danger remove-variant"><i class="fa fa-trash"></i></button>
                </div>
                <div class="variant-options mt-2">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control w-50" name="variants[${variantIndex}][options][0][value]" placeholder="Option (e.g., S, Red)" required>
                        <input type="number" class="form-control w-25" name="variants[${variantIndex}][options][0][price]" placeholder="Price" required step="0.01">
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
                newOption.classList.add('input-group', 'mb-2');
                newOption.innerHTML = `
                    <input type="text" class="form-control w-50" name="variants[${variantId}][options][${optionIndex}][value]" placeholder="Option (e.g., M, Blue)" required>
                    <input type="number" class="form-control w-25" name="variants[${variantId}][options][${optionIndex}][price]" placeholder="Price" required step="0.01">
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

    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('tag-input');
        const container = document.getElementById('tag-container');
        const hiddenInput = document.getElementById('tags-hidden-input');

        let tags = [];

        function renderTags() {
        container.innerHTML = '';
        tags.forEach((tag, index) => {
            const tagElem = document.createElement('div');
            tagElem.classList.add('badge', 'tag-badge', 'text-dark', 'd-flex', 'align-items-center', 'gap-1');
            tagElem.style.padding = '0.35em 0.65em';
            tagElem.style.cursor = 'default';

            tagElem.textContent = tag;

            const closeBtn = document.createElement('span');
            closeBtn.textContent = '×';
            closeBtn.style.marginLeft = '0.4em';
            closeBtn.style.cursor = 'pointer';
            closeBtn.title = 'Remove tag';
            closeBtn.addEventListener('click', () => {
            tags.splice(index, 1);
            updateHiddenInput();
            renderTags();
            });

            tagElem.appendChild(closeBtn);
            container.appendChild(tagElem);
        });
        }

        function updateHiddenInput() {
        hiddenInput.value = JSON.stringify(tags);
        }

        function addTag(tag) {
        tag = tag.trim();
        if(tag && !tags.includes(tag)) {
            tags.push(tag);
            updateHiddenInput();
            renderTags();
        }
        }

        input.addEventListener('keydown', (e) => {
        if(e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
            const val = input.value.trim();
            if(val) {
            addTag(val);
            input.value = '';
            }
        }
        else if(e.key === 'Backspace' && input.value === '') {
            tags.pop();
            updateHiddenInput();
            renderTags();
        }
        });
    });
</script>
@endsection