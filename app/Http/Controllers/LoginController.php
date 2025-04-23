<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;

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

            $userData = User::where('manage_id',$manage_id)->first();

            $result = 1;
        });

        // ログイン成功時のレスポンス
        return response()->json([
            'uid' => $user_Data->user_id,
            'un' => $user_Data->user_name,
            'result' => $result,
        ]);
    }
}
