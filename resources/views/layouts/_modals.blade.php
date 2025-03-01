<!--begin::Modals-->
<!--begin::Modal - New Client-->
<div class="modal fade" id="kt_modal_new_client" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form id="kt_modal_new_client_form" class="form" action="{{ route('quick.new_client') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('POST')

                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">New Client</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name..."
                                    value="{{ old('name') }}" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Enter Phone Number..."
                                    value="{{ old('phone') }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email..."
                                    value="{{ old('email') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="5"
                            placeholder="Enter Address...">{{ old('address') }}</textarea>
                    </div>

                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_client_cancel" class="btn btn-light me-3">Cancel</button>
                        <button type="submit" id="kt_modal_new_client_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->

    @include('scripts.new_client')
</div>
<!--end::Modal - New Client-->

<!--begin::Modal - New Debt-->
<div class="modal fade" id="kt_modal_new_debt" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form id="kt_modal_new_debt_form" class="form" action="{{ route('quick.new_debt') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('POST')
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">New Debt</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->

                    <div id="creditor" class="form-group">
                        <!-- Dynamic select will be appended here -->
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" placeholder="Enter Amount..."
                                    step="any" min="0" value="{{ old('amount') }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required">Currency</label>
                                <select name="currency_id" class="form-select" required>
                                    <option value=""></option>
                                    @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ auth()->user()->currency_id == $currency->id
                                        ?
                                        'selected' : '' }} {{ old('currency_id')==$currency->id ? 'selected' :
                                        '' }}>{{ ucwords($currency->code) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Date</label>
                                <input type="date" class="form-control" name="date" placeholder="Enter Date..."
                                    value="{{ old('date') ?? date('Y-m-d') }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3"
                                    placeholder="Enter Note...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_debt_cancel" class="btn btn-light me-3">Cancel</button>
                        <button type="submit" id="kt_modal_new_debt_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->

    @include('scripts.new_debt')
</div>
<!--end::Modal - New Debt-->

<!--begin::Modal - New Report-->
<div class="modal fade" id="kt_modal_new_report" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form id="kt_modal_new_report_form" class="form" action="{{ route('quick.new_report') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('POST')

                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">New Report</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Date</label>
                                <input type="date" class="form-control" name="date" placeholder="Enter Date..."
                                    value="{{ old('date') ?? date('Y-m-d') }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Currency</label>
                                <select name="currency_id" class="form-select" required>
                                    <option value=""></option>
                                    @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ auth()->user()->currency_id == $currency->id
                                        ?
                                        'selected' : '' }} {{ old('currency_id')==$currency->id ? 'selected' :
                                        '' }}>{{ ucwords($currency->code) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">Start Cash</label>
                                <input type="number" step="any" min="0" class="form-control" name="start_cash"
                                    placeholder="Enter Start Cash..." value="{{ old('start_cash') }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required form-label">End Cash</label>
                                <input type="number" step="any" min="0" class="form-control" name="end_cash"
                                    placeholder="Enter End Cash..." value="{{ old('end_cash') }}" required />
                            </div>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_report_cancel" class="btn btn-light me-3">Cancel</button>
                        <button type="submit" id="kt_modal_new_report_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->

    @include('scripts.new_report')
</div>
<!--end::Modal - New Report-->

<!--begin::Modal - Payment-->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="amountPaid" class="form-label">Amount Paid</label>
                    <input type="number" class="form-control" id="amountPaid" placeholder="Enter the amount paid">
                </div>
                <div class="mb-3">
                    <div class="d-flex align-content-center justify-content-between">
                        <div>
                            <h5>Grand Total: <span id="modalGrandTotal"></span></h5>
                            <h5>Change Due: <span id="changeDue" class="text-success">$0.00</span></h5>
                        </div>
                        <div>
                            <a href="#" class="btn btn-danger btn-sm" id="payment-clear">Clear</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($bank_notes as $bank_note)
                    <div class="col-4 my-auto p-3">
                        <div class="card px-2 py-4 text-white bg-primary text-center bank-note">
                            <h4>{{ $bank_note->name }}</h4>
                            <b>{{ number_format($bank_note->value, 2) }} {{ auth()->user()->currency->symbol }}</b>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmPayment" class="btn btn-primary">Confirm Payment</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal - Payment-->

<!--begin::Modal - Calculator-->
<div class="modal fade" id="kt_modal_calculator" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-550px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-between align-content-center">
                <h3 class="text-primary text-uppercase">Calculator</h3>
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y p-10 px-lg-15">
                <div class="calculator card">

                    <input type="text" class="calculator-screen z-depth-1" value="" disabled />

                    <div class="calculator-keys">

                        <button type="button" data-mdb-button-init class="operator btn btn-primary" value="+">+</button>
                        <button type="button" data-mdb-button-init class="operator btn btn-primary" value="-">-</button>
                        <button type="button" data-mdb-button-init class="operator btn btn-primary"
                            value="*">&times;</button>
                        <button type="button" data-mdb-button-init class="operator btn btn-primary"
                            value="/">&divide;</button>

                        <button type="button" data-mdb-button-init value="7" data-mdb-ripple-init
                            class="btn btn-light waves-effect">7</button>
                        <button type="button" data-mdb-button-init value="8" data-mdb-ripple-init
                            class="btn btn-light waves-effect">8</button>
                        <button type="button" data-mdb-button-init value="9" data-mdb-ripple-init
                            class="btn btn-light waves-effect">9</button>


                        <button type="button" data-mdb-button-init value="4" data-mdb-ripple-init
                            class="btn btn-light waves-effect">4</button>
                        <button type="button" data-mdb-button-init value="5" data-mdb-ripple-init
                            class="btn btn-light waves-effect">5</button>
                        <button type="button" data-mdb-button-init value="6" data-mdb-ripple-init
                            class="btn btn-light waves-effect">6</button>


                        <button type="button" data-mdb-button-init value="1" data-mdb-ripple-init
                            class="btn btn-light waves-effect">1</button>
                        <button type="button" data-mdb-button-init value="2" data-mdb-ripple-init
                            class="btn btn-light waves-effect">2</button>
                        <button type="button" data-mdb-button-init value="3" data-mdb-ripple-init
                            class="btn btn-light waves-effect">3</button>


                        <button type="button" data-mdb-button-init value="0" data-mdb-ripple-init
                            class="btn btn-light waves-effect">0</button>
                        <button type="button" data-mdb-button-init class="decimal function btn btn-secondary"
                            value=".">.</button>
                        <button type="button" data-mdb-button-init class="all-clear function btn btn-danger btn-sm"
                            value="all-clear">AC</button>

                        <button type="button" data-mdb-button-init class="equal-sign operator btn btn-success"
                            value="=">=</button>

                    </div>
                </div>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->

    @include('scripts._calculator')
</div>
<!--end::Modal - Calculator-->

<!--begin::Modal - Last Order -->
<div class="modal fade" id="kt_modal_last_order" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-550px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-between align-content-center">
                <h3 class="text-primary text-uppercase">Last Order</h3>
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y p-10 px-lg-15">
                <div class="last_orders card">
                    @if ($last_order)
                    <div class="last_order rounded p-4 bg-primary text-white">
                        <div class="row">
                            <div class="col-6">
                                Order NO: {{ ucwords($last_order->order_number) }}
                            </div>
                            <div class="col-6 text-right">
                                Cashier: {{ ucwords($last_order->cashier->name) }}
                            </div>
                            <div class="col-12 my-2 text-center">
                                <b><u>Items:</u></b> <br>
                                @foreach ($last_order->items as $item)
                                {{ ucwords($item->product->name) }} : {{$item->quantity}} <br>
                                @endforeach
                            </div>
                            <div class="col-6">
                                Sub Total: {{ number_format($last_order->sub_total, 2) }}
                            </div>
                            <div class="col-6 text-right">
                                Total: {{ number_format($last_order->total, 2) }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="last_order_empty text-center py-4">
                        <p>No Orders Yet ...</p>
                    </div>
                    @endif
                </div>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Last Order-->

<!--end::Modals-->
