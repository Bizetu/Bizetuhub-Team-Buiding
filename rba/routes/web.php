<?php

use App\Http\Controllers\Front\UserEvaluationController;
use App\Http\Controllers\Front\NotificationController;
use App\Http\Controllers\Front\TrainingFeedController;
use App\Http\Controllers\Front\UserLicenseController;
use App\Http\Controllers\Front\CaseStudyController;
use App\Http\Controllers\Front\CommunityController;
use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Front\DueDateController;
use App\Http\Controllers\Front\CourseController;
use App\Http\Controllers\Front\SurveyController;
use App\Http\Controllers\Front\ForumController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SessionExpiry;
use App\Http\Controllers\Front\GoogleCalendarController;
use App\Http\Controllers\Front\UnsubscribeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-conditions', [PageController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/unsubscribe', [UnsubscribeController::class, 'unsubscribe'])
    ->name('unsubscribe');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginChoice'])->name('login.choice');
    Route::get('/login/email', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/email', [LoginController::class, 'login']);

    // SSO
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);

    Route::get('auth/microsoft', [MicrosoftController::class, 'redirectToProvider'])->name('auth.micorsoft');
    Route::get('user/callback', [MicrosoftController::class, 'handleProviderCallback'])->name('user.callback');

    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth', SessionExpiry::class])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Pages
    Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/green-growth', [PageController::class, 'greenGrowth'])->name('green-growth');

    Route::group(['prefix' => 'resources'], function () {
        Route::get('/events', [PageController::class, 'events'])->name('events');
        Route::get('/research', [PageController::class, 'research'])->name('research');
        Route::get('/external-links', [PageController::class, 'externalLinks'])->name('external-links');
        Route::get('/videos', [PageController::class, 'videos'])->name('videos');
    });

    Route::group(['prefix' => 'esg-guidelines'], function () {
        Route::get('/', [PageController::class, 'esgGuidelines'])->name('esg-guidelines');
        Route::get('/pillars', [PageController::class, 'esgPillars'])->name('pillars');
    });

    Route::get('/courses', [CourseController::class, 'courses'])->name('courses');
    Route::get('/courses/detail/{slug}', [CourseController::class, 'detail'])->name('courses.detail');
    Route::get('/courses/enroll/{id}', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/case-studies', [CaseStudyController::class, 'caseStudies'])->name('case-studies');
    Route::get('/casestudy/{id}', [CaseStudyController::class, 'singleCaseStudy'])->name('casestudy.details');

    // Notifications
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Profile
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::post('/profile', [UserController::class, 'updateMF'])->name('profile.complete');
    Route::get('/companyBranches/{company}', [UserController::class, 'companyBranches'])->name('companyBranches');
    Route::get('/departmentJobRoles/{department}', [UserController::class, 'departmentJobRoles'])->name('departmentJobRoles');
    Route::any('/calendar', [DueDateController::class, 'index'])->name('calendar');
    Route::any('/icalendar', [DueDateController::class, 'icalendar'])->name('icalendar');
    // Route::post('training-calendar/', [TrainingCalendarController::class, 'addToCalendar'])->name('trainingCalendar.add');
    // Route::get('/calendar', [FrontendController::class, 'calendar'])->name('calendar')->middleware('auth');

    Route::get('/google/calendar/redirect', [GoogleCalendarController::class, 'redirect'])->name('google.calendar.redirect');
    Route::get('/google/calendar/callback', [GoogleCalendarController::class, 'callback'])->name('google.calendar.callback');
    Route::post('/google/calendar/disconnect', [GoogleCalendarController::class, 'disconnect'])->name('google.calendar.disconnect');

    // Calendar event management (API endpoints)
    Route::post('/api/calendar/add', [GoogleCalendarController::class, 'addToCalendar'])->name('api.calendar.add');
    Route::post('/api/calendar/remove', [GoogleCalendarController::class, 'removeFromCalendar'])->name('api.calendar.remove');
    Route::get('/api/calendar/status', [GoogleCalendarController::class, 'status'])->name('api.calendar.status');

    //Profile inner pages
    Route::group(['prefix' => 'profile'], function () {
        Route::any('/myProfile', function () {
            return redirect()->route('profile.courses', request()->query());
        })->name('myProfile');
        Route::any('/profile-courses', [UserLicenseController::class, 'courses'])->name('profile.courses');
        Route::any('/profile-progress', [UserLicenseController::class, 'progress'])->name('profile.progress');
        Route::any('/downloadProgress', [UserLicenseController::class, 'downloadProgress'])->name('profile.downloadProgress');
        Route::any('/certificateOverall/{userid}', [UserLicenseController::class, 'certificateOverall'])->name('profile.certificateOverall');
        Route::any('/certificateSingleModule/{courseid}', [UserLicenseController::class, 'certificateSingleModule'])->name('profile.certificateSingleModule');
        Route::any('/download/adminCertificateDownload/{id}', [UserLicenseController::class, 'adminCertificateDownload'])->name('download.adminCertificateDownload');
    });

    // Course Evaluations
    Route::get('/course/evaluate/{courseid}', [UserEvaluationController::class, 'show'])->name('course.evaluate');
    Route::post('/course/evaluate/store', [UserEvaluationController::class, 'store'])->name('course.evaluate.store');

    Route::middleware(['throttle:forums'])->group(function () {

        // Training Feed
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/trainingFeed', [TrainingFeedController::class, 'index'])->name('profile.trainingFeed');
            Route::post('/trainingFeed/comment', [TrainingFeedController::class, 'addComment'])->name('profile.trainingFeed.comment');
            Route::post('/trainingFeed/like', [TrainingFeedController::class, 'toggleLike'])->name('profile.trainingFeed.like');
            Route::get('/trainingFeed/comments/{licenseId}/{courseId}', [TrainingFeedController::class, 'getComments'])->name('profile.trainingFeed.comments');
            Route::get('/trainingFeed/followers/{userId}', [TrainingFeedController::class, 'getFollowers'])->name('profile.trainingFeed.followers');

            Route::any('/like/{follow}/{id}/', [UserLicenseController::class, 'myLike'])->name('profile.myLike');
            Route::post('/comment', [UserLicenseController::class, 'myComment'])->name('profile.myComment');
            Route::get('/comments/{follow}/{course}/', [UserLicenseController::class, 'myComments'])->name('profile.myComments');
            Route::get('/subCommentCount/{follow}/{course}/{id}/', [UserLicenseController::class, 'subCommentCount'])->name('profile.subCommentCount');
            Route::get('/subComment/{follow}/{course}/{id}/', [UserLicenseController::class, 'subComment'])->name('profile.subComment');
        });
        // My Community
        Route::get('/myCommunity', [CommunityController::class, 'index'])->name('community.index');
        Route::get('/myCommunity/following', [CommunityController::class, 'following'])->name('community.following');
        Route::get('/myCommunity/followers', [CommunityController::class, 'followers'])->name('community.followers');
        Route::get('/myCommunity/requests', [CommunityController::class, 'requests'])->name('community.requests');

        Route::post('/myCommunity/follow/{targetUser}', [CommunityController::class, 'follow'])->name('community.follow');
        Route::post('/myCommunity/unfollow/{targetUser}', [CommunityController::class, 'unfollow'])->name('community.unfollow');

        Route::post('/myCommunity/requests/{followRequest}/accept', [CommunityController::class, 'acceptRequest'])->name('community.requests.accept');
        Route::post('/myCommunity/requests/{followRequest}/decline', [CommunityController::class, 'declineRequest'])->name('community.requests.decline');

        Route::get('/myCommunity/messages', [CommunityController::class, 'messages'])->name('community.messages.index');
        Route::get('/myCommunity/messages/{targetUser}', [CommunityController::class, 'showMessages'])->name('community.messages.show');
        Route::post('/myCommunity/messages/{targetUser}/send', [CommunityController::class, 'sendMessage'])->name('community.messages.send');

        // Discussion Forums
        Route::get('/forums', [ForumController::class, 'index'])->name('forums');
        Route::get('/forums/create', [ForumController::class, 'create'])->name('community.forums.create');
        Route::post('/forums', [ForumController::class, 'store'])->name('community.forums.store');
        Route::get('/forums/{forum}', [ForumController::class, 'show'])->name('community.forums.show');

        Route::post('/forums/{forum}/join', [ForumController::class, 'join'])->name('community.forums.join');
        Route::post('/forums/{forum}/posts', [ForumController::class, 'createPost'])->name('community.forums.posts.create');

        // Creator only routes
        Route::get('/forums/{forum}/requests', [ForumController::class, 'pendingRequests'])->name('community.forums.requests');
        Route::post('/forums/{forum}/members/{member}/approve', [ForumController::class, 'approveMember'])->name('community.forums.members.approve');
        Route::post('/forums/{forum}/members/{member}/reject', [ForumController::class, 'rejectMember'])->name('community.forums.members.reject');
        Route::post('/forums/{forum}/block/{targetUser}', [ForumController::class, 'blockUser'])->name('community.forums.block');

        // Forum member management
        Route::get('/forums/{forum}/members', [ForumController::class, 'manageMembers'])->name('community.forums.members');
        Route::post('/forums/{forum}/members/{member}/remove', [ForumController::class, 'removeMember'])->name('community.forums.members.remove');
        Route::post('/forums/{forum}/unblock/{targetUser}', [ForumController::class, 'unblockUser'])->name('community.forums.unblock');

        // Forum member management
        Route::get('/survey/data', [SurveyController::class, 'getSurveyData'])->name('survey.data');
        Route::post('/survey/submit', [SurveyController::class, 'submit'])->name('survey.submit');
    });
});
