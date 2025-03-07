@extends('auth.app')

@section('content')
<!--begin::Form-->
<form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('password.confirm') }}">
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
        <h1 class="text-dark fw-bolder mb-3 display-6 primary-color">Confirm Password</h1>
        <!--end::Title-->
    </div>
    <!--begin::Heading-->

    {{ __('Please confirm your password before continuing.') }}

    <div class="fv-row mb-3">
        <!--begin::Password-->
        <input id="password" type="password" placeholder="Password" name="password" autocomplete="current-password"
            required class="form-control bg-transparent @error('password') is-invalid @enderror" />

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <!--end::Password-->
    </div>
    <!--end::Input group=-->

    <!--begin::Submit button-->
    <div class="d-grid mb-5">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary indicator-label-custom">
            <!--begin::Indicator label-->
            <span class="indicator-label">Confirm Password</span>
            <!--end::Indicator label-->
        </button>

        @if (Route::has('password.request'))
        <a class="btn btn-primary" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
        @endif
    </div>
    <!--end::Submit button-->
</form>
<!--end::Form-->
@endsection