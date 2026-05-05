<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BodyguardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/how-it-works', [LandingController::class, 'howItWorks'])->name('how-it-works');

// Bodyguard Discovery — public, only shows verified
Route::get('/bodyguards', [BodyguardController::class, 'index'])->name('bodyguards.index');
Route::get('/bodyguards/{bodyguard}', [BodyguardController::class, 'show'])->name('bodyguards.show');



/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (role-aware redirect handled in controller)
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bodyguard — edit own profile
    Route::middleware('role:bodyguard')->group(function () {
        Route::get('/bodyguard/profile/edit', [BodyguardController::class, 'editProfile'])->name('bodyguard.profile.edit');
        Route::patch('/bodyguard/profile', [BodyguardController::class, 'updateProfile'])->name('bodyguard.profile.update');
    });

    // Bodyguard Registration (untuk user yang ingin menjadi bodyguard)
    Route::middleware('user.only')->group(function () {
        Route::get('/landing/bodyguard', [BodyguardController::class, 'landing'])->name('bodyguard.landing');
        Route::get('/register/bodyguard', [BodyguardController::class, 'registerForm'])->name('bodyguard.register');
        Route::post('/register/bodyguard', [BodyguardController::class, 'registerStore'])->name('bodyguard.register.store');
    });

    // Bookings (User only)
    Route::middleware('role:user')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create/{bodyguard}', [BookingController::class, 'create'])->name('bookings.create');
        // Maksimal 5 booking baru per menit per user
        Route::post('/bookings/{bodyguard}', [BookingController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('bookings.store');
        // Maksimal 5 retry payment per menit per user
        Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])
            ->middleware('throttle:5,1')
            ->name('bookings.pay');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        Route::get('/user/profile', [ProfileController::class, 'profile'])->name('user.profile');
        Route::put('/user/profile', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
    });

    // Booking detail (owner, assigned bodyguard, or admin)
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Status update oleh bodyguard yang ditugaskan (paid→active, active→completed)
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Chat (User OR Bodyguard — both parties to a booking)
    Route::get('/chat/{booking}', [ChatController::class, 'show'])->name('chat.show');
    // Maksimal 30 pesan per menit per user (anti-spam)
    Route::post('/chat/{booking}/messages', [ChatController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('chat.store');
    Route::get('/chat/{booking}/messages', [ChatController::class, 'messages'])
        ->name('chat.messages');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Bodyguard management
    Route::get('/bodyguards', [AdminController::class, 'bodyguards'])->name('bodyguards.index');
    Route::patch('/bodyguards/{bodyguard}/verify', [AdminController::class, 'verify'])->name('bodyguards.verify');
    Route::get('/bodyguards/{bodyguard}/edit', [AdminController::class, 'editBodyguard'])->name('bodyguards.edit');
    Route::patch('/bodyguards/{bodyguard}', [AdminController::class, 'updateBodyguard'])->name('bodyguards.update');
    Route::delete('/bodyguards/{bodyguard}', [AdminController::class, 'destroy'])->name('bodyguards.destroy');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
});

/*
|--------------------------------------------------------------------------
| Webhook Routes (CSRF-exempt)
|--------------------------------------------------------------------------
*/
Route::post('/webhook/midtrans', [PaymentWebhookController::class, 'handle'])
    ->name('webhook.midtrans')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
