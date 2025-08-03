<?php

namespace App\Http\Controllers;

use App\Models\WeaponInstance;
use App\Models\WeaponExp;
use App\Models\ItemInstance;
use App\Models\Item;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelUpController extends Controller
{
    public function __invoke(Request $request)
    {
        $userData = User::where('user_id', $request->input('uid'))->first();
        $manage_id = $userData->manage_id;
        $weapon_id = $request->input('wid');
        $item_id = 1002; // 武器強化アイテムID
        $use_count = (int)$request->input('count');
        try {
            $result = DB::transaction(function () use ($manage_id, $weapon_id, $item_id, $use_count) {
                // アイテム所持数確認
                $itemInstance = ItemInstance::where('manage_id', $manage_id)->where('item_id', $item_id)->first();
                if (!$itemInstance || $itemInstance->has_enhancement_item < $use_count) {
                    throw new \Exception('アイテム不足');
                }

                // 武器インスタンス取得
                $weapon = WeaponInstance::where('manage_id', $manage_id)->where('weapon_id', $weapon_id)->first();
                if (!$weapon) {
                    throw new \Exception('武器が存在しません');
                }
                
                // 武器更新
                WeaponInstance::where('manage_id', $manage_id)
                              ->where('weapon_id', $weapon_id)
                              ->update(['level' => $weapon->level + 1]);

                $current_level = $weapon->level;
                $after_item = $itemInstance->has_enhancement_item - $use_count;

                // アイテム数更新
                ItemInstance::where('manage_id', $manage_id)
                            ->where('item_id', $item_id)
                            ->update(['has_enhancement_item' => $after_item]);
                return [
                    'new_level' => $current_level,
                    'enhancement_item' => $after_item,
                ];
            });

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'result' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}