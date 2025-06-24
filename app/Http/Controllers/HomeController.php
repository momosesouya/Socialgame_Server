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

        DB::transaction(function() use(&$result, $userData, $manage_id) {
            $last_stamina = $userData->last_stamina;
            $max_stamina = $userData->max_stamina;
            $updated = $userData->stamina_updated;

            // スタミナ回復
            if ($last_stamina < $max_stamina) {
                $recoveryStamina = GameUtilService::getCurrentStamina($last_stamina, $max_stamina, $updated);
                if ($recoveryStamina >= $max_stamina) {
                    $currentStamina = $max_stamina;
                }
                else {
                    $currentStamina = $last_stamina + $recoveryStamina;
                    if ($currentStamina >= $max_stamina) {
                        $currentStamina = $max_stamina;
                    }
                }

                $result = User::where('manage_id',$manage_id)->update([
                    'last_stamina' => $currentStamina,
                ]);
            }
            $result = 1;
        });


        if ($result === 0) {
            return response()->json([
                'result' => config('constants.CANT_UPDATE_HOME'),
            ], 500);
        }

        // switch($result) {
        //     case 0:
        //         $errcode = config('constants.CANT_UPDATE_HOME');
        //         $response = [
        //             'errcode' => $errcode,
        //         ];
        //         break;
        //     case 1:
        //         $response = [
        //             'users' => User::where('manage_id',$manage_id)->first(),
        //             'wallets' => UserWallet::where('manage_id', $manage_id)->first(),
        //             // TODO: 他にホームに戻った時に取得したい情報があればここに追記
        //         ];
        //         break;
        // }

        return response()->json([
            'users' => User::where('manage_id',$manage_id)->first(),
            'wallets' => UserWallet::where('manage_id', $manage_id)->first(),
            'result' => $result,
        ]);
    }
}