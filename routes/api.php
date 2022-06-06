<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticlController;
use App\Http\Controllers\CategoryController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

/*
 * Route for default to access to User
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
//Public routes
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'authenticate']);
Route::get('articles', [ArticlController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['status' => __($status)], 200)
        : response()->json(['status' => __($status)], 400);
})->middleware('guest')->name('password.email');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) use ($request) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            $user->setRememberToken(Str::random(60));

            event(new PasswordReset($user));
        }
    );

    return $status == Password::PASSWORD_RESET
        ? response()->json(['status' => __($status)], 200)
        : response()->json(['status' => __($status)], 400);
});

//Private routes
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user/categories', [CategoryController::class, 'categoriesByUser']);
    Route::get('user', [UserController::class, 'getAuthenticatedUser']);
    Route::post('logout', [UserController::class, 'logout']);

    // Articles
    Route::get('articles/{article}', [ArticlController::class, 'show']);
    Route::get('articles/{article}/image', [ArticlController::class, 'image']);
    Route::post('articles', [ArticlController::class, 'store']);
    Route::put('articles/{article}',  [ArticlController::class, 'update']);
    Route::delete('articles/{article}', [ArticlController::class, 'delete']);

    // Comments
    Route::get('articles/{article}/comments', [CommentController::class, 'index']);
    Route::get('articles/{article}/comments/{comment}', [CommentController::class, 'show']);
    Route::post('articles/{article}/comments', [CommentController::class, 'store']);
    Route::put('articles/{article}/comments/{comment}',  [CommentController::class, 'update']);
    Route::delete('articles/{article}/comments/{comment}', [CommentController::class, 'delete']);
});
