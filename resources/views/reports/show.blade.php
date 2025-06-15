<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->

<head>
    <title>Khrabish | Report #{{ $report->id }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('frontend/images/white-logo.png') }}" />

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
                                Report #{{ $report->id }}</h1>
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
                                    <a href="{{ route('reports') }}" class="text-muted text-hover-primary">Reports</a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">Report</li>
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
                            <!-- begin::Print-->
                            <button type="button" class="btn btn-sm btn-primary my-1" onclick="window.print();">Print
                                Report</button>
                            <!-- end::Print-->
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
                        <!-- begin::Report Card-->
                        <div class="card">
                            <!-- begin::Body-->
                            <div class="card-body py-20">
                                <!-- begin::Wrapper-->
                                <div class="mw-lg-950px mx-auto w-100">
                                    <!-- begin::Header-->
                                    <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                                        <div>
                                            <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">Report Summary</h4>
                                            <div class="flex-root d-flex flex-column mt-4">
                                                <span class="text-muted fw-bold">Report Period</span>
                                                <span class="fs-5">
                                                    {{ $report->start_datetime }} <b>to</b>
                                                    {{ $report->end_datetime }}
                                                </span>
                                            </div>
                                            <div class="flex-root d-flex flex-column mt-4">
                                                <span class="text-muted fw-bold">Generated By</span>
                                                <span class="fs-5">{{ ucwords($report->user->name) }}</span>
                                            </div>
                                            <div class="flex-root d-flex flex-column mt-4">
                                                <span class="text-muted fw-bold">Date Generated</span>
                                                <span class="fs-5">{{ $report->created_at }}</span>
                                            </div>
                                        </div>
                                        <!--end::Logo-->
                                        <div class="text-sm-end">
                                            <!--begin::Logo-->
                                            <a href="#" class="d-block mw-150px ms-sm-auto">
                                                <img alt="Logo" src="{{ asset('frontend/images/white-logo.png') }}"
                                                    class="w-50" />
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
                                            <!--begin:Report summary-->
                                            <div class="d-flex justify-content-between flex-column">
                                                <!--begin::Table-->
                                                <div class="table-responsive border-bottom mb-9">
                                                    <table
                                                        class="table table-bordered border-secondary align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                        <thead>
                                                            <tr class="fs-6 fw-bold text-dark">
                                                                <th class="min-w-175px fs-3">Metric</th>
                                                                <th class="min-w-100px text-end fs-3">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <tr>
                                                                <td>Total Transactions</td>
                                                                <td class="text-end">{{ $report->transaction_count }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Sales</td>
                                                                <td class="text-end">{{ $report->currency->symbol }}{{
                                                                    number_format($report->total_sales *
                                                                    $report->currency->rate, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Tax</td>
                                                                <td class="text-end">{{ $report->currency->symbol }}{{
                                                                    number_format($report->total_tax *
                                                                    $report->currency->rate, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Discounts</td>
                                                                <td class="text-end">{{ $report->currency->symbol }}{{
                                                                    number_format($report->total_discounts *
                                                                    $report->currency->rate, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cash Payments</td>
                                                                <td class="text-end">{{ $report->currency->symbol }}{{
                                                                    number_format($report->cash_amount *
                                                                    $report->currency->rate, 2) }}</td>
                                                            </tr>
                                                            <tr class="text-dark fw-bold">
                                                                <td>Net Sales</td>
                                                                <td class="text-end">{{ $report->currency->symbol }}{{
                                                                    number_format(($report->total_sales -
                                                                    $report->total_discounts) * $report->currency->rate,
                                                                    2) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--end::Table-->
                                            </div>
                                            <!--end:Report summary-->

                                            <!--begin:Sold products-->
                                            <h4 class="fw-bolder text-gray-800">Products Sold</h4>
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-bordered border-secondary align-middle table-row-dashed fs-6 gy-5">
                                                    <thead>
                                                        <tr class="fs-6 fw-bold text-dark">
                                                            <th class="min-w-175px">Product</th>
                                                            <th class="min-w-80px text-end">QTY</th>
                                                            <th class="min-w-100px text-end">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                        @foreach($report->items as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="symbol symbol-50px">
                                                                        <span class="symbol-label"
                                                                            style="background-image:url({{ asset($item->product->image) }});"></span>
                                                                    </a>
                                                                    <div class="ms-5">
                                                                        <div class="fw-bold">{{ $item->product->name }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">{{ $item->quantity_sold }}</td>
                                                            <td class="text-end">{{ $report->currency->symbol }}{{
                                                                number_format($item->total_amount, 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                        <tr class="text-dark fw-bold">
                                                            <td colspan="2">Total Products Sold</td>
                                                            <td class="text-end">{{ $report->currency->symbol }}{{
                                                                number_format($report->items->sum('total_amount'), 2) }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--end:Sold products-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!-- end::Wrapper-->
                            </div>
                            <!-- end::Body-->
                        </div>
                        <!-- end::Report Card-->
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