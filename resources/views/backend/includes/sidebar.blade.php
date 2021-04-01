<!-- START: Main Menu-->
<div class="sidebar">
    <div class="site-width" style="margin-top: 4em !important;">
        <!-- START: Menu-->
        <ul id="side-menu" class="sidebar-menu">
            <li class="dropdown active" style="display: none;">
                <ul>
                    <li class="{{activeClass(Route::is('admin.dashboard'), 'active')}}">
                        <a href="{{route('admin.dashboard')}}"><i class="icon-rocket"></i> @lang('Dashboard')</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <ul class="{{activeClass(Route::is('admin.claim.project.*'), 'active')}}">
                    <a href="{{route('admin.claim.project.index')}}">
                        <center>
                            <i class="icon-chart menu-icon"></i>
                            <li class="menu-link">
                                @lang('PROJECTS')
                            </li>
                        </center>
                    </a>
                </ul>
            </li>

            @if(!auth()->user()->hasRole('Project Partner') && !auth()->user()->hasRole('Funder'))
            <li class="dropdown">
                <ul class="{{activeClass(Route::is('admin.auth.user.*'), 'active')}}">
                    <a href="{{route('admin.auth.user.index')}}">
                        <center>
                            <i class="icon-user menu-icon"></i>
                            <li class="menu-link">
                                @lang('USERS')
                            </li>
                        </center>
                    </a>
                </ul>
            </li>
            @endif

            <!-- @if(!auth()->user()->hasRole('Project Partner') && !auth()->user()->hasRole('Funder'))
            <li class="dropdown active">
                <a href="javascript:void(0)"><i class="fa fa-users mr-1"></i> Users</a>
                <ul>
                    <li class="{{activeClass(Route::is('admin.auth.user.*'), 'active')}}">
                        <a href="{{route('admin.auth.user.index')}}"><i class="icon-people"></i> @lang('User')</a>
                    </li>
                </ul>
            </li>
            @endif -->

            <!-- <li class="dropdown active">
                <a href="javascript:void(0)"><i class="fa fa-sticky-note mr-1"></i> Claims</a>
                <ul>
                    <li class="{{activeClass(Route::is('admin.claim.project.*'), 'active')}}">
                        <a href="{{route('admin.claim.project.index')}}"><i class="fa fa-project-diagram"></i> @lang('Projects')</a>
                    </li>
                </ul>
            </li> -->
            
        </ul>
        <!-- END: Menu-->
        {{-- <ol class="breadcrumb bg-transparent align-self-center m-0 p-0 ml-auto">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol> --}}
    </div>
</div>
<!-- END: Main Menu-->
