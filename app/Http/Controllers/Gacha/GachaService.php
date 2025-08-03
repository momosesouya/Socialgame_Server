<?php

namespace App\Http\Controllers\Gacha;

use App\Models\WeaponInstance;
use App\Models\GachaWeapon;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Weapon;
use App\Models\GachaLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GachaService
{
    protected int $gacha_id;

    public function __construct(int $gacha_id)
    {
        $this->gacha_id = $gacha_id;
    }

    public function execute(Request $request): array
    {
        $userData = User::where('user_id', $request->uid)->first();
        $manage_id = $userData->manage_id;
        $gacha_count = $request->gCount;
        $walletsData = UserWallet::where('manage_id', $manage_id)->first();
        $gachaData = GachaWeapon::where('gacha_id', $request->gacha_id)->get();


        $costPerGacha = 160;
        // ガチャのコスト
        $cost = $gacha_count * $costPerGacha;
        if ($walletsData->free_amount + $walletsData->paid_amount < $cost) {
            return ['errcode' => 'NOT_ENOUGH_CURRENCY'];
        }

        $result = [];

        DB::transaction(function () use (&$result, $manage_id, $walletsData, $cost, $gachaData, $gacha_count) {
            // 重み付き抽選
            $weights = $gachaData->map(function ($item) {
                return ['weapon_id' => $item->weapon_id, 'weight' => $item->weight];
            })->toArray();

            $result = $this->draw($weights, $gacha_count, $manage_id);
            // 通貨消費
            $this->useCurrency($walletsData, $cost);
        });

        return [
            'wallets' => $walletsData->fresh()->toArray(),
            'weapons' => WeaponInstance::where('manage_id', $manage_id)->get()->toarray(),
            'gacha_result' => implode('/', array_column($result['draws'], 'weapon_id')),
            'new_weapons' => $result['new_weapons'],
        ];
    }

    
    private function draw(array $weights, int $count, int $manage_id): array
    {
        $draws = [];
        $newWeapons = [];

        for ($i = 0; $i < $count; $i++) {
            $totalWeight = array_sum(array_column($weights, 'weight'));
            $rand = mt_rand(1, $totalWeight);

            foreach ($weights as $entry) {
                $rand -= $entry['weight'];
                if ($rand <= 0) {
                    $weapon_id = $entry['weapon_id'];

                    // ガチャ履歴は新旧関係なく必ず保存
                    GachaLog::create([
                        'manage_id' => $manage_id,
                        'gacha_id' => $this->gacha_id,
                        'weapon_id' => $weapon_id,
                    ]);

                    // すでに持っているか確認
                    $hasWeapon = WeaponInstance::where('manage_id', $manage_id)
                        ->where('weapon_id', $weapon_id)
                        ->exists();

                    if (!$hasWeapon) {
                        $rarity = Weapon::where('weapon_id', $weapon_id)->first();

                        WeaponInstance::create([
                            'manage_id' => $manage_id,
                            'weapon_id' => $weapon_id,
                            'rarity_id' => $rarity->rarity_id,
                        ]);

                        $newWeapons[] = $weapon_id;
                    }

                    // draw結果として追加（新規・重複問わず）
                    $draws[] = ['weapon_id' => $weapon_id];
                    break;
                }
            }
        }

        return [
        'draws' => $draws,             // 全ての抽選結果（新旧問わず）
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
