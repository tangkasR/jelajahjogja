<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\DestinationController;
use App\Http\Controllers\Public\SubmissionController;
use App\Http\Controllers\Public\ReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\TripPlannerController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::post('/destinasi/{slug}/review', [ReviewController::class, 'store'])->name('destinations.review');
Route::get('/submit', [SubmissionController::class, 'create'])->name('submit');
Route::post('/submit', [SubmissionController::class, 'store'])->name('submit.store');
Route::get('/trip-planner', [TripPlannerController::class, 'index'])->name('trip-planner');
Route::post('/trip-planner/generate', [TripPlannerController::class, 'generate'])->name('trip-planner.generate');
Route::get('/trip-planner/result', [TripPlannerController::class, 'result'])->name('trip-planner.result');

// Auth routes
Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/change-password', [App\Http\Controllers\Admin\PasswordController::class, 'index'])->name('admin.password.index');
Route::post('/change-password', [App\Http\Controllers\Admin\PasswordController::class, 'update'])->name('admin.password.update');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/destinations', [AdminDestinationController::class, 'index'])->name('destinations.index');
    Route::get('/destinations/create', [AdminDestinationController::class, 'create'])->name('destinations.create');
    Route::post('/destinations', [AdminDestinationController::class, 'store'])->name('destinations.store');
    Route::get('/destinations/{destination}', [AdminDestinationController::class, 'show'])->name('destinations.show');
    Route::get('/destinations/{destination}/edit', [AdminDestinationController::class, 'edit'])->name('destinations.edit');
    Route::put('/destinations/{destination}', [AdminDestinationController::class, 'update'])->name('destinations.update');
    Route::delete('/destinations/{destination}', [AdminDestinationController::class, 'destroy'])->name('destinations.destroy');
    Route::patch('/destinations/{destination}/approve', [AdminDestinationController::class, 'approve'])->name('destinations.approve');
    Route::patch('/destinations/{destination}/reject', [AdminDestinationController::class, 'reject'])->name('destinations.reject');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});
