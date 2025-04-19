<?php

use App\Http\Controllers\Admin\GoogleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfAuthenticated;

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes();
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth', 'user-access:user'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/activities', [App\Http\Controllers\Student\ActivityController::class, 'index'])->name('student.activities');

    Route::post('/activity/{id}/apply', [App\Http\Controllers\Student\ActivityController::class, 'apply'])->name('student.activities.apply');
    Route::delete('/activity/{id}/cancel', [App\Http\Controllers\Student\ActivityController::class, 'cancel'])->name('student.activities.cancel');
    Route::get('/activity/{id}', [App\Http\Controllers\Student\ActivityController::class, 'view'])->name('student.activities.view');
    Route::get('/my-activity', [App\Http\Controllers\Student\ActivityController::class, 'myActivity'])->name('student.my-activity');

    // For Google OAuth
    Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    Route::get('/admin/google/revoke', [GoogleController::class, 'revokeAccess'])->name('google.revoke');
    // Route::get('/admin/google/reconnect', [App\Http\Controllers\Admin\GoogleController::class, 'reconnectToGoogle'])->name('google.reconnect');
    Route::post('/calendar/sync', [\App\Http\Controllers\Student\ActivityController::class, 'syncCalendar'])->name('calendar.sync');
    Route::get('/connect-google', [GoogleController::class, 'connectToGoogle'])->name('connect.google');
});


Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    Route::resource('admin/activities', \App\Http\Controllers\Admin\ActivityController::class);
    Route::get('/admin/list-student', [StudentController::class, 'index'])->name('admin.students');
    Route::post('/admin/activities/{id}/upload-qr', [App\Http\Controllers\Admin\ActivityController::class, 'uploadQr'])->name('activities.uploadQr');

    Route::get('/create', [App\Http\Controllers\Admin\ActivityController::class, 'create'])->name('activities.create');
    Route::post('/create-activity', [App\Http\Controllers\Admin\ActivityController::class, 'store'])->name('activities.store');
    Route::get('/{id}', [App\Http\Controllers\Admin\ActivityController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\ActivityController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Admin\ActivityController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Admin\ActivityController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/end', [App\Http\Controllers\Admin\ActivityController::class, 'end'])->name('activities.end');

    Route::post('/admin/activity/{id}/accept/{applicationId}', [App\Http\Controllers\Admin\ActivityController::class, 'acceptStudent'])->name('admin.activity.accept');
    Route::post('/admin/activity/{id}/reject/{applicationId}', [App\Http\Controllers\Admin\ActivityController::class, 'rejectStudent'])->name('admin.activity.reject');
});
