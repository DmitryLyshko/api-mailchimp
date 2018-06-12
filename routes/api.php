<?php

use Illuminate\Http\Request;

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

// List actions
Route::get('lists', 'ApiListController@getLists');
Route::patch('lists', 'ApiListController@patchList');
Route::post('lists', 'ApiListController@createList');
Route::delete('lists', 'ApiListController@deleteList');

//Member list actions
Route::post('members', 'ApiMemberController@createMemberList');
Route::get('members', 'ApiMemberController@getMembers');
Route::patch('members', 'ApiMemberController@editMemberList');
Route::delete('members', 'ApiMemberController@deleteMemberList');
