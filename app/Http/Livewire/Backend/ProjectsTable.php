<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Claim\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;

/**
 * Class ProjectsTable.
 */
class ProjectsTable extends TableComponent
{
    use HtmlComponents;

    /**
     * @var string
     */
    public $sortField = 'name';

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped',
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Project::select('projects.*')->addSelect(\DB::raw('GROUP_CONCAT(o.organisation_name) as funders'))->leftJoin('project_funders as pf', 'pf.project_id', 'projects.id')->leftJoin('organisations as o', 'o.id', 'pf.organisation_id')->groupBy('pf.project_id')->whereNotNull('pf.project_id');

        if(!auth()->user()->hasRole('Developer') && !auth()->user()->hasRole('Administrator')) {
            $query = $query->whereHas('usersWithPermissions', function($q) use ($query) {
                $q->where('user_id', auth()->user()->id);
                // $query->orWhere('created_by', auth()->user()->id);
            });
            $query = $query->whereIn('pool_id', current_user_pools()->pluck('id')->toArray());
        }

        if ($this->status === 'deleted') {
            return $query->onlyTrashed();
        }

        if ($this->status === 'deactivated') {
            return $query->onlyDeactivated();
        }

        return $query->onlyActive();
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Logo'), 'logo')
                ->format(function(Project $model){
                    if(!empty($model->logo) && file_exists(public_path('uploads/projects/logos/'.$model->logo))) {
                        // $project_log_path = $this->image(asset('uploads/projects/logos/'.$model->logo), 'Logo', ['class' => '', 'style' => 'height:56px; width: 200px;max-width:200px;']);
                        $img = '<div style="background: url('.asset('uploads/projects/logos/'.$model->logo).');height: 56px; width:100px;background-size: contain;background-repeat: no-repeat;")></div>';
                        return $this->html($img);
                    }

                    $img = '<div style="background-color: transparent;height: 56px; width:100px;background-size: contain;background-repeat: no-repeat;")></div>';
                    return $this->html($img);
                }),
            Column::make(__('Project Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Project Code'), 'number')
                ->searchable()
                ->sortable(),
            Column::make(__('Project Pool'), 'pool_id')
                ->format(function($model) {
                    return (!empty($model->pool)) ? $model->pool->full_name: 'N/A';
                })
                ->searchable()
                ->sortable(),    
            Column::make(__('Funder'), 'funders')
                ->searchable()
                ->sortable(),
            Column::make(__('Project Start Date'), 'start_date')
                ->format(function($model){
                    return $model->start_date->format('m-Y');
                })
                ->searchable()
                ->sortable(),
            Column::make(__('Project Status'), 'status')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Project $model) {
                    return view('backend.claim.project.includes.actions', ['project' => $model]);
                }),
        ];
    }

}
