<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/user');
});

Route::get('/user', [NewsController::class, 'index'])->name("user.user");

Route::get('/admin', [NewsController::class, 'admin'])->name("user.admin");

Route::get('/create', [NewsController::class, 'create'])->name("user.create");
Route::post('/create', [NewsController::class, 'store'])->name("user.store");

Route::delete('admin/{news}', [NewsController::class, 'destroy'])->name('user.destroy');
