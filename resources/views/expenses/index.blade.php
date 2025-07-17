@extends('layouts.app')

@section('title', 'expenses')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('expenses.new') }}">
    <i class="fa-solid fa-plus"></i>
    <span class="d-none d-md-inline">New Expense</span>
</a>
<a class="btn btn-primary btn-sm px-4" href="{{ route('expenses.pdf', request()->query()) }}">
    <i class="fa-solid fa-file-pdf"></i>
    <span class="d-none d-md-inline">Export to PDF</span>
</a>
<a class="btn btn-primary btn-sm px-4" href="{{ route('expenses.export', request()->query()) }}">
    <i class="fa-solid fa-download"></i>
    <span class="d-none d-md-inline">Export to Excel</span>
</a>
@endsection

@section('filter')
<!--begin::filter-->
<div class="filter border-0 px-0 px-md-3 py-4">
    <!--begin::Form-->
    <form action="{{ route('expenses') }}" method="GET" enctype="multipart/form-data" class="form">
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
                    <input type="text" class="form-control ps-10" name="number" value="{{ request()->query('number') }}"
                        placeholder="Search By Number..." />
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
                        <select name="category" class="form-select" data-control="select2"
                            data-placeholder="Select an option">
                            <option value=""></option>
                            @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request()->query('category')==$category ? 'selected' : ''
                                }}>{{ ucwords($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label class="fs-6 form-label fw-bold text-dark">Date</label>
                        <input type="date" class="form-control form-control-solid border" name="date"
                            value="{{ request()->query('date') }}" />
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-12">
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
                            <th class="col-2 p-3">NO</th>
                            <th class="col-2 p-3">Date</th>
                            <th class="col-2 p-3">Category</th>
                            <th class="col-2 p-3">Description</th>
                            <th class="col-2 p-3">Amount</th>
                            <th class="col-2 p-3">Actions</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>
                        @forelse ($expenses as $expense)
                        <tr>
                            <td>
                                <div class="text-center">
                                    <span class="text-primary fw-bold">#
                                        {{ ucwords($expense->number) }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-center">
                                    {{ $expense->date }}
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{ ucwords($expense->category) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-center">
                                    {{ $expense->description }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-center">
                                    {{ $expense->currency->symbol }}{{ number_format($expense->amount, 2) }}
                                </div>
                            </td>
                            <td class="d-flex justify-content-end border-0">
                                <a href="{{ route('expenses.edit', $expense->id) }}"
                                    class="btn btn-icon btn-warning btn-sm me-1">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                @if($expense->can_delete())
                                <a href="{{ route('expenses.destroy', $expense->id) }}"
                                    class="btn btn-icon btn-danger btn-sm show_confirm" data-toggle="tooltip"
                                    data-original-title="Delete Expense">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="6">
                                <div class="text-center">No Expenses Yet ...</div>
                            </th>
                        </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->

                    <tfoot>
                        <tr>
                            <th colspan="6">
                                {{ $expenses->appends(request()->query())->links() }}
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