<?php

use Illuminate\Http\Request;
use App\Transaction;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/{id}/{filename}', function ($id, $filename) {
    $model = Transaction::findOrFail($id);
    $model->isDone = 1;
    $pubPath = url('/').'/exports'.'/'.$filename;
    $model->publicPath = $pubPath;
    $model->save();
    return response('ok', 200);


});
