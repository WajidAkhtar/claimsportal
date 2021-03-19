<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Http\Requests\Backend\User\DeleteUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\EditUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\StoreUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\UpdateUserRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\PermissionService;
use App\Domains\Auth\Services\RoleService;
use App\Domains\Auth\Services\UserService;
use App\Domains\System\Services\PoolService;
use App\Domains\System\Services\OrganisationService;
use App\Domains\System\Models\Organisation;

/**
 * Class UserController.
 */
class UserController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * @var PoolService
     */
    protected $poolService;

    /**
     * @var OrganisationService
     */
    protected $organisationService;

    /**
     * UserController constructor.
     *
     * @param  UserService  $userService
     * @param  RoleService  $roleService
     * @param  PermissionService  $permissionService
     * @param  PoolService  $poolService
     * @param  OrganisationService  $organisationService
     */
    public function __construct(UserService $userService, RoleService $roleService, PermissionService $permissionService, PoolService $poolService, OrganisationService $organisationService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->poolService = $poolService;
        $this->organisationService = $organisationService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $allowToCreate = true;
        $roles = $this->roleService->all();
        if(auth()->user()->hasRole('Project Partner') || auth()->user()->hasRole('Funder')) {
            $allowToCreate = false;
            return redirect()->route('admin.dashboard')->withFlashDanger(__('You do not have access to user management module.'));
        }
        return view('backend.auth.user.index')->withAllowToCreate($allowToCreate)->withRoles($roles);
    }

    /**
     * @return mixed
     */
    public function create($role = '')
    {
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        return view('backend.auth.user.create')
            ->withRoles($this->roleService->get())
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withPools(current_user_pools()->pluck('full_name', 'id'))
            ->withOrganisations($organisations)
            ->withDefaultRole($role)
            ->withProjectRoles([
                'COLLABORATOR' => 'COLLABORATOR',
                'FUNDER' => 'FUNDER',
                'CO-FUNDER' => 'CO-FUNDER',
                'LEAD' => 'LEAD',
            ])
            ->withGeneral($this->permissionService->getUncategorizedPermissions());
    }

    /**
     * @param  StoreUserRequest  $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->store($request->validated());

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('The user was successfully created.'));
    }

    /**
     * @param  User  $user
     *
     * @return mixed
     */
    public function show(User $user)
    {
        return view('backend.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param  EditUserRequest  $request
     * @param  User  $user
     *
     * @return mixed
     */
    public function edit(EditUserRequest $request, User $user)
    {
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($this->roleService->get())
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions())
            ->withPools(current_user_pools()->pluck('full_name', 'id'))
            ->withOrganisations($organisations)
            ->withDefaultRole($user->roles()->first()->name)
            ->withCorrespondenceAddress($user->correspondenceAddress()->first())
            ->withProjectRoles([
                'COLLABORATOR' => 'COLLABORATOR',
                'FUNDER' => 'FUNDER',
                'CO-FUNDER' => 'CO-FUNDER',
                'LEAD' => 'LEAD',
            ])
            ->withUsedPermissions($user->permissions->modelKeys());
    }

    /**
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     *
     * @return mixed
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        if(!in_array(current_user_role(), ['Administrator', 'Super User'])) {
            return redirect()->route('admin.auth.user.edit', [$user->id])->withFlashSuccess(__('Your information has successfully saved.'));    
        }
        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('The user was successfully updated.'));
    }

    /**
     * @param  DeleteUserRequest  $request
     * @param  User  $user
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(DeleteUserRequest $request, User $user)
    {
        $this->userService->delete($user);

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('The user was successfully deleted.'));
    }
}
