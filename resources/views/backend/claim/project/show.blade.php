@extends('backend.layouts.app')

@section('title', __('View Project'))

@push('after-styles')
    <style>
        table tr th, table tr td {
            white-space: nowrap;
            min-width: 150px;
        }
        
        table tr th:first-child, table tr td:first-child {
            min-width: 20px !important;
        }
        table tr th:nth-child(2), table tr td:nth-child(2) {
            min-width: 50px !important;
        }
        
        .light-grey-bg {
            background-color: #e9ecef;
            color: #000;
        }
        
        .dark-grey-bg {
            background-color: #3c424de0;
            color: #fff;
        }
        
        .current-bg {
            /*background-color: #45a164;
            color: #fff;*/
            color: #45a164 !important;
        }

        input:read-only {
            border: 0px;
        }

        .input-group-text.readonly {
            border: 0px;
            padding-right: 2px;
        }
        .quarter-note {
            border-bottom: 1px solid #ebebeb;
            margin-bottom: 10px;
        }
        #table_quarter_notes {
            height: 300px;
            overflow: scroll;
            overflow-x: hidden;
        }
        input.text-success::placeholder {
            color: #45a164 !important;
            font-weight: bold !important;
        }
        span.text-success {
            font-weight: bold !important;   
        }
        .gap {
            background-color: white;
            border: 0px !important;
            border-right: 1px solid #d8dbe0 !important;
            padding: 0px !important;
            min-width: 5mm;
        }
        .input-group {
            padding-left: 22px;
        }
        .input-group input:read-only {
            padding-left: 22px !important;
        }
        .input-group input:read-only.text-success {
            font-weight: bold !important;
        } 

        @media (min-width:961px)  {
            #navSheetActions {
                margin-left: -17em;
            }
            ul.action-nav {
               display: flex;
               flex-direction: row;
               justify-content: flex-start; 
               list-style: none;
               padding: 0;
               white-space: nowrap;
            }
            li.nav-item {
                margin-bottom: 0.2em;
                margin-left: 0.2em;
            }
        }

        ul.action-nav {
            margin-top: 1em;
        }
        li.nav-item {
            margin-bottom: 0.2em;
            list-style: none;
        }
        li.nav-item > button, li.nav-item > a, li.nav-item > form button {
            width: 112px !important;
            height: 33px !important;
        }
        .navbar-toggler {
            margin-top: 1em;
        }
        .navbar-toggler:hover, .navbar-toggler:focus {
            outline: none !important;
        }
        .btn-column-action {
            width: 107px !important;
        }
        .pl-40 {
            padding-left: 40px !important;
        }

    </style>
    <link rel="stylesheet" href="{{asset('assets/backend/vendors/select2/css/select2.css')}}">
