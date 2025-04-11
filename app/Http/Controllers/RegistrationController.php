<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class RegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $usersData = [];

        // ユーザー管理ID
        $manage_id = 0;

        // ユーザーIDの決定
        $user_id = Str::ulid();

        //仮の表示名
        $user_name = 'User_' . rand(1000, 9999);

        // ユーザーデータ登録
        // $usersData = User::create([
        //     'user_id' => $user_id,
        //     'user_name' => $user_name,
        //     'max_stamina' => config('constants.MAX_STAMINA'),
        //     'last_stamina' => config('constants.LAST_STAMINA'),
        // ]);

        return response()->json([
            "user_id" => $user_id,
            "user_name" => $user_name,
        ]);
    }
}
