<?php

namespace App\Domains\Claim\Http\Controllers\Backend\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Auth\Services\UserService;
use App\Domains\Claim\Services\ProjectService;
use App\Domains\Claim\Http\Requests\Backend\Project\EditProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\StoreProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\DeleteProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\UpdateProjectRequest;
use App\Domains\Claim\Models\ProjectCostItem;
use Illuminate\Support\Facades\Auth;

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
        return view('backend.claim.project.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        if(!auth()->user()->hasRole('Administrator')) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('You have no access to this page.'));
        }
        $funders = $this->userService->getByRoleId(7)->pluck('organisation', 'id');
        $costItems = CostItem::onlyActive()->get();
        return view('backend.claim.project.create')
            ->withFunders($funders)
            ->withCostItems($costItems);
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
        if(!$project->isUserPartOfProject(auth()->user()->id, true) && !$project->isUserPartOfProject(auth()->user()->id)) {
            throw new GeneralException(__('You have no access to this page'));
        }

        $allowToEdit = $project->isUserPartOfProject(auth()->user()->id);
        
        if(empty(request()->partner) && $project->created_by == auth()->user()->id) {
            $allowToEdit = false;
        } else if(!empty(request()->partner)) {
            $project->costItems = $project->costItems()->where('user_id', request()->partner)->orderByRaw($project->costItemOrderRaw())->get();
        } else {
            $project->costItems = $project->costItems()->where('user_id', auth()->user()->id)->orderByRaw($project->costItemOrderRaw())->get();
        }

        if(empty(request()->partner) && $allowToEdit && $project->created_by != auth()->user()->id) {
            $allowToEdit = true;
        } else {
            $allowToEdit = false;
        }

        if(!$project->isUserPartOfProject(auth()->user()->id, true) && $project->isUserPartOfProject(auth()->user()->id)) {
            $allowToEdit = true;
            $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
            return view('backend.claim.project.show')
            ->withProject($project)
            ->withAllowToEdit($allowToEdit)
            ->withyearwiseHtml($yearwiseHtml);
        } else {
            $data = [];
            if(empty(request()->partner)) {
                $costItems = $project->costItems->groupBy('pivot.cost_item_id')->all();
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
                $project->costItems = $project->costItems()->orderByRaw($project->costItemOrderRaw())->groupBy('cost_items.name')->get();

                $data = (object) $data;
                $yearwiseHtml = View::make('backend.claim.project.show-yearwise-master', ['project' => $project, 'data' => $data])->render();
                return view('backend.claim.project.show-master')
                ->withProject($project)
                ->withData($data)
                ->withAllowToEdit($allowToEdit)
                ->withyearwiseHtml($yearwiseHtml);
            } else {
                $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
                return view('backend.claim.project.show')
                ->withProject($project)
                ->withAllowToEdit($allowToEdit)
                ->withyearwiseHtml($yearwiseHtml);
            }
        }

        // return view('backend.claim.project.show')
        //     ->withProject($project)
        //     ->withAllowToEdit($allowToEdit)
        //     ->withyearwiseHtml($yearwiseHtml);
    }

    /**
     * @param  EditProjectRequest  $request
     * @param  Project  $project
     *
     * @return mixed
     */
    public function edit(EditProjectRequest $request, Project $project)
    {
        if(!auth()->user()->hasRole('Administrator')) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('You have no access to this page.'));
        }
        $funders = $this->userService->getByRoleId(7)->pluck('organisation', 'id');
        $partners = $this->userService->getByRoleId(6)->pluck('name', 'id');
        $costItems = CostItem::onlyActive()->orderByRaw($project->costItemOrderRaw())->get();
        // $costItems = $project->costItems()->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();
        return view('backend.claim.project.edit')
            ->withProject($project)
            ->withFunders($funders)
            ->withPartners($partners)
            ->withCostItems($costItems);
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
            $costItem = ProjectCostItem::whereProjectId($project->id)->whereCostItemId($costItemId)->whereUserId(Auth()->user()->id)->first();
            $costItem->claims_data = collect($claimValue)->only('quarter_values', 'yearwise', 'total_budget')->toArray();
            $costItem->save();
        }

        $project->costItems = $project->costItems()->where('user_id', auth()->user()->id)->orderByRaw($project->costItemOrderRaw())->get();

        $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
        return response()->json(['success' => 1, 'message' => 'Data saved successfully!', 'data' => ['yearwiseHtml' => $yearwiseHtml]]);
    }
}
