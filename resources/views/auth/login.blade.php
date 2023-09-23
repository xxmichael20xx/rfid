@extends('layouts.auth')

@section('content')
<div class="row g-0 app-auth-wrapper app-login">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="d-flex flex-column align-content-end">
            <div class="app-auth-body mx-auto">	
                <h2 class="auth-heading text-center mb-5">Log in to Portal</h2>
                <div class="auth-form-container text-start">
                    <form class="auth-form login-form" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="email mb-3">
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
                        <div class="password mb-3">
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
                            <div class="extra mt-3 row justify-content-between">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="remember" 
                                            id="RememberPassword"
                                            {{ old('remember') ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="RememberPassword">Remember me</label>
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
    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
        <div class="auth-background-holder"></div>
        <div class="auth-background-mask"></div>
    </div>
</div>
@endsection
