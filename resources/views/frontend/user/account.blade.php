@extends('backend.layouts.app')

@section('title', __('My Account'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{ url()->previous() }}" class="text-large">
                    <span>
                        <i class="fa fa-arrow-left"></i>
                    </span>
                </a>
                <h2 class="page-main-title mt-3">Change Password</h2>
                <x-frontend.card>
                    <x-slot name="header">
                        <strong>Change your account password</strong>
                    </x-slot>
                    <x-slot name="body">
                        <nav style="display: none;">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <!-- <x-utils.link
                                    :text="__('My Profile')"
                                    class="nav-link active"
                                    id="my-profile-tab"
                                    data-toggle="pill"
                                    href="#my-profile"
                                    role="tab"
                                    aria-controls="my-profile"
                                    aria-selected="true" /> -->

                                <!-- <x-utils.link
                                    :text="__('Edit Information')"
                                    class="nav-link"
                                    id="information-tab"
                                    data-toggle="pill"
                                    href="#information"
                                    role="tab"
                                    aria-controls="information"
                                    aria-selected="false"/> -->

                                @if (! $logged_in_user->isSocial())
                                    <!-- <x-utils.link
                                        :text="__('Password')"
                                        class="nav-link active"
                                        id="password-tab"
                                        data-toggle="pill"
                                        href="#password"
                                        role="tab"
                                        aria-controls="password"
                                        aria-selected="false" /> -->
                                @endif

                            </div>
                        </nav>

                        <div class="tab-content" id="my-profile-tabsContent">
                            

                            @if (! $logged_in_user->isSocial())
                                <div class="tab-pane fade show active" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    @include('frontend.user.account.tabs.password')
                                </div><!--tab-password-->
                            @endif

                            <div class="tab-pane fade pt-3" id="two-factor-authentication" role="tabpanel" aria-labelledby="two-factor-authentication-tab">
                                @include('frontend.user.account.tabs.two-factor-authentication')
                            </div><!--tab-information-->
                        </div><!--tab-content-->
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection
