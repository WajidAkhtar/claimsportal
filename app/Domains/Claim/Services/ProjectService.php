<?php

namespace App\Domains\Claim\Services;

use Exception;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Claim\Models\ProjectCostItem;
use App\Domains\Claim\Models\ProjectPartners;
use App\Domains\System\Models\SheetUserPermissions;
use Illuminate\Support\Facades\Schema;

/**
 * Class ProjectService.
 */
class ProjectService extends BaseService
{
    /**
     * ProjectService constructor.
     *
     * @param  Project  $project
     */
    public function __construct(Project $project)
    {
        $this->model = $project;
    }

    /**
     * @param  array  $data
     *
     * @return Project
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): Project
    {
        DB::beginTransaction();

        $cost_items_order = [];

        try {
            $project = $this->model::create([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool_id' => $data['pool_id'],
                'start_date' => '01-'.$data['start_date'],
                'length' => $data['length'],
                'status' => $data['status'],
                'active' => 1,
                'project_funder_ref' => $data['project_funder_ref'],
            ]);

            foreach ($data['cost_items'] as $key => $value) {
                $cost_items_order[] = $value['name'];
            }

            $project->update([
                'cost_items_order' => implode(',', $cost_items_order),
            ]);
            
            // Sync Funders
            $project->funders()->sync($data['funders']);

            // Save partners for the project
            $project->allpartners()->firstOrcreate([
                'project_id' => $project->id,
                'is_master' => '1',
            ]);
            if(!empty($data['project_partners'])) {
                foreach($data['project_partners'] as $project_partner) {
                    $project->allpartners()->create([
                        'project_id' => $project->id,
                        'organisation_id' => $project_partner,
                    ]);
                    foreach ($data['cost_items'] as $key => $value) {
                        $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                        $projectCostItem = ProjectCostItem::firstOrCreate([
                            'project_id' => $project->id,
                            'cost_item_id' => $costItem->id,
                            'organisation_id' => $project_partner,
                        ]);
                        ProjectCostItem::where('project_id', $project->id)->where('cost_item_id', $costItem->id)->update([
                            'cost_item_name' => $value['name'],
                            'cost_item_description' => $value['description'],
                        ]);
                    }
                }
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem creating this project. Please try again.'));
        }

        DB::commit();

        return $project;
    }

    /**
     * @param  Project  $project
     * @param  array  $data
     *
     * @return Project
     * @throws \Throwable
     */
    public function update(Project $project, array $data = []): Project
    {
        $oldCostItems = $project->costItems()->groupBy('cost_item_id')->pluck('cost_item_id')->toArray();
        $oldPartners = $project->costItems()->groupBy('organisation_id')->pluck('organisation_id')->toArray();

        DB::beginTransaction();

        $cost_items_order = [];

        try {
            $project->update([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool_id' => $data['pool_id'],
                'start_date' => '01-'.$data['start_date'],
                'length' => $data['length'],
                'status' => $data['status'],
                'project_funder_ref' => $data['project_funder_ref'],
            ]);

            // Sync Funders
            $project->funders()->sync($data['funders']);

            $costItemIds = [];
            foreach ($data['cost_items'] as $key => $value) {
                $cost_items_order[] = $value['name'];
                $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                $costItemIds[] = $costItem->id;
            }

            $project->update([
                'cost_items_order' => implode(',', $cost_items_order),
            ]);
            
            // Delete removed project cost items
            $costItemIdsToRemove = array_merge(array_diff($costItemIds, $oldCostItems), array_diff($oldCostItems, $costItemIds));
            
            ProjectCostItem::where('project_id', $project->id)->whereIn('cost_item_id', $costItemIdsToRemove)->forceDelete();
            // Delete removed project partners data
            $removeDeletedOrganisationsWhileSync = array_merge(array_diff($data['project_partners'], $oldPartners), array_diff($oldPartners, $data['project_partners']));
            ProjectCostItem::where('project_id', $project->id)->whereIn('organisation_id', $removeDeletedOrganisationsWhileSync)->forceDelete();
            ProjectPartners::where('project_id', $project->id)->whereIn('organisation_id', $removeDeletedOrganisationsWhileSync)->forceDelete();

            // Delete existing project partners
            // $project->allpartners()->forceDelete();
            // Save partners for the project
            $res = $project->allpartners()->firstOrcreate([
                'project_id' => $project->id,
                'is_master' => '1',
            ]);
            foreach ($data['project_partners'] as $key => $project_partner) {
                $project->allpartners()->firstOrCreate([
                    'organisation_id' => $project_partner,
                    'project_id' => $project->id,
                ]);
                foreach ($data['cost_items'] as $key => $value) {
                    $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                    $projectCostItem = ProjectCostItem::firstOrCreate([
                        'project_id' => $project->id,
                        'organisation_id' => $project_partner,
                        'cost_item_id' => $costItem->id,
                    ]);
                    ProjectCostItem::where('project_id', $project->id)->where('cost_item_id', $costItem->id)->update([
                        'cost_item_name' => $value['name'],
                        'cost_item_description' => $value['description'],
                    ]);
                }
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem updating this project. Please try again.'));
        }

        DB::commit();

        return $project;
    }

    /**
     * @param  Project  $project
     *
     * @return Project
     * @throws GeneralException
     */
    public function delete(Project $project): Project
    {
        // if ($this->deleteById($project->id)) {
        if ($project->forceDelete()) {
            Schema::disableForeignKeyConstraints();
            $project->allpartners()->forceDelete();
            $project->innerData()->forceDelete();
            $project->funders()->forceDelete();
            $project->allpartners()->forceDelete();
            SheetUserPermissions::where('project_id', $project->id)->forceDelete();
            Schema::enableForeignKeyConstraints();
            return $project;
        }

        throw new GeneralException('There was a problem deleting this project. Please try again.');
    }

    /**
     * @param Project $project
     *
     * @throws GeneralException
     * @return Project
     */
    public function restore(Project $project): Project
    {
        if ($project->restore()) {
            return $project;
        }

        throw new GeneralException(__('There was a problem restoring this project. Please try again.'));
    }

    /**
     * @param  Project  $project
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(Project $project): bool
    {
        if ($project->forceDelete()) {
            return true;
        }

        throw new GeneralException(__('There was a problem permanently deleting this project. Please try again.'));
    }
}
