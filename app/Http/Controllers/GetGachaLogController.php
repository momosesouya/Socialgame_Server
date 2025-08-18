<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GachaLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GetGachaLogController extends Controller
{
    public function __invoke(Request $request)
    {
        // バリデーション
        $request->validate([
            'uid' => 'required|string|max:255',
        ]);

        // ユーザー情報取得
        $userData = User::where('user_id', $request->uid)->first();

        if (!$userData) {
            return response()->json(['error' => 'constants.LOGIN_NOT_USER_FOUND'], 'ERRCODE_USER_NOT_FOUND');
        }

        $manageId = $userData->manage_id;

        // ガチャログ取得
        $gachaLogs = GachaLog::where('manage_id', $manageId)->get();

        return response()->json(['gacha_log' => $gachaLogs]);
    }
}
