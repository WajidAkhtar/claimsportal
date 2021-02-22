@extends('backend.layouts.app')

@section('title', __('User Management'))

{{-- @section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection --}}

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <x-backend.card>
                <x-slot name="header">
                    @lang(__('User Management'))
                </x-slot>
        
                <x-slot name="headerActions">
                    <x-utils.link
                        icon="icon-plus"
                        class=""
                        :href="route('admin.auth.user.create')"
                        :text="__('Create User')"
                    />
                </x-slot>
        
                <x-slot name="body">
                    <livewire:backend.users-table />
                </x-slot>
            </x-backend.card> 
        </div>
    </div>
@endsection
