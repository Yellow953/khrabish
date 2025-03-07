@extends('auth.app')

@section('content')
<!--begin::Form-->
<form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('password.email') }}">
    @csrf

    {{--
    <!--begin::Logo-->
    <a href="{{ route('home') }}" class="d-flex flex-center">
        <img alt="Logo" src="{{ asset('assets/images/logo.png') }}" class="login-logo" />
    </a>
    <!--end::Logo--> --}}

    <!--begin::Heading-->
    <div class="text-center mb-11">
        <!--begin::Title-->
        <h1 class="text-dark fw-bolder mb-3 display-6 primary-color">Reset Password</h1>
        <!--end::Title-->
    </div>
    <!--begin::Heading-->

    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <!--begin::Input group=-->
    <div class="fv-row mb-8">
        <!--begin::Email-->
        <input type="text" placeholder="Email" name="email" autocomplete="off"
            class="form-control bg-transparent @error('email') is-invalid @enderror" id="email"
            value="{{ old('email') }}" required autocomplete="email" autofocus />

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <!--end::Email-->
    </div>
    <!--end::Input group=-->

    <!--begin::Submit button-->
    <div class="d-grid mb-5">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary indicator-label-custom">
            <!--begin::Indicator label-->
            <span class="indicator-label">Send Password Reset Link</span>
            <!--end::Indicator label-->
        </button>
    </div>
    <!--end::Submit button-->
</form>
<!--end::Form-->
@endsection