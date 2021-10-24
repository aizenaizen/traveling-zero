<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ZeroController;
use App\Http\Controllers\MatrixController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/path_finder', [ZeroController::class, 'path_finder']);
Route::get('/matrix/{algo}/{grid?}/{start?}/{end?}/{wall?}', [MatrixController::class, 'index']);

Route::view('/problem', 'problem');
