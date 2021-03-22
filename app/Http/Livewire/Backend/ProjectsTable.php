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
    public $sort_attribute = 'organisations.organisation_name';

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
        $query = Project::with('funders')->select('*', \DB::raw('SELECT p.id, GROUP_CONCAT(o.organisation_name) as funders FROM `project_funders` pf LEFT JOIN projects p on p.id = pf.project_id LEFT JOIN organisations o on o.id = pf.organisation_id WHERE p.id IS NOT NULL group by pf.project_id'));

        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User')) {
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
                ->format(function($model) {
                    return (!empty($model->funders()->pluck('organisations.organisation_name'))) ? $model->funders()->pluck('organisations.organisation_name')->implode(',') : 'N/A';
                })
                ->searchable()
                ->sortable(),
            // Column::make(__('Project Length'), 'length')
            //     ->format(function($model){
            //         return $model->length.' quarters';
            //     })
            //     ->searchable()
            //     ->sortable(),
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
