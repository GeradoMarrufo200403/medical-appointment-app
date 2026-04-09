<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;


Route::redirect('/', '/admin');
//Route::get('/', function () {
    //return view('welcome');
//});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php')); 
});

