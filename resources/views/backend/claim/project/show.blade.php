@extends('backend.layouts.app')

@section('title', __('View Project'))

@push('after-styles')
    <style>
        table tr th:not(:first-child), table tr td:not(:first-child) {
            white-space: nowrap;
            min-width: 150px;
        }

        input:read-only {
            border: 0px;
        }

        .input-group-text.readonly {
            border: 0px;
            padding-right: 2px;
        }
    </style>
@endpush
@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Project')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.claim.project.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <div class="col-sm-12">
                <h4>Project Profile</h4>
                <div><strong>Project Name:</strong> {{$project->name}}</div>
                <div><strong>Project Number:</strong> {{$project->number}}</div>
                <div><strong>Project Start Date:</strong> {{$project->start_date->format('m-Y')}}</div>
                <div><strong>Funders:</strong> {{$project->funders->implode('name', ', ')}}</div>
            </div>
            <form action="#" id="claims_form">
                <div class="col-sm-12 mt-5">
                    <table class="table table-responsive table-borders table-sm main-claims-table" style="overflow-x: auto;">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                @php
                                    $startDate = $project->start_date;
                                @endphp
                                @for ($i = 0; $i < $project->length; $i++)
                                    @php
                                        $date = clone $startDate;
                                        $date->addMonths(2)->endOfMonth();
                                        $lableClass = '';
                                        if (now()->betweenIncluded($startDate, $date)){
                                            $lableClass = 'text-danger';
                                        }
                                    @endphp
                                    <th class="text-center">
                                        <label class="{{$lableClass}} text-uppercase"> {{$startDate->format('My')}} - {{$date->format('My')}}</label><br>
                                        <label class="{{$lableClass}}">Q{{$i+1}}</label>
                                    </th>
                                    @php
                                        $startDate->addMonths(3);
                                    @endphp
                                @endfor
                            </tr>
                            <tr>
                                <th style="max-width: 10px;min-width:auto;">#</th>
                                <th>COST ITEM</th>
                                <th>DESCRIPTION</th>
                                <th>TOTAL BUDGET</th>
                                @php
                                    $fromDate = clone $project->start_date;
                                @endphp
                                @for ($i = 0; $i < $project->length; $i++)
                                @php
                                    $toDate = clone $fromDate;
                                    $toDate->addMonths(2)->endOfMonth();
                                @endphp
                                <th class="text-center">
                                    @if (now()->betweenIncluded($fromDate, $toDate))
                                        <label class="text-danger mb-0">CURRENT</label>
                                        @elseif($fromDate->lt(now()))
                                        <label class="mb-0">HISTORIC</label>
                                        @else
                                        <label class="mb-0">FORECAST</label>
                                    @endif
                                </th>
                                @php
                                    $fromDate->addMonths(3);
                                @endphp
                                @endfor
                                <th>PROJECT TOTAL</th>
                                <th class="border-right">VARIANCE</th>
                                
                                @for ($i = 1; $i <= round(($project->length/4)); $i++)
                                <th>YR{{$i}} BUDGET</th>
                                <th>YEAR{{$i}}</th>
                                <th class="border-right">VARIANCE</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->costItems as $index => $costItem)
                            @php
                                $fromDate = clone $project->start_date;
                            @endphp
                            <tr>
                                <td style="max-width: 10px;min-width:auto;">{{$index+1}}</td>
                                <td>{{$costItem->name}}</td>
                                <td>{{$costItem->description}}</td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][total_budget]', 0)
                                            ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $yearIndex = 0;
                                @endphp
                                @for ($i = 0; $i < $project->length; $i++)
                                @php
                                    $toDate = clone $fromDate;
                                    $toDate->addMonths(2)->endOfMonth();
                                    $labelClass = '';
                                    if(now()->betweenIncluded($fromDate, $toDate)){
                                        $labelClass = 'text-danger';
                                    }
                                @endphp
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][quarter_values]['.$fromDate->timestamp.']', optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate->timestamp"} ?? 0)
                                            ->placeholder('Amount')
                                            ->class('form-control '.$labelClass)
                                            ->attribute('data-year-index', $yearIndex)
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $fromDate->addMonths(3);
                                    if(($i+1) % 4 == 0){
                                        $yearIndex++;
                                    }
                                @endphp
                                @endfor
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][project_total]')
                                            ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                <td class="border-right">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][variance]', 0)
                                            ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @for ($i = 0; $i < round(($project->length/4)); $i++)
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][budget]', optional(optional($costItem->claims_data)->yearwise)[$i]->budget ?? 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->required() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][amount]', optional(optional($costItem->claims_data)->yearwise)[$i]->amount ?? 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                <td class="border-right">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][variance]', optional(optional($costItem->claims_data)->yearwise)[$i]->variance ?? 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @endfor
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong>Total Cost(for each item)</strong></td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][total_budget]', 0)
                                            ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $fromDate = clone $project->start_date;
                                    $yearIndex = 0;
                                @endphp
                                @for ($i = 0; $i < $project->length; $i++)
                                @php
                                    $toDate = clone $fromDate;
                                    $toDate->addMonths(2)->endOfMonth();
                                    $labelClass = '';
                                    if(now()->betweenIncluded($fromDate, $toDate)){
                                        $labelClass = 'text-danger';
                                    }
                                @endphp
                                <td class="text-center">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][quarter_values]['.$fromDate->timestamp.']', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control '.$labelClass)
                                            ->attribute('data-year-index', $yearIndex)
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $fromDate->addMonths(3);
                                    if(($i+1) % 4 == 0) {
                                        $yearIndex++;
                                    }
                                @endphp
                                @endfor
                                <td class="text-center">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][project_total]', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                <td class="text-center border-right">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][variance]', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @for ($i = 0; $i < round(($project->length/4)); $i++)
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_budget]', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_amount]', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                <td class="border-right">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_variance]', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong>Total Cost(cumulative)</strong></td>
                                <td>&nbsp;</td>
                                @php
                                    $fromDate = clone $project->start_date;
                                @endphp
                                @for ($i = 0; $i < $project->length; $i++)
                                @php
                                    $toDate = clone $fromDate;
                                    $toDate->addMonths(2)->endOfMonth();
                                    $labelClass = '';
                                    if(now()->betweenIncluded($fromDate, $toDate)){
                                        $labelClass = 'text-danger';
                                    }
                                @endphp
                                <td class="text-center">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">&euro;</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[cumulative]['.$fromDate->timestamp.']', 0)
                                            // ->placeholder('Amount')
                                            ->class('form-control '.$labelClass)
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $fromDate->addMonths(3);
                                @endphp
                                @endfor
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < round(($project->length/4)); $i++)
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        <div id="year-wise-claims">
            {!!$yearwiseHtml!!}
        </div>
        </x-slot>
    </x-backend.card>
