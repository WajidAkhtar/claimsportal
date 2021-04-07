<div class="col-sm-12 mt-5">
    <table class="table table-responsive table-borders table-sm" style="overflow-x: auto;">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                    @php
                        $date = clone $startDate;
                        
                        $quarter = $project->quarters()->whereStartTimestamp($startDate->timestamp)->first();
                        $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';
                        
                        $date->addMonths(2)->endOfMonth();
                        
                    @endphp
                    <th class="text-center light-grey-bg" style="background-color: #e9ecef;color: black;">
                        <label class="{{$labelClass}} text-uppercase"> {{strtoupper($startDate->format('My'))}} - {{strtoupper($date->format('My'))}}</label><br>
                        <label class="{{$labelClass}}">Q{{$quarterNo++}}</label>
                    </th>
                    @php
                        $startDate->addMonths(3);
                    @endphp
                @endfor
            </tr>
            <tr class="dark-grey-bg">
                <th style="width:10px;background-color: #3c424d;color: #ffffff;">#</th>
                <th style="width:10px;background-color: #3c424d;color: #ffffff;">COST ITEM</th>
                <th style="width:35px;background-color: #3c424d;color: #ffffff;">DESCRIPTION</th>
                <th style="width:15px;background-color: #3c424d;color: #ffffff;">TOTAL BUDGET</th>
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    $toDate = clone $fromDate;
                    $quarter = $project->quarters()->whereStartTimestamp($fromDate->timestamp)->first();
                    $toDate->addMonths(2)->endOfMonth();

                @endphp
                <th class="text-center" style="width:15px;background-color: #3c424d;color: #ffffff;">
                    @switch($quarter->partner(request()->partner)->pivot->status)
                        @case('current')
                            <label class="current-bg mb-0">CURRENT</label>
                            @break
                        @case('forecast')
                            <label class="mb-0">FORECAST</label>
                            @break
                        @case('historic')
                            <label class="mb-0">HISTORIC</label>
                            @break
                        @default
                            
                    @endswitch
                </th>
                @php
                    $fromDate->addMonths(3);
                @endphp
                @endfor
                <th style="background-color: #3c424d;color: #ffffff;">TOTAL</th>
                <th class="border-right" style="background-color: #3c424d;color: #ffffff;">VARIANCE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_project_total = 0;
                $total_project_variance = 0;
                $total_cumulative_for_each_items = [];
                $overall_total_budget = 0;
            @endphp
            @foreach ($project->costItems as $index => $costItem)
            <tr data-rowid="{{ ($index+1) }}">
                <td style="max-width: 10px;min-width:auto;">{{$index+1}}</td>
                <td>{{$costItem->pivot->cost_item_name}}</td>
                <td>{{$costItem->pivot->cost_item_description}}</td>
                @php
                    $total_budget = optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0;
                    $overall_total_budget+= $total_budget;
                @endphp
                <td style="{{ ($total_budget < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ number_format($total_budget, 2, ".", "") }}</span>
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
                    $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';

                    $toDate->addMonths(2)->endOfMonth();
                    $projectTotal += optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate1->timestamp"} ?? 0;
                    $quarter_value = optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate1->timestamp"} ?? 0.00;
                @endphp
                <td style="{{ ($quarter_value < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ number_format($quarter_value, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                    $fromDate1->addMonths(3);
                @endphp
                @endfor
                @php
                    $total_project_total+= $projectTotal;
                    $total_project_variance+= (optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0) - $projectTotal;
                    $project_variance = (optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0) - $projectTotal;
                @endphp
                <td style="{{ ($projectTotal < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ number_format($projectTotal, 2, ".", "") }}</span>
                    </div>
                </td>
                <td class="border-right" style="{{ ($project_variance < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ number_format($project_variance, 2, ".", "") }}</span>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr class="light-grey-bg">
                <td style="background-color: #e9ecef;color: black;">&nbsp;</td>
                <td style="background-color: #e9ecef;color: black;">&nbsp;</td>
                <td style="background-color: #e9ecef;color: black;"><strong>Total Cost (for each item)</strong></td>
                <td style="background-color: #e9ecef;color: black;{{ ($overall_total_budget < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ number_format($overall_total_budget, 2, ".", "") }}</span>
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
                    $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';

                    $toDate->addMonths(2)->endOfMonth();
                    $total_cost_for_each_item = 0;
                @endphp
                @foreach ($project->costItems as $index => $costItem)
                    @php
                        $total_cost_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate2->timestamp"} ?? 0;
                    @endphp
                @endforeach
                <td class="text-center" style="background-color: #e9ecef;color: black;{{ ($total_cost_for_each_item < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_cost_for_each_item, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                    $fromDate2->addMonths(3);
                @endphp
                @endfor
                <td class="text-center" style="background-color: #e9ecef;color: black;{{ ($total_project_total < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_project_total, 2, ".", "") }}</span>
                    </div>
                </td>
                <td class="text-center border-right" style="background-color: #e9ecef;color: black;{{ ($total_project_variance < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_project_variance, 2, ".", "") }}</span>
                    </div>
                </td>
            </tr>
            <tr class="dark-grey-bg">
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                <td style="background-color: #3c424d;color: #ffffff;"><strong>Total Cost (cumulative)</strong></td>
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                @php
                $fromDate3 = clone $globalFromDate;
                @endphp
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    $toDate = clone $fromDate3;

                    $quarter = $project->quarters()->whereStartTimestamp($fromDate3->timestamp)->first();
                    $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';

                    $toDate->addMonths(2)->endOfMonth();
                    $total_prev_cumulative_for_each_item = 0;
                    $total_cumulative_for_each_item = 0;
                @endphp
                @foreach ($project->costItems as $index => $costItem)
                    @php
                        $total_cumulative_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate3->timestamp"} ?? 0;
                    @endphp
                @endforeach
                @php
                    $total_cumulative_for_each_item = $total_cumulative_for_each_item + ($total_cumulative_for_each_items[$i - 1] ?? 0);
                    $total_cumulative_for_each_items[] = $total_cumulative_for_each_item;
                @endphp

                <td class="text-center" style="background-color: #3c424d;color: #ffffff;{{ ($total_cumulative_for_each_item < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_cumulative_for_each_item, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                    $fromDate3->addMonths(3);
                @endphp
                @endfor
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                <td style="background-color: #3c424d;color: #ffffff;" class="border-right">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>