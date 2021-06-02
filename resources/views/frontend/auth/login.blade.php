@extends('frontend.layouts.session') @section('title', __('Login')) @section('content')
<style type="text/css">
    .card {
        border: none !important;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <x-frontend.card>
                <x-slot name="body">
                    <div class="c-sidebar-brand mb-4" style="justify-content: left !important;">
                            <img src="{{ asset('assets/backend/images/logo_horiz_white.jpg') }}" class="sidebar-logo" height="70">
                        </div>
                    <x-forms.post :action="route('frontend.auth.login')">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">
                    <svg class="c-icon">
                        <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                    </svg></span>
                            </div>
                            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required autofocus autocomplete="email" />
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">
                        <svg class="c-icon">
                            <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                        </svg></span>
                            </div>
                            <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="current-password" />
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input name="remember" id="remember" class="form-check-input" type="checkbox" {{ old( 'remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                </div>
                                <!--form-check-->
                            </div>
                        </div>
                        <!--form-group-->@if(config('boilerplate.access.captcha.login'))
                        <div class="row">
                            <div class="col">@captcha
                                <input type="hidden" name="captcha_status" value="true" />
                            </div>
                            <!--col-->
                        </div>
                        <!--row-->@endif
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary px-4" type="submit">Login</button>
                            </div>
                            <div class="col-6 text-right">
                                <x-utils.link :href="route('frontend.auth.password.request')" class="btn btn-link" :text="__('Forgot Your Password?')" />
                            </div>
                        </div>
                        <div class="text-center">@include('frontend.auth.includes.social')</div>
                    </x-forms.post>
                </x-slot>
            </x-frontend.card>
        </div>
        <!--col-md-8-->
    </div>
    <!--row-->
</div>
<!--container-->@endsection