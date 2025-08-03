<?php

namespace App\Http\Controllers\Gacha;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Weapon;
use App\Models\UserWallet;
use App\Models\GachaWeapon;
use App\Http\Controllers\Gacha\GachaService;
use Illuminate\Http\Request;

class GachaTestController extends Controller
{
    public function __invoke(Request $request)
    {
        // localhost/api/gachaTest?manage_id=264&gacha_id=200&gCount=500
        $manage_id = $request->query('manage_id');
        $gacha_id = $request->query('gacha_id');
        $gacha_count = $request->query('gCount');

        if (!$manage_id) {
            return response()->json(['error' => 'manage_id is required'], 400);
        }

        $user = User::where('manage_id', $manage_id)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        UserWallet::where('manage_id', 264)->update([
            'free_amount' => 800000,
            'paid_amount' => 0,
        ]);

        // ガチャデータ存在チェック
        $gachaData = GachaWeapon::where('gacha_id', $gacha_id)->get();
        if ($gachaData->isEmpty()) {
            return response()->json(['error' => 'No gacha data found'], 404);
        }

        // weapon_id 存在チェック
        $invalidWeaponIds = [];
        foreach ($gachaData as $entry) {
            if (!Weapon::where('weapon_id', $entry->weapon_id)->exists()) {
                $invalidWeaponIds[] = $entry->weapon_id;
            }
        }
        if (!empty($invalidWeaponIds)) {
            return response()->json([
                'error' => 'Invalid weapon_ids found in gacha data',
                'invalid_weapon_ids' => $invalidWeaponIds,
            ], 400);
        }

        try {
            $service = new GachaService($gacha_id);
            $result = $service->execute(new Request([
                'uid' => $user->user_id,
                'gCount' => $gacha_count,
            ]));

            // 出た weapon_id 一覧（出現順）
            $weaponIds = explode('/', $result['gacha_result']);

            // rarity_id = 30000 の武器ID一覧を事前に取得
            $targetWeaponIds = Weapon::where('rarity_id', 30000)->pluck('weapon_id')->toArray();

            // 何回目に出たか（1-based index）
            $appearances = [];
            foreach ($weaponIds as $i => $wid) {
                if (in_array((int)$wid, $targetWeaponIds)) {
                    $appearances[] = $i + 1;
                }
            }

            // レスポンスに追加
            return response()->json([
                'gacha_result_summary' => $result,
                'target_rarity' => 30000,
                'appearances' => $appearances, // [5, 123, 4334] ← この形式で返す
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gacha execution failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
