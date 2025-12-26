<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuranLogController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Admin Auth (WEB)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// 🔐 Rate limit login (ANTI BRUTE FORCE)
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // DASHBOARD
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // =====================
        // USER MANAGEMENT
        // =====================
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');

        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggleActive');

        Route::patch('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])
            ->name('users.toggleRole');

        // 🔥 SOFT DELETE & RESTORE
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::patch('/users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore');

        // =====================
        // QURAN LOGS (ADMIN)
        // =====================
        Route::get('/quran-logs', [QuranLogController::class, 'index'])
            ->name('quran.logs');

        Route::get('/quran-logs/{log}', [QuranLogController::class, 'show'])
            ->name('quran.logs.show');
    });
