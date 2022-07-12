<?php

namespace App\Http\Controllers;

use App\Models\Adopter;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\User as UserResource;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()      // this function direct go to google
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGoogleCallback()  // this function get user login of googlre
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $token = $user->token;
            $finduser = User::where('external_id', $user->id)->where('external_auth', 'google')->first();

            if($finduser){
                Auth::login($finduser);
                $user = JWTAuth::user();
                return back();
            }else{
                $newUser = Adopter::create([
                    'company' => '',
                    'short_bio' => '',
                ]);
                $user = $newUser->user()->create([
                    'name' => $user->user['given_name'],
                    'last_name' => $user->user['family_name'],
                    'cellphone' => '',
                    'address' => '',
                    'date_of_birth' => '1999-08-01',
                    'email' => $user->email,
                    'image' => $user->avatar,
                    'role' => 'ROLE_ADOPTER',
                    'external_id'=> $user->id,
                    'external_auth' => 'google',
                ]);
                $token = JWTAuth::fromUser($user);
                return back();
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
