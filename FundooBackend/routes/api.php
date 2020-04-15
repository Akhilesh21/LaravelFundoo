<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;

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

/****fundoo login registration */

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('logout', 'UserController@logout');

Route::post('create', 'UserController@create');
Route::post('reset', 'UserController@reset');

Route::post('forgetPassword', 'UserController@forgetPassword');
Route::get('verifyMail/{token}', 'UserController@verifyMail');
Route::post('resetPassword/{token}', 'UserController@resetPassword');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'UserController@details');
});

//----------------fundoo---Notes---------------------------


Route::post('notes', 'NoteController@createNote'); 
Route::post('editNote', 'NoteController@editNote');
Route::post('trashNote', 'NoteController@trashNote');
Route::post('restoreNote', 'NoteController@restoreNote');
Route::post('archiveNote', 'NoteController@archiveNote');
Route::post('unarchiveNote', 'NoteController@unarchiveNote');
Route::post('deleteNotes', 'NoteController@deleteNotes');
Route::post('noteColor', 'NoteController@noteColor');
Route::post('updatePin', 'NoteController@updatePin');

//***********display */
Route::get('getNotes', 'NoteController@getNotes');
Route::get('getPinnedNote', 'NoteController@getPinnedNote');
Route::get('getUnPinNotes', 'NoteController@getUnPinNotes');
Route::get('displayTrash', 'NoteController@displayTrash');



Route::middleware('auth:api')->group(function () {
    Route::get('user', 'NoteController@details');
});
