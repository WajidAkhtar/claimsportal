<div class="col-sm-12 mt-5">
    <h4>Year {{$yearIndex + 1}} Finance</h4>
    <table class="table table-responsive table-borders table-sm" style="overflow-x: auto;">
        <thead>
            <tr>
                <th>&nbsp;</th>
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
                    <th class="text-center light-grey-bg">
                        <label class="{{$labelClass}} text-uppercase"> {{$startDate->format('My')}} - {{$date->format('My')}}</label><br>
                        <label class="{{$labelClass}}">Q{{$quarterNo++}}</label>
                    </th>
                    @php
                        $startDate->addMonths(3);
                    @endphp
                @endfor
            </tr>
            <tr class="dark-grey-bg">
                <th style="max-width: 10px;min-width:auto;" class="dynamic-calculator"></th>
                <th style="max-width: 10px;min-width:auto;">#</th>
                <th>COST ITEM</th>
                <th>DESCRIPTION</th>
                <th>TOTAL BUDGET</th>
                @for ($i = 0; $i < $currentYearQuarters; $i++)
                @php
                    $toDate = clone $fromDate;
                    $quarter = $project->quarters()->whereStartTimestamp($fromDate->timestamp)->first();
                    $toDate->addMonths(2)->endOfMonth();

                @endphp
                <th class="text-center">
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
                <th>TOTAL</th>
                <th class="border-right">VARIANCE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_project_total = 0;
                $total_project_variance = 0;
                $total_cumulative_for_each_items = [];
            @endphp
            @foreach ($project->costItems as $index => $costItem)
            <tr data-rowid="{{ ($index+1) }}">
                <td style="max-width: 10px;min-width:auto;" class="dynamic-calculator" data-calculationindex="{{ ($index+1) }}">
                </td>
                <td style="max-width: 10px;min-width:auto;">{{$index+1}}</td>
                <td>{{$costItem->pivot->cost_item_name}}</td>
                <td>{{$costItem->pivot->cost_item_description}}</td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0 }}</span>
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
                @endphp
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ optional(optional($costItem->claims_data)->quarter_values)->{"$fromDate1->timestamp"} ?? 0.00 }}</span>
                    </div>
                </td>
                @php
                    $fromDate1->addMonths(3);
                @endphp
                @endfor
                @php
                    $total_project_total+= $projectTotal;
                    $total_project_variance+= (optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0) - $projectTotal;
                @endphp
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ $projectTotal }}</span>
                    </div>
                </td>
                <td class="border-right">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ (optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0) - $projectTotal }}</span>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr class="light-grey-bg">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><strong>Total Cost (for each item)</strong></td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span class="input-group-text readonly">0</span>
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
                @php
                    $total_cumulative_for_each_items[] = $total_cost_for_each_item;
                @endphp
                <td class="text-center">
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
                <td class="text-center">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_project_total, 2, ".", "") }}</span>
                    </div>
                </td>
                <td class="text-center border-right">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_project_variance, 2, ".", "") }}</span>
                    </div>
                </td>
            </tr>
            <tr class="dark-grey-bg">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><strong>Total Cost (cumulative)</strong></td>
                <td>&nbsp;</td>
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
                @endphp
                <td class="text-center">
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
                <td>&nbsp;</td>
                <td class="border-right">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
@dd(1)