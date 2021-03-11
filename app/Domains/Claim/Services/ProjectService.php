<?php

namespace App\Domains\Claim\Services;

use Exception;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Claim\Models\ProjectCostItem;

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
                'pool_id' => $data['pool_id'],
                'organisation_id' => $data['organisation_id'],
                'start_date' => '01-'.$data['start_date'],
                'length' => $data['length'],
                'number_of_partners' => $data['number_of_partners'],
                'cost_items_order' => $data['cost_items_order'],
                'status' => $data['status'],
                'active' => 1,
                'finance_email' => $data['finance_email'],
                'finance_tel' => $data['finance_tel'],
                'finance_fax' => $data['finance_fax'],
                'vat' => $data['vat'],
                'eori' => $data['eori'],
                'account_name' => $data['account_name'],
                'bank_name' => $data['bank_name'],
                'bank_address' => $data['bank_address'],
                'sort_code' => $data['sort_code'],
                'account_no' => $data['account_no'],
                'swift' => $data['swift'],
                'iban' => $data['iban'],
            ]);
            
            // Sync Funders
            $project->funders()->sync($data['funders']);

            // Save partners for the project
            if(!empty($data['number_of_partners'])) {
                $partnerCount = 1;
                while($partnerCount <= $data['number_of_partners']) {
                    $project->allpartners()->create([
                        'project_id' => $project->id
                    ]);
                    $partnerCount++;
                    foreach ($data['cost_items'] as $key => $value) {
                        $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                        $projectCostItem = ProjectCostItem::firstOrCreate([
                            'project_id' => $project->id,
                            'cost_item_id' => $costItem->id,
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
        $oldPartners = $project->costItems()->groupBy('user_id')->pluck('user_id')->toArray();

        if($data['number_of_partners'] != count($data['project_partners'])) {
            throw new GeneralException(__('Please assign '.$data['number_of_partners'].' partners to this project'));
        }

        DB::beginTransaction();

        try {
            $project->update([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool_id' => $data['pool_id'],
                'organisation_id' => $data['organisation_id'],
                'start_date' => '01-'.$data['start_date'],
                'length' => $data['length'],
                'number_of_partners' => $data['number_of_partners'],
                'cost_items_order' => $data['cost_items_order'],
                'status' => $data['status'],
                'finance_email' => $data['finance_email'],
                'finance_tel' => $data['finance_tel'],
                'finance_fax' => $data['finance_fax'],
                'vat' => $data['vat'],
                'eori' => $data['eori'],
                'account_name' => $data['account_name'],
                'bank_name' => $data['bank_name'],
                'bank_address' => $data['bank_address'],
                'sort_code' => $data['sort_code'],
                'account_no' => $data['account_no'],
                'swift' => $data['swift'],
                'iban' => $data['iban'],
            ]);

            // Sync Funders
            $project->funders()->sync($data['funders']);

            $costItemIds = [];
            foreach ($data['cost_items'] as $key => $value) {
                $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                $costItemIds[] = $costItem->id;
            }
            
            // Delete removed project cost items
            $costItemIdsToRemove = array_merge(array_diff($costItemIds, $oldCostItems), array_diff($oldCostItems, $costItemIds));
            
            ProjectCostItem::where('project_id', $project->id)->whereIn('cost_item_id', $costItemIdsToRemove)->forceDelete();
            // Delete removed project partners data
            $costItemsUsersToRemove = array_merge(array_diff($data['project_partners'], $oldPartners), array_diff($oldPartners, $data['project_partners']));
            ProjectCostItem::where('project_id', $project->id)->whereIn('user_id', $costItemsUsersToRemove)->forceDelete();

            // Delete existing project partners
            // $project->allpartners()->forceDelete();
            // Save partners for the project
            foreach ($data['project_partners'] as $key => $project_partner) {
                $project->allpartners()->firstOrCreate([
                    'user_id' => $project_partner,
                    'project_id' => $project->id,
                ]);
                foreach ($data['cost_items'] as $key => $value) {
                    $costItem = CostItem::firstOrCreate(['name' => $value['name']]);
                    $projectCostItem = ProjectCostItem::firstOrCreate([
                        'project_id' => $project->id,
                        'user_id' => $project_partner,
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
        if ($this->deleteById($project->id)) {
            $project->allpartners()->forceDelete();
            $project->innerData()->forceDelete();
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
