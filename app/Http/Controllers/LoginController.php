<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $errcode = '';

        // ユーザー情報をデータベースで確認
        $user_Data = User::where('user_id', $request->uid)->first();
        // ユーザー管理ID
        $manage_id = $user_Data->manage_id;
        $weapon = WeaponInstance::where('manage_id',$manage_id)->get();
        
        if (!$user_Data) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }
        
        DB::transaction(function () use (&$result, $manage_id) {
            // ログイン時間更新
            $result = User::where('manage_id',$manage_id)->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            
            $user_Data = User::where('manage_id',$manage_id)->first();
            
            $result = 1;
        });
        
        if ($result === 0) {
            return response()->json([
                'result' => config('constants.CANT_UPDATE_HOME'),
            ], 500);
        }
        
        return response()->json([
            'users' => User::where('manage_id',$manage_id)->first(),
            'wallets' => UserWallet::where('manage_id', $manage_id)->first(),
            'weapons' => $weapon,
            'items' => ItemInstance::where('manage_id',$manage_id)->get(),
            'result' => $result,
        ]);
    }
}
