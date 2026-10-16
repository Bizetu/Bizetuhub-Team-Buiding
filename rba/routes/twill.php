<?php

use A17\Twill\Facades\TwillRoutes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Twill\CompletionReportController;
use App\Http\Controllers\Twill\UserEvaluationController;
use App\Http\Controllers\Twill\UserLicenseController;
use App\Http\Controllers\Twill\UserSurveyController;
use App\Http\Controllers\Twill\CourseController;
use App\Http\Controllers\Twill\UserController;
use App\Http\Controllers\Twill\SerialNumberController;
use App\Http\Controllers\Twill\MessageController;

// Register Twill routes here eg.
// TwillRoutes::module('posts');

TwillRoutes::module('menuTypes');
TwillRoutes::module('menus');
TwillRoutes::module('pages');
TwillRoutes::module('platformSettings');
TwillRoutes::module('companies');
TwillRoutes::module('domains');
TwillRoutes::module('departments');
TwillRoutes::module('jobRoles');
TwillRoutes::module('faqCategories');
TwillRoutes::module('faqs');
TwillRoutes::module('caseStudies');
TwillRoutes::module('evaluationOptions');
TwillRoutes::module('courseCategories');
TwillRoutes::module('courseTypes');
TwillRoutes::module('evaluations');
TwillRoutes::module('userEvaluations');
TwillRoutes::module('branches');
TwillRoutes::module('courses');
TwillRoutes::module('userLicenses');
TwillRoutes::module('completionReports');
TwillRoutes::module('partners');

// Route::get('meessageDownload/export', 'MessageController@export')->name('platformMessageDownload.export');
Route::post('/Messages/updates/', [MessageController::class, 'messageUpdate'])->name('messageUpdate');
Route::any('/branch-by-company/', [MessageController::class, 'getBranchesForCompany'])->name('getBranchesForCompany');
Route::any('/jobroles-by-department/', [MessageController::class, 'getJobRolesForDepartment'])->name('getJobRolesForDepartment');
Route::any('/departments-by-company/{company}', [MessageController::class, 'getDepartmentsForCompany'])->name('getDepartmentsForCompany');

Route::group(['prefix' => 'export'], function () {
    Route::get('/completionReports', [CompletionReportController::class, 'export'])->name('export.completionReports');
    Route::get('/userEvaluations', [UserEvaluationController::class, 'export'])->name('export.userEvaluations');
    Route::get('/userLicenses', [UserLicenseController::class, 'export'])->name('export.userLicenses');
    Route::get('/userSurveys', [UserSurveyController::class, 'export'])->name('export.userSurveys');
    Route::get('/users', [UserController::class, 'export'])->name('export.users');
    Route::any('/courseAverage', [CompletionReportController::class, 'exportCourseAverage'])->name('export.courseAverage');
    Route::any('/jobRoleCompletions', [CompletionReportController::class, 'exportJobRoleCompletions'])->name('export.jobRoleCompletions');
    Route::get('/subsidiaryCompletions', [CompletionReportController::class,'exportSubsidiaryCompletions'])->name('export.subsidiaryCompletions');
    Route::get('/certifiedUsers', [SerialNumberController::class, 'exportCertifiedUsers'])->name('exports.certifiedUsers');
    Route::get('/messages', [MessageController::class, 'exportMessages'])->name('exports.messages');
});

Route::group(['prefix' => 'archive', 'middleware' => ['can:archive']], function () {
    Route::get('/users', [UserController::class, 'archiveUsersForm'])->name('archiveUsersForm');
    Route::get('/template', [UserController::class, 'template'])->name('archive.template');
    Route::post('/users', [UserController::class, 'archiveUsers'])->name('archive.users');
    Route::post('/restore', [UserController::class, 'restoreUsers'])->name('archive.restore');
});

Route::group(['prefix' => 'graph'], function () {
    Route::name('graph.dashboard')->get('dashboard', [CompletionReportController::class, 'dashboard']);
    Route::name('graph.eresults')->get('eresults', [CompletionReportController::class, 'eresults']);
    Route::name('graph.cresults')->get('cresults', [CompletionReportController::class, 'cresults']);
    Route::name('graph.yearlyEResults')->get('yearlyEResults', [CompletionReportController::class, 'yearlyEResults']);
    Route::name('graph.yearlyCRresults')->get('yearlyCRresults', [CompletionReportController::class, 'yearlyCRresults']);

    Route::any('subsidiaryCompletion', [CompletionReportController::class, 'subsidiaryCompletion'])->name('subsidiaryCompletion');
    Route::any('courseAverage', [CompletionReportController::class, 'courseAverage'])->name('courseAverage');
    Route::any('jobRoleCompletions', [CompletionReportController::class, 'jobRoleCompletions'])->name('jobRoleCompletions');
});

Route::get('/course/sync', [CourseController::class, 'courseSync'])->name('course.sync');

TwillRoutes::module('dueDates');
TwillRoutes::module('serialNumbers');

TwillRoutes::module('surveys');
TwillRoutes::module('userSurveys');
TwillRoutes::module('messages');
