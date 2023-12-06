@extends('layouts.auth')

@section('content')
<style>
    .app-login .auth-background-holder {
        background: url("/images/background/background-1.jpg") no-repeat center center;
        background-size: cover;
        height: 100vh;
        min-height: 100%
    }

    #authCarousels {
        position: relative;
    }

    #authCarousels::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.3);
        z-index: 2;
    }

    #authCarousels,
    #authCarousels .carousel-inner,
    #authCarousels .carousel-item,
    #authCarousels img {
        height: 100vh;
    }
</style>
<div class="row g-0 app-auth-wrapper app-login">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="row h-100 align-items-center">
            <div class="col-8 mx-auto">
                <div class="card mx-auto border-0 shadow-lg">
                    <div class="card-body p-5">
                        <img class="img-fluid w-50 mx-auto" src="{{ asset('images/full_logo.png') }}" alt="logo">
                        <h2 class="auth-heading text-center mb-5">Log in to Portal</h2>
                        <div class="auth-form-container text-start">
                            <form class="auth-form login-form" method="POST" action="{{ route('login') }}">
                                @csrf
        
                                <div class="input-container mb-3">
                                    <label class="sr-only" for="signin-email">Email</label>
                                    <input
                                        id="signin-email"
                                        name="email"
                                        type="email"
                                        class="form-control signin-email @error('email') is-invalid @enderror"
                                        placeholder="Email address"
                                        value="{{ old('email') }}"
                                        required="required"
                                        autofocus
                                    >
        
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input-container mb-3">
                                    <label class="sr-only" for="signin-password">Password</label>
                                    <input
                                        id="signin-password"
                                        name="password"
                                        type="password"
                                        class="form-control signin-password @error('password') is-invalid @enderror"
                                        placeholder="Password"
                                        required="required"
                                    >
        
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="mt-3 row justify-content-between">
                                        <div class="col-6">
                                            <div class="form-check d-flex align-items-center">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="remember"
                                                    id="RememberPassword"
                                                    {{ old('remember') ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label m-0 ms-2" for="RememberPassword">Remember me</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">{{ __('Login') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5 col-lg-6 h-100">
        @php
            $bgImages = [
                asset('images/background/background-2.png'),
                asset('images/background/background-3.png'),
                asset('images/background/background-4.png'),
            ];
        @endphp
        <div
            id="authCarousels"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel"
        >
            <div class="carousel-inner">
                @php $imagesCount = 0; @endphp
                @foreach ($bgImages as $bgImageKey => $bgImage)
                    <div class="carousel-item text-center {{ ($imagesCount) == 0 ? 'active' : '' }}">
                        <img
                            src="{{ $bgImage }}"
                            class="img-fluid key-{{ $imagesCount }}"
                        />
                    </div>
                    @php $imagesCount = $imagesCount + 1; @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
