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
            return response()->json(['error' => 'ユーザーが見つかりません'], 404);
        }

        $manage_id = $userData->manage_id;

        // ガチャログ取得
        $gachaLogs = GachaLog::where('manage_id', $manage_id)->get();

        return response()->json(['gacha_log' => $gachaLogs]);
    }
}
