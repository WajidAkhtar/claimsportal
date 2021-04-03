@extends('backend.layouts.app')

@section('title', __('Edit Project'))

@push('after-styles')
<link rel="stylesheet" href="{{asset('assets/backend/vendors/select2/css/select2.css')}}">
@endpush
@section('content')
    <h2 class="page-main-title">Edit Project</h2>
    <x-forms.patch :action="route('admin.claim.project.update', $project)" enctype="multipart/form-data">
        @php
            html()->model($project);
        @endphp
        <x-backend.card>
            <x-slot name="header">
                Edit project details
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.claim.project.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">

                <h6>PROJECT PROFILE</h6>
                <hr/>

                <div class="form-group row">
                    {{ html()->label(__('Project Pool'))->class('col-md-2 col-form-label')->for('pool_id') }}

                    <div class="col-md-10">
                        {{ html()->select('pool_id', $pools)
                            ->class('form-control select2')
                            ->placeholder('Select Project Pool')
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Project Name'))->class('col-md-2 col-form-label')->for('name') }}

                    <div class="col-md-10">
                        {{ html()->text('name')
                            ->class('form-control')
                            ->maxlength(191)
                            ->autofocus()
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Project Code'))->class('col-md-2 col-form-label')->for('number') }}

                    <div class="col-md-10">
                        {{ html()->text('number')
                            ->class('form-control')
                            ->maxlength(191)
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Lead Organisation'))->class('col-md-2 col-form-label')->for('number') }}

                    <div class="col-md-10">
                        {{ html()->select('lead_organisation', $funders, $leadOrganisation)
                            ->class('form-control select2')
                            ->placeholder('Select Lead Organisation')
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Funders'))->class('col-md-2 col-form-label')->for('funders') }}

                    <div class="col-md-10">
                        {{ html()->select('funders[]', $funders, old('funders')?? $project->funders->pluck('id'))
                            ->class('form-control select2')
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Project Funder Ref.'))->class('col-md-2 col-form-label')->for('project_funder_ref') }}

                    <div class="col-md-10">
                        {{ html()->text('project_funder_ref')
                            ->class('form-control')
                            ->maxlength(191)
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Project Status'))->class('col-md-2 col-form-label')->for('status') }}

                    <div class="col-md-10">
                        {{ html()->select('status', $projectStatuses, $project->status)
                            ->class('form-control')
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Project Logo'))->class('col-md-2 col-form-label')->for('project_logo') }}
                    
                    @if(!empty($project->logo) && file_exists(public_path('uploads/projects/logos/'.$project->logo)))
                        <div class="col-md-1">
                            <img src="{{ asset('uploads/projects/logos/'.$project->logo) }}" height="72" width="72" />
                        </div>
                    @endif
                    
                    <div class="col">
                        {{ html()->file('project_logo') }}
                    </div>
                </div>

                <h6 class="mt-4">PROJECT CLAIM TABLE</h6>
                <hr/>
                
                <div class="form-group row">
                    {{ html()->label(__('Project Start Date'))->class('col-md-2 col-form-label')->for('start_date') }}

                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-2">
                                {{ html()->select('start_date_month', months(), $project->start_date_month)
                                ->class('form-control')
                                ->placeholder('Select Month')
                                ->attribute('autocomplete', 'off')
                                ->required() }}
                            </div>
                            <div class="col-md-2">
                                {{ html()->select('start_date_year', years(), $project->start_date_year)
                                ->class('form-control')
                                ->placeholder('Select Year')
                                ->attribute('autocomplete', 'off')
                                ->required() }}
                            </div>
                        </div>
                        <p class="text-info">*Editing this field will reset quarter values for this project.</p>
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Number of Quarters'))->class('col-md-2 col-form-label')->for('length') }}

                    <div class="col-md-10">
                        {{ html()->input('number', 'length')
                            ->class('form-control')
                            ->maxlength('5')
                            // ->placeholder('Select no of quarters')
                            ->required() }}
                            <p class="text-info">*Editing this field will reset quarter values for this project.</p>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Project Partners'))->class('col-md-2 col-form-label')->for('project_partners') }}

                    <div class="col-md-10">
                        {{ html()->multiselect('project_partners[]', $partners,  $project->allpartners()->pluck('organisation_id'))
                            ->class('form-control select2')
                            // ->placeholder('Choose Project Partners')
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Claims Table'))->class('col-md-2 col-form-label')->for('cost_items') }}

                    <div class="col-md-10">
                        <table class="table cost_items">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="repeatable">
                                @if (!empty(old('cost_items')) || !empty($costItems))
                                @php $costItemCount = 1; @endphp
                                @foreach (old('cost_items')?? ( $costItems) as $key => $costItem)
                                <tr class="field-group" id="{{ $costItem->name ?? '' }}">
                                    <td>{{ $costItemCount++ }}</td>
                                    <td>
                                        {{ html()->text('cost_items['.$key.'][name]', $costItem->pivot->cost_item_name ?? '')
                                            ->placeholder(__('Name'))
                                            ->class('form-control')
                                            ->required() }}
                                    </td>
                                    <td>
                                        {{ html()->text('cost_items['.$key.'][description]', $costItem->pivot->cost_item_description ?? '')
                                            ->placeholder('Description')
                                            ->class('form-control')
                                            ->required() }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" align="right"><button type="button"
                                            class="btn btn-primary btn-sm add mt-1 float-right"><i
                                                class="fa fa-plus"></i></button></td>
                                </tr>
                            </tfoot>
                        </table>
                        {{ html()->input('hidden', 'cost_items_order', $project->cost_items_order ?? $costItems->pluck('name')->implode(','))
                            ->class('form-control')
                        }}
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Project')</button>
            </x-slot>
        </x-backend.card>
        @php
            html()->endModel();
        @endphp
    </x-forms.post>
    <script type="text/template" id="cost-items-template">
        <tr class="field-group">
            <td></td>
            <td>
                {{ html()->text('cost_items[{?}][name]')
                    ->placeholder(__('Name'))
                    ->class('form-control')
                    ->required() }}
            </td>
            <td>
                {{ html()->text('cost_items[{?}][description]')
                    ->placeholder('Description')
                    ->class('form-control')
                    ->required() }}
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </script>
@endsection
@push('after-scripts')
<script src="{{asset('assets/backend/vendors/select2/js/select2.js')}}"></script>
<script src="{{asset('assets/backend/vendors/repeatable/jquery.repeatable.js')}}"></script>
<script>
    $(document).ready(function(){
        $('input').attr('autocomplete', Math.random().toString(36).substring(7));
        $('.select2').select2();
        $( ".repeatable" ).sortable({
            connectWith: '.repeatable',
            update: function(event, ui) {
                var cost_items_order = $(this).sortable('toArray');
                $("#cost_items_order").val(cost_items_order.join(','));
            }
        });
        $('.repeatable').disableSelection();
        $('.cost_items .repeatable').repeatable({
            addTrigger: ".cost_items .add",
            deleteTrigger: ".cost_items .delete",
            template: "#cost-items-template",
            min: 1,
            prefix: '',
            idStartIndex: '{{ count(old('claim_items') ?? ($costItems ?? [])) }}',
            afterAdd : function() {
                $('.cost_items tbody tr').each(function(i, v) {
                    $(this).find('td:first').html(i+1);
                });
            },
            afterDelete : function() {
                $('.cost_items tbody tr').each(function(i, v) {
                    $(this).find('td:first').html(i+1);
                });
            }
        });
        var dateToday = new Date();
        var yrRange = 2015 + ":" + (2015 + 60);
        $("#start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange : yrRange,
            dateFormat : 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).val($.datepicker.formatDate('mm-yy', new Date(year, month, 1)));
            }
        });
        $("#start_date").focus(function () {
            $(".ui-datepicker-calendar").hide();
            $("#ui-datepicker-div").position({
                my: "left top",
                at: "left bottom",
                of: $(this)
            });
        });
        setTimeout(function() {
            $('#start_date').datepicker('setDate', new Date($('#start_date').val()));
        }, 0)
    });
</script>
@endpush