<div class="c-wrapper">
    <header class="c-header c-header-light c-header-fixed">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <svg class="c-icon c-icon-lg">
            <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
        </svg>
        </button>
            <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
            <svg class="c-icon c-icon-lg">
                <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
            </svg>
            </button>
            <ul class="c-header-nav d-md-down-none">
                <li class="c-header-nav-item px-3">
                    <!-- <h4 class="mt-2 ml-2">Claims Portal</h4> -->
                    <img class="ml-2" src="{{ asset('assets/backend/images/app_title_name_logo.png') }}" width="160" height="auto" />
                </li>
            </ul>
            <ul class="c-header-nav mfs-auto">
                <li class="c-header-nav-item px-3 c-d-legacy-none">
                    
                </li>
            </ul>
            <ul class="c-header-nav">
                <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2" style="margin-top:3px;">{{ $logged_in_user->full_name }} </span>
                    <div class="c-avatar ml-2 mr-2"><img class="c-avatar-img" src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->name }}">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0 mr-2">
                    <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
                    <a href="{{ route('admin.auth.user.edit', [$logged_in_user->id]) }}" class="dropdown-item">
                        <span class="cil-pencil mr-2 h6 mb-0"></span> Edit Profile
                    </a>
                    <a href="{{ route('frontend.user.account') }}" class="dropdown-item">
                        <span class="cil-low-vision mr-2 h6 mb-0"></span> Change Password
                    </a>
                    <x-utils.link
                    class="dropdown-item"
                    icon="cil-account-logout mr-2 h6  mb-0"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <x-slot name="text">
                    @lang('Logout')
                    <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                    </x-slot>
                    </x-utils.link>
                    </div>
                    </li>
                    <button class="c-header-toggler c-class-toggler mfe-md-3" type="button" data-target="#aside" data-class="c-sidebar-show" style="display: none;">
                    
                    </button>
                </ul>
                
            </header>