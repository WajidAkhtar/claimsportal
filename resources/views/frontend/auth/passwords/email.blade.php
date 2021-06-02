@extends('frontend.layouts.session')

@section('title', __('Reset Password'))

<style type="text/css">
    .card {
        border: none !important;
    }
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <x-frontend.card>
                    <x-slot name="body">
                        <div class="c-sidebar-brand mb-4" style="justify-content: left !important;">
                            <img src="{{ asset('assets/backend/images/logo_horiz_white.jpg') }}" class="sidebar-logo" height="50">
                        </div>
                        <x-forms.post :action="route('frontend.auth.password.email')">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <svg class="c-icon">
                                            <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                        </svg>
                                    </span>
                                </div>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('E-mail Address') }}" maxlength="255" required autofocus autocomplete="email" />
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="submit">@lang('Send Password Reset Link')</button>
                                </div>
                            </div><!--form-group-->
                        </x-forms.post>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection
