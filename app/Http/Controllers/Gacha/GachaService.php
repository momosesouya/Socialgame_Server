<?php

namespace App\Http\Controllers\Gacha;

use App\Models\WeaponInstance;
use App\Models\GachaWeapon;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Weapon;
use App\Models\GachaLog;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GachaService
{
    protected int $gachaId;

    public function __construct(int $gachaId)
    {
        $this->gachaId = $gachaId;
    }

    public function execute(Request $request): array
    {
        $userData = User::where('user_id', $request->uid)->first();
        $manageId = $userData->manage_id;
        $gachaCount = $request->gCount;
        $walletsData = UserWallet::where('manage_id', $manageId)->first();
        $gachaData = GachaWeapon::where('gacha_id', $request->gacha_id)->get();

        // ガチャのコスト
        $cost = $gachaCount * config('constants.GACHA_COST');
        if ($walletsData->free_amount + $walletsData->paid_amount < $cost) {
            return ['errcode' => 'constants.NOT_ENOUGH_CURRENCY'];
        }

        $result = [];

        DB::transaction(function () use (&$result, $manageId, $walletsData, $cost, $gachaData, $gachaCount) {
            // 重み付き抽選
            $weights = $gachaData->map(function ($item) {
                return ['weapon_id' => $item->weapon_id, 'weight' => $item->weight];
            })->toArray();

            $result = $this->draw($weights, $gachaCount, $manageId);
            // 通貨消費
            $this->useCurrency($walletsData, $cost);
        });

        return [
            'wallets' => $walletsData->fresh()->toArray(),
            'weapons' => WeaponInstance::where('manage_id', $manageId)->get()->toarray(),
            'gacha_result' => implode('/', array_column($result['draws'], 'weapon_id')),
            'new_weapons' => $result['new_weapons'],
        ];
    }

    private function draw(array $weights, int $count, int $manageId): array
    {
        $draws = [];
        $newWeapons = [];

        for ($i = 0; $i < $count; $i++) {
            $totalWeight = array_sum(array_column($weights, 'weight'));
            $rand = mt_rand(1, $totalWeight);

            foreach ($weights as $entry) {
                $rand -= $entry['weight'];
                if ($rand <= 0) {
                    $weaponId = $entry['weapon_id'];

                    // ガチャ履歴の保存
                    GachaLog::create([
                        'manage_id' => $manageId,
                        'gacha_id' => $this->gachaId,
                        'weapon_id' => $weaponId,
                    ]);

                    // すでに持っているか確認
                    $hasWeapon = WeaponInstance::where('manage_id', $manageId)
                        ->where('weapon_id', $weaponId)
                        ->exists();

                    if (!$hasWeapon) {
                        $rarity = Weapon::where('weapon_id', $weaponId)->first();

                        WeaponInstance::create([
                            'manage_id' => $manageId,
                            'weapon_id' => $weaponId,
                            'rarity_id' => $rarity->rarity_id,
                        ]);

                        $newWeapons[] = $weaponId;
                    }

                    // draw結果として追加
                    $draws[] = ['weapon_id' => $weaponId];
                    break;
                }
            }
        }

        return [
            'draws' => $draws,             // 全ての抽選結果
            'new_weapons' => $newWeapons, // 新規に獲得した武器IDのみ
        ];
    }

    private function useCurrency(UserWallet $walletsData, int $cost): void
    {
        if ($walletsData->free_amount >= $cost) {
            $walletsData->free_amount -= $cost;
        } else {
            $remain = $cost - $walletsData->free_amount;
            $walletsData->free_amount = 0;
            $walletsData->paid_amount -= $remain;
        }

        $walletsData->save();
    }
}
