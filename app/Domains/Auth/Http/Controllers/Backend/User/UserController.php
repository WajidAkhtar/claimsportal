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
        return view('backend.auth.user.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $organisations = Organisation::ordered()->pluck('organisation_name', 'id');
        return view('backend.auth.user.create')
            ->withRoles($this->roleService->get())
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withPools($this->poolService->whereIn('id', auth()->user()->pools()->pluck('pool_id'))->get()->pluck('full_name', 'id'))
            ->withOrganisations($organisations)
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
            ->withPools($this->poolService->whereIn('id', auth()->user()->pools()->pluck('pool_id'))->get()->pluck('full_name', 'id'))
            ->withOrganisations($organisations)
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
