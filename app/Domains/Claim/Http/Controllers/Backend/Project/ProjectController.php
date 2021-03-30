<?php

namespace App\Domains\Claim\Http\Controllers\Backend\Project;

use Illuminate\Http\Request;
use App\Exports\InvoiceExport;
use App\Domains\Auth\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use App\Domains\System\Models\Pool;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;
use Illuminate\Support\Facades\Validator;
use App\Domains\Auth\Services\UserService;
use App\Domains\System\Models\Organisation;
use App\Domains\Claim\Models\ProjectCostItem;
use App\Domains\Claim\Models\ProjectPartners;
use App\Domains\Claim\Services\ProjectService;
use App\Domains\System\Models\SheetPermission;
use App\Domains\System\Models\SheetUserPermissions;
use App\Domains\Claim\Http\Requests\Backend\Project\EditProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\StoreProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\DeleteProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\UpdateProjectRequest;

/**
 * Class ProjectController.
 */
class ProjectController
{
    /**
     * @var ProjectService
     */
    protected $projectService;
    
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * ProjectController constructor.
     *
     * @param  ProjectService  $projectService
     * @param  UserService  $userService
     */
    public function __construct(ProjectService $projectService, UserService  $userService)
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $allowToCreate = TRUE;
        if(in_array(current_user_role(), ['Project Partner', 'Funder'])) {
            $allowToCreate = FALSE;
        }
        return view('backend.claim.project.index')->withAllowToCreate($allowToCreate);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User') && !auth()->user()->hasRole('Finance Officer') && !auth()->user()->hasRole('Project Admin')) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('you have no access to create project.'));
        }
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        $costItems = CostItem::onlyActive()->onlySystemGenerated()->get();
        $pools = current_user_pools()->pluck('full_name', 'id');
        return view('backend.claim.project.create')
            ->withFunders($organisations)
            ->withPartners($organisations)
            ->withCostItems($costItems)
            ->withPools($pools)
            ->withProjectStatuses(Project::statuses());
    }

    /**
     * @param  StoreProjectRequest  $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->store($request->validated());

        return redirect()->route('admin.claim.project.index')->withFlashSuccess(__('The project was successfully created.'));
    }

    /**
     * @param  Project  $project
     *
     * @return mixed
     */
    public function show(Project $project)
    {
        $userHasPartialAccessToProject = $project->userHasPartialAccessToProject();
        if(!$userHasPartialAccessToProject) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('you have no access to this project.'));
        }
        if(!in_array(current_user_role(), ['Administrator', 'Super User']) && !in_array($project->pool_id, current_user_pools()->pluck('id')->toArray())) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('you have no access to this project.'));
        }
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        $allowToEdit = true;
        $users = $project->usersInSamePool(true)->pluck('full_name', 'id');
        $organisationTypes = Organisation::organisationTypes();
        $organisationRoles = Organisation::organisationRoles();

        $sheetPermissions = SheetPermission::pluck('permission', 'id');

        $SheetUserPermissions = SheetUserPermissions::where('project_id', $project->id);
        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User')) {
            $SheetUserPermissions = $SheetUserPermissions->where('user_id', auth()->user()->id);
        }
        $SheetUserPermissions = $SheetUserPermissions->get();

        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User') && count($SheetUserPermissions) >= 1 && empty(request()->partner)) {
            request()->partner = $SheetUserPermissions[0]->partner_id;
        }
        
        if(!empty(request()->partner)) {
            $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('organisation_id', request()->partner)->orderByRaw($project->costItemOrderRaw())->get();
        }

        if(in_array(current_user_role(), ['Administrator', 'Super User'])) {
            $userHasMasterAccess = true;
            $userHasMasterAccessWithPermission = 'READ_WRITE_ALL';
        } else {
            $userHasMasterAccess = false;
            $userHasMasterAccessWithPermission = '';
        }

        if(SheetUserPermissions::where('user_id', auth()->user()->id)->where('project_id', $project->id)->where('is_master', '1')->count() > 0) {
            $userHasMasterAccess = true;
            $userHasMasterAccessWithPermissionId = SheetUserPermissions::where('project_id', $project->id)->where('is_master', '1')->pluck('sheet_permission_id');
            $userHasMasterAccessWithPermission = SheetPermission::find($userHasMasterAccessWithPermissionId)->first()->permission;
            if($userHasMasterAccessWithPermission == 'LEAD_USER'){
                $userHasMasterAccess = false;
            }
        }

        dd($userHasMasterAccess, $userHasMasterAccessWithPermission, auth()->user()->id);

        $leadUser = optional(SheetUserPermissions::where('project_id', $project->id)->where('is_master', '1')->whereHas('sheetPermissions', function($q){
            return $q->wherePermission('LEAD_USER');
        })->first())->user;
        $leadUserPartner = $project->allpartners()->whereNull('organisation_id')->whereIsMaster('1')->first();
        
        $data = [];
        if(empty(request()->partner) && $project->userHasFullAccessToProject()) {
            $costItems = $project->costItems->whereNull('project_cost_items.deleted_at')->whereIn('pivot.organisation_id', $project->allpartners()->pluck('project_partners.organisation_id'))->groupBy('pivot.cost_item_id')->all();
            foreach ($costItems as $key => $costItem) {
                $quarterDates = [];
                if(!empty($costItem)) {
                    foreach($costItem as $ckey => $ciClaimData) {
                        if(!empty($ciClaimData) && !empty($ciClaimData->claims_data)) {
                            $quarterDates = array_keys((array) $ciClaimData->claims_data->quarter_values);
                            break;
                        }
                    }
                }
                if(empty($quarterDates)) {
                    continue;
                }
                foreach ($quarterDates as $key => $timestamp) {
                    $data['claims_data'][$costItem[0]->id]['quarter_values'][$timestamp] = $costItem->pluck('claims_data.quarter_values.'.$timestamp)->sum(); 
                }
            }
            foreach ($costItems as $key => $costItem) {
                $yearwise = [];
                if(!empty($costItem)) {
                    foreach($costItem as $ckey => $ciClaimData) {
                        if(!empty($ciClaimData) && !empty($ciClaimData->claims_data)) {
                            $yearwise = array_keys((array) $ciClaimData->claims_data->yearwise);
                            break;
                        }
                    }
                }
                if(empty($yearwise)) {
                    continue;
                }
                foreach ($yearwise as $key => $yearwise) {
                    $data['claims_data'][$costItem[0]->id]['yearwise'][$key]['budget'] = $costItem->pluck('claims_data.yearwise.'.$key)->sum('budget'); 
                    $data['claims_data'][$costItem[0]->id]['yearwise'][$key]['amount'] = $costItem->pluck('claims_data.yearwise.'.$key)->sum('amount'); 
                    $data['claims_data'][$costItem[0]->id]['yearwise'][$key]['variance'] = $costItem->pluck('claims_data.yearwise.'.$key)->sum('variance'); 
                }
            }
            $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();
            $sheet_owner = (!empty(request()->partner)) ? request()->partner : 0;
            $partnerAdditionalInfo = ProjectPartners::where('project_id', $project->id)->where('is_master', '1')->first();
            $SheetUserPermissions = SheetUserPermissions::where('project_id', $project->id)->where('is_master', '1')->get();

            $data = (object) $data;
            $yearwiseHtml = View::make('backend.claim.project.show-yearwise-master', ['project' => $project, 'data' => $data])->render();
            return view('backend.claim.project.show-master')
            ->withProject($project)
            ->withSheetOwner($sheet_owner)
            ->withPartnerAdditionalInfo($partnerAdditionalInfo)
            ->withOrganisations($organisations)
            ->withSheetPermissions($sheetPermissions)
            ->withSheetUserPermissions($SheetUserPermissions)
            ->withUsers($users)
            ->withData($data)
            ->withOrganisationTypes($organisationTypes)
            ->withOrganisationRoles($organisationRoles)
            ->withAllowToEdit(FALSE)
            ->withyearwiseHtml($yearwiseHtml)
            ->withLeadUser($leadUser)
            ->withLeadUserPartner($leadUserPartner);
        } else {
            $sheet_owner = (!empty(request()->partner)) ? request()->partner : 0;
            $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
            $partnerAdditionalInfo = ProjectPartners::where('project_id', $project->id)->where('organisation_id', $sheet_owner)->first();
            $SheetUserPermissions = SheetUserPermissions::where('project_id', $project->id);
            if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User')) {
                $SheetUserPermissions = $SheetUserPermissions->where('user_id', auth()->user()->id);
            }
            if($sheet_owner != 0 && $project->userHasFullAccessToProject()) {
                $SheetUserPermissions = $SheetUserPermissions->where('partner_id', $sheet_owner); 
            }
            $SheetUserPermissions = $SheetUserPermissions->get();

            if($sheet_owner == 0 && !in_array(current_user_role(), ['Administrator', 'Super User'])) {
                // $SheetUserPermissions = SheetUserPermissions::where('project_id', $project->id)->where('user_id', auth()->user()->id)->get();
            }

            $users = $project->partnersInSamePool()->pluck('full_name', 'id');
            $sheetPermissions = SheetPermission::whereIn('permission', ['WRITE_ONLY_FORECAST', 'READ_ONLY'])->pluck('permission', 'id');
            foreach($project->usersWithPermissions($sheet_owner)->get() as $key => $partner) {
                if($sheet_owner == 0 && $key == 0) {
                    $sheet_owner = $partner->partner_id;
                    break;
                }
            }
            $currentSheetUserPermission = '';
            if($sheet_owner != 0) {
                $currentSheetUserPermissionId = SheetUserPermissions::where('project_id', $project->id)->where('user_id', auth()->user()->id)->where('partner_id', $sheet_owner)->first();
                if(!empty($currentSheetUserPermissionId)) {
                    $currentSheetUserPermission = SheetPermission::find($currentSheetUserPermissionId->sheet_permission_id)->permission;
                } else {
                    $currentSheetUserPermissionId = SheetUserPermissions::where('project_id', $project->id)->where('user_id', auth()->user()->id)->where('is_master', '1')->first();
                    if(!empty($currentSheetUserPermissionId)) {
                        $currentSheetUserPermission = SheetPermission::find($currentSheetUserPermissionId->sheet_permission_id)->permission;
                    }
                }
                if($currentSheetUserPermission == 'READ_ONLY') {
                    $allowToEdit = FALSE;
                }
            }
            $project->costItems = $project->costItems()->where('organisation_id', $sheet_owner)->whereNull('project_cost_items.deleted_at')->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();  

            return view('backend.claim.project.show')
            ->withProject($project)
            ->withSheetOwner($sheet_owner)
            ->withOrganisation(($sheet_owner != 0) ? Organisation::find($sheet_owner) : NULL)
            ->withPartnerAdditionalInfo($partnerAdditionalInfo)
            ->withOrganisations($organisations)
            ->withAllowToEdit($allowToEdit)
            ->withUsers($users)
            ->withSheetPermissions($sheetPermissions)
            ->withOrganisationTypes($organisationTypes)
            ->withSheetUserPermissions($SheetUserPermissions)
            ->withOrganisationRoles($organisationRoles)
            ->withUserHasMasterAccess($userHasMasterAccess)
            ->withUserHasMasterAccessWithPermission($userHasMasterAccessWithPermission)
            ->withCurrentSheetUserPermission($currentSheetUserPermission)
            ->withyearwiseHtml($yearwiseHtml)
            ->withLeadUser($leadUser)
            ->withLeadUserPartner($leadUserPartner);
        }
    }

    /**
     * @param  EditProjectRequest  $request
     * @param  Project  $project
     *
     * @return mixed
     */
    public function edit(EditProjectRequest $request, Project $project)
    {
        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User') && !auth()->user()->hasRole('Finance Officer') && !auth()->user()->hasRole('Project Admin')) {
            
        }
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        $costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->whereNotNull('cost_item_description')->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        $pools = current_user_pools()->pluck('full_name', 'id');
        return view('backend.claim.project.edit')
            ->withProject($project)
            ->withFunders($organisations)
            ->withPartners($organisations)
            ->withCostItems($costItems)
            ->withPools($pools)
            ->withProjectStatuses(Project::statuses())
            ->withOrganisations($organisations);
    }

    /**
     * @param  UpdateProjectRequest  $request
     * @param  Project  $project
     *
     * @return mixed
     * @throws \Throwable
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->projectService->update($project, $request->validated());

        return redirect()->route('admin.claim.project.index')->withFlashSuccess(__('The project was successfully updated.'));
    }

    /**
     * @param  DeleteProjectRequest  $request
     * @param  Project  $project
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(DeleteProjectRequest $request, Project $project)
    {
        $this->projectService->delete($project);

        return redirect()->route('admin.claim.project.index')->withFlashSuccess(__('The project was successfully deleted.'));
    }
    
    /**
     * @param  Project  $project
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function saveClaims(Request $request, Project $project)
    {
        foreach($request->claim_values as $costItemId => $claimValue) {
            $claimValue['total_budget'] = number_format($claimValue['total_budget'], 2, '.', '');
            $claimValue['project_total'] = number_format($claimValue['project_total'], 2, '.', '');
            $claimValue['variance'] = number_format($claimValue['variance'], 2, '.', '');
            foreach($claimValue['quarter_values'] as $key => &$claimQuarterValue) {
                $claimQuarterValue = number_format($claimQuarterValue, 2, '.', '');
            }
            foreach($claimValue['yearwise'] as $key => &$claimYearwiseValue) {
                $claimYearwiseValue['amount'] = number_format($claimYearwiseValue['amount'], 2, '.', '');
                $claimYearwiseValue['variance'] = number_format($claimYearwiseValue['variance'], 2, '.', '');
            }
            // $costItem = $project->costItems()->whereId($costItemId)->first();
            $costItem = ProjectCostItem::whereProjectId($project->id)->whereCostItemId($costItemId)->whereOrganisationId($request->sheet_owner)->first();
            $costItem->claims_data = collect($claimValue)->only('quarter_values', 'yearwise', 'total_budget')->toArray();
            $costItem->save();
        }

        $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('organisation_id', $request->sheet_owner)->orderByRaw($project->costItemOrderRaw())->get();

        $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
        return response()->json(['success' => 1, 'message' => 'Data saved successfully!', 'data' => ['yearwiseHtml' => $yearwiseHtml]]);
    }

    public function savePartnerAdditionalFields(Request $request, Project $project) {
        $data = [
            'invoice_organisation_id' => $request->organisation_id,
            'organisation_type' => $request->organisation_type,
            'organisation_role' => $request->organisation_role,
            'office_team_name' => $request->office_team_name,
            'building_name' => $request->building_name,
            'street' => $request->street,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'county' => $request->county,
            'post_code' => $request->post_code,
            'finance_contact_name' => $request->finance_contact_name,
            'finance_email' => $request->finance_email,
            'finance_tel' => $request->finance_tel,
            'finance_fax' => $request->finance_fax,
            'vat' => $request->vat,
            'eori' => $request->eori,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'bank_address' => $request->bank_address,
            'sort_code' => $request->sort_code,
            'account_no' => $request->account_no,
            'swift' => $request->swift,
            'iban' => $request->iban,
            'web_url' => $request->web_url,
            'funder_id' => $request->funder_id,
            'funder_office' => $request->funder_office,
            'funder_building_name' => $request->funder_building_name,
            'funder_address_line_1' => $request->funder_address_line_1,
            'funder_address_line_2' => $request->funder_address_line_2,
            'funder_city' => $request->funder_city,
            'funder_county' => $request->funder_county,
            'funder_post_code' => $request->funder_post_code,
            'customer_ref' => $request->customer_ref
        ];
        if(!empty($request->is_master) && $request->is_master == 1) {
            $isSaved = ProjectPartners::where('is_master', '1')->where('project_id', $project->id)->update($data);
        } else {
            $isSaved = ProjectPartners::where('organisation_id', $request->sheet_owner)->where('project_id', $project->id)->update($data);
        }
        return response()->json(['success' => $isSaved, 'message' => 'Data saved successfully!']);
    }

    public function saveSheetUserPermissions(Request $request, Project $project) {
        if(!empty($request->sheet_user_id)) {
            SheetUserPermissions::where('partner_id', $request->sheet_owner_for_permission)->where('project_id', $project->id)->delete();
            foreach($request->sheet_user_id as $key => $user_id) {
                if(!empty($project->id) && !empty($user_id) && !empty($request->sheet_permission_id[$key])) {
                    if(!empty($request->is_master) && $request->is_master == 1) {
                        $partner_id = 0;
                        $is_master = '1';
                    } else {
                        $partner_id = $request->sheet_owner_for_permission;
                        $is_master = '0';
                    }
                    SheetUserPermissions::where('is_master', $is_master)->where('project_id', $project->id)->where('user_id', $user_id)->where('partner_id', $partner_id)->delete();
                    SheetUserPermissions::where('project_id', $project->id)->where('user_id', $user_id)->where('is_master', '1')->delete();
                    $created = SheetUserPermissions::create([
                        'partner_id' => $partner_id,
                        'is_master' => $is_master,
                        'project_id' => $project->id,
                        'user_id' => $user_id,
                        'sheet_permission_id' => $request->sheet_permission_id[$key],
                    ]);
                }
            }
            return response()->json(['success' => TRUE, 'message' => 'Sheet users & permissions saved successfully!']);   
        } else {
            if(!empty($request->is_master) && $request->is_master == 1) {
                SheetUserPermissions::where('partner_id', $request->sheet_owner_for_permission)->where('project_id', $project->id)->where('is_master', '1')->delete();
                return response()->json(['success' => TRUE, 'message' => 'Master Sheet permissions cleared successfully.']);
            } else {
                SheetUserPermissions::where('partner_id', $request->sheet_owner_for_permission)->where('project_id', $project->id)->where('is_master', '0')->delete();
                return response()->json(['success' => TRUE, 'message' => 'Sheet users & permissions cleared successfully.']);
            }
        }
    }

    /**
     * @param Request $request
     * @param Project $project
     */
    public function submitClaim(Request $request, Project $project) {
        $validator = Validator::make($request->all(), [
            'quarterId' => 'required|exists:project_quarters,id',
            'organisationId' => 'required|exists:organisations,id',
            'po_number' => 'required',
            'invoice_no' => 'required',
            'invoice_date' => 'required|date_format:Y-m-d',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $quarter = $project->quarters()->whereId($request->quarterId)->first();
        $quarterPartner = $quarter->partner($request->organisationId);
        if($quarterPartner->pivot->claim_status == 1) {
            return response()->json([
                'success' => 0,
                'message' => 'You already submitted claim for this quarter'
            ]);
        }

        $quarterPartner->pivot->po_number = $request->po_number;
        $quarterPartner->pivot->invoice_no = $request->invoice_no;
        $quarterPartner->pivot->invoice_date = $request->invoice_date;
        $quarterPartner->pivot->claim_status = 1;
        $quarterPartner->pivot->save();

        return response()->json(['success' => 1, 'message' => 'Claim submitted successfully!']);
    }
    
    /**
     * @param Request $request
     * @param Project $project
     */
    public function closeClaim(Request $request, Project $project) {
        $validator = Validator::make($request->all(), [
            'quarterId' => 'required|exists:project_quarters,id',
            'organisationId' => 'required|exists:organisations,id',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $quarter = $project->quarters()->whereId($request->quarterId)->first();
        $quarterPartner = $quarter->partner($request->organisationId);
        if($quarterPartner->pivot->claim_status == 2 && !$request->regenerate) {
            return response()->json([
                'success' => 0,
                'message' => 'You already closed claim for this quarter'
            ]);
        }

        // Generate PDF
        $quarterId = $request->quarterId;
        $organisationId = $request->organisationId;
        // $quarter = $project->quarters()->whereId($quarterId)->first();
        // $quarterPartner = $quarter->partner($organisationId);

        // Invoice To Lead
        $invoiceFrom = Organisation::findOrFail($organisationId);
        $invoiceFromPartner = $project->allpartners()->whereOrganisationId($organisationId)->first();
        $invoiceTo = auth()->user()->organisation;
        $invoiceToPartner = $project->allpartners()->whereNull('organisation_id')->whereIsMaster('1')->first();
        if(empty($invoiceFromPartner) || (!empty($invoiceFromPartner) && empty($invoiceFromPartner->invoiceOrganisation))) {
            return response()->json([
                'success' => 0,
                'message' => 'Finance information for '.$invoiceFrom->organisation_name.' is not available'
            ]);
        }
        
        if(empty($invoiceToPartner) || (!empty($invoiceToPartner) && empty($invoiceToPartner->invoiceOrganisation))) {
            return response()->json([
                'success' => 0,
                'message' => 'Pleae fill up your finance information',
            ]);
        }

        $this->generateAndSaveInvoice('', $project, $organisationId, $quarter, $quarterPartner, $invoiceFrom, $invoiceFromPartner, $invoiceTo, $invoiceToPartner);
        
        // Invoice To Funder
        $invoiceFrom = auth()->user()->organisation;
        $invoiceFromPartner = $project->allpartners()->whereNull('organisation_id')->whereIsMaster('1')->first();
        $invoiceTo = $invoiceFrom;
        $invoiceToPartner = $invoiceFromPartner;

        $this->generateAndSaveInvoice('lead', $project, $organisationId, $quarter, $quarterPartner, $invoiceFrom, $invoiceFromPartner, $invoiceTo, $invoiceToPartner);
        
        $quarterPartner->pivot->status = 'historic';
        $quarterPartner->pivot->claim_status = 2;
        $quarterPartner->pivot->save();
        
        // Next Quarter
        if(!$request->regenerate) {
            $nextQuarter = $project->quarters()->where('id', '>', $quarter->id)->first();
            $nextQuarterPartner = $nextQuarter->partner($request->organisationId);
            $nextQuarterPartner->pivot->status = 'current';
            $nextQuarterPartner->pivot->save();
        }

        $message = ($request->regenerate)? 'Invoice regenerated successfully' : 'Claim closed successfully!';
        return response()->json(['success' => 1, 'message' => $message]);
    }

    protected function generateAndSaveInvoice($invoiceNamePrefix = '', $project, $organisationId, $quarter, $quarterPartner, $invoiceFrom, $invoiceFromPartner, $invoiceTo, $invoiceToPartner)
    {
        $invoiceItems = [];
        $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('organisation_id', $organisationId)->orderByRaw($project->costItemOrderRaw())->get();
        foreach ($project->costItems as $index => $costItem) {
            $invoiceItems[$index]['item_name'] = $costItem->pivot->cost_item_name;
            $invoiceItems[$index]['item_description'] = $costItem->pivot->cost_item_description;
            $invoiceItems[$index]['item_price'] = optional(optional($costItem->claims_data)->quarter_values)->{"$quarter->start_timestamp"} ?? 0;
            $invoiceItems[$index]['vat_perc'] = 0;
        }

        $pdf = PDF::loadView('backend.claim.project.invoice', [
            'quarter' => $quarter,
            'quarterPartner' => $quarterPartner,
            'invoiceItems' => json_decode(json_encode($invoiceItems)),
            'invoiceTo' => $invoiceTo,
            'invoiceToPartner' => $invoiceToPartner,
            'invoiceFrom' => $invoiceFrom,
            'invoiceFromPartner' => $invoiceFromPartner,
        ]);
        $pdf->save(public_path('uploads/invoices/'.($invoiceNamePrefix ? $invoiceNamePrefix.'-' : '').$quarter->id.'.pdf'));

        return true;
    }

    /**
     * @param Request $request
     * @param Project $project
     */
    public function generateInvoiceForMastersheet(Request $request, Project $project) {
        $validator = Validator::make($request->all(), [
            'quarterId' => 'required|exists:project_quarters,id',
            'po_number' => 'required',
            'invoice_no' => 'required',
            'invoice_date' => 'required|date_format:Y-m-d',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $quarter = $project->quarters()->whereId($request->quarterId)->first();
        $quarterPartner = $quarter->user;
        if($quarterPartner->status == 'historic' && !$request->regenerate) {
            return response()->json([
                'success' => 0,
                'message' => 'You already generated invoice for this quarter'
            ]);
        }

        // Generate PDF
        $quarterId = $request->quarterId;
        $organisationId = $request->organisationId;
        // $quarter = $project->quarters()->whereId($quarterId)->first();
        // $quarterPartner = $quarter->partner($organisationId);

        // Invoice From Lead To Funder
        $invoiceFrom = auth()->user()->organisation;
        $invoiceFromPartner = $project->allpartners()->whereNull('organisation_id')->whereIsMaster('1')->first();
        
        if(empty($invoiceFromPartner) || (!empty($invoiceFromPartner) && empty($invoiceFromPartner->invoiceOrganisation))) {
            return response()->json([
                'success' => 0,
                'message' => 'Finance information for master sheet is not available'
            ]);
        }

        $response = $this->generateAndSaveMasterInvoice($project, $quarter, $quarterPartner, $invoiceFrom, $invoiceFromPartner);
        if($response['success'] == 0) {
            return response()->json($response);
        }

        $quarterPartner->status = 'historic';
        $quarterPartner->po_number = $request->po_number;
        $quarterPartner->invoice_no = $request->invoice_no;
        $quarterPartner->invoice_date = $request->invoice_date;
        $quarterPartner->save();
        
        // Next Quarter
        if(!$request->regenerate) {
            $nextQuarter = $project->quarters()->where('id', '>', $quarter->id)->first();
            $nextQuarterPartner = $nextQuarter->user;
            $nextQuarterPartner->status = 'current';
            $nextQuarterPartner->save();
        }

        $message = ($request->regenerate)? 'Invoice regenerated successfully' : 'Invoice generated successfully!';
        return response()->json(['success' => 1, 'message' => $message]);
    }

    protected function generateAndSaveMasterInvoice($project, $quarter, $quarterPartner, $invoiceFrom, $invoiceFromPartner)
    {
        $costItems = $project->costItems->whereNull('project_cost_items.deleted_at')->whereIn('pivot.organisation_id', $project->allpartners()->pluck('project_partners.organisation_id'))->groupBy('pivot.cost_item_id')->all();
        $data = [];
        foreach ($costItems as $key => $costItem) {
            $quarterDates = [];
            if(!empty($costItem)) {
                foreach($costItem as $ckey => $ciClaimData) {
                    if(!empty($ciClaimData) && !empty($ciClaimData->claims_data)) {
                        $quarterDates = array_keys((array) $ciClaimData->claims_data->quarter_values);
                        break;
                    }
                }
            }
            
            if(empty($quarterDates)) {
                continue;
            }
            foreach ($quarterDates as $key => $timestamp) {
                $data['claims_data'][$costItem[0]->id]['quarter_values'][$timestamp] = $costItem->pluck('claims_data.quarter_values.'.$timestamp)->sum(); 
            }
        }

        // dd($data);

        if(empty($data)) {
            return [
                'success' => 0,
                'message' => 'Sheet Data not available!'
            ];
        }

        $invoiceItems = [];
        $data = (object) $data;
        
        $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();
        foreach ($project->costItems as $index => $costItem) {
            $invoiceItems[$index]['item_name'] = $costItem->pivot->cost_item_name;
            $invoiceItems[$index]['item_description'] = $costItem->pivot->cost_item_description;
            $invoiceItems[$index]['item_price'] = $data->claims_data[$costItem->id]['quarter_values'][$quarter->start_timestamp];
            $invoiceItems[$index]['vat_perc'] = 0;
        }

        // return view('backend.claim.project.invoice-master', [
        //     'quarter' => $quarter,
        //     'quarterPartner' => $quarterPartner,
        //     'invoiceItems' => json_decode(json_encode($invoiceItems)),
        //     'invoiceFrom' => $invoiceFrom,
        //     'invoiceFromPartner' => $invoiceFromPartner,
        // ]);

        $pdf = PDF::loadView('backend.claim.project.invoice-master', [
            'quarter' => $quarter,
            'quarterPartner' => $quarterPartner,
            'invoiceItems' => json_decode(json_encode($invoiceItems)),
            'invoiceFrom' => $invoiceFrom,
            'invoiceTo' => $invoiceFrom,
            'invoiceFromPartner' => $invoiceFromPartner,
            'invoiceToPartner' => $invoiceFromPartner,
            'invoiceFunder' => $invoiceFromPartner->invoiceFunder ?? $project->funders()->first(),
        ]);
        $pdf->save(public_path('uploads/invoices/master-'.$quarter->id.'.pdf'));

        return [
            'success' => 1,
            'message' => 'Invoice Saved!'
        ];
    }
}
