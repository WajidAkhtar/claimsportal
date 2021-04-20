
@extends('frontend.layouts.session')


@section('title', __('Login'))
@push('after-custom-styles')
<link rel="stylesheet" href="{{ asset('material/css/material-dashboard.min.css?v=2.1.1"') }}">

@endpush
@section('content')
    <div class="custom-container py-4">
        <video class="video" autoplay src="{{ asset('assets/video/hd.mov')}} " loop></video>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <x-frontend.card>
                    <x-slot name="header">
                        {{-- @lang('Login') --}}
                        <div class="header_img">
                            <img style="width:100px; height:100px;" src="{{ asset('img/sercs-logo-color-blackBG-square.png') }}">
                            <span style="color: grey; font-size:20px; font-weight:bold;">SERCSOFT</span>
                        </div>
                    </x-slot>

                    <x-slot name="body">
                        <x-forms.post :action="route('frontend.auth.login')">

                            <div class="form-group bmd-form-group"> <!-- left unspecified, .bmd-form-group will be automatically added (inspect the code) -->
                                <label for="email" class="bmd-label-floating">@lang('E-mail Address')</label>
                                <input type="email" class="form-control" id="email" name="email">
                              </div>
                              <div class="form-group bmd-form-group"> <!-- manually specified --> 
                                <label for="password" class="bmd-label-floating">@lang('Password')</label>
                                <input type="password" class="form-control" id="password" name="password">
                              </div>

                            {{-- <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input name="remember" id="remember" class="form-check-input" type="checkbox" {{ old('remember') ? 'checked' : '' }} />

                                        <label class="form-check-label" for="remember">
                                            @lang('Remember Me')
                                        </label>
                                    </div><!--form-check-->
                                </div>
                            </div><!--form-group--> --}}
                            <div class="form-group bmd-form-group ">
                                    <x-utils.link :href="route('frontend.auth.password.request')" class="p-0 btn btn-link" :text="__('Forgot Your Password?')" />

                                   
                            </div><!--form-group-->
                            <div class="form-group bmd-form-group">
                                <button type="submit" style="width:100%;" class="btn btn-fill btn-rose">@lang('Login')</button>

                               
                        </div><!--form-group-->

                            @if(config('boilerplate.access.captcha.login'))
                                <div class="row">
                                    <div class="col">
                                        @captcha
                                        <input type="hidden" name="captcha_status" value="true" />
                                    </div><!--col-->
                                </div><!--row-->
                            @endif
                            <div class="text-center">
                                @include('frontend.auth.includes.social')
                            </div>
                        </x-forms.post>
                        <div class="form-group bmd-form-group">
                            <a href="https://instagram.com/" class="mr-2" target="_blank">
                                <img class="lazy social-icon" src="{{ asset('img/instagram.png')}}">
                            </a>
                            <a href="https://www.facebook.com/" class="mr-2" target="_blank">
                                <img class="lazy social-icon" src="{{ asset('img/facebook.png')}}">
                            </a>
                            <a href="https://twitter.com/" class="mr-2" target="_blank">
                                <img class="lazy social-icon" src="{{ asset('img/twitter.png')}}">
                            </a>
                            <a href="https://g.page/" class="mr-2" target="_blank">
                                <img class="lazy social-icon" src="{{ asset('img/google.png')}}">
                            </a>

                        </div>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection

@push('after-scripts')

<script src="{{ asset('material') }}/js/core/jquery.min.js"></script>
<script src="{{ asset('material') }}/js/core/popper.min.js"></script>
<script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>
<script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>


<script src="{{ asset('material/js/material-dashboard.min.js') }}"></script>
@endpush