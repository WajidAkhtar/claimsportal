<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand">
        <a href="{{route('admin.claim.project.index')}}" class="horizontal-logo text-left">
            <center>
                <img src="{{ asset('assets/backend/images/logo.png') }}" class="sidebar-logo" height="70">
            </center>
        </a>
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-title">Menus</li>
        <li class="c-sidebar-nav-item">
            <ul class="c-sidebar-nav {{activeClass(Route::is('admin.claim.project.*'), 'active')}}">
                <a class="c-sidebar-nav-link" href="{{route('admin.claim.project.index')}}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-chart-line') }}"></use>
                    </svg>
                    <li class="c-sidebar-nav-item">
                        @lang('PROJECTS')
                    </li>
                </a>
            </ul>
        </li>
        @if(!auth()->user()->hasRole('Project Partner') && !auth()->user()->hasRole('Funder'))
        <li class="c-sidebar-nav-item">
            <ul class="c-sidebar-nav {{activeClass(Route::is('admin.auth.user.*'), 'active')}}">
                <a class="c-sidebar-nav-link" href="{{route('admin.auth.user.index')}}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                    </svg>
                    <li class="c-sidebar-nav-item">
                        @lang('USERS')
                    </li>
                </a>
            </ul>
        </li>
        @endif
    </ul>
</div>