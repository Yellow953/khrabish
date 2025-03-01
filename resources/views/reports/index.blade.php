@extends('layouts.app')

@section('title', 'reports')

@section('actions')
<a class="btn btn-success btn-sm px-4" href="{{ route('reports.new') }}"><i class="fa-solid fa-plus"></i> <span
        class="d-none d-md-inline">New Report</span></a>
<a class="btn btn-primary btn-sm px-4" href="{{ route('reports.export') }}"><i class="fa-solid fa-download"></i><span
        class="d-none d-md-inline">Export to Excel</span></a>
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
                    <div class="d-flex gap-4">
                        <div class="from">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control ps-10" name="date_from"
                                value="{{ request()->query('date_from') }}" placeholder="Date From..." />

                        </div>
                        <div class="to">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control ps-10" name="date_to"
                                value="{{ request()->query('date_to') }}" placeholder="Date To..." />
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin:Action-->
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary me-5 px-3 py-2 d-flex align-items-center">
                        Search <span class="ml-2"><i class="fas fa-search"></i></span>
                    </button>
                    <button type="reset" class="btn btn-danger clear-btn py-2 px-4 ms-3">Clear</button>
                </div>
                <!--end:Action-->
            </div>
            <!--end::Compact form-->
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
                            <th class="col-4 p-3">Report Date</th>
                            <th class="col-4 p-3">Cash</th>
                            <th class="col-4 p-3">Actions</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>
                        @forelse ($reports as $report)
                        <tr>
                            <td class="text-center">
                                <span class="text-dark fw-bold d-block fs-7">{{ $report->date }}</span>
                            </td>
                            <td>
                                <div class="text-center">
                                    Start Cash: <span class="text-success">{{ number_format($report->start_cash, 2)
                                        }} {{ $report->currency->symbol }}</span>
                                    <br>
                                    End Cash: <span class="text-danger">{{ number_format($report->end_cash,2) }} {{
                                        $report->currency->symbol }}</span> <br>
                                    Profit: <span class="text-primary">{{ number_format($report->get_profit(), 2) }} {{
                                        $report->currency->symbol }}</span>
                                </div>
                            </td>
                            <td class="d-flex justify-content-end border-0">
                                <a href="{{ route('reports.edit', $report->id) }}"
                                    class="btn btn-icon btn-warning btn-sm me-1">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                @if($report->can_delete())
                                <a href="{{ route('reports.destroy', $report->id) }}"
                                    class="btn btn-icon btn-danger btn-sm show_confirm" data-toggle="tooltip"
                                    data-original-title="Delete Report">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="3">
                                <div class="text-center">No Reports Yet ...</div>
                            </th>
                        </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->

                    <tfoot>
                        <tr>
                            <th colspan="3">
                                {{ $reports->appends(['date_from' => request()->query('date_from'), 'date_to' =>
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