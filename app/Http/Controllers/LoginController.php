<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserWallet;

use Carbon\Carbon;


class LoginController extends Controller
{
    /**
     * uid = ユーザーID
     */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';

        // ユーザー情報をデータベースで確認
        $user_Data = User::where('user_id', $request->uid)->first();

        if (!$user_Data) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        // ユーザー管理ID
        $manage_id = $user_Data->manage_id;
        DB::transaction(function () use (&$result, $manage_id) {
            $result = User::where('manage_id',$manage_id)->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            $user_Data = User::where('manage_id',$manage_id)->first();

            $result = 1;
        });

        switch($result) {
            case 0:
                $errcode = config('constants.CANT_LOGIN');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'users' => User::where('manage_id', $manage_id)->first(),
                    //'wallets' => UserWallet::where('manage_id', $manage_id)->first(),
                ];
                break;
        }

        // ログイン成功時のレスポンス
        return json_encode($response);
    }
}
