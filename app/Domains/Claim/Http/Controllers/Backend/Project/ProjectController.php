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
use App\Exceptions\GeneralException;
use App\Domains\System\Models\Organisation;
use App\Domains\System\Models\Pool;

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
        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF BIRMINGHAM',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'Edgbaston',
            'city' => 'Birmingham',
            'postcode' => 'B15 2TT',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF BRISTOL',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'BEACON HOUSE',
            'street' => 'QUEENS ROAD',
            'address_line_2' => '',
            'county' => 'BRISTOL',
            'city' => 'BRISTOL',
            'postcode' => 'BS8 1QU',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF CAMBRIDGE',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'THE OLD SCHOOLS',
            'street' => 'TRINITY LANE',
            'address_line_2' => '',
            'county' => 'CAMBRIDGESHIRE',
            'city' => 'CAMBRIDGE',
            'postcode' => 'CB2 1TN',
        ]);

        Organisation::create([
            'organisation_name' => 'CARDIFF UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'WALES',
            'city' => 'CARDIFF',
            'postcode' => 'CF10 3AT',
        ]);

        Organisation::create([
            'organisation_name' => 'DURHAM UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'THE PALATINE CENTRE',
            'street' => 'STOCKTON ROAD',
            'address_line_2' => '',
            'county' => 'DURHAM',
            'city' => 'DURHAM',
            'postcode' => 'DH1 3LE',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF EDINBURGH',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'OLD COLLEGE',
            'street' => 'SOUTH BRIDGE',
            'address_line_2' => '',
            'county' => 'EDINBURGH',
            'city' => 'EDINBURGH',
            'postcode' => 'EH8 9YL',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF EXETER',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'NORTHCOTE HOUSE',
            'street' => 'THE QUEEN’S DRIVE',
            'address_line_2' => '',
            'county' => 'EXETER',
            'city' => 'EXETER',
            'postcode' => 'EX4 4QJ',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF GLASGOW',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '1 UNIVERSITY AVENUE',
            'street' => '',
            'address_line_2' => '',
            'county' => 'GLASGOW',
            'city' => 'GLASGOW',
            'postcode' => 'G12 8QQ',
        ]);

        Organisation::create([
            'organisation_name' => 'IMPERIAL COLLEGE LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'SOUTH KENSINGTON CAMPUS',
            'street' => '',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'SW7 2AZ',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF LEEDS',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'E C STONER BUILDING',
            'street' => '11.72',
            'address_line_2' => '',
            'county' => 'LEEDS',
            'city' => 'LEEDS',
            'postcode' => 'LS2 9JT',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF LIVERPOOL',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'FOUNDATION BUILDING',
            'street' => 'BROWNLOW HILL',
            'address_line_2' => '',
            'county' => 'LIVERPOOL',
            'city' => 'LIVERPOOL',
            'postcode' => 'L69 7ZX',
        ]);

        Organisation::create([
            'organisation_name' => 'LONDON SCHOOL OF ECONOMICS & POLITICAL SCIENCE',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'HOUGHTON STREET',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'WC2A 2AE',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF MANCHESTER',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'OXFORD ROAD',
            'address_line_2' => '',
            'county' => 'MANCHESTER',
            'city' => 'MANCHESTER',
            'postcode' => 'M13 9PL',
        ]);

        Organisation::create([
            'organisation_name' => 'NEWCASTLE UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'TYNE AND WEAR',
            'city' => 'NEWCASTLE UPON TYNE',
            'postcode' => 'NE1 7RU',    
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF NOTTINGHAM',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'A107 TRENT BUILDING',
            'street' => 'UNIVERSITY PARK',
            'address_line_2' => '',
            'county' => 'NOTTINGHAM',
            'city' => 'NOTTINGHAM',
            'postcode' => 'NG7 2RD',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF OXFORD',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'UNIVERSITY OFFICES',
            'street' => 'WELLINGTON SQUARE',
            'address_line_2' => '',
            'county' => 'OXFORD',
            'city' => 'OXFORD',
            'postcode' => 'OX1 2JD',
        ]);

        Organisation::create([
            'organisation_name' => 'QUEEN MARY UNIVERSITY OF LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'MILE END ROAD',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'E1 4NS',
        ]);

        Organisation::create([
            'organisation_name' => "QUEEN'S UNIVERSITY BELFAST",
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'RIDDEL HALL',
            'street' => '185 STRANMILLIS ROAD',
            'address_line_2' => '',
            'county' => 'BELFAST',
            'city' => 'BELFAST',
            'postcode' => 'BT9 5EE',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF SHEFFIELD',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => 'WESTERN BANK',
            'street' => '',
            'address_line_2' => '',
            'county' => 'SHEFFIELD',
            'city' => 'SHEFFIELD',
            'postcode' => 'S10 2TN',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF SOUTHAMPTON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'UNIVERSITY ROAD',
            'address_line_2' => '',
            'county' => 'SOUTHAMPTON',
            'city' => 'SOUTHAMPTON',
            'postcode' => 'SO17 1BJ',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY COLLEGE LONDON',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'GOWER STREET',
            'address_line_2' => '',
            'county' => 'LONDON',
            'city' => 'LONDON',
            'postcode' => 'WC1E 6BT',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF WARWICK',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'COVENTRY',
            'city' => 'COVENTRY',
            'postcode' => 'CV4 7AL',
        ]);

        Organisation::create([
            'organisation_name' => 'UNIVERSITY OF YORK',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => '',
            'address_line_2' => '',
            'county' => 'YORK',
            'city' => 'HESLINGTON',
            'postcode' => 'YO10 5DD',
        ]);

        Organisation::create([
            'organisation_name' => 'OXFORD BROOKES UNIVERSITY',
            'organisation_type' => 'ACADEMIC',
            'building_name_no' => '',
            'street' => 'HEADINGTON CAMPUS',
            'address_line_2' => '',
            'county' => 'OXFORD',
            'city' => 'OXFORD',
            'postcode' => 'OX3 0BP',
        ]);

        Organisation::create([
            'organisation_name' => 'DIAMOND LIGHT SOURCE LTD',
            'organisation_type' => 'INDUSTRY',
            'building_name_no' => 'DIAMOND HOUSE',
            'street' => 'HARWELL SCIENCE & INNOVATION CAMPUS',
            'address_line_2' => '',
            'county' => 'OXFORDSHIRE',
            'city' => 'DIDCOT',
            'postcode' => 'OX11 0DE',
        ]);

        Organisation::create([
            'organisation_name' => 'THE FARADAY INSTITUTION',
            'organisation_type' => 'FUNDER',
            'building_name_no' => 'QUAD ONE',
            'street' => 'BECQUEREL AVENUE',
            'address_line_2' => 'HARWELL CAMPUS',
            'county' => 'OXFORDSHIRE',
            'city' => 'DIDCOT',
            'postcode' => 'OX11 0RA',
        ]);

        Organisation::create([
            'organisation_name' => 'ENGINEERING & PHYSICAL SCIENCES RESEARCH COUNCIL',
            'organisation_type' => 'FUNDER',
            'building_name_no' => 'POLARIS HOUSE',
            'street' => 'NORTH STAR AVENUE',
            'address_line_2' => '',
            'county' => 'WILTSHIRE',
            'city' => 'SWINDON',
            'postcode' => 'SN2 1ET',
        ]);
        Pool::create([
            'code' => 'EPS',
            'name' => 'Engineering & Physical Sciences'
        ]);

        Pool::create([
            'code' => 'LES',
            'name' => 'Life & Environmental Sciences'
        ]);

        Pool::create([
            'code' => 'CoSS',
            'name' => 'College of Social Sciences'
        ]);

        Pool::create([
            'code' => 'CAL',
            'name' => 'College of Arts & Law'
        ]);

        Pool::create([
            'code' => 'MDS',
            'name' => 'College of Medical & Dental Sciences'
        ]);
        exit;
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
        $costItems = CostItem::onlyActive()->onlySystemGenerated()->get();
        $organisations = Organisation::pluck('organisation_name', 'id');
        $pools = Pool::get()->pluck('full_name', 'id');
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
        if(!$project->isUserPartOfProject(auth()->user()->id, true) && !$project->isUserPartOfProject(auth()->user()->id)) {
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
            return view('backend.claim.project.show')
            ->withProject($project)
            ->withAllowToEdit($allowToEdit)
            ->withSheetOwner($sheet_owner)
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
                return view('backend.claim.project.show')
                ->withProject($project)
                ->withSheetOwner($sheet_owner)
                ->withAllowToEdit($allowToEdit)
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
        if(!auth()->user()->hasRole('Administrator')) {
            return redirect()->route('admin.claim.project.index')->withFlashDanger(__('You have no access to this page.'));
        }
        $funders = $this->userService->getByRoleId(7)->pluck('organisation', 'id');
        $partners = $this->userService->getByRoleId(6)->pluck('organisation', 'id');
        $costItems = $project->costItems()->whereNull('project_cost_items.deleted_at')->whereNotNull('cost_item_description')->groupBy('cost_item_id')->orderByRaw($project->costItemOrderRaw())->get();
        $organisations = Organisation::pluck('organisation_name', 'id');
        $pools = Pool::get()->pluck('full_name', 'id');
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
}
