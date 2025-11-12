<?php

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

Route::group([ 'middleware' => 'api', 'prefix' => 'auth' ], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});
Route::group([ 'middleware' => 'auth:api', 'prefix' => 'collaborators', 'namespace' => 'Collaborators' ], function() {
    Route::group(['middleware' => 'can:view collaborators'], function() {
        Route::post('/', 'CollaboratorController@index');
        Route::post('/archive', 'CollaboratorController@archive');
        Route::get('/gender', 'CollaboratorController@collaboratorsNumberByGender');
        Route::get('/department', 'CollaboratorController@collaboratorsNumberByDepartment');
        Route::get('/{user}', 'CollaboratorController@show');
    });
    Route::post('/create', 'CollaboratorController@store')->middleware('can:add collaborators');
    Route::prefix('/{user}')->group(function() {
        Route::resource('leaves', 'LeaveController')->parameters(['leaves' => 'leave']);
        Route::resources([
            'skills' => 'SkillController',
            'trainings' => 'TrainingController',
            'evaluations' => 'EvaluationController'
        ]);
        Route::group(['middleware' => 'can:edit collaborators'], function() {
            Route::put('/', 'CollaboratorController@update'); // /api/collaborators/{user_id}
            Route::get('/restore', 'CollaboratorController@restore');
        });
        Route::group(['middleware' => 'can:delete collaborators'], function() { // /api/collaborators/{user_id}/
            Route::delete('/', 'CollaboratorController@destroy');
            Route::delete('/delete-permantly', 'CollaboratorController@deletePermantly');
        });
    });
});
Route::middleware('auth:api, can:edit collaborators')->group(function() {
    // validation only
    Route::post('/validate/leave', 'ValidationController@leave');
    Route::post('/validate/skill', 'ValidationController@skill');
    Route::post('/validate/training', 'ValidationController@training');
    Route::post('/validate/evaluation', 'ValidationController@evaluation');
    // manage departments
    Route::post('/departments', 'DepartmentController@store');
    Route::put('/departments/{department}', 'DepartmentController@update');
    Route::post('/departments/{department}', 'DepartmentController@getUsers');
    Route::delete('/departments/{department}', 'DepartmentController@destroy');
});
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/departments', 'DepartmentController@index');
    Route::get('/departments/{department}', 'DepartmentController@show');
    // update account
    Route::post('/account/update', 'UserController@update');
    Route::post('/users/{user}/profile-image', 'UserController@setProfileImage')->middleware('can:edit collaborators');
});