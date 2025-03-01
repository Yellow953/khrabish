@extends('layouts.app')

@section('title', 'debts')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('debts.new') }}"><i class="fa-solid fa-plus"></i> <span
        class="d-none d-md-inline">New Debt</span></a>
<a class="btn btn-primary btn-sm px-4" href="{{ route('debts.export') }}"><i class="fa-solid fa-download"></i><span
        class="d-none d-md-inline">Export to Excel</span></a>
@endsection

@section('filter')
<!--begin::filter-->
<div class="filter border-0 px-0 px-md-3 py-4">
    <!--begin::Form-->
    <form action="{{ route('debts') }}" method="GET" enctype="multipart/form-data" class="form">
        @csrf
        <div class="pt-0 pt-3 px-2 px-md-4">
            <!--begin::Compact form-->
            <div class="d-flex align-items-center">
                <!--begin::Input group-->
                <div class="position-relative w-md-400px me-md-2">
                    <select name="type" class="form-select ps-10" data-control="select2"
                        data-placeholder="Select an option">
                        <option value=""></option>
                        @foreach ($types as $type)
                        <option value="{{ $type }}" {{ request()->query('type')==$type ? 'selected' : '' }}>{{
                            ucwords($type) }}</option>
                        @endforeach
                    </select>
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
                        <label class="fs-6 form-label fw-bold text-dark">Client</label>
                        <select name="client_id" class="form-select" data-control="select2"
                            data-placeholder="Select an option">
                            <option value=""></option>
                            @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ request()->query('client_id')==$client->id ?
                                'selected' :
                                '' }}>{{ ucwords($client->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label class="fs-6 form-label fw-bold text-dark">Supplier</label>
                        <select name="supplier_id" class="form-select" data-control="select2"
                            data-placeholder="Select an option">
                            <option value=""></option>
                            @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request()->query('supplier_id')==$supplier->id ?
                                'selected' :
                                '' }}>{{ ucwords($supplier->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label class="fs-6 form-label fw-bold text-dark">Date From</label>
                        <input type="date" class="form-control form-control-solid border" name="date_from"
                            value="{{ request()->query('date_from') }}" placeholder="Enter Date From..." />
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label class="fs-6 form-label fw-bold text-dark">Date To</label>
                        <input type="date" class="form-control form-control-solid border" name="date_to"
                            value="{{ request()->query('date_to') }}" placeholder="Enter Date To..." />
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-12">
                        <label class="fs-6 form-label fw-bold text-dark">Note</label>
                        <input type="text" class="form-control form-control-solid border" name="note"
                            value="{{ request()->query('note') }}" placeholder="Enter Note..." />
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
                            <th class="col-3 p-3">Creditor</th>
                            <th class="col-3 p-3">Date</th>
                            <th class="col-2 p-3">Amount</th>
                            <th class="col-2 p-3">Actions</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>
                        @forelse ($debts as $debt)
                        <tr>

                            <td>
                                <span class="text-gray-800 fw-bold d-block fs-7">{{ ucwords($debt->client_id ?
                                    $debt->client->name :
                                    $debt->supplier->name) }}</span>
                            </td>
                            <td class="text-center">
                                {{ ucwords($debt->date) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($debt->amount, 2) }} {{ $debt->currency->code }}
                            </td>
                            <td class="d-flex justify-content-end border-0">
                                <a href="{{ route('debts.edit', $debt->id) }}"
                                    class="btn btn-icon btn-warning btn-sm me-1">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                @if($debt->can_delete())
                                <a href="{{ route('debts.destroy', $debt->id) }}"
                                    class="btn btn-icon btn-danger btn-sm show_confirm" data-toggle="tooltip"
                                    data-original-title="Delete Debt">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="4">
                                <div class="text-center">No Debts Yet ...</div>
                            </th>
                        </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->

                    <tfoot>
                        <tr>
                            <th colspan="4">
                                {{ $debts->appends(['supplier_id' =>
                                request()->query('supplier_id'), 'client_id' =>
                                request()->query('client_id'), 'type' =>
                                request()->query('type'), 'note' =>
                                request()->query('note'), 'date_from' =>
                                request()->query('date_from'), 'date_to' =>
                                request()->query('date_to')])->links() }}
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