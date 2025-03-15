<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->

<head>
    <title>Khrabish | Order #{{ $order->order_number }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/yellowpos_favicon.png') }}" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" class="print-content-only app-default">
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Main-->
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <!--begin::Content wrapper-->
            <div class="d-flex flex-column flex-column-fluid">
                <!--begin::Toolbar-->
                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                    <!--begin::Toolbar container-->
                    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                        <!--begin::Page title-->
                        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                            <!--begin::Title-->
                            <h1
                                class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                Order #{{ $order->order_number }}</h1>
                            <!--end::Title-->
                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('orders') }}" class="text-muted text-hover-primary">Orders</a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">View Order</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="{{ url()->previous() }}"
                                class="btn btn-sm btn-secondary my-1 d-flex align-items-center">
                                <i class="bi bi-caret-left-fill"></i>
                                Back
                            </a>
                            <!-- begin::Pint-->
                            <button type="button" class="btn btn-sm btn-primary my-1" onclick="window.print();">Print
                                Order</button>
                            <!-- end::Pint-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar container-->
                </div>
                <!--end::Toolbar-->
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-xxl">
                        <!-- begin::Invoice 3-->
                        <div class="card">
                            <!-- begin::Body-->
                            <div class="card-body py-20">
                                <!-- begin::Wrapper-->
                                <div class="mw-lg-950px mx-auto w-100">
                                    <!-- begin::Header-->
                                    <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                                        <div>
                                            <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">Order</h4>
                                            <div class="flex-root d-flex flex-column mt-4">
                                                <span class="text-muted">Order Number</span>
                                                <span class="fs-5">#{{ $order->order_number }}</span>
                                            </div>
                                            <div class="flex-root d-flex flex-column mt-4">
                                                <span class="text-muted">Date</span>
                                                <span class="fs-5">{{ $order->created_at }}</span>
                                            </div>
                                        </div>
                                        <!--end::Logo-->
                                        <div class="text-sm-end">
                                            <!--begin::Logo-->
                                            <a href="#" class="d-block mw-150px ms-sm-auto">
                                                <img alt="Logo" src="//Logo" class="w-50" />
                                            </a>
                                            <!--end::Logo-->
                                            <!--begin::Text-->
                                            <div class="text-sm-end fw-semibold fs-4 mt-7">
                                                <div class="text-dark">Khrabish</div>
                                                <div>Khrabish.store@gmail.com</div>
                                                <div>70231446</div>
                                                <div>Jdeideh, Sagesse street, Al Arzeh Building</div>
                                            </div>
                                            <!--end::Text-->
                                        </div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="pb-12">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column gap-7 gap-md-10">
                                            @if ($order->note)
                                            <!--begin::Message-->
                                            <div class="fw-bold fs-2">
                                                Note:
                                                <span class="text-muted fs-5">{{ $order->note }}</span>
                                            </div>
                                            <!--begin::Message-->
                                            <!--begin::Separator-->
                                            <div class="separator"></div>
                                            <!--begin::Separator-->
                                            @endif
                                            <!--begin:Order summary-->
                                            <div class="d-flex justify-content-between flex-column">
                                                <!--begin::Table-->
                                                <div class="table-responsive border-bottom mb-9">
                                                    <table
                                                        class="table table-bordered border-secondary align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                        <thead>
                                                            <tr class="border-bottom fs-6 fw-bold text-dark">
                                                                <th class="min-w-175px pb-2">Products</th>
                                                                <th class="min-w-80px text-end pb-2">QTY</th>
                                                                <th class="min-w-100px text-end pb-2">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <!--begin::Products-->
                                                            @forelse ($order->items as $item)
                                                            <tr>
                                                                <!--begin::Product-->
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <!--begin::Thumbnail-->
                                                                        <a href="#" class="symbol symbol-50px">
                                                                            <span class="symbol-label"
                                                                                style="background-image:url({{ asset($item->product->image) }});"></span>
                                                                        </a>
                                                                        <!--end::Thumbnail-->
                                                                        <!--begin::Title-->
                                                                        <div class="ms-5">
                                                                            <div class="fw-bold">{{
                                                                                ucwords($item->product->name) }}</div>
                                                                        </div>
                                                                        <!--end::Title-->
                                                                    </div>
                                                                </td>
                                                                <!--end::Product-->
                                                                <!--begin::Quantity-->
                                                                <td class="text-end">{{ $item->quantity }}</td>
                                                                <!--end::Quantity-->
                                                                <!--begin::Total-->
                                                                <td class="text-end">{{ $currency->symbol }}{{
                                                                    number_format($item->total, 2)
                                                                    }}</td>
                                                                <!--end::Total-->
                                                            </tr>
                                                            @empty

                                                            @endforelse
                                                            <!--end::Products-->
                                                            <!--begin::Subtotal-->
                                                            <tr class="text-dark fw-bold text-end">
                                                                <td colspan="2">Subtotal</td>
                                                                <td class="text-end">{{ $currency->symbol }}{{
                                                                    number_format($order->sub_total, 2) }}</td>
                                                            </tr>
                                                            <!--end::Subtotal-->
                                                            <!--begin::Shipping-->
                                                            <tr class="text-dark fw-bold text-end">
                                                                <td colspan="2">Discount
                                                                </td>
                                                                <td class="text-end">{{ $currency->symbol }}{{
                                                                    number_format($order->discount, 2) }}</td>
                                                            </tr>
                                                            <!--end::Shipping-->
                                                            <!--begin::Grand total-->
                                                            <tr class="text-dark fw-bold text-end">
                                                                <td colspan="2">
                                                                    Grand Total</td>
                                                                <td class="text-dark fs-3 fw-bolder text-end">
                                                                    {{ $currency->symbol }}{{
                                                                    number_format($order->total, 2) }}</td>
                                                            </tr>
                                                            <!--end::Grand total-->
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--end::Table-->
                                            </div>
                                            <!--end:Order summary-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!-- end::Wrapper-->
                            </div>
                            <!-- end::Body-->
                        </div>
                        <!-- end::Invoice 1-->
                    </div>
                    <!--end::Content container-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Content wrapper-->
        </div>
        <!--end:::Main-->
    </div>
    <!--end::App-->

    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>