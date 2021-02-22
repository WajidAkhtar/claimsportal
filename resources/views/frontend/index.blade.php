<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ appName() }}</title>
        <meta name="description" content="@yield('meta_description', appName())">
        <meta name="author" content="@yield('meta_author', appName())">
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
        @stack('after-page-styles')
        <!-- END: Page CSS-->

        <!-- START: Custom CSS-->
        <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}">
        @stack('after-custom-styles')
        <!-- END: Custom CSS-->

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

        <!-- START: Main Content-->
        <main class="ml-0">
            <div class="container-fluid site-width">
                <div class="row">
                    <div class="col-sm-12 mt-3 text-center">
                        <h1>{{appName()}}</h1>
                        <h2 class="mt-3">We are launching soon.... Stay Tuned.</h2>
                    </div>
                </div>
            </div>
        </main>

        <!-- START: Footer-->
        {{-- <footer class="site-footer fixed-bottom ml-0">
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

        <!-- START: APP JS-->
        <script src="{{ asset('assets/backend/js/app.js') }}"></script>
        @push('after-app-scripts')
            
        @endpush
        <!-- END: APP JS-->
        @stack('after-scripts')
    </body>
</html>
