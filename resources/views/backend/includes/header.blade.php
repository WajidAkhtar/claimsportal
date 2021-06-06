<div class="c-wrapper">
    <header class="c-header c-header-light c-header-fixed">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
            data-class="c-sidebar-show">
            <svg class="c-icon c-icon-lg">
                <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar"
            data-class="c-sidebar-lg-show" responsive="true">
            <svg class="c-icon c-icon-lg">
                <use xlink:href="{{ asset('assets/backend/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <ul class="c-header-nav d-md-down-none">
            <li class="c-header-nav-item px-3">
                <!-- <h4 class="mt-2 ml-2">Claims Portal</h4> -->
                <img class="ml-2" src="{{ asset('assets/backend/images/app_title_name_logo.png') }}" width="160"
                    height="auto" />
            </li>
        </ul>
        <ul class="c-header-nav mfs-auto">
            <li class="c-header-nav-item px-3 c-d-legacy-none">

            </li>
        </ul>
        <ul class="c-header-nav">
            <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2" style="margin-top:3px;">{{ $logged_in_user->full_name }} </span>
                    <div class="c-avatar ml-2 mr-2"><img class="c-avatar-img" src="{{ $logged_in_user->avatar }}"
                            alt="{{ $logged_in_user->name }}">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0 mr-2">
                    <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
                    <a href="{{ route('admin.auth.user.edit', [$logged_in_user->id]) }}" class="dropdown-item">
                        <span class="cil-pencil mr-2 h6 mb-0"></span> Edit Profile
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal" data-target="#avatarModal">
                        <span class="cil-pencil mr-2 h6 mb-0"></span> Avatar
                    </a>
                    <a href="{{ route('frontend.user.account') }}" class="dropdown-item">
                        <span class="cil-low-vision mr-2 h6 mb-0"></span> Change Password
                    </a>
                    <x-utils.link class="dropdown-item" icon="cil-account-logout mr-2 h6  mb-0"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <x-slot name="text">
                            @lang('Logout')
                            <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                        </x-slot>
                    </x-utils.link>
                </div>
            </li>
            <button class="c-header-toggler c-class-toggler mfe-md-3" type="button" data-target="#aside"
                data-class="c-sidebar-show" style="display: none;">

            </button>
        </ul>

    </header>
    <div class="modal" tabindex="-1" role="dialog" id="avatarModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Avatars</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @php
                            $avatars = \File::files(public_path('assets/backend/avatars'));
                        @endphp
                        @foreach ($avatars as $avatar)
                        <div class="col-sm-4 col-md-2 p-2">
                            <a href="javascript:void(0)" data-image-name="{{$avatar->getFileName()}}" onclick="updateAvatar(this)">
                                <img src="{{asset('assets/backend/avatars/'.$avatar->getFileName())}}"/>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('after-scripts')
        <script>
            function updateAvatar(obj) {
                $.ajax({
                    url: '{{route('frontend.user.profile.update.avatar')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {avatar: $(obj).data('image-name')},
                    success: function(response) {
                        if(response.status == 'success') {
                            toastr.success(response.message);
                            $('#avatarModal').modal('hide');
                            $('body').find('.modal-backdrop.show').remove();
                            $('img.c-avatar-img').attr('src', '{{asset('assets/backend/avatars/')}}'+'/'+$(obj).data('image-name'));
                        }
                        else {
                            toastr.erros(response.message);
                        }
                    }
                })
            }
        </script>
    @endpush