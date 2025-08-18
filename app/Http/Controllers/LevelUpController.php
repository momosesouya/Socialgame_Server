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
        $manageId = $userData->manage_id;
        $weaponId = $request->input('wid');
        $itemId = config('constants.WEAPON_ENHANCE_ITEM'); // 武器強化アイテムID
        $useCount = (int)$request->input('count');
        try {
            $result = DB::transaction(function () use ($manageId, $weaponId, $itemId, $useCount) {
                // アイテム所持数確認
                $itemInstance = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->first();
                if (!$itemInstance || $itemInstance->has_enhancement_item < $useCount) {
                    throw new \Exception('アイテム不足');
                }

                // 武器インスタンス取得
                $weapon = WeaponInstance::where('manage_id', $manageId)->where('weapon_id', $weaponId)->first();
                if (!$weapon) {
                    throw new \Exception('武器が存在しません');
                }
                
                // 武器更新
                WeaponInstance::where('manage_id', $manageId)
                              ->where('weapon_id', $weaponId)
                              ->update(['level' => $weapon->level + 1]);

                $currentLevel = $weapon->level;
                $afterItem = $itemInstance->has_enhancement_item - $useCount;

                // アイテム数更新
                ItemInstance::where('manage_id', $manageId)
                            ->where('item_id', $itemId)
                            ->update(['has_enhancement_item' => $afterItem]);
                return [
                    'new_level' => $currentLevel,
                    'enhancement_item' => $afterItem,
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