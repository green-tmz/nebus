<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show']);
    Route::get('/organizations/search/name', [OrganizationController::class, 'searchByName']);
    Route::get('/organizations/building/{building}', [OrganizationController::class, 'byBuilding']);
    Route::get('/organizations/activity/{activity}', [OrganizationController::class, 'byActivity']);
    Route::get('/organizations/nearby', [OrganizationController::class, 'byGeoLocation']);

    // Buildings
    Route::get('/buildings', [BuildingController::class, 'index']);

    // Activities
    Route::get('/activities', [ActivityController::class, 'index']);
});
