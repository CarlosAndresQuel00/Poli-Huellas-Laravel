<?php

namespace App\Traits\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ChangeImageTrait
{
    public function image(Request $request, User $user)
    {
        $rules = [
            'image' => 'required|image|dimensions:min_width=200,min_height=200'
        ];
        $request->validate($rules);
        try {
            DB::beginTransaction();
            $file = $request->image->storeOnCloudinaryAs('users', $user->id);
            $path = $file->getSecurePath();
            $user->image = $path;
            $user->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return response()->json($user, 200);
    }
}
