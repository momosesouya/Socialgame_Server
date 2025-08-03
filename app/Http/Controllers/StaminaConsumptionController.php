<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ItemInstance;
use App\Libs\GameUtilService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaminaConsumptionController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();
        // ユーザー管理ID
        $manage_id = $userData->manage_id;
        
        $currentStamina = GameUtilService::getCurrentStamina($userData->last_stamina, $userData->max_stamina, $userData->stamina_updated);
        $consumptionStamina = 5; // 消費するスタミナ
        $resultStamina = $currentStamina - $consumptionStamina;

        $itemId = 1002;
        DB::transaction(function() use ($userData, $manage_id, $itemId, $resultStamina) {

            if ($resultStamina >= 0) {
                // スタミナ更新
                // User::where('manage_id', $manage_id)->update([
                //     'last_stamina' => $resultStamina,
                //     'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
                // ]);
                $userData->last_stamina = $resultStamina;
                $userData->stamina_updated = Carbon::now()->format('Y-m-d H:i:s');
                $userData->save(); // ユーザーデータ保存
                $item = ItemInstance::where('manage_id', $manage_id)->where('item_id', $itemId)->increment('has_enhancement_item', 5);
            }
        });
        // ユーザーデータを再取得
        $userData = User::where('user_id',$request->uid)->first();
        $hasItem = ItemInstance::where('manage_id', $manage_id)->where('item_id', $itemId)->first();
        // dd($hasItem->has_enhancement_item);
        $response = [
            'last_stamina' => $userData->last_stamina,
            'item_id' => $itemId,
            'hasItem' => $hasItem->has_enhancement_item,
        ];

        return response()->json($response);
    }
}
