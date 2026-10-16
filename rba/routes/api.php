<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\CourseController;
use App\Http\Controllers\Front\UserLicenseController;
use App\Http\Controllers\Front\UserController;

// use App\Http\Controllers\Front\D365Controller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('courses/process-reflection', [CourseController::class, 'processReflection'])->name('process-reflection');
Route::post('/profile/dashboard', [UserLicenseController::class, 'processProfile'])->name('profile.dashboard');
