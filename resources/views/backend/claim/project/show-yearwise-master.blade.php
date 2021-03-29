@php
    $startDate = clone $project->start_date;
    $fromDate = clone $project->start_date;
    $globalFromDate = clone $fromDate;
    $remainingQuarters = $project->length;
    $quarterNo = 1;
@endphp
@for ($yearIndex = 0; $yearIndex < ceil($project->length/4); $yearIndex++)
<div class="col-sm-12 mt-5">
    <h4>Year {{$yearIndex + 1}} Total</h4>
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
                        $quarter = $project->quarters()->whereStartTimestamp($startDate->timestamp)->first();
                        $labelClass = $quarter->user->status == 'current' ? 'text-danger' : '';
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
                    @switch($quarter->user->status)
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
                <th>TOTAL</th>
                <th class="border-right">VARIANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($project->costItems as $index => $costItem)
            <tr>
                <td style="max-width: 10px;min-width:auto;">{{$index+1}}</td>
                <td>{{$costItem->pivot->cost_item_name}}</td>
                <td>{{$costItem->pivot->cost_item_description}}</td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][total_budget]', $data->claims_data[$costItem->id]['yearwise'][$yearIndex]['budget'] ?? '')
                            ->placeholder('0.00')
                            ->class('form-control')
                            ->readOnly()
                            ->required() }}
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
                    $labelClass = $quarter->user->status == 'current' ? 'text-danger' : '';
                    $toDate->addMonths(2)->endOfMonth();
                    $projectTotal += $data->claims_data[$costItem->id]['quarter_values'][$fromDate1->timestamp] ?? 0;
                @endphp
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][quarter_values]['.$fromDate1->timestamp.']', $data->claims_data[$costItem->id]['quarter_values'][$fromDate1->timestamp] ?? 0.00)
                            ->placeholder('0.00')
                            ->class('form-control '.$labelClass)
                            ->readOnly()
                            ->required() }}
                    </div>
                </td>
                @php
                    $fromDate1->addMonths(3);
                @endphp
                @endfor
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][project_total]', $projectTotal)
                            ->placeholder('0.00')
                            ->class('form-control')
                            ->readOnly()
                            ->required() }}
                    </div>
                </td>
                <td class="border-right">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][claim_values]['.$costItem->id.'][variance]', ((empty($data->claims_data) || (empty($data->claims_data[$costItem->id]['yearwise'][$yearIndex]['budget']))) ? 0 : $data->claims_data[$costItem->id]['yearwise'][$yearIndex]['budget']) - $projectTotal)
                            ->placeholder('0.00')
                            ->class('form-control')
                            ->readOnly()
                            ->required() }}
                    </div>
                </td>
            </tr>
            @endforeach
            <tr class="light-grey-bg">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><strong>Total Cost (for each item)</strong></td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item][total_budget]', 0)
                            ->placeholder('0.00')
                            ->class('form-control')
                            ->readOnly()
                            ->required() }}
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
                    $labelClass = $quarter->user->status == 'current' ? 'text-danger' : '';
                    $labelClass = '';

                    $toDate->addMonths(2)->endOfMonth();
                @endphp
                <td class="text-center">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item][quarter_values]['.$fromDate2->timestamp.']', 0)
                            // ->placeholder('0.00')
                            ->class('form-control '.$labelClass)
                            ->readOnly()
                            ->required() }}
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
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item][project_total]', 0)
                            // ->placeholder('0.00')
                            ->class('form-control')
                            ->readOnly()
                            ->required() }}
                    </div>
                </td>
                <td class="text-center border-right">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text readonly">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][for_each_item][variance]', 0)
                            // ->placeholder('0.00')
                            ->class('form-control')
                            ->readOnly()
                            ->required() }}
                    </div>
                </td>
            </tr>
            <tr class="dark-grey-bg">
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
                    $labelClass = $quarter->user->status == 'current' ? 'text-danger' : '';
                    $labelClass = '';

                    $toDate->addMonths(2)->endOfMonth();
                @endphp
                <td class="text-center" style="color: #fff;">
                    <div class="input-group" style="color: #fff;">
                        <div class="input-group-prepend" style="color: #fff;">
                            <span class="input-group-text readonly" style="color: #fff;">£</span>
                        </div>
                        {{ html()->input('number', 'yearly_data['.$yearIndex.'][total_costs][cumulative]['.$fromDate3->timestamp.']', 0)
                            // ->placeholder('0.00')
                            ->class('form-control '.$labelClass)
                            ->attribute('style', 'color: #fff;')
                            ->readOnly()
                            ->required() }}
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
@php
    $remainingQuarters -= $currentYearQuarters;
    $globalFromDate = clone $startDate;
@endphp
@endfor