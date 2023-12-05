<?php
use App\Http\Controllers\ImportCsvController;
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
    return view('welcome');
});

Route::get('/parse', 'App\Http\Controllers\ParsingController@parseData');
Route::get('/import', 'App\Http\Controllers\ImportCsvController@src')->name('import');
Route::post('/import-csv', 'App\Http\Controllers\ImportCsvController@importCSV')->name('importCSV');