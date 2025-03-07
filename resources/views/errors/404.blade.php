@extends('auth.app')

@section('content')
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <!--begin::Body-->
        <div class="d-flex flex-center w-lg-50 p-10" style="margin: auto">
            <!--begin::Card-->
            <div class="card login-card-custom rounded-3 w-md-400px">
                <div class="card-body text-center">
                    <!--begin::Title-->
                    <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">Oops!</h1>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fw-semibold fs-6 text-gray-500 mb-7">We can't find that page.</div>
                    <!--end::Text-->
                    <!--begin::Illustration-->
                    <div class="mb-3">
                        <img src="{{ asset('assets/images/404.png') }}" class="mw-100 mh-300px theme-light-show"
                            alt="" />
                    </div>
                    <!--end::Illustration-->

                    <!--begin::Link-->
                    <div class="mb-0">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">Back</a>
                    </div>
                    <!--end::Link-->
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
@endsection