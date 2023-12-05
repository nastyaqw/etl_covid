<?php

use App\Http\Controllers\API\DatatableController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::post('register', 'RegisterController@register');

Route::post('/register', [RegisterController::class, 'register'])->middleware('isadmin')  -> name('register');

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/datatable', [DatatableController::class, 'index'])->middleware('auth:api')  -> name('index');
Route::post('/datatable', [DatatableController::class, 'store'])->middleware('isadmin')  -> name('store');
Route::get('/datatable/{id}', [DatatableController::class, 'show'])->middleware('auth:api')  -> name('show');
Route::put('/datatable/{id}', [DatatableController::class, 'update'])->middleware('isadmin')  -> name('update');
Route::delete('/datatable/{id}', [DatatableController::class, 'destroy'])->middleware('isadmin')  -> name('destroy');
//Route::middleware('auth:api')->group( function () {
   /// Route::resource('datatable', DatatableController::class);
  //});
