@extends('layouts.app')

@section('title', 'products')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('products.new') }}"><i class="fa-solid fa-plus"></i> <span
        class="d-none d-md-inline">New Product</span></a>
<a class="btn btn-primary btn-sm px-4" href="{{ route('products.export') }}"><i class="fa-solid fa-download"></i><span
        class="d-none d-md-inline">Export to Excel</span></a>
@endsection

@section('filter')
<!--begin::filter-->
<div class="filter border-0 px-0 px-md-3 py-4">
    <!--begin::Form-->
    <form action="{{ route('products') }}" method="GET" enctype="multipart/form-data" class="form">
        @csrf
        <div class="pt-0 pt-3 px-2 px-md-4">
            <!--begin::Compact form-->
            <div class="d-flex align-items-center">
                <!--begin::Input group-->
                <div class="position-relative w-md-400px me-md-2">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" class="form-control ps-10" name="name" value="{{ request()->query('name') }}"
                        placeholder="Search By Name..." />
                </div>
                <!--end::Input group-->
                <!--begin:Action-->
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary me-5 px-3 py-2 d-flex align-items-center">
                        Search <span class="ml-2"><i class="fas fa-search"></i></span>
                    </button>
                    <a id="kt_horizontal_search_advanced_link" class="btn btn-link" data-bs-toggle="collapse"
                        href="#kt_advanced_search_form">Advanced Search</a>
                    <button type="reset" class="btn btn-danger clear-btn py-2 px-4 ms-3">Clear</button>
                </div>
                <!--end:Action-->
            </div>
            <!--end::Compact form-->
            <!--begin::Advance form-->
            <div class="collapse" id="kt_advanced_search_form">
                <!--begin::Separator-->
                <div class="separator separator-dashed mt-9 mb-6"></div>
                <!--end::Separator-->
                <!--begin::Row-->
                <div class="row g-8 mb-8">
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label class="fs-6 form-label fw-bold text-dark">Category</label>
                        <select name="category_id" class="form-select" data-control="select2"
                            data-placeholder="Select an option">
                            <option value=""></option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request()->query('category_id')==$category->id ?
                                'selected' :
                                '' }}>{{ ucwords($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label class="fs-6 form-label fw-bold text-dark">Description</label>
                        <input type="text" class="form-control form-control-solid border" name="description"
                            value="{{ request()->query('description') }}" placeholder="Enter Description..." />
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Advance form-->
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::filter-->
@endsection

@section('content')
<div class="container">
    <!--begin::Tables Widget 10-->
    <div class="card mb-5 mb-xl-8">
        @yield('filter')

        <!--begin::Body-->
        <div class="card-body pt-3">
            <!--begin::Table container-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <!--begin::Table head-->
                    <thead>
                        <tr class="text-center">
                            <th class="col-2 p-3">Product</th>
                            <th class="col-2 p-3">Description</th>
                            <th class="col-2 p-3">Quantity</th>
                            <th class="col-2 p-3">Price</th>
                            <th class="col-2 p-3">Category</th>
                            <th class="col-2 p-3">Actions</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-45px me-5">
                                        <img alt="product" src="{{ asset($product->image) }}" />
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Name-->
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{
                                            ucwords($product->name) }}</a>
                                    </div>
                                    <!--end::Name-->
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{ Str::limit($product->description, 50) }}
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{ $product->quantity }}
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    Price: <span class="text-success">{{ number_format($product->price *
                                        $currency->rate, 2)
                                        }} {{ $currency->symbol }}</span>
                                    <br>
                                    Cost: <span class="text-danger">{{ number_format($product->cost * $currency->rate,
                                        2)
                                        }} {{ $currency->symbol }}</span> <br>
                                    Profit: <span class="text-primary">{{ number_format($product->get_profit() *
                                        $currency->rate,
                                        2)
                                        }} {{ $currency->symbol }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                {{ ucwords($product->category->name) }}
                            </td>
                            <td class="d-flex justify-content-end border-0">
                                <a href="{{ route('products.import', $product->id) }}"
                                    class="btn btn-icon btn-success btn-sm me-1">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="btn btn-icon btn-warning btn-sm me-1">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                @if($product->can_delete())
                                <a href="{{ route('products.destroy', $product->id) }}"
                                    class="btn btn-icon btn-danger btn-sm show_confirm" data-toggle="tooltip"
                                    data-original-title="Delete Product">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="5">
                                <div class="text-center">No Products Yet ...</div>
                            </th>
                        </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->

                    <tfoot>
                        <tr>
                            <th colspan="5">
                                {{ $products->appends(['name' => request()->query('name'), 'category_id' =>
                                request()->query('category_id'), 'description' =>
                                request()->query('description')])->links() }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table container-->
        </div>
        <!--begin::Body-->
    </div>
    <!--end::Tables Widget 10-->
</div>
@endsection