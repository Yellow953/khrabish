@extends('layouts.app')

@section('title', 'categories')

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
        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data"
            class="form">
            @csrf
            <div class="card-head">
                <h1 class="text-center text-primary">Edit Category</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name..."
                                value="{{ $category->name }}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="parent_id" class="form-select" data-control="select2"
                                data-placeholder="Select an option">
                                <option value=""></option>
                                @foreach ($categories as $c)
                                <option value="{{ $c->id }}" {{ $category->parent_id==$c->id ? 'selected' :
                                    '' }}>{{ ucwords($c->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-4 form-label">Image</label>
                            <div class="col-8">
                                <!--begin::Image input-->
                                <div class="image-input image-input-empty" data-kt-image-input="true">
                                    <!--begin::Image preview wrapper-->
                                    <div class="image-input-wrapper w-100px h-100px"
                                        style="background-image: url({{ asset($category->image) }})"></div>
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
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="5"
                        placeholder="Enter Description...">{{ $category->description }}</textarea>
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

    <div class="row mt-5">
        @if ($category->products->count() > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>Products</h3>

                    <ul>
                        @foreach ($category->products as $product)
                        <li>{{ $product->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if ($category->subCategories->count() > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>Sub Categories</h3>

                    <ul>
                        @foreach ($category->subCategories as $category)
                        <li>{{ $category->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection