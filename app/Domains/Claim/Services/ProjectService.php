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
use Intervention\Image\Facades\Image as Image;

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

        $data['start_date'] = '01-'.$data['start_date_month'].'-'.$data['start_date_year'];

        try {
            $project = $this->model::create([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool_id' => $data['pool_id'],
                'start_date' => $data['start_date'],
                'length' => $data['length'],
                'status' => $data['status'],
                'active' => 1,
                'project_funder_ref' => $data['project_funder_ref'],
            ]);

            if(!empty($data['project_logo'])) {
                $project_logo_name = time().'.'.$data['project_logo']->extension();
                
                // Resize project logo to 225 X 225
                // $canvas = Image::canvas(225, 225);
                // $image  = Image::make($data['project_logo']->getRealPath())->resize(225, 225, function($constraint) {
                //     $constraint->aspectRatio();
                // });
                // $canvas->insert($image, 'center');
                // $canvas->save('uploads/projects/logos/'.$project_logo_name);

                $image = Image::make($data['project_logo']);
                $image->save('uploads/projects/logos/'.$project_logo_name);

                $project->update([
                    'logo' => $project_logo_name
                ]);
            }

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
                'invoice_organisation_id' => $data['lead_organisation']
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

            // Sync Quarters
            $quarters = [];
            $fromDate = clone $project->start_date;
            for ($i = 0; $i < $project->length; $i++) {
                $toDate = clone $fromDate;
                $toDate->addMonths(2)->endOfMonth();

                $quarters[$i]['length'] = $fromDate->format('My').' - '.$toDate->format('My');
                $quarters[$i]['name'] = 'Q'.($i + 1);
                $quarters[$i]['start_timestamp'] = $fromDate->timestamp;

                $fromDate->addMonths(3);
            }

            if(!empty($quarters)) {
                $project->quarters()->createMany($quarters);
            }

            // Sync Quarter Partner Data
            foreach($project->quarters as $key => $quarter) {
                $quarter->user()->create([
                    'status' => $key == 0 ? 'current' : 'forecast',
                    'po_number' => null,
                    'invoice_date' => null,
                    'invoice_no' => null,
                ]);
                foreach($project->partners as $partner){
                    $quarter->partners()->attach($partner->id, [
                        'status' => $key == 0 ? 'current' : 'forecast',
                        'po_number' => null,
                        'invoice_date' => null,
                        'invoice_no' => null,
                        'claim_status' => 0,
                    ]);
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
        $data['start_date'] = '01-'.$data['start_date_month'].'-'.$data['start_date_year'];

        try {
            $project->update([
                'name' => $data['name'],
                'number' => $data['number'],
                'pool_id' => $data['pool_id'],
                'start_date' => $data['start_date'],
                'length' => $data['length'],
                'status' => $data['status'],
                'project_funder_ref' => $data['project_funder_ref'],
            ]);

            if(!empty($data['project_logo'])) {
                if(!empty($project->logo)) {
                    $current_project_logo = public_path('uploads/projects/logos/'.$project->logo);
                    if(file_exists($current_project_logo)) {
                        unlink($current_project_logo);
                    }
                }
                $project_logo_name = time().'.'.$data['project_logo']->extension();

                // Resize project logo to 225 X 225
                // $canvas = Image::canvas(225, 225);
                // $image  = Image::make($data['project_logo']->getRealPath())->resize(225, 225, function($constraint) {
                //     $constraint->aspectRatio();
                // });
                // $canvas->insert($image, 'center');
                // $canvas->save('uploads/projects/logos/'.$project_logo_name);

                $image = Image::make($data['project_logo']);
                $image->save('uploads/projects/logos/'.$project_logo_name);

                $project->update([
                    'logo' => $project_logo_name
                ]);
            }

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
            
            // Save lead organisation
            if(!empty($data['lead_organisation']) && !empty($res)) {
                $res->invoice_organisation_id = $data['lead_organisation'];
                $res->save();
            }

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

            if($project->wasChanged(['start_date', 'length'])) {
                $project->quarters()->delete();
                // Sync Quarters
                $quarters = [];
                $fromDate = clone $project->start_date;
                for ($i = 0; $i < $project->length; $i++) {
                    $toDate = clone $fromDate;
                    $toDate->addMonths(2)->endOfMonth();

                    $quarters[$i]['length'] = $fromDate->format('My').' - '.$toDate->format('My');
                    $quarters[$i]['name'] = 'Q'.($i + 1);
                    $quarters[$i]['start_timestamp'] = $fromDate->timestamp;

                    $fromDate->addMonths(3);
                }

                if(!empty($quarters)) {
                    $project->quarters()->createMany($quarters);
                }

                // Sync Quarter Partner Data
                foreach($project->quarters as $key => $quarter) {
                    $quarter->user()->create([
                        'status' => $key == 0 ? 'current' : 'forecast',
                        'po_number' => null,
                        'invoice_date' => null,
                        'invoice_no' => null,
                    ]);
                    foreach($project->partners as $partner){
                        $quarter->partners()->attach($partner->id, [
                            'status' => $key == 0 ? 'current' : 'forecast',
                            'po_number' => null,
                            'invoice_date' => null,
                            'invoice_no' => null,
                            'claim_status' => 0,
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
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
            DB::table('project_cost_items')->where('project_id', $project->id)->delete();
            DB::table('project_funders')->where('project_id', $project->id)->delete();
            ProjectPartners::where('project_id', $project->id)->delete();
            SheetUserPermissions::where('project_id', $project->id)->delete();
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
