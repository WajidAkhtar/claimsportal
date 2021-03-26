<?php

use Tabuna\Breadcrumbs\Trail;
use App\Domains\Claim\Models\Project;
use App\Domains\Claim\Http\Controllers\Backend\Project\ProjectController;

// All route names are prefixed with 'admin.claim'.
Route::group([
    'prefix' => 'claim',
    'as' => 'claim.',
    // 'middleware' => config('boilerplate.access.middleware.confirm'),
], function () {
    Route::group([
        'prefix' => 'project',
        'as' => 'project.',
    ], function () {
        Route::group([
            // 'middleware' => 'role:'.config('boilerplate.access.role.admin').'|Project Partner',
        ], function () {
            Route::get('create', [ProjectController::class, 'create'])
                ->name('create')
                ->breadcrumbs(function (Trail $trail) {
                    $trail->parent('admin.claim.project.index')
                        ->push(__('Create Project'), route('admin.claim.project.create'));
                });

            Route::post('/', [ProjectController::class, 'store'])->name('store');

            Route::group(['prefix' => '{project}'], function () {
                Route::get('edit', [ProjectController::class, 'edit'])
                    ->name('edit')
                    ->breadcrumbs(function (Trail $trail, Project $project) {
                        $trail->parent('admin.claim.project.index')
                            ->push(__('Edit'), route('admin.claim.project.edit', $project));
                    });

                Route::patch('/', [ProjectController::class, 'update'])->name('update');
                Route::delete('/', [ProjectController::class, 'destroy'])->name('destroy');

                Route::post('/save-claims-data', [ProjectController::class, 'saveClaims'])->name('save.claims');
                Route::post('/save-partner-additional-data', [ProjectController::class, 'savePartnerAdditionalFields'])->name('save.partner.additional');
                Route::post('/save-sheet-user-permissions', [ProjectController::class, 'saveSheetUserPermissions'])->name('save.sheet.user.permissions');
                Route::post('/submit-claim', [ProjectController::class, 'submitClaim'])->name('submit.claim');
                Route::post('/close-claim', [ProjectController::class, 'closeClaim'])->name('close.claim');
            });
        });

        Route::group([
            // 'middleware' => 'permission:admin.access.project.list',
        ], function () {
            Route::get('/', [ProjectController::class, 'index'])
                ->name('index')
                // ->middleware('permission:admin.access.project.list|admin.access.project.deactivate|admin.access.project.clear-session|admin.access.project.impersonate|admin.access.project.change-password')
                ->breadcrumbs(function (Trail $trail) {
                    $trail->parent('admin.dashboard')
                        ->push(__('Project Management'), route('admin.claim.project.index'));
                });

            Route::group(['prefix' => '{project}'], function () {
                Route::get('/', [ProjectController::class, 'show'])
                    ->name('show')
                    // ->middleware('permission:admin.access.project.list')
                    ->breadcrumbs(function (Trail $trail, Project $project) {
                        $trail->parent('admin.claim.project.index')
                            ->push($project->name, route('admin.claim.project.show', $project));
                    });

                Route::patch('mark/{status}', [DeactivatedProjectController::class, 'update'])
                    ->name('mark')
                    ->where(['status' => '[0,1]']);
                    // ->middleware('permission:admin.access.project.deactivate|admin.access.project.reactivate');
            });
        });
    });
});
