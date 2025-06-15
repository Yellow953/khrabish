@extends('layouts.app')

@section('title', 'reports')

@section('actions')
<a class="btn btn-primary btn-sm px-4" href="{{ route('reports.export', request()->query()) }}">
    <i class="fa-solid fa-download"></i>
    <span class="d-none d-md-inline">Export to Excel</span>
</a>
@endsection

@section('filter')
<!--begin::filter-->
<div class="filter border-0 px-0 px-md-3 py-4">
    <!--begin::Form-->
    <form action="{{ route('reports') }}" method="GET" enctype="multipart/form-data" class="form">
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
                    <input type="text" class="form-control ps-10" name="id" value="{{ request()->query('id') }}"
                        placeholder="Search By Report #..." />
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
                    <div class="col-md-4">
                        <label class="fs-6 form-label fw-bold text-dark">User</label>
                        <select name="user_id" class="form-select" data-control="select2"
                            data-placeholder="Select a User">
                            <option value=""></option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request()->query('user_id') == $user->id ? 'selected' :
                                '' }}>
                                {{ ucfirst($user->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control ps-10" name="date_from"
                                value="{{ request()->query('date_from') }}" placeholder="Date From..." />

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control ps-10" name="date_to"
                                value="{{ request()->query('date_to') }}" placeholder="Date To..." />
                        </div>
                    </div>
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
    <div class="card border-custom mb-5 mb-xl-8">
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
                            <th class="col-1 p-3">#</th>
                            <th class="col-2 p-3">Period</th>
                            <th class="col-2 p-3">Generated By</th>
                            <th class="col-2 p-3">Transactions</th>
                            <th class="col-2 p-3">Total Sales</th>
                            <th class="col-2 p-3">Currency</th>
                            <th class="col-1 p-3">Actions</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>
                        @forelse ($reports as $report)
                        <tr class="text-center">
                            <td>
                                <span class="text-dark fw-bold d-block fs-7">{{ $report->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span><b>From:</b> <br> {{ $report->start_datetime
                                        }}</span>
                                    <span><b>To:</b> <br> {{ $report->end_datetime
                                        }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark fw-bold d-block fs-7">{{ ucwords($report->user->name) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-primary fs-7">{{ $report->transaction_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-center gap-1">
                                        <span class="text-muted fs-8">Sales:</span>
                                        <span class="text-success fw-bold fs-7">
                                            {{ $report->currency->symbol }}
                                            {{ number_format($report->total_sales * $report->currency->rate, 2) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-1">
                                        <span class="text-muted fs-8">Tax:</span>
                                        <span class="text-danger fs-8">
                                            {{ $report->currency->symbol }}
                                            {{ number_format($report->total_tax * $report->currency->rate, 2) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-1">
                                        <span class="text-muted fs-8">Discount:</span>
                                        <span class="text-danger fs-8">
                                            {{ $report->currency->symbol }}
                                            {{ number_format($report->total_discounts * $report->currency->rate, 2) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-1">
                                        <span class="text-muted fs-8">Cash:</span>
                                        <span class="text-primary fs-8">
                                            {{ number_format($report->cash_amount * $report->currency->rate, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light-info">{{ $report->currency->code }}</span>
                            </td>
                            <td class="d-flex justify-content-center border-0 gap-1">
                                <a href="{{ route('reports.show', $report->id) }}"
                                    class="btn btn-icon btn-primary btn-sm" data-bs-toggle="tooltip"
                                    title="View Report">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                @if($report->can_delete())
                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger btn-sm show_confirm"
                                        data-bs-toggle="tooltip" title="Delete Report">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="7">
                                <div class="text-center py-4">No Reports Generated Yet</div>
                            </th>
                        </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->

                    <tfoot>
                        <tr>
                            <th colspan="7">
                                {{ $reports->appends(request()->query())->links() }}
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