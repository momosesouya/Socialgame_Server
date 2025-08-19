<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GachaWeapon;
use Illuminate\Support\Facades\DB;


class AddGachaWeaponMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:GachaWeaponMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addGachaWeaponData = [
            // ノーマルガチャ
            // NORMAL
            [
                'gacha_id' => 100,
                'weapon_id' => 1000001,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1000002,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1000003,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1000004,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1000005,
                'weight' => 150000,
            ],
            // RARE
            [
                'gacha_id' => 100,
                'weapon_id' => 1500001,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1500002,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1500003,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1500004,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 1500005,
                'weight' => 34000,
            ],
            // S RARE
            [
                'gacha_id' => 100,
                'weapon_id' => 2000001,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2000002,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2000003,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2000004,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2000005,
                'weight' => 10000,
            ],
            // SS RARE
            [
                'gacha_id' => 100,
                'weapon_id' => 2500001,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2500002,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2500003,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 100,
                'weapon_id' => 2500004,
                'weight' => 7500,
            ],
            
            // レアガチャ
            // NORMAL
            [
                'gacha_id' => 200,
                'weapon_id' => 1000001,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1000002,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1000003,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1000004,
                'weight' => 150000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1000005,
                'weight' => 150000,
            ],
            // RARE
            [
                'gacha_id' => 200,
                'weapon_id' => 1500001,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1500002,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1500003,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1500004,
                'weight' => 34000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 1500005,
                'weight' => 34000,
            ],
            // S RARE
            [
                'gacha_id' => 200,
                'weapon_id' => 2000001,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2000002,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2000003,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2000004,
                'weight' => 10000,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2000005,
                'weight' => 10000,
            ],
            // SS RARE
            [
                'gacha_id' => 200,
                'weapon_id' => 2500001,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2500002,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2500003,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 2500004,
                'weight' => 7500,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 3000001,
                'weight' => 1666,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 3000002,
                'weight' => 1667,
            ],
            [
                'gacha_id' => 200,
                'weapon_id' => 3000003,
                'weight' => 1667,
            ],
        ];

        DB::transaction(function () use ($addGachaWeaponData) {
            foreach ($addGachaWeaponData as $data) {
                $exists = GachaWeapon::where('weapon_id', $data['weapon_id'])
                                     ->where('gacha_id', $data['gacha_id'])
                                     ->exists();
                if (!$exists) {
                GachaWeapon::create([
                    'gacha_id'  => $data['gacha_id'],
                    'weapon_id' => $data['weapon_id'],
                    'weight'    => $data['weight'],
                ]);
                    $this->info("追加: gacha_id={$data['gacha_id']}, weapon_id={$data['weapon_id']}");
                } else {
                    $this->line("スキップ: gacha_id={$data['gacha_id']}, weapon_id={$data['weapon_id']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}