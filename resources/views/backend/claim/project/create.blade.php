@extends('backend.layouts.app')

@section('title', __('Create Project'))

@push('after-styles')
<link rel="stylesheet" href="{{asset('assets/backend/vendors/select2/css/select2.css')}}">
@endpush
@section('content')
    <x-forms.post :action="route('admin.claim.project.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Project')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.claim.project.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
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
                    {{ html()->label(__('Project Number'))->class('col-md-2 col-form-label')->for('number') }}

                    <div class="col-md-10">
                        {{ html()->text('number')
                            ->class('form-control')
                            ->maxlength(191)
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Project Pool'))->class('col-md-2 col-form-label')->for('pool') }}

                    <div class="col-md-10">
                        {{ html()->text('pool')
                            ->class('form-control')
                            ->maxlength(191)
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Project Start Date'))->class('col-md-2 col-form-label')->for('start_date') }}

                    <div class="col-md-10">
                        {{ html()->text('start_date')
                            ->class('form-control')
                            ->placeholder('MM-YYYY')
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Project Length (No. of Quarters)'))->class('col-md-2 col-form-label')->for('length') }}

                    <div class="col-md-10">
                        {{ html()->input('number', 'length')
                            ->class('form-control')
                            ->maxlength('5')
                            // ->placeholder('Select no of quarters')
                            ->required() }}
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Project Status'))->class('col-md-2 col-form-label')->for('status') }}

                    <div class="col-md-10">
                        {{ html()->text('status')
                            ->class('form-control')
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Funders'))->class('col-md-2 col-form-label')->for('funders') }}

                    <div class="col-md-10">
                        {{ html()->multiselect('funders[]', $funders)
                            ->class('form-control select2')
                            // ->placeholder('Choose Funders')
                            ->required() }}
                    </div>
                </div><!--form-group-->
                
                <div class="form-group row">
                    {{ html()->label(__('Cost Items'))->class('col-md-2 col-form-label')->for('cost_items') }}

                    <div class="col-md-10">
                        <table class="table cost_items">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="repeatable">
                                @if (!empty(old('cost_items')) || !empty($costItems))
                                @foreach (old('cost_items')?? $costItems as $key => $costItem)
                                <tr class="field-group">
                                    <td>
                                        {{ html()->text('cost_items['.$key.'][name]', $costItem->name ?? '')
                                            ->placeholder(__('Name'))
                                            ->class('form-control')
                                            ->required() }}
                                    </td>
                                    <td>
                                        {{ html()->text('cost_items['.$key.'][description]', $costItem->description ?? '')
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
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Project')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
    <script type="text/template" id="cost-items-template">
        <tr class="field-group">
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
        $('.select2').select2();

        $('.cost_items .repeatable').repeatable({
            addTrigger: ".cost_items .add",
            deleteTrigger: ".cost_items .delete",
            template: "#cost-items-template",
            min: 1,
            prefix: '',
            idStartIndex: '{{ count(old('claim_items') ?? [] ) }}',
        });
    });
</script>
@endpush