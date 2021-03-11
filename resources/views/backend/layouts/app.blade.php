<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ appName() }} | Admin | @yield('title', 'Dashboard')</title>
        <meta name="description" content="@yield('meta_description', appName())">
        <meta name="author" content="@yield('meta_author', appName())">
        <meta name="csrf-token" content="{{csrf_token()}}">
        @yield('meta')

        @stack('before-styles')
        <link rel="shortcut icon" href="{{ asset('assets/backend/images/favicon.ico') }}" />
        <!-- START: Template CSS-->
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/jquery-ui/jquery-ui.theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/simple-line-icons/css/simple-line-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/flags-icon/css/flag-icon.min.css') }}">
        @stack('after-template-styles')
        <!-- END Template CSS-->      
        
        <!-- START: Page CSS-->   
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/fontawesome/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/weather-icons/css/pe-icon-set-weather.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/ionicons/css/ionicons.min.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('assets/backend/vendors/sweetalert/sweetalert.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{asset('assets/backend/vendors/select2/css/select2.css')}}">
        <livewire:styles />
        @stack('after-page-styles')
        <!-- END: Page CSS-->

        <!-- START: Custom CSS-->
        <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}">
        @stack('after-custom-styles')
        <!-- END: Custom CSS-->

        <style>
            .error{
                color: red;
            }

            /* Chrome, Safari, Edge, Opera */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }

            /* Firefox */
            input[type=number] {
            -moz-appearance: textfield;
            }
        </style>
        @stack('after-styles')

    </head>
    <body id="main-container" class="default">
        <!-- START: Pre Loader-->
        <div class="se-pre-con">
            <div class="loader"></div>
        </div>
        <!-- END: Pre Loader-->

        @include('includes.partials.read-only')
        @include('includes.partials.logged-in-as')
        @include('includes.partials.announcements')
        
        @include('backend.includes.header')
        @include('backend.includes.sidebar')

        <!-- START: Main Content-->
        <main>
            @include('includes.partials.messages')
            <div class="container-fluid site-width">
                <!-- START: Breadcrumbs-->
                <div class="row">
                    <div class="col-12  align-self-center">
                        <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                            <div class="w-sm-100 mr-auto"><h4 class="mb-0">@yield('page-title')</h4> <p>@yield('page-sub-title')</p></div>

                            @include('backend.includes.partials.breadcrumbs')
                            @yield('breadcrumb-links')
                        </div>
                    </div>
                </div>
                <!-- END: Breadcrumbs-->
                @yield('content')
            </div>
        </main>

        <!-- START: Footer-->
        {{-- <footer class="site-footer ml-0">
            {{ date('Y') }} © {{ appName() }}
        </footer> --}}
        <!-- END: Footer-->


        <!-- START: Back to top-->
        <a href="#" class="scrollup text-center"> 
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END: Back to top-->

        @stack('before-scripts')
        <!-- START: Template JS-->
        <script src="{{ asset('assets/backend/vendors/jquery/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('assets/backend/vendors/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/backend/vendors/moment/moment.js') }}"></script>
        <script src="{{ asset('assets/backend/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>    
        <script src="{{ asset('assets/backend/vendors/slimscroll/jquery.slimscroll.min.js') }}"></script>
        @stack('after-template-scripts')
        <!-- END: Template JS-->
        
        <!-- START: PAGE Vendor JS-->
        {{-- <script src="{{ asset('assets/backend/vendors/sweetalert/sweetalert.min.js') }}"></script> --}}
        <script src="{{asset('assets/backend/vendors/toastr/toastr.min.js')}}"></script>
        <script src="{{asset('assets/backend/vendors/select2/js/select2.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script> --}}
        <livewire:scripts />
        @stack('after-page-vendor-scripts')

        <!-- START: PAGE JS-->
        @stack('after-page-scripts')

        <!-- START: APP JS-->
        <script src="{{ asset('assets/backend/js/app.js') }}"></script>
        @stack('after-app-scripts')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.select2').select2();
        </script>
        <!-- END: APP JS-->
        @stack('after-scripts')
    </body>
</html>