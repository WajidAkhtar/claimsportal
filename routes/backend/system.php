<?php

use App\Domains\System\Models\Organisation;

// All route names are prefixed with 'admin.system'.

Route::group([
    'prefix' => 'system',
    'as' => 'system.',
], function () {
    Route::get('organisation/{organisation_id}', function($organisation_id) {
    	return response()->json(Organisation::find($organisation_id));
    })->name('get.organisation.details');
});

