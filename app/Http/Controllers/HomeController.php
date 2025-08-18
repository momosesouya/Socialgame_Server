<?php

namespace App\Http\Controllers;

use App\Libs\GameUtilService;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserWallet;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザー情報取得
        $userData = User::where('user_id', $request->uid)->first();
        $manageId = $userData->manage_id;

        $recoveryStamina = GameUtilService::getCurrentStamina($userData->last_stamina, $userData->max_stamina, $userData->stamina_updated);

        return response()->json([
            'users' => User::where('manage_id',$manageId)->first(),
            'wallets' => UserWallet::where('manage_id', $manageId)->first(),
            'currentStamina' => $recoveryStamina,
        ]);
    }
}