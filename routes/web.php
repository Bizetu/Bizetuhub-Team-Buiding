<?php

use App\Http\Controllers\Front\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::redirect('/home', '/', 301)->name('home.alias');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/services/{service:slug}', [PageController::class, 'showService'])->name('services.show');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/events/{event:slug}', [PageController::class, 'showEvent'])->name('events.show');
Route::get('/industries', [PageController::class, 'industries'])->name('industries');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/packages', [PageController::class, 'packages'])->name('packages');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/insights', [PageController::class, 'insights'])->name('insights');
Route::get('/insights/{insight:slug}', [PageController::class, 'showInsight'])->name('insights.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeEnquiry'])->middleware('throttle:5,1')->name('contact.store');
Route::get('/book', [PageController::class, 'book'])->name('book');
Route::post('/book', [PageController::class, 'storeBooking'])->middleware('throttle:5,1')->name('book.store');
Route::get('/pages/{slug}', [PageController::class, 'page'])->name('pages.show');
// RBA-style root-level URLs for custom CMS pages. Fixed routes above remain authoritative.
Route::get('/{slug}', [PageController::class, 'page'])
    ->where('slug', '(?!admin$)[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('pages.root');
