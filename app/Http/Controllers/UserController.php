<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Protector;
use App\Models\User;
use App\Models\Adopter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\User as UserResource;
use Tymon\JWTAuth\JWTGuard;


class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }
        $user = JWTAuth::user();

        return response()->json(compact('token', 'user'))
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'), // ttl => time to live
                '/', // path
                null, // domain
                config('app.env') !== 'local', // Secure
                true, // httpOnly
                false, //
                config('app.env') !== 'local' ? 'None' : 'Lax' // SameSite
            );
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'cellphone' => 'required|string|max:10',
            'address' => 'required',
            'image' => 'required|image|dimensions:min_width=200,min_height=200',
            'date_of_birth' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company' => 'required|string',
            'short_bio' => 'required|string',
            'role' => 'required'
        ]);

        if ($request->role == User::ROLE_PROTECTOR) {
            $userable = Protector::create([
                'company' => $request->get('company'),
                'short_bio' => $request->get('short_bio'),
            ]);
        } elseif ($request->role == User::ROLE_ADOPTER) {
            $userable = Adopter::create([
                'company' => $request->get('company'),
                'short_bio' => $request->get('short_bio'),
            ]);
        } else {
            $userable = Admin::create([
                'identity_card' => $request->get('identity_card'),
            ]);
        }

        $user = $userable->user()->create([
            'name' => $request->get('name'),
            'last_name' => $request->get('last_name'),
            'cellphone' => $request->get('cellphone'),
            'address' => $request->get('address'),
            'image' => $request->get('image'),
            'date_of_birth' => $request->get('date_of_birth'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),]);

        $token = JWTAuth::fromUser($user);

        return response()->json(new UserResource($user, $token), 201)
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'
            );
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'token_absent'], $e->getStatusCode());
        }
        return response()->json(new UserResource($user), 200);
    }

    public function index()
    {
        $this->authorize('view', User::class);
        return User::all();
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function delete(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json(null, 204);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

//            Cookie::queue(Cookie::forget('token'));
//            $cookie = Cookie::forget('token');
//            $cookie->withSameSite('None');
            return response()->json([
                "status" => "success",
                "message" => "User successfully logged out."
            ], 200)
                ->withCookie('token', null,
                    config('jwt.ttl'),
                    '/',
                    null,
                    config('app.env') !== 'local',
                    true,
                    false,
                    config('app.env') !== 'local' ? 'None' : 'Lax'
                );
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(["message" => "No se pudo cerrar la sesión."], 500);
        }
    }
}
