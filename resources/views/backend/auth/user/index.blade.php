@extends('backend.layouts.app')

@section('title', __('User Management'))

{{-- @section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection --}}
@push('after-styles')
    <style>
        .table th, .table td {
            width: 17%;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <h2 class="page-main-title">Users</h2>

            @foreach($roles as $role)
                @if(current_user_role() == 'Developer')
                    @if(!in_array($role->name, ['Administrator', 'Super User', 'Finance Officer', 'Project Admin', 'Project Partner', 'Funder']))
                        @php continue; @endphp;
                    @endif
                @endif
                @if(current_user_role() == 'Administrator')
                    @if(!in_array($role->name, ['Super User', 'Finance Officer', 'Project Admin', 'Project Partner', 'Funder']))
                        @php continue; @endphp;
                    @endif
                @endif
                @if(current_user_role() == 'Super User')
                    @if(!in_array($role->name, ['Finance Officer', 'Project Admin', 'Project Partner', 'Funder']))
                        @php continue; @endphp;
                    @endif
                @endif
                @if(current_user_role() == 'Finance Officer')
                    @if(!in_array($role->name, ['Project Admin', 'Project Partner', 'Funder']))
                        @php continue; @endphp;
                    @endif
                @endif
                @if(current_user_role() == 'Project Admin')
                    @if(!in_array($role->name, ['Project Partner', 'Funder']))
                        @php continue; @endphp;
                    @endif
                @endif
                @php $roleName = $role->name; @endphp
                @if($roleName == 'Administrator')
                    @php $roleName = 'Executive'; @endphp
                @endif
                <x-backend.card>
                    <x-slot name="header">
                        <strong class="page-main-title">{{ $roleName }}</strong>
                    </x-slot>

                    <x-slot name="headerActions">
                        <x-utils.link
                            icon="icon-plus"
                            class=""
                            :href="route('admin.auth.user.create', [$role->name])"
                            :text="__('Create '.$roleName)"
                        />
                    </x-slot>

                    <x-slot name="body">
                        @livewire('backend.users-table', ['role' => $role])
                    </x-slot>
                </x-backend.card>
                <br /><br />
            @endforeach
            @if($allowToCreate)
            <!-- <x-slot name="headerActions">
                <x-utils.link
                    icon="icon-plus"
                    class=""
                    :href="route('admin.auth.user.create')"
                    :text="__('Create User')"
                />
            </x-slot> -->
            @endif
        </div>
    </div>
@endsection
