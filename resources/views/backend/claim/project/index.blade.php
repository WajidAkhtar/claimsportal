@extends('backend.layouts.app')

@section('title', __('Project Management'))

{{-- @section('breadcrumb-links')
    @include('backend.claim.project.includes.breadcrumb-links')
@endsection --}}

<style type="text/css">
    th {
        font-size: 0.875rem;
    }
    td {
        font-size: 0.875rem;
        font-weight: 400;
    }
    th:nth-child(2) {
        width: 12%;
        font-size: small;
    }
    /*th:nth-child(3) {
        width: 12%;
        font-size: small;
    }*/
    th:nth-child(5) {
        width: 12%;
    }
    th:nth-child(4) {
        width: 22%;
        font-size: small;
    }
    /*th:nth-child(6) {
        width: 5%;
        font-size: small;
    }
    th:nth-child(7) {
        width: 5%;
        font-size: small;
    }*/
</style>

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <h2 class="page-main-title">Projects</h2>
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
