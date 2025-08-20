<?php

use Illuminate\Support\Facades\Route;

//TODO: ->middleware('auth:sanctum');
Route::prefix('v1')->group(function () {
    Route::apiResource('companies', App\Http\Controllers\Api\V1\CompanyController::class);
    Route::apiResource('employees', App\Http\Controllers\Api\V1\EmployeeController::class);
});
