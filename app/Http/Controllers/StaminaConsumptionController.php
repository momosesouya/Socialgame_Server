<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ItemInstance;
use App\Libs\GameUtilService;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaminaConsumptionController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();
        // ユーザー管理ID
        $manageId = $userData->manage_id;
        
        $currentStamina = GameUtilService::getCurrentStamina($userData->last_stamina, $userData->max_stamina, $userData->stamina_updated);
        $consumptionStamina = 5; // 消費するスタミナ
        $resultStamina = $currentStamina - $consumptionStamina;

        $itemId = config('constants.WEAPON_ENHANCE_ITEM');
        $addNum = config('constants.ENHANCE_ITEM_ADD_NUM');
        DB::transaction(function() use ($userData, $manageId, $itemId, $resultStamina, $addNum) {

            if ($resultStamina >= 0) {
                // スタミナ更新
                $userData->last_stamina = $resultStamina;
                $userData->stamina_updated = Carbon::now()->format('Y-m-d H:i:s');
                $userData->save(); // ユーザーデータ保存
                $item = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->increment('has_enhancement_item', $addNum);
            }
        });
        // ユーザーデータを再取得
        $userData = User::where('user_id',$request->uid)->first();
        $hasItem = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->first();
        $response = [
            'last_stamina' => $userData->last_stamina,
            'item_id' => $itemId,
            'hasItem' => $hasItem->has_enhancement_item,
        ];

        return response()->json($response);
    }
}
