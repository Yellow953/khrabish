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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required">Status</label>
                                <select name="status" class="form-select" data-control="select2"
                                    data-placeholder="Select an option" required>
                                    <option value=""></option>
                                    @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status')==$status ? 'selected' : '' }}>{{
                                        ucwords($status) }}</option>
                                    @endforeach
                                </select>
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <select name="country" class="form-select" data-control="select2"
                                    data-placeholder="Select an option">
                                    <option value=""></option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country }}" {{ old('country')==$country ? 'selected' : '' }}>{{
                                        ucwords($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state" placeholder="Enter State..."
                                    value="{{ old('state') }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" placeholder="Enter City..."
                                    value="{{ old('city') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="5"
                                    placeholder="Enter Address...">{{ old('address') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="5"
                                    placeholder="Enter Note...">{{ old('note') }}</textarea>
                            </div>
                        </div>
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

<!--begin::Modal - Payment-->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Amount Paid Input with Clear Button -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label for="amountPaid" class="form-label mb-0">Amount Paid</label>
                        <button type="button" class="btn btn-sm btn-danger" id="payment-clear">
                            <i class="fas fa-times me-1"></i> Clear
                        </button>
                    </div>
                    <input type="number" class="form-control form-control-lg" id="amountPaid"
                        placeholder="Enter amount">
                </div>

                <!-- Currency Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card bg-custom-1">
                            <div class="card-body">
                                <h6 class="card-title">Grand Total</h6>
                                <div class="text-right">
                                    <div class="fs-3 fw-bold text-success" id="grandTotalUSD">$0.00</div>
                                    <div class="fs-5" id="grandTotalLBP">0 LBP</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-custom-2">
                            <div class="card-body">
                                <h6 class="card-title">Change Due</h6>
                                <div class="text-right">
                                    <div class="fs-3 fw-bold text-success" id="changeDueUSD">$0.00</div>
                                    <div class="fs-5" id="changeDueLBP">0 LBP</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cash Notes Tabs -->
                <ul class="nav nav-tabs nav-line-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#usd_notes">USD Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#lbp_notes">LBP Notes</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- USD Notes -->
                    <div class="tab-pane fade show active" id="usd_notes">
                        <div class="row g-3">
                            @foreach ($bank_notes->where('currency_code', 'USD') as $bank_note)
                            <div class="col-4">
                                <div class="card text-center p-3 bank-note" style="cursor: pointer;">
                                    <div class="fw-bold">{{ $bank_note->name }}</div>
                                    <div>${{ number_format($bank_note->value, 2) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- LBP Notes -->
                    <div class="tab-pane fade" id="lbp_notes">
                        <div class="row g-3">
                            @foreach ($bank_notes->where('currency_code', 'LBP') as $bank_note)
                            <div class="col-4">
                                <div class="card text-center p-3 bank-note" style="cursor: pointer;">
                                    <div class="fw-bold">{{ $bank_note->name }}</div>
                                    <div>{{ number_format($bank_note->value) }} L.L.</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning text-dark" id="mark-as-unpaid">Mark as Unpaid</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmPayment" class="btn btn-primary">Confirm Payment</button>
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