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

            <div class="col-sm-12 mt-5">
                <table class="table table-responsive table-borders table-sm" style="overflow-x: auto;">
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
                            <td>&euro;{{$costItem->value}}</td>
                            @for ($i = 0; $i < $project->length; $i++)
                            @php
                                // $toDate = clone $fromDate;
                                // $toDate->addMonths(2);
                            @endphp
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'claim_values['.$costItem->id.'][quarter_values]['.$fromDate->timestamp.']', 0)
                                        ->placeholder('Amount')
                                        ->class('form-control')
                                        ->required() }}
                                </div>
                            </td>
                            @php
                                $fromDate->addMonths(3);
                            @endphp
                            @endfor
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'claim_values['.$costItem->id.'][project_total]', 0)
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
                                    {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][budget]', 0)
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
                                    {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][amount]', 0)
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
                                    {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][variance]', 0)
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
                            <td>&euro;{{number_format($project->costItems->sum('value'), 2)}}</td>
                            @php
                                $fromDate = clone $project->start_date;
                            @endphp
                            @for ($i = 0; $i < $project->length; $i++)
                            @php
                                $toDate = clone $fromDate;
                                $toDate->addMonths(2);
                            @endphp
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'total_costs[for_each_item]['.$fromDate->timestamp.']', 0)
                                        // ->placeholder('Amount')
                                        ->class('form-control')
                                        ->readOnly()
                                        ->required() }}
                                </div>
                            </td>
                            @php
                                $fromDate->addMonths(3);
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
                                        <span class="input-group-text">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_budget]', 0)
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
                                $toDate->addMonths(2);
                            @endphp
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'total_costs[cumulative]['.$fromDate->timestamp.']', 0)
                                        // ->placeholder('Amount')
                                        ->class('form-control')
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

            @php
                $startDate = clone $project->start_date;
                $fromDate = clone $project->start_date;
                $remainingQuarters = $project->length;
            @endphp
            @for ($yearIndex = 0; $yearIndex < round($project->length/4); $yearIndex++)
            <div class="col-sm-12 mt-5">
                <h4>Year {{$yearIndex + 1}} Accountintg</h4>
                <table class="table table-responsive table-borders table-sm" style="overflow-x: auto;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            @php
                                // $endDate = (clone $startDate)->addMonths(12);
                                $currentYearQuarters = $remainingQuarters > 4 ? 4 : $remainingQuarters;
                            @endphp
                            {{-- @for ($i = 0; $i < $project->length/4; $i++) --}}
                            @for ($i = 0; $i < $currentYearQuarters; $i++)
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
                            @for ($i = 0; $i < $currentYearQuarters; $i++)
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
                            <td>&euro;{{$costItem->value}}</td>
                            @for ($i = 0; $i < $currentYearQuarters; $i++)
                            @php
                                // $toDate = clone $fromDate;
                                // $toDate->addMonths(2);
                            @endphp
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][quarter_values]['.$fromDate->timestamp.']', 0)
                                        ->placeholder('Amount')
                                        ->class('form-control')
                                        ->readOnly()
                                        ->required() }}
                                </div>
                            </td>
                            @php
                                $fromDate->addMonths(3);
                            @endphp
                            @endfor
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][project_total]', 0)
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
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][variance]', 0)
                                        ->placeholder('Amount')
                                        ->class('form-control')
                                        ->readOnly()
                                        ->required() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><strong>Total Cost(for each item)</strong></td>
                            <td>&euro;{{number_format($project->costItems->sum('value'), 2)}}</td>
                            @php
                                $fromDate = clone $project->start_date;
                            @endphp
                            @for ($i = 0; $i < $currentYearQuarters; $i++)
                            @php
                                $toDate = clone $fromDate;
                                $toDate->addMonths(2);
                            @endphp
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item]['.$fromDate->timestamp.']', 0)
                                        // ->placeholder('Amount')
                                        ->class('form-control')
                                        ->readOnly()
                                        ->required() }}
                                </div>
                            </td>
                            @php
                                $fromDate->addMonths(3);
                            @endphp
                            @endfor
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item][project_total]', 0)
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
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item][variance]', 0)
                                        // ->placeholder('Amount')
                                        ->class('form-control')
                                        ->readOnly()
                                        ->required() }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><strong>Total Cost(cumulative)</strong></td>
                            <td>&nbsp;</td>
                            @php
                                $fromDate = clone $project->start_date;
                            @endphp
                            @for ($i = 0; $i < $currentYearQuarters; $i++)
                            @php
                                $toDate = clone $fromDate;
                                $toDate->addMonths(2);
                            @endphp
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text readonly">&euro;</span>
                                    </div>
                                    {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][cumulative]['.$fromDate->timestamp.']', 0)
                                        // ->placeholder('Amount')
                                        ->class('form-control')
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
                        </tr>
                    </tbody>
                </table>
            </div>
            @php
                $remainingQuarters -= $currentYearQuarters;
            @endphp
            @endfor
        </x-slot>
    </x-backend.card>
@endsection
