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
        if (!$userData) {
            return response()->json([
                'result' => 'error',
                'message' => 'ユーザーが存在しません'
            ], 400);
        }

        $manageId = $userData->manage_id;
        $weaponId = $request->input('wid');
        $addLevel = (int)$request->input('add_level');
        $itemId = config('constants.WEAPON_ENHANCE_ITEM'); // 武器強化アイテムID

        $weapon = WeaponInstance::where('manage_id', $manageId)->where('weapon_id', $weaponId)->first();
        // 武器インスタンス取得
        if (!$weapon) {
            throw new \Exception('武器が存在しません');
        }

        
        
        try {
            DB::transaction(function () use ($manageId, $weaponId, $addLevel, $itemId, &$weapon, &$itemInstance) {
                
                $newLevel = $weapon->level + $addLevel;
                // 最大レベルを超えないように制限
                if ($newLevel > $weapon->level_max) {
                    $newLevel = $weapon->level_max;
                }
                
                // 武器更新
                WeaponInstance::where('manage_id', $manageId)
                ->where('weapon_id', $weaponId)
                ->update(['level' => $newLevel]);
                
                $costPerLevel = $this->getRequiredItemCount($weapon->rarity_id); // レアリティごとの強化に必要なコスト
                $totalCost = $costPerLevel * $addLevel; // 必要なアイテムの合計
                
                // アイテム所持数確認
                $itemInstance = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->first();
                if (!$itemInstance) {
                    throw new \Exception('アイテムが存在しません');
                }
                if ($itemInstance->has_enhancement_item < $totalCost) {
                    throw new \Exception('アイテム不足');
                }

                $newItemCount = $itemInstance->has_enhancement_item - $totalCost;

                // アイテム更新
                ItemInstance::where('manage_id', $manageId)
                              ->where('item_id', $itemId)
                              ->update(['has_enhancement_item' => $newItemCount]);
                

                $weapon->level = $newLevel;
                $itemInstance->has_enhancement_item = $newItemCount;

                // return [
                //     'level' => $weapon->level,
                //     'has_enhancement_item' => $itemInstance->has_enhancement_item,
                // ];
            });

            return response()->json([
                'result' => 'success',
                'level' => $weapon->level,
                'has_enhancement_item' => $itemInstance->has_enhancement_item,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'result' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * レアリティごとに必要な数を返す
     */
    private function getRequiredItemCount($rarityId)
    {
        // レアリティごとに必要数を変える
        switch ($rarityId) {
            case 10000: return 1; // Normal
            case 15000: return 5; // R
            case 20000: return 10; // SR
            case 25000: return 15; // SSR
            case 30000: return 20; // UR
            default: return 0;
        }
    }
}