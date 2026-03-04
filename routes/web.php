<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/colocations', [ColocationController::class, 'store'])
        ->name('colocations.store');

    Route::get('/colocation/{colocation}', [ColocationController::class, 'show'])
        ->name('colocations.show');

    Route::get('/invitations/{token}/{action}', [InvitationController::class, 'respond'])
        ->name('invitations.respond');

    Route::post('colocations/{colocation}/invite', [InvitationController::class, 'store'])
        ->name('invitations.store');

    Route::get('/invitations/{token}', [InvitationController::class, 'accept'])
        ->name('invitations.accept');

    Route::post('/memberships/{membership}/remove', [MembershipController::class, 'remove'])
        ->name('memberships.remove');

    Route::post('/colocation/leave', [MembershipController::class, 'leave'])
        ->name('memberships.leave');

    Route::get('/colocation/{colocation}/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::post('/colocation/{colocation}/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
        
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

});

Route::get('/colocations/{colocation}/expenses/create', [ExpenseController::class, 'create'])
    ->name('expenses.create');

Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])
    ->name('expenses.store');


Route::post('/payments', [PaymentController::class, 'store'])
    ->name('payments.store')->middleware('auth');

Route::post('/colocation/{colocation}/cancel', [ColocationController::class, 'cancel'])
    ->name('colocations.cancel')
    ->middleware('auth');

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/toggle-ban/{user}', [AdminController::class, 'toggleBan'])->name('admin.toggleBan');
});



require __DIR__ . '/auth.php';
