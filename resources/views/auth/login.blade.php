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
                    <!--begin::Card body-->
                    <div class="card-body d-flex flex-column px-10 py-5">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-center flex-column-fluid">
                            <!--begin::Form-->
                            <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
                                @csrf

                                {{--
                                <!--begin::Logo-->
                                <a href="{{ route('home') }}" class="d-flex flex-center">
                                    <img alt="Logo" src="{{ asset('assets/images/logo.png') }}" class="login-logo"
                                        width="150" height="125" />
                                </a>
                                <!--end::Logo--> --}}

                                <!--begin::Heading-->
                                <div class="text-center mb-11">
                                    <!--begin::Title-->
                                    <h1 class="text-white fw-bolder my-4 display-6 orange-color">Sign In</h1>
                                    <!--end::Title-->
                                </div>
                                <!--begin::Heading-->
                                <!--begin::Input group=-->
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <input type="text" placeholder="Email" name="email" autocomplete="off"
                                        class="form-control bg-transparent text-white @error('email') is-invalid @enderror"
                                        id="email" value="{{ old('email') }}" required autocomplete="email" autofocus />

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
                                    <input id="password" type="password" placeholder="Password" name="password"
                                        autocomplete="current-password" required
                                        class="form-control bg-transparent text-white @error('password') is-invalid @enderror" />

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!--end::Password-->
                                </div>
                                <!--end::Input group=-->

                                <div class="d-flex justify-content-between px-3 mt-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
        old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>

                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                        <!--begin::Link-->
                                        <a href="{{ route('password.request') }}" class="link-primary orange-color">Forgot
                                            Password ?</a>
                                        <!--end::Link-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>

                                <!--begin::Submit button-->
                                <div class="d-grid mb-5">
                                    <button type="submit" id="kt_sign_in_submit"
                                        class="btn btn-primary indicator-label-custom">
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label text-white">Sign In</span>
                                        <!--end::Indicator label-->
                                    </button>
                                </div>
                                <!--end::Submit button-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
@endsection