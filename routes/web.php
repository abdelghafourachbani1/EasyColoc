<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/colocations', [ColocationController::class, 'store'])
        ->name('colocations.store');

    Route::middleware('auth')->get('/colocation', [ColocationController::class, 'show'])
    ->name('colocations.show');

});

Route::middleware('auth')->group(function() {
    Route::post('/invitations' , [InvitationController::class , 'store'])
        ->name('invitations.store');

    Route::get('/invitations/{token}/{action}' , [InvitationController::class , 'repond'])
        ->name('invitations.respod');
});

Route::middleware('auth')->group(function () {
    Route::post('colocations/{colocation}/invite',[InvitationController::class , 'store'])
        ->name('invitations.store');

    Route::get('/invitations/{token}' , [InvitationController::class , 'accept'])
        ->name('invitations.accept');

    Route::post('/memberships/{membership}/remove' , [MembershipController::class , 'remove'])
        ->name('memberships.remove');

    Route::post('/colocation/leave' , [MembershipController::class , 'leave'])
        ->name('memberships.leave');
    
});

Route::post('/colocations/{colocation}/expenses', [ExpenseController::class , 'store'])
    ->name('expenses.store')->middleware('auth');


require __DIR__.'/auth.php';
