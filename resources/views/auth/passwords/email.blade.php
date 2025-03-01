@extends('auth.app')

@section('title', 'forget password')

@section('content')
<!--begin::Form-->
<form class="form w-100" novalidate="novalidate" action="{{ route('password.email') }}" enctype="multipart/form-data"
    method="POST">
    @csrf

    <!--begin::Heading-->
    <div class="text-center mb-10">
        <!--begin::Title-->
        <h1 class="text-dark fw-bolder mb-3">Forgot Password ?</h1>
        <!--end::Title-->
        <!--begin::Link-->
        <div class="text-gray-500 fw-semibold fs-6">Enter your email to reset your password.</div>
        <!--end::Link-->
    </div>
    <!--begin::Heading-->

    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <!--begin::Input group=-->
    <div class="fv-row mb-8">
        <input id="email" type="email" class="form-control bg-transparent @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <!--begin::Actions-->
    <div class="d-flex flex-wrap justify-content-center pb-lg-0">
        <button type="submit" class="btn btn-primary me-4">
            <!--begin::Indicator label-->
            <span class="indicator-label">Submit</span>
            <!--end::Indicator label-->
        </button>
        <a href="{{ route('login') }}" class="btn btn-light">Cancel</a>
    </div>
    <!--end::Actions-->
</form>
<!--end::Form-->
@endsection