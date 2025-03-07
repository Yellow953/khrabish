@extends('auth.app')

@section('content')
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <div class="card-body text-center">
            <!--begin::Title-->
            <h1 class="fw-bolder fs-2qx text-gray-900 mb-4">Unauthorized Access</h1>
            <!--end::Title-->
            <!--begin::Text-->
            <div class="fw-semibold fs-6 text-gray-500 mb-7">You are not allowed to access this page.
            </div>
            <!--end::Text-->
            <!--begin::Illustration-->
            <div class="mb-11">
                <img src="{{ asset('assets/images/403.png') }}" class="theme-light-show" width="250" height="250"
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
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
@endsection