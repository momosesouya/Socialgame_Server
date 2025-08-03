<?php

namespace App\Http\Controllers;

use App\Libs\GameUtilService;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';

        // $authUserData = Auth::user();
        // if (!$authUserData) {
        //     return response()->json([
        //         'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
        //     ], 401);
        // }

        // ユーザー情報取得
        $userData = User::where('user_id', $request->uid)->first();
        // if (!$userData) {
        //     return response()->json([
        //         'errcode' => 'ERRCODE_USER_NOT_FOUND',
        //     ], 404);
        // }

        $manage_id = $userData->manage_id;

        // ログインユーザーと照合
        // if ($manage_id != $authUserData->manage_id) {
        //     return response()->json([
        //         'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
        //     ], 403);
        // }

        $recoveryStamina = GameUtilService::getCurrentStamina($userData->last_stamina, $userData->max_stamina, $userData->stamina_updated);

        return response()->json([
            'users' => User::where('manage_id',$manage_id)->first(),
            'wallets' => UserWallet::where('manage_id', $manage_id)->first(),
            'currentStamina' => $recoveryStamina,
        ]);
    }
}