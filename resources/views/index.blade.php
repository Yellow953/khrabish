@extends('layouts.app')

@section('title', 'dashboard')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="container">
        <div class="d-flex flex-column flex-xl-row">
            <!-- Sidebar with Current Order -->
            <div class="flex-row-auto w-xl-450px">
                <form action="{{ route('checkout') }}" method="post" enctype="multipart/form-data" id="kt_pos_form">
                    @csrf
                    <input type="hidden" name="order_items" value="">
                    <input type="hidden" name="total" value="0">
                    <input type="hidden" name="tax" value="0">
                    <input type="hidden" name="discount" value="0">
                    <input type="hidden" name="grand_total" value="0">
                    <input type="text" id="barcode_input" class="form-control d-none">

                    <div class="card card-flush bg-body" id="kt_pos_form">
                        <div class="card-header px-4 pt-5">
                            <h3 class="card-title fw-bold text-gray-800 fs-2qx">Current Order</h3>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-danger fs-4 fw-bold py-4" id="clear_all">Clear All</a>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-0">
                            <div class="table-responsive mb-8">
                                <table class="table align-middle gs-0 gy-4 my-0">
                                    <thead>
                                        <tr>
                                            <th class="min-w-150px"></th>
                                            <th class="w-100px"></th>
                                            <th class="w-50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="order_items">
                                        <!-- Order items will be dynamically added here -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-stack bg-price rounded-3 p-6 mb-11">
                                <div class="fs-6 fw-bold">
                                    <span class="d-block lh-1 mb-4">Subtotal</span>
                                    <span class="d-block mb-4">Discounts</span>
                                    <span class="d-block mb-9">Tax({{ auth()->user()->business->tax->rate }}%)</span>
                                    <span class="d-block fs-2qx lh-1">Total</span>
                                </div>
                                <div class="fs-6 fw-bold text-end">
                                    <span class="d-block lh-1 mb-4" data-kt-pos-element="total">{{ $currency->symbol
                                        }}0.00</span>
                                    <span class="d-block mb-4 text-danger" data-kt-pos-element="discount"
                                        id="discount_text">{{ $currency->symbol }}0.00</span>
                                    <input type="number" class="form-control d-none" id="discount_input" min="0"
                                        step="any" placeholder="Enter discount">
                                    <span class="d-block mb-9" data-kt-pos-element="tax">{{ $currency->symbol
                                        }}0.00</span>
                                    <span class="d-block fs-2qx lh-1" data-kt-pos-element="grant-total">{{
                                        $currency->symbol }}0.00</span>
                                </div>
                            </div>
                            <div class="m-0">
                                <button type="submit" class="btn btn-primary fs-1 w-100 py-4"
                                    id="complete_order">Complete Order</button>
                            </div>

                            <div class="form-group mt-8">
                                <label for="note" class="form-label">Note</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Main content with product categories and items -->
            <div class="d-flex flex-row-fluid justify-content-center mb-10 mb-xl-0">
                <div class="card card-p-0 p-5 mx-3 border-0">
                    <div class="card-body">
                        <div class="mb-5">
                            <input type="text" id="product_search" class="form-control"
                                placeholder="Search Products by Name...">
                        </div>

                        <ul
                            class="nav nav-pills d-flex justify-content-between nav-pills-custom flex-nowrap overflow-x-auto gap-3 mb-6">
                            @foreach ($categories as $category)
                            <li class="nav-item mb-3 me-0">
                                <a class="nav-link border-primary btn btn-outline flex-column flex-stack py-5 page-bg {{ $loop->first ? 'active show' : '' }}"
                                    data-bs-toggle="pill" href="#kt_pos_food_content_{{ $category->id }}"
                                    style="width: 150px;height: 150px">
                                    <div class="nav-icon mb-3">
                                        <img src="{{ asset($category->image) }}" class="w-50px"
                                            alt="{{ $category->name }}" />
                                    </div>
                                    <div class="">
                                        <span class="text-gray-800 fw-bold fs-4 d-block">{{ ucwords($category->name)
                                            }}</span>
                                        <span class="text-gray-400 fw-semibold fs-7">{{ $category->products->count() }}
                                            Items</span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($categories as $category)
                            <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}"
                                id="kt_pos_food_content_{{ $category->id }}">
                                <div class="d-flex flex-wrap d-grid gap-3">
                                    @forelse ($category->products as $product)
                                    <div class="card card-flush flex-row-fluid p-0 pb-5 mw-100 border-primary product-item"
                                        data-product-id="{{ $product->id }}">
                                        <div class="card-body text-center">
                                            <img src="{{ asset($product->image) }}"
                                                class="rounded-3 mb-4 w-150px h-150px" alt="{{ $product->name }}" />
                                            <div class="mb-2">
                                                <div class="text-center">
                                                    <span
                                                        class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">{{
                                                        ucwords($product->name) }}</span>
                                                </div>
                                            </div>
                                            <span class="text-success text-end fw-bold fs-1">{{
                                                number_format($product->price * $currency->rate, 2) }}
                                                {{ $currency->symbol }}</span>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="card card-flush flex-row-fluid p-0 mw-100 border-primary">
                                        <div class="card-body text-center my-2">
                                            <span class="fw-bold text-gray-800 fs-3 fs-xl-1">No
                                                Products in this Category...</span>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="d-flex flex-row flex-md-column justify-content-center justify-content-md-start">
                <div class="card p-4 d-flex flex-row flex-md-column">
                    <a href="#" class="btn btn-primary text-center mb-0 mb-md-4 mx-2 mx-md-0 rotate-90"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_new_client">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                        </svg>
                        Client
                    </a>
                    <a href="#" class="btn btn-primary text-center mb-0 mb-md-4 mx-2 mx-md-0 rotate-90"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_new_debt">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                        </svg>
                        Debt
                    </a>
                    <a href="#" class="btn btn-primary text-center mb-0 mb-md-4 mx-2 mx-md-0 rotate-90"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_new_report">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                        </svg>
                        Report
                    </a>
                    <a href="#" class="btn btn-primary text-center mb-0 mb-md-4 mx-2 mx-md-0 py-6"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_calculator">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-calculator-fill" viewBox="0 0 16 16">
                            <path
                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5m0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5z" />
                        </svg>
                    </a>
                    <a href="#" class="btn btn-primary text-center mb-0 mb-md-4 mx-2 mx-md-0" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_last_order">
                        Last <br>
                        Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts._modals')
@endsection

@section('scripts')
@include('scripts.pos')
@endsection
