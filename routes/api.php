<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show']);
    Route::prefix('/organizations/search')->group(function () {
        Route::post('name', [OrganizationController::class, 'searchByName']);
        Route::get('building/{building}', [OrganizationController::class, 'searchByBuilding']);
        Route::get('activity/{activity}', [OrganizationController::class, 'searchByActivity']);
        Route::post('nearby', [OrganizationController::class, 'searchByGeoLocation']);
    });
});
