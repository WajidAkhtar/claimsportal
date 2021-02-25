<?php

namespace App\Domains\Claim\Services;

use Exception;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;

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

        try {
            $project = $this->model::create([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool' => $data['pool'],
                'start_date' => '01-'.$data['start_date'],
                'length' => $data['length'],
                'status' => $data['status'],
                'active' => 1,
            ]);
            
            // Sync Funders
            $project->funders()->sync($data['funders']);

            // Save cost items
            foreach ($data['cost_items'] as $key => $value) {
                $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                $project->costItems()->create(['cost_item_id' => $costItem->id]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
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
        DB::beginTransaction();

        try {
            $project->update([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool' => $data['pool'],
                'start_date' => '01-'.$data['start_date'],
                'length' => $data['length'],
                'status' => $data['status'],
            ]);

            // Sync Funders
            $project->funders()->sync($data['funders']);
            // Delete old and Save new cost items
            // $project->costItems()->delete();
            foreach ($data['cost_items'] as $key => $value) {
                $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                $project->costItems()->create(['cost_item_id' => $costItem->id]);
            }
            // $project->costItems()->createMany($data['cost_items']);
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
        if ($this->deleteById($project->id)) {
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
