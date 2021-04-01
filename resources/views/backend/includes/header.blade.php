<!-- START: Header-->
<div id="header-fix" class="header fixed-top">
    <div class="site-width">
        <nav class="navbar navbar-expand-lg p-0">
            <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left bottom-shadow-transparent">  
                <a href="{{route('admin.claim.project.index')}}" class="horizontal-logo text-left">
                    <center>
                        <img src="{{ asset('assets/backend/images/logo.png') }}" class="logo" height="70">
                    </center>
                </a>                   
            </div>
            <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">
                <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
            </div>

            <div class="form-group mb-0 position-relative nav-title">
                <h4 class="mt-2 ml-2">Claims Portal</h4>
            </div>
            
            {{-- <form class="float-left d-none d-lg-block search-form">
                <div class="form-group mb-0 position-relative">
                    <input type="text" class="form-control border-0 rounded bg-search pl-5" placeholder="Search anything...">
                    <div class="btn-search position-absolute top-0">
                        <a href="#"><i class="h6 icon-magnifier"></i></a>
                    </div>
                    <a href="#" class="position-absolute close-button mobilesearch d-lg-none" data-toggle="dropdown" aria-expanded="false"><i class="icon-close h5"></i>                               
                    </a>

                </div>
            </form> --}}
            <div class="navbar-right ml-auto h-100 bottom-shadow">
                <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                    {{-- <li class="d-inline-block align-self-center">
                        <a href="#" class="btn btn-outline-primary btn-lg">Signup / Login</a>
                    </li> --}}
                    @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
                        <li class="dropdown align-self-center">
                            <x-utils.link
                                :text="__(getLocaleName(app()->getLocale()))"
                                class="nav-link"
                                id="navbarDropdownLanguageLink"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false" />

                            @include('includes.partials.lang')
                        </li>
                    @endif
                    <li class="dropdown user-profile align-self-center d-inline-block">
                        <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false"> 
                            <div class="media">                                   
                                <img src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->name }}" class="d-flex img-fluid rounded-circle" width="29">
                                <span class="ml-2" style="margin-top:3px;">{{ $logged_in_user->full_name }} </span>
                                <span class="ml-2 toggle-user-actions" style="margin-top: 5px;"><i class="icon-arrow-down"></i></span>
                            </div>
                        </a>

                        <div class="dropdown-menu border dropdown-menu-right p-0">
                            <a href="{{ route('admin.auth.user.edit', [$logged_in_user->id]) }}" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                            <a href="{{ route('frontend.user.account') }}" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-eye mr-2 h6 mb-0"></span> Change Password</a>
                            <div class="dropdown-divider"></div>
                            <x-utils.link
                                class="dropdown-item px-2 text-danger align-self-center d-flex"
                                icon="icon-logout mr-2 h6  mb-0"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <x-slot name="text">
                                    @lang('Logout')
                                    <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                                </x-slot>
                            </x-utils.link>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>