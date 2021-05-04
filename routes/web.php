<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\DataSourceController;
use App\Http\Controllers\ConnectionStringController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\QueryParamController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ColorMapController;
use App\Http\Controllers\LabelMapController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('_api')->group(function () {
    Route::get('data-source/{query}/{connection_string}', [DataSourceController::class, 'getDataSourceFromConnStringAndQuery'])->name('data-source.get-one');;
    Route::post('connection-string/test', [ConnectionStringController::class, 'checkConnectionString'])->name('connection-string.test');
    Route::post('query/test/{connection_string}', [QueryController::class, 'checkSQL'])->name('query.test');;
    Route::patch('element/{element}/config', [ElementController::class, 'updateConfig']);
    Route::post('element/{element}/image', [ElementController::class, 'updateImageConfig']);
    crudRoutes('element', ElementController::class);
    crudRoutes('connection-string', ConnectionStringController::class);
    crudRoutes('query', QueryController::class);
    crudRoutes('query-param', QueryParamController::class);
    crudRoutes('document', DocumentController::class);
    crudRoutes('page', PageController::class);
    crudRoutes('color-map', ColorMapController::class);
    crudRoutes('label-map', LabelMapController::class);
});
