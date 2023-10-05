<?php

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

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::get('error', function () {
        return response()->json(['message' => 'Unauthorized'], 401);
    })->name('login');
});

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('payload', 'AuthController@payload');
    Route::get('firebase', 'AuthController@registerFirebaseToken');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['api']
], function ($router) {
    Route::get('/regFirebase', 'App\Http\Controllers\Api\AuthController@newFirebaseToken');

    Route::get('/categories', 'App\Http\Controllers\Api\CategoryController@index');
    Route::get('/categories-data', 'App\Http\Controllers\Api\CategoryController@categoryData');

    Route::get('/recipe/explore', 'App\Http\Controllers\Api\RecipeController@explore');
    Route::get('/recipe/detail', 'App\Http\Controllers\Api\RecipeController@recipeData');
    Route::get('/recipe/search', 'App\Http\Controllers\Api\RecipeController@searchRecipe');
    Route::get('/recipe/featured', 'App\Http\Controllers\Api\RecipeController@featuredRecipe');
    Route::post('/recipe/request', 'App\Http\Controllers\Api\RecipeController@recipeRequest');
});


Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['api', 'auth:api']
], function ($router) {
    Route::post('/favorite/save', 'FavouriteController@newFavourite');
    Route::get('/favorite/list', 'FavouriteController@listFavourite');
    Route::post('/favorite/delete', 'FavouriteController@deleteFavourite');
});
