<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
Route::get('games/genre/{genre}', [GameController::class, 'getGamesByGenre']);
Route::get('games/{game}/comments', [GameController::class, 'getCommentsWithUser']);
Route::get('users/{user}/reviews', [ReviewController::class, 'getUserReviews']);
Route::get('games/{game}/reviews', [ReviewController::class, 'getGameReviews']);
Route::get('search', [SearchController::class, 'search']);
Route::get('games/random', [GameController::class, 'getRandomGames']);
Route::get('reviews/random', [ReviewController::class, 'getRandomReviews']);
Route::get('games/top-rated', [GameController::class, 'getTopRatedGames']);
Route::get('search-game', [GameController::class,'searchGame']);

Route::get('games', [GameController::class, 'index']);
Route::get('games/{game}', [GameController::class, 'show']);

Route::get('genres', [GenresController::class, 'index']);
Route::get('genres/{genres}', [GenresController::class, 'show']);

Route::get('reviews', [ReviewController::class, 'index']);
Route::get('reviews/{review}', [ReviewController::class, 'show']);

Route::group(['middleware' => 'api'],function() {
    Route::apiResource('games',\App\Http\Controllers\GameController::class)->except('index','show');
    Route::apiResource('genres',\App\Http\Controllers\GenresController::class)->except('index','show');
    Route::apiResource('platforms',\App\Http\Controllers\PlatformsController::class);
    Route::apiResource('comments',\App\Http\Controllers\CommentController::class);
    Route::apiResource('requirement',\App\Http\Controllers\RequirementController::class);
    Route::apiResource('reports',\App\Http\Controllers\ReportController::class);
    Route::apiResource('reviews',\App\Http\Controllers\ReviewController::class)->except('index','show');
    Route::apiResource('developers',\App\Http\Controllers\DeveloperController::class);
    Route::apiResource('users',\App\Http\Controllers\UserController::class)->except('store');
    Route::get('/users/{userId}/comments', [CommentController::class, 'getCommentsByUser']);

    Route::post('/user/update-password', [UserController::class, 'updatePassword']);
    //Route::post('/user/update-password', 'UserController@updatePassword')->middleware('auth');
});
