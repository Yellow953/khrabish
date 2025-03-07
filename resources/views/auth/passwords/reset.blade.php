@extends('auth.app')

@section('content')
<!--begin::Form-->
<form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('password.update') }}">
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

    <input type="hidden" name="token" value="{{ $token }}">

    <!--begin::Input group=-->
    <div class="fv-row mb-8">
        <!--begin::Email-->
        <input type="text" placeholder="Email" name="email" autocomplete="off"
            class="form-control bg-transparent @error('email') is-invalid @enderror" id="email"
            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus />

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <!--end::Email-->
    </div>
    <!--end::Input group=-->
    <div class="fv-row mb-3">
        <!--begin::Password-->
        <input id="password" type="password" placeholder="Password" name="password" autocomplete="new-password" required
            class="form-control bg-transparent @error('password') is-invalid @enderror" />

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <!--end::Password-->
    </div>
    <!--end::Input group=-->
    <!--end::Input group=-->
    <div class="fv-row mb-3">
        <!--begin::Password-->
        <input id="password-confirm" type="password" placeholder="Password" name="password_confirmation"
            autocomplete="new-password" required
            class="form-control bg-transparent @error('password') is-invalid @enderror" />

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
            <span class="indicator-label">Reset Password</span>
            <!--end::Indicator label-->
        </button>
    </div>
    <!--end::Submit button-->
</form>
<!--end::Form-->
@endsection