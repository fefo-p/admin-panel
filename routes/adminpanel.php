<?php

    use FefoP\AdminPanel\AdminPanel;
    use Illuminate\Support\Facades\Route;
    use FefoP\AdminPanel\Middlewares\IsAdmin;
    use FefoP\AdminPanel\Controllers\UserController;
    use App\Models\User;
    use FefoP\AdminPanel\Models\Role;
    use FefoP\AdminPanel\Models\Permission;
    
    
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::middleware( [ 'web', IsAdmin::class, config( 'jetstream.auth_session', 'verified' ) ] )->group( function() {
        Route::prefix( 'adminpanel' )->group( function() {

            Route::controller( AdminPanel::class )->group( function() {
                Route::get( '/', 'index' )->name( 'adminpanel.dashboard' );
                Route::get( '/about', 'about' )->name( 'adminpanel.about' );
                Route::get( '/users', 'users' )->name( 'adminpanel.users' );
                Route::get( '/roles', 'roles' )->name( 'adminpanel.roles' )->can( 'administer', Role::class );
                Route::get( '/permissions', 'permissions' )->name( 'adminpanel.permissions' )->can( 'administer', Permission::class );
            } );

            /*Route::controller( UserController::class )->group( function() {
                Route::get( '/users/{user}', 'show' )->name( 'adminpanel.users.show' )->can( 'ver usuarios' );
            } );*/

        } );
    } );
