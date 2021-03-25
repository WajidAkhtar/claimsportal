@extends('backend.layouts.app')

@section('title', __('Project Management'))

{{-- @section('breadcrumb-links')
    @include('backend.claim.project.includes.breadcrumb-links')
@endsection --}}

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <h2>PROJECTS</h2>
            <br />
            <x-backend.card>
                <x-slot name="header">
                    <h2></h2>
                </x-slot>
                
                @if($allowToCreate)
                <x-slot name="headerActions">
                    <x-utils.link
                        icon="icon-plus"
                        class=""
                        :href="route('admin.claim.project.create')"
                        :text="__('Create Project')"
                    />
                </x-slot>
                @endif
        
                <x-slot name="body">
                    <livewire:backend.projects-table />
                </x-slot>
            </x-backend.card> 
        </div>
    </div>
@endsection
