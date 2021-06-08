@php
    $startDate = clone $project->start_date;
    $fromDate = clone $project->start_date;
    $globalFromDate = clone $fromDate;
    $remainingQuarters = $project->length;
    $quarterNo = 1;
    $defaultCellStyle = 'height:15px;';
    $hedingStyle = 'border-bottom: 1px solid #000000;font-weight: bold;border-left: 1px thin #DEEAF6;border-right: 1px thin #DEEAF6;';
    $fontBold = 'font-weight: bold;';
@endphp
@for ($yearIndex = 0; $yearIndex < ceil($project->length/4); $yearIndex++)
<table>
    <tr>
        <td style="{{ $defaultCellStyle }}"></td>
        <td colspan="8" style="{{ $defaultCellStyle }}">
            <strong>Year {{$yearIndex + 1}} Finance</strong>
        </td>
    </tr>
</table>
<div class="col-sm-12 mt-5">
    <table class="table table-responsive table-borders table-sm" style="overflow-x: auto;" id="year-reporting-table-{{ ($yearIndex + 1) }}">
        <thead>
            <tr>
                <th>&nbsp;</th>
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
                        $quarter = $project->quarters()->whereStartTimestamp($startDate->timestamp)->first();
                        $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color: red;' : '';
                        $date->addMonths(2)->endOfMonth();
                    @endphp
                    <th class="text-center light-grey-bg" style="background-color: #DEEAF6;{{ $labelClass }}">
                        <label class="{{$labelClass}} text-uppercase"> {{ strtoupper($startDate->format('My')) }} - {{ strtoupper($date->format('My')) }}</label>
                    </th>
                    @php
                        $startDate->addMonths(3);
                    @endphp
                @endfor
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                    <th class="text-center light-grey-bg" style="{{ $labelClass }}">
                        <label class="{{$labelClass}}">Q{{$quarterNo++}}</label>
                    </th>
                @endfor
            </tr>
            <tr class="dark-grey-bg">
                <th>&nbsp;</th>
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}text-align: center;">#</th>
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}text-align: center;">COST ITEM</th>
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}">DESCRIPTION</th>
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}text-align: center;">TOTAL BUDGET</th>
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    $toDate = clone $fromDate;
                    $quarter = $project->quarters()->whereStartTimestamp($fromDate->timestamp)->first();
                    $toDate->addMonths(2)->endOfMonth();
                @endphp
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }} {{ $labelClass }}text-align: center;">
                    @switch($quarter->partner($partner)->pivot->status)
                        @case('current')
                            <label class="current-bg mb-0">&nbsp;CURRENT&nbsp;</label>
                            @break
                        @case('historic')
                            <label class="mb-0">HISTORIC</label>
                            @break
                        @case('forecast')
                            <label class="mb-0">FORECAST</label>
                            @break
                        @default
                            
                    @endswitch
                </th>
                @php
                    $fromDate->addMonths(3);
                @endphp
                @endfor
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}text-align: center;">PROJECT TOTAL</th>
                <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}text-align: center;">VARIANCE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_project_total = 0;
                $total_project_variance = 0;
                $total_cumulative_for_each_items = [];
                $overall_total_budget = 0;
            @endphp
            @foreach ($costItems as $index => $costItem)
            @php
                $cellBgStyle = 'background-color: #ffffff;border-right: 1px solid #ffffff; border-left: 1px solid #ffffff;';
                if($index % 2 == 0) {
                    $cellBgStyle = 'background-color: #eaeaea;border-right: 1px thin #eaeaea; border-left: 1px thin #eaeaea;';
                }
            @endphp
            <tr data-rowid="{{ ($index+1) }}">
                <td></td>
                <td style="font-weight:bold;background-color: #ffffff;{{ $cellBgStyle }}text-align: center;">{{$index+1}}</td>
                <td style="font-weight:bold;background-color: #ffffff;{{ $cellBgStyle }}text-align: center;">{{$costItem->pivot->cost_item_name}}</td>
                <td style="{{ $cellBgStyle }}">{{$costItem->pivot->cost_item_description}}</td>
                @php
                    $total_budget = optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0;
                    $overall_total_budget+= $total_budget;
                @endphp
                <td style="{{ ($total_budget < 0) ? 'color: red;' : '' }}background-color: #ffffff;{{ $cellBgStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($total_budget, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                $fromDate1 = clone $globalFromDate;
                $projectTotal = 0;
                @endphp
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    $toDate = clone $fromDate1;
                    $quarter = $project->quarters()->whereStartTimestamp($fromDate1->timestamp)->first();
                    $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color: red;' : '';
                    $toDate->addMonths(2)->endOfMonth();
                    $quarter_value = optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate1->timestamp"} ?? 0;
                    $projectTotal += $quarter_value;
                    $project_variance = $total_budget - $projectTotal;
                @endphp
                <td style="{{ ($quarter_value < 0) ? 'color: red;' : '' }}background-color: #ffffff;{{ $defaultCellStyle }} {{ $labelClass }}{{ $cellBgStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($quarter_value, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                    $fromDate1->addMonths(3);
                @endphp
                @endfor
                @php
                    $total_project_total+= $projectTotal;
                    $total_project_variance+= $project_variance;
                @endphp
                <td style="{{ ($projectTotal < 0) ? 'color: red;' : '' }}background-color: #ffffff;{{ $defaultCellStyle }} {{ $fontBold }} {{ $labelClass }}{{ $cellBgStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($projectTotal, 2, ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($project_variance < 0) ? 'color: red;' : '' }}background-color: #ffffff;{{ $defaultCellStyle }} {{ $fontBold }} {{ $labelClass }}{{ $cellBgStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($project_variance, 2, ".", "") }}</span>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr class="light-grey-bg">
                <td>&nbsp;</td>
                <td style="border-top: 1px solid #000000;background-color: #ffffff;">&nbsp;</td>
                <td style="border-top: 1px solid #000000;background-color: #ffffff;">&nbsp;</td>
                <td style="border-top: 1px solid #000000;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}border-bottom: 1px solid #000000;"><strong>Total Cost (for each item)</strong></td>
                <td style="{{ ($overall_total_budget < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}border-top: 1px solid #000000;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        {{ number_format($overall_total_budget, 2, ".", "") }}
                    </div>
                </td>
                @php
                $fromDate2 = clone $globalFromDate;
                @endphp
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    // $fromDate2 = clone $globalFromDate;
                    $toDate = clone $fromDate2;

                    $quarter = $project->quarters()->whereStartTimestamp($fromDate2->timestamp)->first();
                    $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color: red;' : '';

                    $toDate->addMonths(2)->endOfMonth();
                    $total_cost_for_each_item = 0;
                @endphp
                @foreach ($costItems as $index => $costItem)
                    @php
                        $total_cost_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate2->timestamp"} ?? 0;
                    @endphp
                @endforeach
                <td style="{{ ($total_cost_for_each_item < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}border-top: 1px solid #000000;{{ $labelClass }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($total_cost_for_each_item, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                    $fromDate2->addMonths(3);
                @endphp
                @endfor
                <td style="{{ ($total_project_total < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}border-top: 1px solid #000000;{{ $labelClass }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($total_project_total, 2, ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($total_project_variance < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $fontBold }}border-top: 1px solid #000000;{{ $labelClass }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly"></span>
                        </div>
                        <span>{{ number_format($total_project_variance, 2, ".", "") }}</span>
                    </div>
                </td>
            </tr>
            <tr class="dark-grey-bg">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}"><strong>Total Cost (cumulative)</strong></td>
                <td style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}{{ $defaultCellStyle }} {{ $hedingStyle  }}">&nbsp;</td>
                @php
                $fromDate3 = clone $globalFromDate;
                @endphp
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    $toDate = clone $fromDate3;

                    $quarter = $project->quarters()->whereStartTimestamp($fromDate3->timestamp)->first();
                    $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color: red;' : '';
                    

                    $toDate->addMonths(2)->endOfMonth();
                    $total_prev_cumulative_for_each_item = 0;
                    $total_cumulative_for_each_item = 0;
                @endphp

                @foreach ($costItems as $index => $costItem)
                    @php
                        $total_cumulative_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate3->timestamp"} ?? 0;
                    @endphp
                @endforeach
                @php
                    $total_cumulative_for_each_item = $total_cumulative_for_each_item + ($total_cumulative_for_each_items[$i - 1] ?? 0);
                    $total_cumulative_for_each_items[] = $total_cumulative_for_each_item;
                @endphp

                <td class="text-center" style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}{{ $labelClass }}">
                    <div class="input-group" style="color: #fff;">
                        <div class="input-group-prepend" style="color: #fff;">
                            <span class="input-group-text readonly" style="color: #fff;">£</span>
                        </div>
                        <span>{{ number_format($total_cumulative_for_each_item, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                    $fromDate3->addMonths(3);
                @endphp
                @endfor
                <td style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}">&nbsp;</td>
                <td style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
@php
    $remainingQuarters -= $currentYearQuarters;
    $globalFromDate = clone $startDate;
@endphp
@endfor