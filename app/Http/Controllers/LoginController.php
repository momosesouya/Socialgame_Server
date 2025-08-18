<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WeaponInstance;
use App\Models\ItemInstance;

use Carbon\Carbon;


class LoginController extends Controller
{
    /**
     * uid = ユーザーID
     */
    public function __invoke(Request $request)
    {
        $result = 0;
        
        // ユーザー情報をデータベースで確認
        $userData = User::where('user_id', $request->uid)->first();
        // ユーザー管理ID
        $manageId = $userData->manage_id;
        
        if (!$userData) {
            return response()->json([
                'error' => config('constants.LOGIN_USER_NOT_FOUND'),
            ], 'ERRCODE_USER_NOT_FOUND');
        }
        
        DB::transaction(function () use (&$result, $manageId) {
            // ログイン時間更新
            $result = User::where('manage_id',$manageId)->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            
            $result = 1;
        });
        
        if ($result === 0) {
            return response()->json([
                'result' => config('constants.CANT_UPDATE_HOME'),
            ], 'constants.ERRCODE_CANT_UPDATE_HOME');
        }
        
        return response()->json([
            'users' => User::where('manage_id',$manageId)->first(),
            'wallets' => UserWallet::where('manage_id', $manageId)->first(),
            'weapons' => WeaponInstance::where('manage_id',$manageId)->get(),
            'items' => ItemInstance::where('manage_id',$manageId)->get(),
            'result' => $result,
        ]);
    }
}
