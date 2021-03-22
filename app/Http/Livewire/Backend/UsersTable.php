<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class UsersTable.
 */
class UsersTable extends TableComponent
{
    use HtmlComponents;

    /**
     * @var string
     */
    public $sortField = 'first_name';

    /**
     * @var string
     */
    public $status;

    public $role;

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
    public function mount($status = 'active', $role = 'Administrator'): void
    {
        $this->status = $status;
        $this->role = $role;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = User::with('roles', 'twoFactorAuth')
            ->withCount('twoFactorAuth');

        // if(current_user_role() == 'Super User') {
        //     $query = $query->whereHas('roles', function($q) {
        //         $q->whereIn('name', ['Finance Officer', 'Project Admin', 'Project Partner', 'Funder']);
        //     });
        // } else if(current_user_role() == 'Finance Officer') {
        //     $query = $query->whereHas('roles', function($q) {
        //         $q->whereIn('name', ['Project Admin', 'Project Partner', 'Funder']);
        //     });
        // } else if(current_user_role() == 'Project Admin') {
        //     $query = $query->whereHas('roles', function($q) {
        //         $q->whereIn('name', ['Project Partner', 'Funder']);
        //     });
        // } else if(current_user_role() == 'Project Partner') {
        //     $query = $query->whereHas('roles', function($q) {
        //         $q->whereIn('name', ['Funder']);
        //     });
        // }

        $query = $query->whereHas('roles', function($q) {
            $q->whereIn('name', [$this->role->name]);
        });

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
            // Column::make(__('Type'), 'type')
            //     ->sortable()
            //     ->format(function (User $model) {
            //         return view('backend.auth.user.includes.type', ['user' => $model]);
            //     }),
            Column::make(__('First Name'), 'first_name')
                ->searchable()
                ->sortable(),
            Column::make(__('Surname'), 'last_name')
                ->searchable()
                ->sortable(),
            Column::make(__('Job Title'), 'job_title')
                ->searchable()
                ->sortable(),
            Column::make(__('Organisation'), 'organisation_id')
                ->format(function($model) {
                    return (!empty($model->organisation)) ? $model->organisation->organisation_name: 'N/A';
                })
                ->searchable()
                ->sortable(),
            Column::make(__('E-mail'), 'email')
                ->searchable()
                ->sortable()
                ->format(function (User $model) {
                    return $this->mailto($model->email);
                }),
            // Column::make(__('Verified'), 'email_verified_at')
            //     ->sortable()
            //     ->format(function (User $model) {
            //         return view('backend.auth.user.includes.verified', ['user' => $model]);
            //     }),
            // Column::make(__('2FA'))
            //     ->sortable(function ($builder, $direction) {
            //         return $builder->orderBy('two_factor_auth_count', $direction);
            //     })
            //     ->format(function (User $model) {
            //         return view('backend.auth.user.includes.2fa', ['user' => $model]);
            //     }),
            // Column::make(__('Roles'), 'roles_label')
            //     ->searchable(function ($builder, $term) {
            //         return $builder->orWhereHas('roles', function ($query) use ($term) {
            //             return $query->where('name', 'like', '%'.$term.'%');
            //         });
            //     })
            //     ->format(function (User $model) {
            //         return $this->html($model->roles_label);
            //     }),
            // Column::make(__('Additional Permissions'), 'permissions_label')
            //     ->searchable(function ($builder, $term) {
            //         return $builder->orWhereHas('permissions', function ($query) use ($term) {
            //             return $query->where('name', 'like', '%'.$term.'%');
            //         });
            //     })
            //     ->format(function (User $model) {
            //         return $this->html($model->permissions_label);
            //     }),
            Column::make(__('Actions'))
                ->format(function (User $model) {
                    return view('backend.auth.user.includes.actions', ['user' => $model]);
                }),
        ];
    }
}
