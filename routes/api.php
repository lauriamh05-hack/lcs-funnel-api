<?php

use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::post('/leads', [LeadController::class, 'store']);
Route::patch('/leads/{lead}/action', [LeadController::class, 'action']);

Route::get('/stats', [StatsController::class, 'index']);
Route::patch('/leads/{lead}/enroll', [StatsController::class, 'markEnrolled']);
