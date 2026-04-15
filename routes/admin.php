<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

//candado directo y explicito en este archivo
Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
  
    
    Route:: get ('/',function(){
        return view('admin.dashboard');
    })->name('dashboard');

    //Gestion de roles
    Route::resource('roles', RoleController::class);

    //Gestion de usuarios
    Route::resource('users', UserController::class);

});  