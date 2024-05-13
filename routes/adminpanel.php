<?php

    use FefoP\AdminPanel\AdminPanel;
    use FefoP\AdminPanel\Models\Role;
    use Illuminate\Support\Facades\Route;
    use FefoP\AdminPanel\Models\Permission;
    use FefoP\AdminPanel\Middlewares\IsAdminUserPermission;

    /**
    |--------------------------------------------------------------------------
    | Admin Panel Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::middleware([ 'web', IsAdminUserPermission::class, config('jetstream.auth_session', 'verified') ])->group(function () {
        Route::prefix('adminpanel')->group(function () {

            Route::controller(AdminPanel::class)->group(function () {
                Route::get('/', 'index')->name('adminpanel.dashboard');
                Route::get('/about', 'about')->name('adminpanel.about');
                Route::get('/users', 'users')->name('adminpanel.users');
                Route::get('/roles', 'roles')->name('adminpanel.roles');
                Route::get('/permissions', 'permissions')->name('adminpanel.permissions');
            });

        });
    });
