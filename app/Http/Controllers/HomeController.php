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

        // ログイン確認(ログインしてなかったらリダイレクト)
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        // ログイン確認済みのユーザー
        $authUserData = Auth::user();
        // ユーザー情報取得
        $user_Data = User::where('user_id', $request->uid)->first();
        $manage_id = $user_Data->manage_id;

        // ログインしているアカウントが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }

        DB::transaction(function() use(&$result, $user_Data, $manage_id) {
            $last_stamina = $user_Data->last_stamina;
            $max_stamina = $user_Data->max_stamina;
            $updated = $user_Data->stamina_updated;

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

        switch($result) {
            case 0:
                $errcode = config('constants.CANT_UPDATE_HOME');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'users' => User::where('manage_id',$manage_id)->first(),
                    //'wallets' => UserWallet::where('manage_id', $manage_id)->first(),
                    // TODO: 他にホームに戻った時に取得したい情報があればここに追記
                ];
                break;
        }
        return json_encode($response);
    }
}