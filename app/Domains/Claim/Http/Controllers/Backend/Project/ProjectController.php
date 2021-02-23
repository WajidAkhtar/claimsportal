<?php

namespace App\Domains\Claim\Http\Controllers\Backend\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Auth\Services\UserService;
use App\Domains\Claim\Services\ProjectService;
use App\Domains\Claim\Http\Requests\Backend\Project\EditProjectRequest;
use App\Domains\CLaim\Http\Requests\Backend\Project\StoreProjectRequest;
use App\Domains\Claim\Http\Requests\Backend\Project\DeleteProjectRequest;
use App\Domains\CLaim\Http\Requests\Backend\Project\UpdateProjectRequest;

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
        $funders = $this->userService->getByRoleId(7)->pluck('name', 'id');
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
        $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
        return view('backend.claim.project.show')
            ->withProject($project)
            ->withyearwiseHtml($yearwiseHtml);
    }

    /**
     * @param  EditProjectRequest  $request
     * @param  Project  $project
     *
     * @return mixed
     */
    public function edit(EditProjectRequest $request, Project $project)
    {
        $funders = $this->userService->getByRoleId(7)->pluck('name', 'id');
        $costItems = CostItem::onlyActive()->get();
        return view('backend.claim.project.edit')
            ->withProject($project)
            ->withFunders($funders)
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
            $costItem = $project->costItems()->whereId($costItemId)->first();
            $costItem->claims_data = collect($claimValue)->only('quarter_values', 'yearwise')->toArray();
            $costItem->save();
        }

        $yearwiseHtml = View::make('backend.claim.project.show-yearwise', ['project' => $project])->render();
        return response()->json(['success' => 1, 'message' => 'Data saved successfully!', 'data' => ['yearwiseHtml' => $yearwiseHtml]]);
    }
}
