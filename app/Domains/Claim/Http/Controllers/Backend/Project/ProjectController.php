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
        if(auth()->user()->hasRole('Project Partner')) {
            return redirect()->route('admin.dashboard')->withFlashDanger(__('You have no access to this page.'));
        }
        return view('backend.claim.project.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        // dd(auth()->user()->roles()->get());
        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User') && !auth()->user()->hasRole('Finance Officer') && !auth()->user()->hasRole('Project Admin')) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('You have no access to this page.'));
        }
        $funders = $this->userService->getByRoleId(7)->pluck('organisation.organisation_name', 'id');
        $costItems = CostItem::onlyActive()->onlySystemGenerated()->get();
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        $pools = current_user_pools()->pluck('full_name', 'id');
        return view('backend.claim.project.create')
            ->withFunders($funders)
            ->withCostItems($costItems)
            ->withPools($pools)
            ->withOrganisations($organisations);
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
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        if(!auth()->user()->hasRole('Administrator')) {
            $users = User::get()->pluck('full_name', 'id');
        } else {
            $users = $project->usersInSamePool()->pluck('full_name', 'id');
        }
        $sheetPermissions = SheetPermission::pluck('permission', 'id');

        if(!$project->isUserPartOfProject(auth()->user()->id, true) && !$project->isUserPartOfProject(auth()->user()->id) && !in_array($project->pool_id, auth()->user()->pools()->pluck('pool_id')->toArray())) {
            throw new GeneralException(__('You have no access to this page'));
        }

        $allowToEdit = $project->isUserPartOfProject(auth()->user()->id);
        
        if(empty(request()->partner) && $project->created_by == auth()->user()->id) {
            $allowToEdit = false;
        } else if(!empty(request()->partner)) {
            $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('user_id', request()->partner)->orderByRaw($project->costItemOrderRaw())->get();
        } else {
            $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('user_id', auth()->user()->id)->orderByRaw($project->costItemOrderRaw())->get();
        }

        if(empty(request()->partner) && $allowToEdit && $project->created_by != auth()->user()->id) {
            $allowToEdit = true;
        } else {
            $allowToEdit = false;
        }

        if(!$project->isUserPartOfProject(auth()->user()->id, true) && $project->isUserPartOfProject(auth()->user()->id)) {
            $allowToEdit = true;
            $sheet_owner = (!empty(request()->partner)) ? request()->partner : auth()->user()->id;
            $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
            $partnerAdditionalInfo = ProjectPartners::where('project_id', $project->id)->where('user_id', $sheet_owner)->first();
            return view('backend.claim.project.show')
            ->withProject($project)
            ->withPartnerAdditionalInfo($partnerAdditionalInfo)
            ->withOrganisations($organisations)
            ->withAllowToEdit($allowToEdit)
            ->withSheetOwner($sheet_owner)
            ->withUsers($users)
            ->withSheetPermissions($sheetPermissions)
            ->withyearwiseHtml($yearwiseHtml);
        } else {
            $data = [];
            if(empty(request()->partner)) {
                $costItems = $project->costItems->whereNull('project_cost_items.deleted_at')->whereIn('pivot.user_id', $project->partners()->pluck('user_id'))->groupBy('pivot.cost_item_id')->all();
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

                $data = (object) $data;
                $yearwiseHtml = View::make('backend.claim.project.show-yearwise-master', ['project' => $project, 'data' => $data])->render();
                return view('backend.claim.project.show-master')
                ->withProject($project)
                ->withData($data)
                ->withAllowToEdit($allowToEdit)
                ->withyearwiseHtml($yearwiseHtml);
            } else {
                if(!empty(request()->partner) && $project->created_by == auth()->user()->id) {
                    $allowToEdit = true;
                }
                $sheet_owner = (!empty(request()->partner)) ? request()->partner : auth()->user()->id;
                $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
                $partnerAdditionalInfo = ProjectPartners::where('project_id', $project->id)->where('user_id', $sheet_owner)->first();
                $SheetUserPermissions = SheetUserPermissions::where('project_id', $project->id)->where('partner_id', $sheet_owner)->get();

                return view('backend.claim.project.show')
                ->withProject($project)
                ->withSheetOwner($sheet_owner)
                ->withPartnerAdditionalInfo($partnerAdditionalInfo)
                ->withOrganisations($organisations)
                ->withAllowToEdit($allowToEdit)
                ->withUsers($users)
                ->withSheetPermissions($sheetPermissions)
                ->withSheetUserPermissions($SheetUserPermissions)
                ->withyearwiseHtml($yearwiseHtml);
            }
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
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('You have no access to this page.'));
        }
        $funders = $this->userService->getByRoleId(7)->pluck('organisation.organisation_name', 'id');
        $partners = $this->userService->getByRoleId(6)->pluck('organisation.organisation_name', 'id');
        $costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->whereNotNull('cost_item_description')->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        $pools = current_user_pools()->pluck('full_name', 'id');
        return view('backend.claim.project.edit')
            ->withProject($project)
            ->withFunders($funders)
            ->withPartners($partners)
            ->withCostItems($costItems)
            ->withPools($pools)
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
            $costItem = ProjectCostItem::whereProjectId($project->id)->whereCostItemId($costItemId)->whereUserId($request->sheet_owner)->first();
            $costItem->claims_data = collect($claimValue)->only('quarter_values', 'yearwise', 'total_budget')->toArray();
            $costItem->save();
        }

        $project->costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->where('user_id', $request->sheet_owner)->orderByRaw($project->costItemOrderRaw())->get();

        $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
        return response()->json(['success' => 1, 'message' => 'Data saved successfully!', 'data' => ['yearwiseHtml' => $yearwiseHtml]]);
    }

    public function savePartnerAdditionalFields(Request $request, Project $project) {
        $isSaved = ProjectPartners::where('user_id', $request->sheet_owner)->where('project_id', $project->id)->update([
                'organisation_id' => $request->organisation_id,
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
            ]
        );
        return response()->json(['success' => $isSaved, 'message' => 'Data saved successfully!']);
    }

    public function saveSheetUserPermissions(Request $request, Project $project) {
        if(!empty($request->sheet_user_id)) {
            SheetUserPermissions::where('partner_id', $request->sheet_owner_for_permission)->where('project_id', $project->id)->delete();
            foreach($request->sheet_user_id as $key => $user_id) {
                if(!empty($request->sheet_owner_for_permission) && !empty($project->id) && !empty($user_id) && !empty($request->sheet_permission_id[$key])) {
                    SheetUserPermissions::create([
                        'partner_id' => $request->sheet_owner_for_permission,
                        'project_id' => $project->id,
                        'user_id' => $user_id,
                        'sheet_permission_id' => $request->sheet_permission_id[$key],
                    ]);
                }
            }
            return response()->json(['success' => TRUE, 'message' => 'Sheet users & permissions saved successfully!']);   
        } else {
            return response()->json(['success' => FALSE, 'message' => 'Nothing to save!']);   
        }
    }

}
