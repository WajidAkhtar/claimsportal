@php
    $defaultCellStyle = 'height:15px;';
    $hedingStyle = 'border-bottom: 1px solid #000000;font-weight: bold;';
    $yearlyTableStyleLeft = 'border-left:3px solid black;border-bottom:3px solid #ffffff;';
    $yearlyTableStyleRight = 'border-right:3px solid black;border-bottom:3px solid #ffffff;';
    $yearlyTableStyleBottom = 'border-bottom:3px solid black;';
    $yearlyTableStyleTop = 'border-top:3px solid black;border-bottom:3px solid #ffffff;';
    $textCenterStyle = "text-align: center;";
@endphp
<table>
    @for($i = 0; $i <= 7;$i++)
        <tr>
            <td></td>
        </tr>
    @endfor
</table>
<table>
    <table>
        <tr>
            <td style="{{ $defaultCellStyle }}"></td>
            <td colspan="3" style="background-color: #ffffff;{{ $defaultCellStyle }}"><strong>PROJECT</strong></td>
            <td colspan="3" style="background-color: #ffffff;{{ $defaultCellStyle }}"><strong>PROJECT LEAD</strong></td>
            <td colspan="3" style="background-color: #ffffff;{{ $defaultCellStyle }}"><strong>PARTNER</strong></td>
            <td colspan="3" style="background-color: #ffffff;{{ $defaultCellStyle }}"><strong>PROJECT FUNDER</strong></td>
        </tr>
        <tr>
            <td style="{{ $defaultCellStyle }}"></td>
            <td colspan="3">Name: {{$project->name}}</td>
            <td colspan="3">Name: {{optional($leadUserPartner->invoiceOrganisation)->organisation_name ?? 'N/A'}}</td>
            <td colspan="3">Name: {{optional($partnerAdditionalInfo->invoiceOrganisation)->organisation_name ?? optional($partnerAdditionalInfo->organisation)->organisation_name}}</td>
            <td colspan="3">Name: {{optional($project->funders()->first())->organisation_name ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td style="{{ $defaultCellStyle }}"></td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Code: {{$project->number}}</td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Contact: {{optional($leadUserPartner)->contact ?? 'N/A'}}</td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Contact: {{$partnerAdditionalInfo->contact ?? 'N/A'}}</td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Contact: {{$partnerAdditionalInfo->funder_contact ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Start: {{$project->start_date->format('m-Y')}}</td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Web URL: {{ optional($leadUserPartner)->web_url ?? 'N/A' }}</td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Web URL: {{ $partnerAdditionalInfo->web_url ?? 'N/A' }}</td>
            <td colspan="3" style="{{ $defaultCellStyle }}">Web URL: {{ $partnerAdditionalInfo->funder_web_url ?? 'N/A' }}</td>
        </tr>
    </table>
</table>
<table>
    <tr>
        <td style="{{ $defaultCellStyle }}"></td>
        <td colspan="7" style="{{ $defaultCellStyle }}">
            <strong>PROJECT TOTALS</strong>
        </td>
    </tr>
</table>
<table class="table table-responsive table-borders table-sm main-claims-table" style="overflow-x: auto;" id="main_claims_table">
    <thead>
        <tr>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            @foreach ($project->quarters as $quarter)
            @php
            $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color:red;' : '';
            @endphp
            <th style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $labelClass }}">
                <label class="{{$labelClass ?? ''}} text-uppercase"> {{ strtoupper($quarter->length) }}</label>
            </th>
            @endforeach
            <th></th>
            <th></th>
            <th></th>
            @for ($i = 1; $i <= ceil(($project->length/4)); $i++)
                <th colspan="3" style="{{ $defaultCellStyle }}text-align: center;font-weight: bold;">YEAR {{$i}} BUDGET</th>
                <th></th>
            @endfor
        </tr>
        <tr>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            @foreach ($project->quarters as $quarter)
            @php
            $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color:red;' : '';
            @endphp
            <th style="{{ $defaultCellStyle }} {{ $labelClass }}">
                <label class="{{$labelClass ?? ''}}">{{ strtoupper($quarter->name) }}</label>
            </th>
            @endforeach
        </tr>
        <tr class="dark-grey-bg">
            <th style="{{ $defaultCellStyle }}">&nbsp;</th>
            <th style="width:5px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}text-align: center;">#</th>
            <th style="width:18px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}text-align: center;">COST ITEM</th>
            <th style="width:35px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}">DESCRIPTION</th>
            <th style="width:15px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}text-align: center;">TOTAL BUDGET</th>
            @foreach ($project->quarters as $quarter)
            @php
            $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color:red;' : '';
            @endphp
            <th style="width:15px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $labelClass }}text-align: center;">
                @switch($quarter->partner($partner)->pivot->status)
                @case('current')
                <label class="current-bg mb-0" >&nbsp;CURRENT&nbsp;</label>
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
            @endforeach
            <th style="width: 18px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}">PROJECT TOTAL</th>
            <th style="width: 18px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}">VARIANCE</th>

            <th></th>
            @for ($i = 1; $i <= ceil(($project->length/4)); $i++)
                <th style="width: 15px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $yearlyTableStyleLeft }} {{ $yearlyTableStyleTop }}">YR{{$i}} BUDGET</th>
                <th style="width: 15px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $yearlyTableStyleTop }}">YR{{$i}} TOTAL</th>
                <th style="width: 15px;background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}  {{ $yearlyTableStyleRight }} {{ $yearlyTableStyleTop }}">VARIANCE</th>
                <th></th>
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
        @foreach ($costItems as $index => $costItem)
        @php
            $cellBgStyle = 'background-color: #ffffff;';
            if($index % 2 == 0) {
                $cellBgStyle = 'background-color: #eaeaea;';
            }
        @endphp
        <tr data-rowid="{{ ($index+1) }}">
            <td style="{{ $defaultCellStyle }}"></td>
            <td style="max-width: 10px;min-width:auto;{{ $defaultCellStyle }} {{ $cellBgStyle }}text-align: center;font-weight: bold;">{{$index+1}}</td>
            <td style="{{ $defaultCellStyle }} {{ $cellBgStyle }}text-align: center;font-weight: bold;">{{$costItem->pivot->cost_item_name}}</td>
            <td style="{{ $defaultCellStyle }} {{ $cellBgStyle }}">{{$costItem->pivot->cost_item_description}}</td>
            @php
                $yearIndex = 0;
                $total_budget = 0;
                for ($i = 0; $i < ceil(($project->length/4)); $i++) {
                    if(empty($costItem->claims_data)) {
                        $total_budget+=0;
                    } else {
                        $total_budget+= optional(optional($costItem->claims_data)->yearwise)[$i]->budget ?? 0;
                    }
                }
                $overall_total_budget+= $total_budget;
                if($loop->iteration % 4 == 0){
                    $yearIndex++;
                }
            @endphp
            <td style="{{ ($total_budget < 0) ? 'color: red' : 'black' }};{{ $defaultCellStyle }} {{ $cellBgStyle }} {{ $textCenterStyle }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span class="input-group-text readonly">{{ number_format($total_budget, 2, ".", "") }}</
                </div>
            </td>
            @php
                $projectTotal = 0;
            @endphp
            @php
            $yearIndex = 0;
            @endphp
            @foreach ($project->quarters as $quarter)
            @php
            $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color:red;' : '';
            $quarter_value = optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0.00;
            $projectTotal += $quarter_value;
            $projectVariance = $total_budget - $projectTotal;
            @endphp
            <td style="{{ ($quarter_value < 0) ? 'color: red' : 'black' }};background-color: #ffffff;{{ $defaultCellStyle }} {{ $labelClass }} {{ $cellBgStyle }} {{ $textCenterStyle }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span class="input-group-text readonly">{{ number_format($quarter_value, 2, ".", "") }}
                </div>
            </td>
            @php
            if(($loop->iteration) % 4 == 0){
            $yearIndex++;
            }
            @endphp
            @endforeach
            @php
                $total_project_total+= $projectTotal;
                $total_project_variance+= $projectVariance;
            @endphp
            <td style="{{ ($projectTotal < 0) ? 'color: red' : 'black' }};background-color: #ffffff;{{ $defaultCellStyle }} {{ $cellBgStyle }} {{ $textCenterStyle }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span>{{ number_format($projectTotal, 2 , ".", "") }}</span>
                </div>
            </td>
            <td style="{{ ($projectVariance < 0) ? 'color: red' : 'black' }};background-color: #ffffff;{{ $defaultCellStyle }} {{ $cellBgStyle }} {{ $textCenterStyle }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span>{{ number_format($projectVariance, 2 , ".", "") }}</span>
                </div>
            </td>
            <td></td>
            @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                    @php
                        $readOnly = false;
                        $yearBudgetReadOnly = false;
                        $yearBudget = optional(optional($costItem->claims_data)->yearwise)[$i]->budget ?? 0.00;
                        $yearAmount = optional(optional($costItem->claims_data)->yearwise)[$i]->amount ?? 0.00;
                        $yearVariance = optional(optional($costItem->claims_data)->yearwise)[$i]->variance ?? 0.00;
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
                <td style="{{ ($yearBudget < 0) ? 'color: red' : 'black' }};background-color: #ffffff;{{ $defaultCellStyle }} {{ $cellBgStyle }}{{ $yearlyTableStyleLeft }} {{ $yearlyTableStyleTop }} {{ $textCenterStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">£</span>
                        </div>
                        <span>{{ number_format($yearBudget, 2, ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($yearAmount < 0) ? 'color: red' : 'black' }};background-color: #ffffff;{{ $defaultCellStyle }} {{ $cellBgStyle }}{{ $yearlyTableStyleTop }} {{ $textCenterStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($yearAmount, 2, ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($yearVariance < 0) ? 'color: red' : 'black' }};background-color: #ffffff;{{ $defaultCellStyle }} {{ $cellBgStyle }}{{ $yearlyTableStyleRight }} {{ $yearlyTableStyleTop }} {{ $textCenterStyle }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($yearVariance, 2, ".", "") }}</span>
                    </div>
                </td>
                <td></td>
                @endfor
        </tr>
        @endforeach
        <tr class="light-grey-bg">
            <td></td>
            <td style="border-top: 1px solid black;background-color: #ffffff;">&nbsp;</td>
            <td style="border-top: 1px solid black;background-color: #ffffff;">&nbsp;</td>
            <td style="border-top: 1px solid black;background-color: #DEEAF6;{{ $defaultCellStyle }}border-bottom: 1px solid #000000;"><strong>Total Cost (for each item)</strong></td>
            <td style="{{ ($overall_total_budget < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $textCenterStyle }}border-top: 1px solid black;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span>{{ number_format($overall_total_budget, 2, ".", "") }}</span>
                </div>
            </td>
            @php
            $yearIndex = 0;
            @endphp
            @foreach ($project->quarters as $quarter)
            @php
            $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color:red;' : '';
            $total_cost_for_each_item = 0;
            @endphp
            
            @foreach ($costItems as $index => $costItem)
                @php
                    $total_cost_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0.00;
                @endphp
            @endforeach

            <td style="{{ ($total_cost_for_each_item < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $textCenterStyle }}border-top: 1px solid black;{{ $labelClass }}">
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
            <td style="{{ ($total_project_total < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $textCenterStyle }}border-top: 1px solid black;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span>{{ number_format($total_project_total, 2, ".", "") }}</span>
                </div>
            </td>
            <td style="{{ ($total_project_variance < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $textCenterStyle }}border-top: 1px solid black;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text readonly">£</span>
                    </div>
                    <span>{{ number_format($total_project_variance, 2, ".", "") }}</span>
                </div>
            </td>
            <td></td>
            @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                <td style="{{ ($total_yearly_budget[$i] < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}{{ $yearlyTableStyleLeft }} {{ $yearlyTableStyleTop }} {{ $textCenterStyle }}border-bottom:1px solid black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_yearly_budget[$i], 2, ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($total_yearly_amount[$i] < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}{{ $yearlyTableStyleTop }} {{ $textCenterStyle }}border-bottom:1px solid black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_yearly_amount[$i], 2, ".", "") }}</span>
                    </div>
                </td>
                <td style="{{ ($total_yearly_variance[$i] < 0) ? 'color: red' : 'black' }};background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}{{ $yearlyTableStyleRight }} {{ $yearlyTableStyleTop }} {{ $textCenterStyle }}border-bottom:1px solid black;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        <span>{{ number_format($total_yearly_variance[$i], 2, ".", "") }}</span>
                    </div>
                </td>
                <td></td>
                @endfor
        </tr>
        <tr class="dark-grey-bg">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="background-color: #DEEAF6;{{ $defaultCellStyle }}border-bottom: 1px solid black;"><strong>Total Cost (cumulative)</strong></td>
            <td style="background-color: #DEEAF6;{{ $defaultCellStyle }} {{ $hedingStyle  }}">&nbsp;</td>
            @php
                $count = 0; 
                @endphp
            @foreach ($project->quarters as $quarter)
            @php
            $labelClass = $quarter->partner($partner)->pivot->status == 'current' ? 'color: red;' : '';
            $total_cumulative_for_each_item = 0;
            @endphp
            @foreach ($costItems as $index => $costItem)
                    @php
                        $total_cumulative_for_each_item+= optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0.00;
                    @endphp
                @endforeach
                @php
                    $total_cumulative_for_each_item = $total_cumulative_for_each_item + ($total_cumulative_for_each_items[$count-1] ?? 0);
                    $total_cumulative_for_each_items[] = $total_cumulative_for_each_item;
                    $count++;
                @endphp
            <td style="background-color: #DEEAF6;{{ ($total_cumulative_for_each_item < 0) ? 'color: red' : 'black' }};{{ $defaultCellStyle }} {{ $hedingStyle  }} {{ $labelClass }} {{ $textCenterStyle }}">
                <div class="input-group" style="color: #fff;">
                    <div class="input-group-prepend" style="color: #fff;">
                        <span class="input-group-text readonly" style="color: #fff;">£</span>
                    </div>
                    <span>{{ number_format($total_cumulative_for_each_item, 2, ".", "") }}</span>
                </div>
            </td>
            @endforeach
            <td style="background-color: #DEEAF6;border-bottom:1px solid black;">&nbsp;</td>
            <td style="background-color: #DEEAF6;border-bottom:1px solid black;">&nbsp;</td>
            <td></td>
            @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                <td style="background-color: #DEEAF6;{{ $yearlyTableStyleLeft }} {{ $yearlyTableStyleTop }}border-bottom:1px solid black;">&nbsp;</td>
                <td style="background-color: #DEEAF6;{{ $yearlyTableStyleTop }}border-bottom:1px solid black;">&nbsp;</td>
                <td style="background-color: #DEEAF6;{{ $yearlyTableStyleRight }} {{ $yearlyTableStyleTop }}border-bottom:1px solid black;">&nbsp;</td>
                <td></td>
                @endfor
        </tr>
    </tbody>
</table>

{!!$yearwiseHtml!!}

