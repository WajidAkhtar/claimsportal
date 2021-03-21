<!-- START: Header-->
<div id="header-fix" class="header fixed-top">
    <div class="site-width">
        <nav class="navbar navbar-expand-lg  p-0">
            <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">  
                <a href="{{route('frontend.index')}}" class="horizontal-logo text-left">
                    {{-- <svg height="20pt" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" width="20pt" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(.1 0 0 -.1 0 512)" fill="#1e3d73">
                    <path d="m1450 4481-1105-638v-1283-1283l1106-638c1033-597 1139-654 1139-619 0 4-385 674-855 1489-470 814-855 1484-855 1488 0 8 1303 763 1418 822 175 89 413 166 585 190 114 16 299 13 408-5 100-17 231-60 314-102 310-156 569-509 651-887 23-105 23-331 0-432-53-240-177-460-366-651-174-175-277-247-738-512-177-102-322-189-322-193s104-188 231-407l231-400 46 28c26 15 360 207 742 428l695 402v1282 1282l-1105 639c-608 351-1107 638-1110 638s-502-287-1110-638z"/><path d="m2833 3300c-82-12-190-48-282-95-73-36-637-358-648-369-3-3 580-1022 592-1034 5-5 596 338 673 391 100 69 220 197 260 280 82 167 76 324-19 507-95 184-233 291-411 320-70 11-89 11-165 0z"/>
                    </g>
                    </svg> <span class="h4 font-weight-bold align-self-center mb-0 ml-auto">PICK</span> --}}
                    {{ appName() }}
                </a>                   
            </div>
            <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">
                <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
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
            <div class="navbar-right ml-auto h-100">
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