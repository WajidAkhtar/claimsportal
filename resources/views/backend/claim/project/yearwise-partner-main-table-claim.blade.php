<div class="col-sm-12 mt-5">
    <table class="table table-responsive table-borders table-sm main-claims-table" style="overflow-x: auto;" id="main_claims_table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                @foreach ($project->quarters as $quarter)
                @php
                $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';
                @endphp
                <th class="text-center light-grey-bg" style="background-color: #e9ecef;color: black;">
                    <label class="{{$labelClass}} text-uppercase"> {{ strtoupper($quarter->length) }}</label><br>
                    <label class="{{$labelClass}}">{{$quarter->name}}</label>
                </th>
                @endforeach
            </tr>
            <tr class="dark-grey-bg">
                <th style="width:10px;background-color: #3c424d;color: #ffffff;">#</th>
                <th style="width:10px;background-color: #3c424d;color: #ffffff;">COST ITEM</th>
                <th style="width:35px;background-color: #3c424d;color: #ffffff;">DESCRIPTION</th>
                <th style="width:15px;background-color: #3c424d;color: #ffffff;">TOTAL BUDGET</th>
                @foreach ($project->quarters as $quarter)
                <th class="text-center" style="width:15px;background-color: #3c424d;color: #ffffff;">
                    <label class="{{$quarter->partner(request()->partner)->pivot->status == 'current' ? 'current-bg' : ''}} mb-0">&nbsp;{{strtoupper($quarter->partner(request()->partner)->pivot->status)}}&nbsp;</label>
                </th>
                @endforeach
                <th style="width: 12px;background-color: #3c424d;color: #ffffff;">PROJECT TOTAL</th>
                <th style="width: 12px;background-color: #3c424d;color: #ffffff;" class="border-right">VARIANCE</th>

                @for ($i = 1; $i <= ceil(($project->length/4)); $i++)
                    <th style="width:12px;background-color: #3c424d;color: #ffffff;">YR{{$i}} BUDGET</th>
                    <th style="width:12px;background-color: #3c424d;color: #ffffff;">YR{{$i}} TOTAL</th>
                    <th style="width:12px;background-color: #3c424d;color: #ffffff;">VARIANCE</th>
                    @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $total_project_total = 0;
                $total_project_variance = 0;
                $total_yearly_budget = [];
                $overall_total_budget = 0;
                $total_cumulative_for_each_items = [];
            @endphp
            @foreach ($project->costItems as $index => $costItem)
            <tr data-rowid="{{ ($index+1) }}">
                <td style="max-width: 10px;min-width:auto;">{{$index+1}}</td>
                <td>{{$costItem->pivot->cost_item_name}}</td>
                <td>{{$costItem->pivot->cost_item_description}}</td>
                @php
                    $yearIndex = 0;
                    $total_budget = optional(optional($costItem->claims_data)->yearwise)[$yearIndex]->budget ?? 0;
                    $overall_total_budget+= $total_budget;
                    if($loop->iteration % 4 == 0){
                        $yearIndex++;
                    }
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
                $projectTotal = 0;
                @endphp
                @foreach ($project->quarters as $quarter)
                @php
                
                $isForeCast = 0;
                $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';
                $isForeCast = collect(['current', 'forecast'])->contains($quarter->partner(request()->partner)->pivot->status) ? 1 : 0;
                
                $readOnly = false;
                $quarter_value = optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0.00;
                $projectTotal += $quarter_value;
                $projectVariance = $total_budget - $projectTotal;

                @endphp
                <td style="{{ ($quarter_value < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text {{ $readOnly ? 'readonly' : '' }}">£</span>
                        </div>
                        <span class="input-group-text readonly">{{ number_format($quarter_value, 2, ".", "") }}</span>
                    </div>
                </td>
                
                @endforeach
                @php
                    $total_project_total+= $projectTotal;
                    $total_project_variance+= $projectVariance;
                @endphp
                <td style="{{ ($projectTotal < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($projectTotal, 2 , ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($projectVariance < 0) ? 'color: red;' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($projectVariance, 2 , ".", "") }}</span>
                    </div>
                </td>
                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                    @php
                    $readOnly = false;
                    $yearBudgetReadOnly = false;
                    $yearBudget = optional(optional($data->claims_data[$costItem->id])['yearwise'])[$i]['budget'] ?? 0.00;
                    $yearAmount = optional(optional($data->claims_data[$costItem->id])['yearwise'])[$i]['amount'] ?? 0.00;
                    $yearVariance = optional(optional($data->claims_data[$costItem->id])['yearwise'])[$i]['variance'] ?? 0.00;
                    if(empty($total_yearly_budget[$i])) {
                        $total_yearly_budget[$i] = 0;
                    }
                    if(empty($total_yearly_amount[$i])) {
                        $total_yearly_amount[$i] = 0;
                    }
                    if(empty($total_yearly_variance[$i])) {
                        $total_yearly_variance[$i] = 0;
                    }
                    $total_yearly_budget[$i]+= $yearBudget;
                    $total_yearly_amount[$i]+= $yearAmount;
                    $total_yearly_variance[$i]+= $yearVariance;
                    @endphp
                    <td style="{{ ($yearBudget < 0) ? 'color: red;' : '' }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text {{ $readOnly ? 'readonly' : '' }}">£</span>
                            </div>
                            <span>{{ number_format($yearBudget, 2, ".", "") }}</span>
                        </div>
                    </td>
                    <td style="{{ ($yearAmount < 0) ? 'color: red;' : '' }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text readonly">£</span>
                            </div>
                            <span>{{ number_format($yearAmount, 2, ".", "") }}</span>
                        </div>
                    </td>
                    <td style="{{ ($yearVariance < 0) ? 'color: red;' : '' }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text readonly">£</span>
                            </div>
                            <span>{{ number_format($yearVariance, 2, ".", "") }}</span>
                        </div>
                    </td>
                    @endfor
            </tr>
            @endforeach
            <tr class="light-grey-bg">
                <td style="background-color: #e9ecef;color: black;">&nbsp;</td>
                <td style="background-color: #e9ecef;color: black;">&nbsp;</td>
                <td style="background-color: #e9ecef;color: black;"><strong>Total Cost (for each item)</strong></td>
                <td style="{{ ($overall_total_budget < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ number_format($overall_total_budget, 2, ".", "") }}
                    </div>
                </td>
                @php
                $yearIndex = 0;
                @endphp
                @foreach ($project->quarters as $quarter)
                @php
                $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';
                $total_cost_for_each_item = 0;
                @endphp
               
                @foreach ($project->costItems as $index => $costItem)
                    @php
                        $total_cost_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0.00;
                        
                    @endphp
                @endforeach

                <td class="text-center" style="{{ ($total_cost_for_each_item < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_cost_for_each_item, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                if(($loop->iteration) % 4 == 0) {
                $yearIndex++;
                }
                @endphp
                @endforeach
                <td class="text-center" style="{{ ($total_project_total < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_project_total, 2, ".", "") }}</span>
                    </div>
                </td>
                <td class="text-center border-right" style="{{ ($total_project_variance < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_project_variance, 2, ".", "") }}</span>
                    </div>
                </td>
                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                    <td style="{{ ($total_yearly_budget[$i] < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text readonly">£</span>
                            </div>
                            <span>{{ number_format($total_yearly_budget[$i], 2, ".", "") }}</span>
                        </div>
                    </td>
                    <td style="{{ ($total_yearly_amount[$i] < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text readonly">£</span>
                            </div>
                            <span>{{ number_format($total_yearly_amount[$i], 2, ".", "") }}</span>
                        </div>
                    </td>
                    <td style="{{ ($total_yearly_variance[$i] < 0) ? 'color: red;' : '' }}background-color: #e9ecef;color: black;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text readonly">£</span>
                            </div>
                            <span>{{ number_format($total_yearly_variance[$i], 2, ".", "") }}</span>
                        </div>
                    </td>
                    @endfor
            </tr>
            <tr class="dark-grey-bg">
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                <td style="background-color: #3c424d;color: #ffffff;"><strong>Total Cost (cumulative)</strong></td>
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                @php
                $count = 0; 
                @endphp
                @foreach ($project->quarters as $quarter)
                @php
                $yearIndex = 0;
                $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-danger' : '';
                $total_cumulative_for_each_item = 0;
                @endphp
                @foreach ($project->costItems as $index => $costItem)
                    @php
                        $total_cumulative_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0.00;
                    @endphp
                @endforeach
                @php
                    $total_cumulative_for_each_item = $total_cumulative_for_each_item + ($total_cumulative_for_each_items[$count-1] ?? 0);
                    $total_cumulative_for_each_items[] = $total_cumulative_for_each_item;
                    $count++;
                @endphp
                <td class="text-center" style="background-color: #3c424d;color: #ffffff;{{ ($total_cumulative_for_each_item < 0) ? 'color: red;' : '' }}">
                    <div class="input-group" style="color: #ffffff;">
                        <div class="input-group-prepend" style="color: #ffffff;">
                            <span class="input-group-text readonly" style="color: #ffffff;">£</span>
                        </div>
                        <span>{{ number_format($total_cumulative_for_each_item, 2, ".", "") }}</span>
                    </div>
                </td>
                @php
                if(($loop->iteration) % 4 == 0) {
                $yearIndex++;
                }
                @endphp
                @endforeach
                <td style="background-color: #3c424d;color: #ffffff;">&nbsp;</td>
                <td class="border-right" style="color: #ffffff;">&nbsp;</td>
                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="border-right">&nbsp;</td>
                    @endfor
            </tr>
        </tbody>
    </table>
</div>