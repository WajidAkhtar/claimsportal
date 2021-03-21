<?php

namespace App\Domains\Claim\Http\Controllers\Backend\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Claim\Models\ProjectPartners;
use App\Domains\Auth\Services\UserService;
use App\Domains\Claim\Services\ProjectService;
use App\Domains\Claim\Http\Requests\Backend\Project\EditProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\StoreProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\DeleteProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\UpdateProjectRequest;
use App\Domains\Claim\Models\ProjectCostItem;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\GeneralException;
use App\Domains\System\Models\Organisation;
use App\Domains\System\Models\Pool;
use App\Domains\System\Models\SheetPermission;
use App\Domains\System\Models\SheetUserPermissions;
use App\Domains\Auth\Models\User;

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

        $sheetPermissions = SheetPermission::whereIn('permission', ['READ_WRITE_ALL', 'WRITE_ONLY_FORECAST', 'READ_ONLY'])->pluck('permission', 'id');
        
        if(!empty(request()->partner)) {
            $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('organisation_id', request()->partner)->orderByRaw($project->costItemOrderRaw())->get();
        }

        $userHasMasterAccess = false;
        $userHasMasterAccessWithPermission = '';

        if(SheetUserPermissions::where('user_id', auth()->user()->id)->where('project_id', $project->id)->where('is_master', '1')->count() > 0) {
            $userHasMasterAccess = true;
            $userHasMasterAccessWithPermissionId = SheetUserPermissions::where('project_id', $project->id)->where('is_master', '1')->pluck('sheet_permission_id');
            $userHasMasterAccessWithPermission = SheetPermission::find($userHasMasterAccessWithPermissionId)->first()->permission;
        }

        if(in_array(current_user_role(), ['Administrator', 'Super User'])) {
            $userHasMasterAccess = true;
            $userHasMasterAccessWithPermission = 'READ_WRITE_ALL';
        }
        
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
            ->withyearwiseHtml($yearwiseHtml);
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
            ->withyearwiseHtml($yearwiseHtml);
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

}