@endsection
@push('after-scripts')
    <script>
        function calculateFields() {
            var cumulativeTotal = 0;
            $('.main-claims-table [name^="total_costs[for_each_item][quarter_values]"]').each(function(i, v){
                var rowId = $(v).attr('name').replace('total_costs[for_each_item][quarter_values]', '');
                var total = 0;
                $('.main-claims-table [name$="[quarter_values]'+rowId+'"][name^="claim_values"]').each(function(i1, v1){
                    total += parseFloat($(v1).val());
                });

                $(v).val(total);
                cumulativeTotal += total;
                $('.main-claims-table [name^="total_costs[cumulative]'+rowId+'"]').val(cumulativeTotal);
            });

            $('.main-claims-table [name^="claim_values"][name$="[total_budget]"]').each(function(i, v){
                var total_budget = 0;
                $(v).closest('tr').find('[name*="yearwise"][name$="[budget]"]').each(function(i1, v1){
                    total_budget += parseFloat($(v1).val());
                });

                $(v).val(total_budget);
            });

            $('.main-claims-table [name$="[project_total]"]').each(function(i, v){
                var rowId = $(v).attr('name').replace('[project_total]', '');
                var project_total = 0;
                $(v).closest('tr').find('[name*="'+rowId+'[quarter_values]"]').each(function(i1, v1){
                    project_total += parseFloat($(v1).val());
                });

                $(v).val(project_total);

                var total_budget = $(v).closest('tr').find('[name*="'+rowId+'[total_budget]"]').val();
                $(v).closest('tr').find('[name="'+rowId+'[variance]"]').val(total_budget - project_total)
            });
            
            $('.main-claims-table [name*="[yearwise]"][name$="[amount]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[2];

                var yearWiseTotal = 0;
                $(v).closest('tr').find('[data-year-index="'+yearIndex+'"]').each(function(i1, v1){
                    yearWiseTotal += parseFloat($(v1).val());
                });

                $(v).val(yearWiseTotal);

                // var total_budget = $(v).closest('tr').find('[name*="'+rowId+'[total_budget]"]').val();
                // $(v).closest('tr').find('[name="'+rowId+'[variance]"]').val(total_budget - project_total)
            });
            
            $('.main-claims-table [name*="[yearwise]"][name$="[total_amount]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[2];

                var yearWiseTotal = 0;
                $(v).closest('tr').find('[data-year-index="'+yearIndex+'"]').each(function(i1, v1){
                    yearWiseTotal += parseFloat($(v1).val());
                });

                $(v).val(yearWiseTotal);

                // var total_budget = $(v).closest('tr').find('[name*="'+rowId+'[total_budget]"]').val();
                // $(v).closest('tr').find('[name="'+rowId+'[variance]"]').val(total_budget - project_total)
            });
            
            $('.main-claims-table [name*="[yearwise]"][name$="[variance]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[2];
                var yearBudget = parseFloat($(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][budget]"]').val());
                var yearAmount = parseFloat($(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][amount]"]').val());
                var variance = yearBudget - yearAmount;
                $(v).val(variance);
                if(variance < 0) {
                    $(v).addClass('text-danger');
                }else{
                    $(v).removeClass('text-danger');
                }
            });

            $('.main-claims-table [name^="total_costs[for_each_item][yearwise]"][name$="[total_budget]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[2];
                var total_budget = 0;
                $(v).closest('table.main-claims-table').find('[name$="[yearwise]['+yearIndex+'][budget]"]').each(function(i1, v1){
                    total_budget += parseFloat($(v1).val());
                });
                $(v).val(total_budget);
            });

            $('.main-claims-table [name^="total_costs[for_each_item][yearwise]"][name$="[total_variance]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[2];
                var total = 0;
                $('.main-claims-table [name$="[yearwise]['+yearIndex+'][variance]"').each(function(i1, v1){
                    total += parseFloat($(v1).val());
                });

                $(v).val(total);
            });
            
            for_each_total_budget = 0;
            $('[name^="claim_values"][name$="[total_budget]"]').each(function(i, v) {
                for_each_total_budget += parseFloat($(v).val());
            });
            $('[name="total_costs[for_each_item][total_budget]"]').val(for_each_total_budget)
        }

        function calculateYearwiseFields() {
            var cumulativeTotal = 0;
            var prevYearIndex = 0;
            $('#year-wise-claims [name*="[total_costs][for_each_item][quarter_values]"]').each(function(i, v){
                // var rowId = $(v).attr('name').replace('[total_costs][for_each_item][quarter_values]', '');
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[0];
                var quarterId = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[2];
                var rowId = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[4];
                // console.log(rowId)
                var total = 0;
                if(prevYearIndex != yearIndex) {
                    cumulativeTotal = 0;
                }
                prevYearIndex = yearIndex;
                $(v).closest('table').find('[name^="yearly_data['+yearIndex+'][claim_values]"][name$="[quarter_values]['+rowId+']"]').each(function(i1, v1){
                    total += parseFloat($(v1).val());
                });

                $(v).val(total);
                cumulativeTotal += total;
                $(v).closest('table').find('[name^="yearly_data['+yearIndex+'][total_costs][cumulative]['+rowId+']"]').val(cumulativeTotal);
            });

            $('#year-wise-claims [name$="[for_each_item][project_total]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[0];
                var project_total = 0;
                $(v).closest('tr').find('[name^="yearly_data['+yearIndex+'][total_costs][for_each_item][quarter_values]"]').each(function(i1, v1){
                    project_total += parseFloat($(v1).val());
                });

                $(v).val(project_total);

                var total_budget = $(v).closest('tr').find('[name*="yearly_data['+yearIndex+'][total_costs][for_each_item][total_budget]"]').val();
                $(v).closest('tr').find('[name$="[for_each_item][variance]"]').val(total_budget - project_total)
            });

            $('[name^="yearly_data"][name$="[total_costs][for_each_item][total_budget]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/(?<=\[).*?(?=\])/g)[0];
                for_each_total_budget = 0;
                $('[name^="yearly_data['+yearIndex+'][claim_values]"][name$="[total_budget]"]').each(function(i, v) {
                    for_each_total_budget += parseFloat($(v).val());
                });
                $(v).val(for_each_total_budget);
            });
        }
        $(document).ready(function(){
            calculateFields();
            calculateYearwiseFields();
            formatNegativeValue();

            $('table.main-claims-table input[name*="[quarter_values]"], table.main-claims-table input[name*="[budget]"]').change(function(){
                calculateFields();
                var formData = new FormData($('#claims_form')[0]);
                $.ajax({
                    url: '{{route('admin.claim.project.save.claims', $project)}}',
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(!response.success) {
                            alert(response.message);
                        }
                        else{
                            $('#year-wise-claims').html(response.data.yearwiseHtml)
                            calculateYearwiseFields();
                            formatNegativeValue();
                        }
                    }
                })
            })

            $(document).on('change', 'table input', function(){
                formatNegativeValue();                
            });
        });

        function formatNegativeValue() {
            $('table input').each(function(i, v){
                if(parseFloat($(v).val()) < 0) {
                    $(v).addClass('text-danger');
                }
                else{
                    $(v).removeClass('text-danger');
                }
            })
        }
    </script>
@endpush