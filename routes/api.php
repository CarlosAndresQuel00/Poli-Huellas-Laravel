<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\FormController;
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
Route::get('pets', [PetController::class, 'index']);
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
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'delete']);
    Route::post('logout', [UserController::class, 'logout']);

    // Pets
    Route::get('pets/{pet}', [PetController::class, 'show']);
    Route::get('pets/{pet}/image', [PetController::class, 'image']);
    Route::post('pets', [PetController::class, 'store']);
    Route::put('pets/{pet}',  [PetController::class, 'update']);
    Route::delete('pets/{pet}', [PetController::class, 'delete']);

    // Categories
    Route::get('categories/{category}', [PetController::class, 'show']);
    Route::post('categories', [PetController::class, 'store']);
    Route::put('categories/{category}',  [PetController::class, 'update']);
    Route::delete('categories/{category}', [PetController::class, 'delete']);

    // Forms
    Route::get('pets/{pet}/forms', [FormController::class, 'index']);
    Route::get('pets/{pet}/forms/{form}', [FormController::class, 'show']);
    Route::post('pets/{pet}/forms', [FormController::class, 'store']);
    Route::put('pets/{pet}/forms/{form}',  [FormController::class, 'update']);
    Route::delete('pets/{pet}/forms/{form}', [FormController::class, 'delete']);

    // Comments
    Route::get('pets/{pet}/comments', [CommentController::class, 'index']);
    Route::get('pets/{pet}/comments/{comment}', [CommentController::class, 'show']);
    Route::post('pets/{pet}/comments', [CommentController::class, 'store']);
    Route::put('pets/{pet}/comments/{comment}',  [CommentController::class, 'update']);
    Route::delete('pets/{pet}/comments/{comment}', [CommentController::class, 'delete']);

    // Notifications
    Route::get('markAsRead', function() {
        auth()->user()->unreadNotifications->markAsRead();
    });
});