@endpush
@section('content')
    <h2 class="page-main-title mt-3">View Project</h2>

    @if($project->userHasPartialAccessToProject())
    <x-backend.card>
            <x-slot name="header">
                @lang('Filter by Partner')
            </x-slot>
            <x-slot name="body">
                <div class="col-sm-12">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <form action="#" id="filter_project_claims_data">
                                <select class="form-control" onchange="this.form.submit()" name="partner">
                                    @php $partnerCount = 1; @endphp
                                    @if($project->userHasFullAccessToProject() || $userHasMasterAccess)
                                        <option value="">PROJECT TOTALS</option>
                                        @foreach($project->allpartners as $partner)
                                            @if($partner->organisation_id != 0 && $partner->organisation_id != NULL)
                                                <option value="{{ $partner->organisation->id ?? 0 }}" {{ (!empty($partner->organisation) && request()->partner == $partner->organisation->id ? 'selected':'') }}>{{ $partner->organisation->organisation_name ?? 'Partener - '.$partnerCount++ }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($sheetUserPermissions as $partner)
                                            @if(auth()->user()->id == $partner->user_id && $partner->partner_id == 0 && $partner->is_master == '1')
                                                <option value="">PROJECT TOTALS</option>
                                            @elseif($partner->partner_id != 0 && $partner->partner_id != NULL && \App\Domains\System\Models\Organisation::find($partner->partner_id)->organisation_name)
                                                <option value="{{ $partner->partner_id ?? 0 }}" {{ (request()->partner == $partner->partner_id ? 'selected':'') }}>{{ \App\Domains\System\Models\Organisation::find($partner->partner_id)->organisation_name ?? 'Partener - '.$partnerCount++ }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </form>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-2">
                            <button class="navbar-toggler pull-right" type="button" style="float: right;">
                            <span class=""><i class="fas fa-bars fa-1x"></i></span></button>
                            <div class="navbar-collapse collapse" id="navSheetActions" style="">
                                <!-- Links -->
                                <ul class="action-nav mr-auto">
                                  <li class="nav-item active">
                                    <button class="btn btn-outline-primary toggle_user_permissions_info togget_action_content"><span class="toggle_action_text_hide"></span> PERMISSIONS</button>
                                  </li>
                                  <li class="nav-item">
                                    <button class="btn btn-outline-primary toggle_partner_additional_info togget_action_content"><span class="toggle_action_text_hide"></span> FINANCE</button></td>
                                  </li>
                                  <li class="nav-item">
                                    <form>
                                        <input type="hidden" name="exportExcel" value="1" />
                                        <input type="hidden" name="partner" value="{{ $sheetOwner }}" />
                                        <button class="btn btn-success" onclick="this.form.submit()">Export Excel</button>
                                    </form>
                                  </li>
                                </ul>
                                <!-- Links -->
                              </div>
                        </div>
                    </div><!--form-group-->
                </div>
            </x-slot>
    </x-backend.card>
    <br />
    @endif

    @if(current_user_role() == 'Developer' || current_user_role() == 'Administrator' || current_user_role() == 'Super User' || current_user_role() == 'Finance Officer' || current_user_role() == 'Project Admin')
    <x-backend.card>
        <x-slot name="header">
            
        </x-slot>        

        <x-slot name="body">
                <div class="action-container">
                    <form method="post" action="" id="partner_additional_info" style="display: none;">
                        {{ html()->input('hidden', 'project_id', $project->id) }}
                        {{ html()->input('hidden', 'sheet_owner', $sheetOwner) }}

                        <h6>FINANCE INFORMATION</h6>
                        <hr />

                        <div class="row">
                            <div class="col">
                                <div class="form-group row"> 
                                    {{ html()->label('Organisation')->for('organisation_id')->class('col-md-4') }}
                                    <div class="col-md-8"> 
                                        {{ html()->select('organisation_id', $organisations, $partnerAdditionalInfo->invoiceOrganisation ?? $partnerAdditionalInfo->organisation)
                                            ->class('form-control additional-info select2')
                                            ->attribute('style', 'width:100%;')
                                            ->disabled()
                                            ->required()
                                         }}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                    {{ html()->label('Office')->for('office_team_name')->class('col-md-4') }}
                                    <div class="col-md-8">
                                        {{ html()->text('office_team_name', $partnerAdditionalInfo->office_team_name ?? '')
                                            ->class('form-control additional-info')
                                            ->required()
                                         }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group row"> 
                                    {{ html()->label('Organisation Type')->for('organisation_type')->class('col-md-4') }}
                                    <div class="col-md-8"> 
                                        {{ html()->select('organisation_type', $organisationTypes, $partnerAdditionalInfo->organisation_type ?? optional(optional($partnerAdditionalInfo->invoiceOrganisation)->organisation_type))
                                            ->class('form-control additional-info')
                                            ->placeholder('Select Organisation Type')
                                            ->required()
                                         }}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                    {{ html()->label('Building Name/No')->for('building_name')->class('col-md-4') }}
                                    <div class="col-md-8">
                                    {{ html()->text('building_name', $partnerAdditionalInfo->building_name ?? optional($partnerAdditionalInfo->invoiceOrganisation)->building_name_no)
                                        ->class('form-control additional-info')
                                        ->required()
                                     }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group row"> 
                                    {{ html()->label('Organisation Role')->for('organisation_role')->class('col-md-4') }}
                                    <div class="col-md-8">
                                    {{ html()->select('organisation_role', $organisationRoles, $partnerAdditionalInfo->organisation_role ?? '')
                                        ->class('form-control additional-info')
                                        ->placeholder('Select Organisation Type')
                                        ->required()
                                     }}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                    {{ html()->label('Address Line 1')->for('street')->class('col-md-4') }}
                                    <div class="col-md-8">
                                        {{ html()->text('street', $partnerAdditionalInfo->street ?? optional($partnerAdditionalInfo->invoiceOrganisation)->street)
                                            ->class('form-control additional-info')
                                            ->required()
                                         }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Finance Contact Name')->for('finance_contact_name')->class('col-md-4') }}
                                    <div class="col-md-8">
                                        {{ html()->text('finance_contact_name', $partnerAdditionalInfo->finance_contact_name ?? '')
                                            ->class('form-control additional-info')
                                         }}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Address Line 2')->for('address_line_2')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('address_line_2', $partnerAdditionalInfo->address_line_2 ?? optional($partnerAdditionalInfo->invoiceOrganisation)->address_line_2)
                                    ->class('form-control additional-info')
                                 }}
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Finance Email')->for('finance_email')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('finance_email', $partnerAdditionalInfo->finance_email ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('City')->for('city')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('city', $partnerAdditionalInfo->city ?? optional($partnerAdditionalInfo->invoiceOrganisation)->city)
                                    ->class('form-control additional-info')
                                 }}
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Finance Tel')->for('finance_tel')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('finance_tel', $partnerAdditionalInfo->finance_tel ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('County')->for('county')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('county', $partnerAdditionalInfo->county ?? optional($partnerAdditionalInfo->invoiceOrganisation)->county)
                                    ->class('form-control additional-info')
                                 }}
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Finance Fax')->for('finance_fax')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('finance_fax', $partnerAdditionalInfo->finance_fax ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Post Code')->for('post_code')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('post_code', $partnerAdditionalInfo->post_code ?? optional($partnerAdditionalInfo->invoiceOrganisation)->postcode)
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group row"> 
                                {{ html()->label('Web URL')->for('web_url')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('web_url', $partnerAdditionalInfo->web_url ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>   
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row"> 
                                {{ html()->label('Project Contact No')->for('contact')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('contact', $partnerAdditionalInfo->contact ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <h6>Banking Details</h6>
                                <hr />
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Account Name')->for('account_name')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('account_name', $partnerAdditionalInfo->account_name ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row"> 
                                {{ html()->label('Customer Ref')->for('customer_ref')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('customer_ref', $partnerAdditionalInfo->customer_ref ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Bank Name')->for('bank_name')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('bank_name', $partnerAdditionalInfo->bank_name ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                            <div class="col">
                                <div class="form-group row">
                                {{ html()->label('Payment Link')->for('payment_link')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('payment_link', $partnerAdditionalInfo->payment_link ?? '')
                                    ->class('form-control additional-info')
                                }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('Bank Address')->for('bank_address')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('bank_address', $partnerAdditionalInfo->bank_address ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                            <div class="col">
                                <div class="form-group row"> 
                                {{ html()->label('VAT No')->for('vat')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('vat', $partnerAdditionalInfo->vat ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group row">
                                {{ html()->label('Sort Code')->for('sort_code')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('sort_code', $partnerAdditionalInfo->sort_code ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row"> 
                                {{ html()->label('EORI No')->for('eori')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('eori', $partnerAdditionalInfo->eori ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group row"> 
                                {{ html()->label('Account Number')->for('account_no')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('account_no', $partnerAdditionalInfo->account_no ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group row"> 
                                {{ html()->label('SWIFT Code')->for('swift')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('swift', $partnerAdditionalInfo->swift ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group row">
                                {{ html()->label('IBAN')->for('iban')->class('col-md-4') }}
                                <div class="col-md-8">
                                {{ html()->text('iban', $partnerAdditionalInfo->iban ?? '')
                                    ->class('form-control additional-info')
                                 }}
                                </div></div>
                            </div>
                        </div>
                    </form>
                    <form method="post" action="" id="user_permissions_info" style="display: none;">
                        {{ html()->input('hidden', 'sheet_owner_for_permission', $sheetOwner) }}
                        <table class="table sheet_user_permissions">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Permission</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="repeatable">
                                @if(!empty($sheetUserPermissions))
                                    @foreach($sheetUserPermissions as $permission)
                                        <tr class="field-group" style="{{ ($permission->user_id == auth()->user()->id) ? 'display: none;' : '' }}">
                                            <td>
                                                {{ html()->select('sheet_user_id[]',  ($permission->user_id == auth()->user()->id) ? [
                                                $permission->user_id => $permission->user_id
                                                ] : $users, $permission->user_id)
                                                    ->class('form-control')
                                                    ->placeholder('Select User')
                                                 }}
                                            </td>
                                            <td>
                                                {{ html()->select('sheet_permission_id[]', $sheetPermissions, $permission->sheet_permission_id)
                                                    ->class('form-control')
                                                    ->placeholder('Select Permission')
                                                 }}
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
                                    <td colspan="4">
                                        <button type="button" class="btn btn-primary btn-sm add"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn-save-sheet-user-permissions btn btn-sm btn-success">SAVE PERMISSIONS</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
        </x-slot>

    </x-backend.card>
    <br />
    @endif

    <script type="text/template" id="sheet-user-permission-template">
        <tr class="field-group">
            <td>
                {{ html()->select('sheet_user_id[]', $users)
                    ->class('form-control')
                    ->placeholder('Select User')
                 }}
            </td>
            <td>
                {{ html()->select('sheet_permission_id[]', $sheetPermissions)
                    ->class('form-control')
                    ->placeholder('Select Permission')
                 }}
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </script>

    <x-backend.card>

        <x-slot name="header">
            &nbsp;
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.claim.project.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <!-- <div class="col-sm-12">
                <h4>Project Profile</h4>
                <div><strong>Project Name:</strong> {{$project->name}}</div>
                <div><strong>Project Code:</strong> {{$project->number}}</div>
                <div><strong>Project Start Date:</strong> {{$project->start_date->format('m-Y')}}</div>
                <div><strong>Funders:</strong> {{ $project->funders->implode('organisation_name', ', ') }}</div>
            </div> -->
            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="col">
                            @if(!empty($project->logo) && file_exists(public_path('uploads/projects/logos/'.$project->logo)))
                                <img src="{{ asset('uploads/projects/logos/'.$project->logo) }}" class="header-project-logo" />
                            @else
                                <img src="{{ asset('uploads/projects/logos/default-logo.png') }}" class="header-project-logo" />
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <div><strong>PROJECT</strong></div>
                            <div>Name: {{$project->name}}</div>
                            <div>Code: {{$project->number}}</div>
                            <div>Start: {{$project->start_date->format('m-Y')}}</div>
                        </div>
                    </div>
                </div>
                @if(!empty($leadUserPartner))
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col">
                                @if (!empty(optional($leadUserPartner->invoiceOrganisation)->logo))
                                    <img src="{{ asset('uploads/organisations/logos/'.optional($leadUserPartner->invoiceOrganisation)->logo) }}" class="header-logo" />
                                @else
                                    <img src="{{ asset('uploads/projects/logos/default-logo.png') }}" class="header-logo" />
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div><strong>PROJECT LEAD</strong></div>
                                <div>Name: {{optional($leadUserPartner->invoiceOrganisation)->organisation_name ?? 'N/A'}}</div>
                                <div>Contact: {{optional($leadUserPartner)->contact ?? 'N/A'}}</div>
                                <div>Web URL: 
                                    @if(optional($leadUserPartner)->web_url) 
                                        <a class="text-primary" href="{{ optional($leadUserPartner)->preety_web_url }}" target="_blank">
                                            {{ optional($leadUserPartner)->web_url }}
                                        </a>
                                    @else 
                                        {{ 'N/A' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty(request()->partner) && !empty($partnerAdditionalInfo))
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col">
                                @if (!empty(optional($partnerAdditionalInfo->invoiceOrganisation)->logo))
                                    <img src="{{ asset('uploads/organisations/logos/'.optional($partnerAdditionalInfo->invoiceOrganisation)->logo) }}" class="header-logo" />
                                @else
                                    <img src="{{ asset('uploads/organisations/logos/'.optional($partnerAdditionalInfo->organisation)->logo) }}" class="header-logo" />
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div><strong>PARTNER</strong></div>
                                <div>Name: {{optional($partnerAdditionalInfo->invoiceOrganisation)->organisation_name ?? optional($partnerAdditionalInfo->organisation)->organisation_name}}</div>
                                <div>Contact: {{$partnerAdditionalInfo->contact ?? 'N/A'}}</div>
                                <div>Web URL: 
                                    @if($partnerAdditionalInfo->web_url) 
                                        <a class="text-primary" href="{{ $partnerAdditionalInfo->preety_web_url }}" target="_blank">
                                            {{ $partnerAdditionalInfo->web_url }}
                                        </a>
                                    @else 
                                        {{ 'N/A' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty($project->funders()))
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col">
                                @if (!empty(optional($project->funders()->first())->logo))
                                    <img src="{{ asset('uploads/organisations/logos/'.optional($project->funders()->first())->logo) }}" class="header-logo" />
                                @else
                                    <img src="{{ asset('uploads/projects/logos/default-logo.png') }}" class="header-logo" />
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div><strong>FUNDER</strong></div>
                                <div>Name: {{optional($project->funders()->first())->organisation_name ?? 'N/A'}}</div>
                                <div>Contact: {{$funderAdditionalInfo->funder_contact ?? 'N/A'}}</div>
                                <div>Web URL: 
                                    @if($funderAdditionalInfo->funder_web_url) 
                                        <a class="text-primary" href="{{ $funderAdditionalInfo->preety_funder_web_url }}" target="_blank">
                                            {{ $funderAdditionalInfo->funder_web_url }}
                                        </a>
                                    @else 
                                        {{ 'N/A' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if(!empty(request()->partner))
            <form action="#" id="claims_form">
                {{ html()->input('hidden', 'sheet_owner', $sheetOwner) }}
                <div class="col-sm-12 mt-5">
                    <table class="table table-responsive table-borders table-sm main-claims-table" style="overflow-x: auto;" id="main_claims_table">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $labelClass = '';
                                    if(!empty($quarter->partner(request()->partner))) {
                                        $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-success' : '';
                                    }
                                @endphp
                                <th class="text-center light-grey-bg">
                                    <label class="{{$labelClass}} text-uppercase"> {{ $quarter->length }}</label><br>
                                    <label class="{{$labelClass}}">{{$quarter->name}}</label>
                                </th>
                                @endforeach
                            </tr>
                            <tr class="dark-grey-bg">
                                <th style="max-width: 10px;min-width:auto;" class="dynamic-calculator"></th>
                                <th style="max-width: 20px;min-width:auto;">#</th>
                                <th>COST ITEM</th>
                                <th>DESCRIPTION</th>
                                <th>TOTAL BUDGET</th>
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $labelClass = '';
                                    $labelStatus = '';
                                    if(!empty($quarter->partner(request()->partner))) {
                                        $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'current-bg' : '';
                                        $labelStatus = strtoupper($quarter->partner(request()->partner)->pivot->status);
                                    }
                                @endphp
                                <th class="text-center">
                                    <label class="{{ $labelClass }} mb-0">&nbsp;{{$labelStatus}}&nbsp;</label>
                                </th>
                                @endforeach
                                <th class="text-center">PROJECT TOTAL</th>
                                <th class="border-right text-center">VARIANCE</th>
                                
                                @for ($i = 1; $i <= ceil(($project->length/4)); $i++)
                                <th class="gap">&nbsp;</th>
                                <th class="text-center">YR{{$i}} BUDGET</th>
                                <th class="text-center">YR{{$i}} TOTAL</th>
                                <th class="border-right text-center">VARIANCE</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->costItems as $index => $costItem)
                            <tr data-rowid="{{ ($index+1) }}">
                                <td style="max-width: 10px;min-width:auto;" class="dynamic-calculator" data-calculationindex="{{ ($index+1) }}">
                                    {{ html()->checkbox('dynamic_calculator_main_'.($index+1))
                                        ->class('dynamic-calculator')
                                        ->attribute('data-parenttable', 'main_claims_table')
                                     }}
                                </td>
                                <td style="max-width: 10px;min-width:auto;">{{$index+1}}</td>
                                <td>{{$costItem->pivot->cost_item_name}}</td>
                                <td>{{$costItem->pivot->cost_item_description}}</td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">£</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][total_budget]')
                                            ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $yearIndex = 0;
                                @endphp
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $isForeCast = 0;
                                    $labelClass = '';
                                    if(!empty($quarter->partner(request()->partner))) {
                                        $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-success' : '';
                                    }
                                    $isForeCast = collect(['current', 'forecast'])->contains(optional(optional($quarter->partner(request()->partner))->pivot)->status) ? 1 : 0;

                                    $readOnly = false;

                                    if($userHasMasterAccess && $userHasMasterAccessWithPermission == 'READ_ONLY') {
                                        $readOnly = true;
                                    }
                                    if($userHasMasterAccess && $userHasMasterAccessWithPermission == 'WRITE_ONLY_FORECAST' && !$isForeCast) {
                                        $readOnly = true;
                                    }
                                    else if($currentSheetUserPermission == 'WRITE_ONLY_FORECAST' && !$isForeCast) {
                                        $readOnly = true;
                                    }
                                    else if($currentSheetUserPermission == 'READ_ONLY') {
                                        $readOnly = true;
                                    }

                                    if(!$userHasMasterAccess && $userHasMasterAccessWithPermission != 'LEAD_USER' && $quarter->partner(request()->partner)->pivot->status == 'current' && $quarter->partner(request()->partner)->pivot->claim_status == 1) {
                                        $readOnly = true;
                                    }
                                @endphp
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text {{ $readOnly ? 'readonly' : '' }} {{ $labelClass }}">£</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][quarter_values]['.$quarter->start_timestamp.']', optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? '')
                                            ->placeholder('0.00')
                                            ->class('form-control '.$labelClass)
                                            ->attribute('data-year-index', $yearIndex)
                                            ->readonly($readOnly)
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    if($loop->iteration % 4 == 0){
                                        $yearIndex++;
                                    }
                                @endphp
                                @endforeach
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">£</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][project_total]')
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
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][variance]')
                                            ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                @php
                                    $readOnly = false;
                                    $yearBudgetReadOnly = false;

                                    if($userHasMasterAccessWithPermission == 'READ_ONLY' || $userHasMasterAccessWithPermission == 'WRITE_ONLY_FORECAST' || $currentSheetUserPermission == 'WRITE_ONLY_FORECAST')
                                        $readOnly = true;
                                    else if($currentSheetUserPermission == 'READ_ONLY')
                                        $readOnly = true;
                                @endphp
                                <td class="gap">&nbsp;</td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text {{ $readOnly ? 'readonly' : '' }}">£</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][budget]', optional(optional($costItem->claims_data)->yearwise)[$i]->budget ?? '')
                                            ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly($readOnly)
                                            ->required() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">£</span>
                                        </div>
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][amount]', optional(optional($costItem->claims_data)->yearwise)[$i]->amount ?? '')
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
                                        {{ html()->input('number', 'claim_values['.$costItem->id.'][yearwise]['.$i.'][variance]', optional(optional($costItem->claims_data)->yearwise)[$i]->variance ?? '')
                                            ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @endfor
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
                                        {{ html()->input('number', 'total_costs[for_each_item][total_budget]')
                                            ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    $yearIndex = 0;
                                @endphp
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $labelClass = '';
                                    if(!empty($quarter->partner(request()->partner))) {
                                        $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-success' : '';
                                    }
                                @endphp
                                <td class="text-center">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly {{ $labelClass }}">£</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][quarter_values]['.$quarter->start_timestamp.']')
                                            // ->placeholder('0.00')
                                            ->class('form-control '.$labelClass)
                                            ->attribute('data-year-index', $yearIndex)
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @php
                                    if(($loop->iteration) % 4 == 0) {
                                        $yearIndex++;
                                    }
                                @endphp
                                @endforeach
                                <td class="text-center">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">£</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][project_total]')
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
                                        {{ html()->input('number', 'total_costs[for_each_item][variance]')
                                            // ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">£</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_budget]')
                                            // ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text readonly">£</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_amount]')
                                            // ->placeholder('0.00')
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
                                        {{ html()->input('number', 'total_costs[for_each_item][yearwise]['.$i.'][total_variance]')
                                            // ->placeholder('0.00')
                                            ->class('form-control')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @endfor
                            </tr>
                            <tr class="dark-grey-bg">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong>Total Cost (cumulative)</strong></td>
                                <td style="color: #fff;">&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $labelClass = '';
                                    if(!empty($quarter->partner(request()->partner))) {
                                        $labelClass = $quarter->partner(request()->partner)->pivot->status == 'current' ? 'text-success' : '';
                                    }
                                @endphp
                                <td class="text-center" style="color: #fff;">
                                    <div class="input-group" style="color: #fff;">
                                        <div class="input-group-prepend" style="color: #fff;">
                                            <span class="input-group-text readonly {{ $labelClass }}" style="color: #fff;">£</span>
                                        </div>
                                        {{ html()->input('number', 'total_costs[cumulative]['.$quarter->start_timestamp.']')
                                            // ->placeholder('0.00')
                                            ->class('form-control '.$labelClass)
                                            ->attribute('style', 'color: #fff;')
                                            ->readOnly()
                                            ->required() }}
                                    </div>
                                </td>
                                @endforeach
                                <td style="color: #fff;">&nbsp;</td>
                                <td class="border-right" style="color: #fff;">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong>PO NUMBER</strong></td>
                                <td>&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $showReadOnly = false;
                                    if(($userHasMasterAccess && !auth()->user()->isMasterAdmin()) || ($currentSheetUserPermission != 'LEAD_USER' && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 2 || optional(optional($quarter->partner(request()->partner))->pivot)->status != 'current')) || ($currentSheetUserPermission == 'LEAD_USER' && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 0 || optional(optional($quarter->partner(request()->partner))->pivot)->status == 'forecast'))) {
                                        $showReadOnly = true;
                                    }
                                    if($canSubmitClaim && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 1)) {
                                        $showReadOnly = false;   
                                    }
                                @endphp
                                <td class="text-center pl-40">
                                    {{ html()->input('text', 'po_number['.$quarter->start_timestamp.']', optional(optional($quarter->partner(request()->partner))->pivot)->po_number)
                                            // ->placeholder('0.00')
                                            ->class('form-control invoice-field')
                                            ->readOnly($showReadOnly) }}
                                </td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong>INVOICE DATE</strong></td>
                                <td>&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $showReadOnly = false;
                                    if(($userHasMasterAccess && !auth()->user()->isMasterAdmin()) || ($currentSheetUserPermission != 'LEAD_USER' && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 2 || optional(optional($quarter->partner(request()->partner))->pivot)->status != 'current')) || ($currentSheetUserPermission == 'LEAD_USER' && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 0 || optional(optional($quarter->partner(request()->partner))->pivot)->status == 'forecast'))) {
                                        $showReadOnly = true;
                                    }
                                    if($canSubmitClaim && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 1)) {
                                        $showReadOnly = false;   
                                    }
                                @endphp
                                <td class="text-center pl-40">
                                    {{ html()->input('text', 'invoice_date['.$quarter->start_timestamp.']', optional(optional($quarter->partner(request()->partner))->pivot)->invoice_date)
                                            // ->placeholder('DD-MM-YYYY')
                                            ->class('form-control invoice-field')
                                            ->readOnly($showReadOnly) }}
                                </td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong>INVOICE NO</strong></td>
                                <td>&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                @php
                                    $showReadOnly = false;
                                    if(($userHasMasterAccess && !auth()->user()->isMasterAdmin()) || ($currentSheetUserPermission != 'LEAD_USER' && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 2 || optional(optional($quarter->partner(request()->partner))->pivot)->status != 'current')) || ($currentSheetUserPermission == 'LEAD_USER' && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 0 || optional(optional($quarter->partner(request()->partner))->pivot)->status == 'forecast'))) {
                                        $showReadOnly = true;
                                    }
                                    if($canSubmitClaim && (optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 1)) {
                                        $showReadOnly = false;   
                                    }
                                @endphp
                                <td class="text-center pl-40">
                                    {{ html()->input('text', 'invoice_no['.$quarter->start_timestamp.']', optional(optional($quarter->partner(request()->partner))->pivot)->invoice_no)
                                            // ->placeholder('0.00')
                                            ->class('form-control invoice-field')
                                            ->readOnly($showReadOnly) }}
                                </td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                <td class="pl-40">
                                    @switch(optional(optional($quarter->partner(request()->partner))->pivot)->status)
                                        @case('historic')
                                            <a target="_blank" href="{{asset('uploads/invoices/'.$quarter->id.'.pdf')}}" class="btn btn-primary btn-column-action" role="button">Invoice</a>
                                            @break
                                        @case('current')
                                            @if (!$userHasMasterAccess && $userHasMasterAccessWithPermission == 'LEAD_USER' && optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 1)
                                            <a href="javascript:void(0)" class="btn btn-success btn-column-action" role="button" onclick="closeClaim(this, {{$quarter->id}}, {{request()->partner}}, {{$quarter->start_timestamp}})">Close Claim</a>
                                            @endif
                                            @if($canSubmitClaim || (((!$userHasMasterAccess && $userHasMasterAccessWithPermission != 'LEAD_USER') || auth()->user()->isMasterAdmin()) && optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 0))
                                            <a href="javascript:void(0)" class="btn btn-success {{optional(optional($quarter->partner(request()->partner))->pivot)->claim_status == 1 && !$canSubmitClaim ? 'disabled' : ''}} btn-column-action" role="button" onclick="submitClaim(this, {{$quarter->id}}, {{$quarter->start_timestamp}})">Submit Claim</a>
                                            @endif
                                            @break
                                        @default
                                    @endswitch
                                </td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                            @if (!$userHasMasterAccess && $userHasMasterAccessWithPermission == 'LEAD_USER')
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                <td class="pl-40">
                                @if( $quarter->partner(request()->partner)->pivot->claim_status == 2)
                                    @switch($quarter->partner(request()->partner)->pivot->status)
                                        @case('historic')
                                            <a href="javascript:void(0)" class="btn btn-secondary btn-column-action" role="button" onclick="closeClaim(this, {{$quarter->id}}, {{request()->partner}}, {{$quarter->start_timestamp}}, true)">Regenerate</a>
                                            @break
                                        @default
                                    @endswitch
                                @endif
                                </td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @endfor
                            </tr>
                            @endif
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @foreach ($project->quarters as $quarter)
                                <td class="pl-40">
                                    @if($quarter->partner(request()->partner)->pivot->status == 'historic' || $quarter->partner(request()->partner)->pivot->status == 'current')
                                        <button type="button" class="btn btn-danger btn-notes btn-column-action" style="width: 107px;" data-quarter="{{ $quarter->id }}" data-toggle="modal" data-target="#projectQuarterNotesModal">Notes ({{ $quarter->notes()->count() }})</button>
                                    @endif
                                </td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td class="border-right">&nbsp;</td>
                                @for ($i = 0; $i < ceil(($project->length/4)); $i++)
                                <td class="gap">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="">&nbsp;</td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <div id="year-wise-claims">
                {!!$yearwiseHtml!!}
            </div>
            @endif
        </x-slot>
    </x-backend.card>

    <div class="modal fade" id="projectQuarterNotesModal" tabindex="-1" role="dialog" aria-labelledby="projectQuarterNotesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="hidden" id="note_quarter_id" name="note_quarter_id" />
                <div class="form-group">
                    <textarea id="note_text" name="note_text" class="form-control" placeholder="Note"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-save-quarter-note">Save Note</button>
                </div>
                <p><strong>Notes</strong></p>
                <div id="table_quarter_notes">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close-quarter-note-modal" data-dismiss="modal">Close</button>
              </div>
            </div>
        </div>
    </div>

@endsection
@push('after-scripts')
    <script src="{{asset('assets/backend/vendors/repeatable/jquery.repeatable.js')}}"></script>
    <script src="{{asset('assets/backend/vendors/select2/js/select2.js')}}"></script>
    <script src="{{asset('assets/backend/vendors/jquery-inputmask/jquery.inputmask.min.js')}}"></script>
    <script>
        function calculateFields() {
            var cumulativeTotal = 0;
            $('.main-claims-table [name^="total_costs[for_each_item][quarter_values]"]').each(function(i, v){
                var rowId = $(v).attr('name').replace('total_costs[for_each_item][quarter_values]', '');
                var total = 0;
                $('.main-claims-table [name$="[quarter_values]'+rowId+'"][name^="claim_values"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    total += parseFloat(value);
                });

                $(v).val(total.toFixed(2));
                cumulativeTotal += total;
                $('.main-claims-table [name^="total_costs[cumulative]'+rowId+'"]').val(cumulativeTotal.toFixed(2));
            });

            $('.main-claims-table [name^="claim_values"][name$="[total_budget]"]').each(function(i, v){
                var total_budget = 0;
                $(v).closest('tr').find('[name*="yearwise"][name$="[budget]"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    total_budget += parseFloat(value);
                });

                $(v).val(total_budget.toFixed(2));
            });

            $('.main-claims-table [name$="[project_total]"]').each(function(i, v){
                var rowId = $(v).attr('name').replace('[project_total]', '');
                var project_total = 0;
                $(v).closest('tr').find('[name*="'+rowId+'[quarter_values]"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    project_total += parseFloat(value);
                });

                $(v).val(project_total.toFixed(2));

                var total_budget = $(v).closest('tr').find('[name*="'+rowId+'[total_budget]"]').val();
                $(v).closest('tr').find('[name="'+rowId+'[variance]"]').val((total_budget - project_total).toFixed(2))
            });
            
            $('.main-claims-table [name*="[yearwise]"][name$="[amount]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[2].toString().replace("[", "").replace("]", "");

                var yearWiseTotal = 0;
                $(v).closest('tr').find('[data-year-index="'+yearIndex+'"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    yearWiseTotal += parseFloat(value);
                });

                $(v).val(yearWiseTotal.toFixed(2));

                // var total_budget = $(v).closest('tr').find('[name*="'+rowId+'[total_budget]"]').val();
                // $(v).closest('tr').find('[name="'+rowId+'[variance]"]').val(total_budget - project_total)
            });
            
            $('.main-claims-table [name*="[yearwise]"][name$="[total_amount]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[2].toString().replace("[", "").replace("]", "");
                
                var yearWiseTotal = 0;
                $(v).closest('tr').find('[data-year-index="'+yearIndex+'"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    yearWiseTotal += parseFloat(value);
                });

                $(v).val(yearWiseTotal.toFixed(2));

                // var total_budget = $(v).closest('tr').find('[name*="'+rowId+'[total_budget]"]').val();
                // $(v).closest('tr').find('[name="'+rowId+'[variance]"]').val(total_budget - project_total)
            });
            
            $('.main-claims-table [name*="[yearwise]"][name$="[variance]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[2].toString().replace("[", "").replace("]", "");
                
                if($(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][budget]"]').val() == '' || isNaN($(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][budget]"]').val())) {
                    yearBudgetValue = 0;
                } else {
                    yearBudgetValue = $(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][budget]"]').val();
                }
                if($(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][amount]"]').val() == '' || isNaN($(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][amount]"]').val())) {
                    yearAmountValue = 0;
                } else {
                    yearAmountValue = $(v).closest('tr').find('[name*="[yearwise]['+yearIndex+'][amount]"]').val();
                }
                var yearBudget = parseFloat(yearBudgetValue);
                var yearAmount = parseFloat(yearAmountValue);
                var variance = yearBudget - yearAmount;
                $(v).val(variance.toFixed(2));
                if(variance < 0) {
                    $(v).addClass('text-danger');
                }else{
                    $(v).removeClass('text-danger');
                }
            });

            $('.main-claims-table [name^="total_costs[for_each_item][yearwise]"][name$="[total_budget]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[2].toString().replace("[", "").replace("]", "");
                var total_budget = 0;
                $(v).closest('table.main-claims-table').find('[name$="[yearwise]['+yearIndex+'][budget]"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    total_budget += parseFloat(value);
                });
                $(v).val(total_budget.toFixed(2));
            });

            var total_project_variance = 0;
            $('table.main-claims-table').find('[name ^="claim_values["][name $="[variance]"]').not('[name*="[yearwise]"]').not('[name*="[for_each_item][variance]"]').each(function(i1, v1) {
                if($(v1).val() == '' || isNaN($(v1).val())) {
                    value = 0;
                } else {
                    value = $(v1).val();
                }
                total_project_variance += parseFloat(value);
                $('[name="total_costs[for_each_item][variance]"]').val(total_project_variance.toFixed(2));
            });

            $('.main-claims-table [name^="total_costs[for_each_item][yearwise]"][name$="[total_variance]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[2].toString().replace("[", "").replace("]", "");
                var total = 0;
                $('.main-claims-table [name$="[yearwise]['+yearIndex+'][variance]"').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    total += parseFloat(value);
                });

                $(v).val(total.toFixed(2));
            });
            
            for_each_total_budget = 0;
            $('[name^="claim_values"][name$="[total_budget]"]').each(function(i, v) {
                if($(v).val() == '' || isNaN($(v).val())) {
                    value = 0;
                } else {
                    value = $(v).val();
                }
                for_each_total_budget += parseFloat(value);
            });
            $('[name="total_costs[for_each_item][total_budget]"]').val(for_each_total_budget.toFixed(2))
        }

        function calculateYearwiseFields() {
            var cumulativeTotal = 0;
            var prevYearIndex = 0;
            $('#year-wise-claims [name*="[total_costs][for_each_item][quarter_values]"]').each(function(i, v){
                // var rowId = $(v).attr('name').replace('[total_costs][for_each_item][quarter_values]', '');
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[0].toString().replace("[", "").replace("]", "");
                var quarterId = $(v).attr('name').match(/\[(.*?)\]/g)[2].toString().replace("[", "").replace("]", "");
                var rowId = $(v).attr('name').match(/\[(.*?)\]/g)[4].toString().replace("[", "").replace("]", "");
                // console.log(rowId)
                var total = 0;
                if(prevYearIndex != yearIndex) {
                    cumulativeTotal = 0;
                }
                prevYearIndex = yearIndex;
                $(v).closest('table').find('[name^="yearly_data['+yearIndex+'][claim_values]"][name$="[quarter_values]['+rowId+']"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    total += parseFloat(value);
                });

                $(v).val(total.toFixed(2));
                cumulativeTotal += total;
                $(v).closest('table').find('[name^="yearly_data['+yearIndex+'][total_costs][cumulative]['+rowId+']"]').val(cumulativeTotal.toFixed(2));
            });

            $('#year-wise-claims [name$="[for_each_item][project_total]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[0].toString().replace("[", "").replace("]", "");
                var project_total = 0;
                $(v).closest('tr').find('[name^="yearly_data['+yearIndex+'][total_costs][for_each_item][quarter_values]"]').each(function(i1, v1){
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    project_total += parseFloat(value);
                });

                $(v).val(project_total.toFixed(2));

                var total_budget = $(v).closest('tr').find('[name*="yearly_data['+yearIndex+'][total_costs][for_each_item][total_budget]"]').val();
                $(v).closest('tr').find('[name$="[for_each_item][variance]"]').val((total_budget - project_total).toFixed(2))
            });

            $('[name ^="yearly_data["][name $="[total_costs][for_each_item][variance]"]').not('[name*="[yearwise]"]').each(function(i, v) {
                var total_project_variance = 0;
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[0].toString().replace("[", "").replace("]", "");
                $('[name^="yearly_data['+yearIndex+'][claim_values]"][name$="[variance]"]').each(function(i1, v1) {
                    if($(v1).val() == '' || isNaN($(v1).val())) {
                        value = 0;
                    } else {
                        value = $(v1).val();
                    }
                    total_project_variance += parseFloat(value);
                });
                $('[name="yearly_data['+yearIndex+'][total_costs][for_each_item][variance]"]').val(total_project_variance.toFixed(2));
            });

            $('[name^="yearly_data"][name$="[total_costs][for_each_item][total_budget]"]').each(function(i, v){
                var yearIndex = $(v).attr('name').match(/\[(.*?)\]/g)[0].toString().replace("[", "").replace("]", "");
                for_each_total_budget = 0;
                $('[name^="yearly_data['+yearIndex+'][claim_values]"][name$="[total_budget]"]').each(function(i, v) {
                    if($(v).val() == '' || isNaN($(v).val())) {
                        value = 0;
                    } else {
                        value = $(v).val();
                    }
                    for_each_total_budget += parseFloat(value);
                });
                $(v).val(for_each_total_budget.toFixed(2));
            });
        }
        $(document).ready(function(){
            calculateFields();
            calculateYearwiseFields();
            formatNegativeValue();
            adjustSheetUsers();
            $('.select2').select2();
            $('[name^="invoice_date"]').inputmask({
                'alias': 'date',
                placeholder: "__/__/____",
            });
            
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
                if($(this).attr('type') == 'number') {
                    var field_row = $(this).closest('tr');
                    $(field_row).find('td:first').find('input:checkbox.dynamic-calculator').trigger('change').trigger('change');
                }
                formatNegativeValue();
            });

            $(document).on('click', 'table input', function(i, v){
                if($(this).val() == '0.00') {
                    $(this).val('');
                }
            });

            var allowToEdit = '{{ $allowToEdit }}';
            if(!allowToEdit) {
                $('table input').each(function() {
                    $(this).attr('disabled', 'disabled');
                    $(this).attr('readonly', 'readonly');
                    $(this).parent().find('.input-group-prepend span').addClass('readonly');
                });
            }

            $(".dynamic-calculator").removeAttr('disabled');

            $(document).on("change", "input:checkbox.dynamic-calculator", function() {
                $('#'+$(this).attr('data-parenttable')).find('.subtotals-row').remove();
                var selector = $('#'+$(this).attr('data-parenttable')).find('input:checkbox.dynamic-calculator:checked:last');
                var calculationIndex = $(selector).parent().attr('data-calculationindex');
                var calculationRow = initiateCalculationRow(selector);
                $(calculationRow).find('td').each(function(crindex, crvalue) {
                    if(crindex > 3) {
                        var field_to_set = $(this);
                        var field_sum = 0;
                        $('#'+$(calculationRow).attr('data-parenttable')).find('tr').each(function(mt, me) {
                            var row = $(this);
                            if($(row).attr('data-rowid') != 'undefined' && parseInt($(row).attr('data-rowid')) <= calculationIndex && $(row).find('td:first').find('input:checkbox.dynamic-calculator').prop('checked')) {
                                var value = parseFloat($(row).find('td:nth('+crindex+')').find('input').val());
                                if(isNaN(value)) {
                                    value = 0;
                                }
                                field_sum+= parseFloat(value);
                            }
                        });
                        var text_class = '';
                        if(isNaN(field_sum)) {
                            field_sum = 0;
                        } else if(field_sum < 0) {
                            text_class = 'text-danger';
                        }

                        var field_html = '<div class="input-group">';
                        field_html+= '<div class="input-group-prepend">';
                        field_html+= '<span class="input-group-text readonly">£</span></div>';
                        field_html+= '<input class="form-control no-border '+text_class+'" disabled value="'+parseFloat(field_sum).toFixed(2)+'"/>';
                        field_html+= '</div>';
                        $(field_to_set).html(field_html);
                    }
                });
            });

            $(".additional-info").on("blur, change", function() {
                var organisation_id = $("#organisation_id.additional-info").val();
                savePartnerAdditionalFields();
            })

            $(".btn-save-sheet-user-permissions").on("click", function() {
                saveSheetUserPermissions();
            })

            $("#partner_additional_info").parent().parent().hide();
            $(".toggle_partner_additional_info").on("click", function() {
                if($(this).find('.toggle_action_text').html() == 'Hide') {
                    $(this).find('.toggle_action_text').html('Show');
                    $("#partner_additional_info").hide();
                    $("#partner_additional_info").parent().parent().hide();
                } else {
                    hideAllActionsContainers();
                    $(this).find('.toggle_action_text').html('Hide');
                    $("#partner_additional_info").show();
                    $("#partner_additional_info").parent().parent().show();
                }
            })
            $(".toggle_user_permissions_info").on("click", function() {
                if($(this).find('.toggle_action_text').html() == 'Hide') {
                    $(this).find('.toggle_action_text').html('Show');
                    $("#user_permissions_info").hide();
                    $("#partner_additional_info").parent().parent().hide();
                } else {
                    hideAllActionsContainers();
                    $(this).find('.toggle_action_text').html('Hide');
                    $("#user_permissions_info").show();
                    $("#partner_additional_info").parent().parent().show();
                }
            })

            $('.sheet_user_permissions .repeatable').repeatable({
                addTrigger: ".sheet_user_permissions .add",
                deleteTrigger: ".sheet_user_permissions .delete",
                template: "#sheet-user-permission-template",
                min: 1,
                prefix: 'ds',
                idStartIndex: '{{ count(old('claim_items') ?? [] ) }}',
                afterAdd : function() {
                    $(".toggle_user_permissions_info").trigger('focus');
                    adjustSheetUsers();
                },
                afterDelete : function() {
                    $(".toggle_user_permissions_info").trigger('focus');    
                }
            });

            $("#projectQuarterNotesModal").on("show.coreui.modal", function(e) {
                var quarterId = $(e.relatedTarget).attr('data-quarter');
                $("#projectQuarterNotesModal").find("input[name='note_quarter_id']").val(quarterId);
                var organisationId = '{{request()->partner}}';
                showLatestQuarterNotes(quarterId, organisationId);
            });

            $(".btn-save-quarter-note").on("click", function() {
                var obj = $(this);
                var prevHtml = $(obj).html();
                var quarterId = $("#projectQuarterNotesModal").find("input[name='note_quarter_id']").val();
                var note = $("#projectQuarterNotesModal").find("textarea[name='note_text']").val();
                if(stringIsEmpty(note)) {
                    return false;
                }
                var organisationId = '{{request()->partner}}';
                $.ajax({
                    url: '{{route('admin.claim.project.save.quarter.note', $project)}}',
                    data: {quarterId: quarterId, organisationId: organisationId, note: note},
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function(){
                        $(obj).html('Please wait....');
                        $(obj).attr('disabled', 'disabled').addClass('disabled');
                    },
                    success: function(response){
                        $(obj).html(prevHtml);
                        if(response.success) {
                            toastr.success(response.message);
                            $("#projectQuarterNotesModal").find("textarea[name='note_text']").val('');
                            $(obj).removeAttr('disabled').removeClass('disabled');
                            showLatestQuarterNotes(quarterId, organisationId);
                        }
                        else{
                            $(obj).removeAttr('disabled').removeClass('disabled');
                            toastr.warning('Something goes wrong!');
                        }
                    }
                })
            });

            $('.navbar-toggler').click(function(){
                $('#navSheetActions').toggle().trigger('showAdded');
            });

            $('#navSheetActions').on('showAdded', function() {
                if($(this).css('display') == 'none') {
                    $("#partner_additional_info").toggle();
                    $("#partner_additional_info").parent().parent().toggle();
                }
            });

            var tabindex = 1;
            $( "input[name^='po_number']" ).each(function(i) {
                var name = $(this).attr('name');
                name = name.toString().replace('po_number', '');
                $(this).attr('tabindex', tabindex++);
                $("input:not([readonly='readonly'])[name='invoice_date"+name+"']").attr('tabindex', tabindex++);
                $("input:not([readonly='readonly'])[name='invoice_no"+name+"']").attr('tabindex', tabindex++);
            });

            $("input:not([readonly='readonly'])[name^=claim_values][name*='[quarter_values]['").each(function(i1, v1){
                var readonly = $(this).attr('readonly');
                if (typeof readonly !== 'undefined' && readonly !== false) {
                    return false;       
                }
                var name = $(this).attr('name');
                var timestamp = name.substr(name.indexOf("[quarter_values]") + 1).replace('quarter_values]', '');
                $(this).attr('tabindex', tabindex++);
                $("input:not([readonly='readonly'])[name^=claim_values][name*='[quarter_values]"+timestamp+"']").attr('tabindex', tabindex++); 
            });

        });

        function initiateCalculationRow(selector) {
            var calculationRow = $(selector).parent().parent( ).clone();
            $(calculationRow).find('td:first').css('max-width', '');
            $(calculationRow).attr('data-parenttable', $(selector).closest('table').attr('id'));
            $(calculationRow).addClass('subtotals-row');
            $(calculationRow).removeAttr('data-rowid');
            $(calculationRow).find('td:first').removeAttr('data-calculationindex').html('<strong>Subtotal</strong>');
            $(calculationRow).find('td:nth(1)').html('-');
            $(calculationRow).find('td:nth(2)').html('-');
            $(calculationRow).find('td:nth(3)').html('-');
            $(calculationRow).find('td input').val('');
            $(calculationRow).insertAfter($(selector).parent().parent());
            formatCalculationRows();
            return calculationRow;
        }

        function formatCalculationRows() {
            var formatClass = 'calculation-row';
            $('table tr').each(function() {
                if($(this).find('td:first').find('input:checkbox.dynamic-calculator').prop('checked')) {
                    $(this).addClass(formatClass);
                } else {
                    $(this).removeClass(formatClass);
                }
            });
        }

        function formatNegativeValue() {
            $('table input:not(.invoice-field)').each(function(i, v){
                if(parseFloat($(v).val()) < 0) {
                    $(v).addClass('text-danger');
                }
                else{
                    $(v).removeClass('text-danger');
                }
                $(v).val(parseFloat($(v).val()).toFixed(2));
            })
        }

        function savePartnerAdditionalFields() {
            var formData = new FormData($('#partner_additional_info')[0]);
            $.ajax({
                    url: '{{route('admin.claim.project.save.partner.additional', $project)}}',
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.success) {
                            toastr.success(response.message);
                        }
                        else{
                            toastr.warning('Something goes wrong...');
                        }
                    }
                })
        }

        function saveSheetUserPermissions() {
            var isError = false;
            $("select[name='sheet_user_id[]']").each(function() {
                if($(this).val() == '') {
                    isError = true;
                    return false;
                }
            });
            $("select[name='sheet_permission_id[]']").each(function() {
                if($(this).val() == '') {
                    isError = true;
                    return false;
                }
            });
            if(isError) {
                toastr.warning('User and Permission must be assigned for giving access to the sheet.');
                return;
            }
            var formData = new FormData($('#user_permissions_info')[0]);
            $.ajax({
                url: '{{route('admin.claim.project.save.sheet.user.permissions', $project)}}',
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(response){
                    if(response.success) {
                        toastr.success(response.message);
                    }
                    else{
                        toastr.warning('Something goes wrong...');
                    }
                }
            })
        }

        $(document).on('change', 'select[name="sheet_user_id[]"]', function() {
            adjustSheetUsers();
        });

        function adjustSheetUsers() {
            var selectedSheetUsers = [];
            $('select[name="sheet_user_id[]').each(function(i, v) {
                var sheetUser = new Array();
                sheetUser['index'] = i;
                sheetUser['value'] = $(this).val();
                selectedSheetUsers.push(sheetUser);
                $(this).find("option").prop("disabled", false);
            });
            $.each(selectedSheetUsers, function(i, v) {
                $('select[name="sheet_user_id[]').each(function(si, sv) {
                    if(si != i) {
                        $(this).find("option[value='"+v['value']+"']").prop("disabled", true);
                    }
                    $(this).find("option[value='']").prop("disabled", false);
                });
            });
        }

        function hideAllActionsContainers() {
            $(".action-container form").each(function() {
                $(this).hide();
            })
            $(".toggle_action_text").each(function() {
                $(this).html('Show');
            });
        }
        
        function submitClaim(element, quarterId, timestamp) {
            var po_number = $('[name="po_number['+timestamp+']"]').val();
            var invoice_no = $('[name="invoice_no['+timestamp+']"]').val();
            var invoice_date = $('[name="invoice_date['+timestamp+']"]').val();
            var organisationId = '{{$sheetOwner}}';
            if(invoice_date.length == 0) {
                toastr.warning('Please fill all invoice related fields!');
                return false;
            }

            $.ajax({
                url: '{{route('admin.claim.project.submit.claim', $project)}}',
                data: {quarterId: quarterId, po_number: po_number, invoice_no: invoice_no, invoice_date: invoice_date, organisationId : organisationId},
                type: 'POST',
                dataType: 'json',
                beforeSend: function(){
                    $(element).html('Please wait....');
                    $(element).attr('disabled', 'disabled').addClass('disabled');
                },
                success: function(response){
                    $(element).html('Submit Claim');
                    if(response.success) {
                        toastr.success(response.message);
                        $(element).attr('disabled', 'disabled').addClass('disabled');
                        $('[name="po_number['+timestamp+']"], [name="invoice_no['+timestamp+']"], [name="invoice_date['+timestamp+']"]').attr('readonly', 'readonly');
                        window.location.href = '{{route("admin.claim.project.show", [$project, "partner" => request()->partner])}}'
                    }
                    else{
                        $(element).removeAttr('disabled').removeClass('disabled');
                        toastr.warning(response.message);
                    }
                }
            })
        }
        
        function closeClaim(element, quarterId, organisationId, timestamp, regenerate = false) {
            var text = $(element).text();

            var po_number = $('[name="po_number['+timestamp+']"]').val();
            var invoice_no = $('[name="invoice_no['+timestamp+']"]').val();
            var invoice_date = $('[name="invoice_date['+timestamp+']"]').val();
            if(po_number.length == 0 || invoice_no.length == 0 || invoice_date.length == 0) {
                toastr.warning('Please fill all invoice related fields!');
                return false;
            }

            $.ajax({
                url: '{{route('admin.claim.project.close.claim', $project)}}',
                data: {quarterId: quarterId, organisationId: organisationId, regenerate: regenerate, po_number: po_number, invoice_no: invoice_no, invoice_date: invoice_date},
                type: 'POST',
                dataType: 'json',
                beforeSend: function(){
                    $(element).html('Please wait....');
                    $(element).attr('disabled', 'disabled').addClass('disabled');
                },
                success: function(response){
                    $(element).html(text);
                    if(response.success) {
                        toastr.success(response.message);
                        $(element).attr('disabled', 'disabled').addClass('disabled');
                        window.location.href = '{{route("admin.claim.project.show", [$project, "partner" => request()->partner])}}'
                    }
                    else{
                        $(element).removeAttr('disabled').removeClass('disabled');
                        toastr.warning(response.message);
                    }
                }
            })
        }

        function showLatestQuarterNotes(quarterId, organisationId) {
            $.ajax({
                url: '{{route('admin.claim.project.get.quarter.notes', $project)}}',
                data: {quarterId: quarterId, organisationId: organisationId},
                type: 'GET',
                dataType: 'json',
                beforeSend: function(){
                    
                },
                success: function(response){
                    if(response.success) {
                        var notes = '';
                        if(response.notes.length == 0) {
                            notes+= '<div>';
                            notes+= 'No notes available.';
                            notes+= '</div>';
                        } else {
                            $.each(response.notes, function(i, v) {
                                var note_number = i+1;
                                notes+= '<div class="quarter-note">';
                                notes+= '<div class="mb-1">';
                                notes+= note_number+". "+v.note;
                                notes+= '</div>';
                                notes+= '<div class="small text-muted mb-1">';
                                notes+= v.user.first_name+' '+v.user.last_name+' | ';
                                if(v.user.organisation != null) {
                                    notes+= v.user.organisation.organisation_name+ ' | ';
                                }
                                notes+= v.created_at;
                                notes+= '</div>';
                                notes+= '</div>';
                            });
                        }
                        notes = notes.replace(/\r?\n/g, '<br />');
                        $("#projectQuarterNotesModal").find("#table_quarter_notes").html(notes);
                    }
                }
            });
        }

        function stringIsEmpty(value) {
            return value ? value.trim().length == 0 : true;
        }

    </script>
@endpush