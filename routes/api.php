<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('notariat_actions', '\\App\\Http\\Controllers\\Api\\TblDocumentTypesController@notoriat_actions');
Route::get('documents/{id}', '\\App\\Http\\Controllers\\Api\\TblDocumentsController@documents');
Route::get('store_ticket', '\\App\\Http\\Controllers\\Api\\TblTicketsController@store_ticket');
Route::get('ticket_status', '\\App\\Http\\Controllers\\Api\\TblTicketsController@ticket_status');
Route::get('my_tickets', '\\App\\Http\\Controllers\\Api\\TblTicketsController@my_tickets');
Route::get('test', function (){ return response()->json(['data'=>['ok']]); });
