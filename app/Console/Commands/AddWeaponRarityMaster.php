<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WeaponRarity;
use Illuminate\Support\Facades\DB;


class AddWeaponRarityMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:WeaponRarityMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addWeaponRarityData = [
            [
                'rarity_id' => 10000,
                'rarity_name' => "NORMAL",
                'get_item_amount' => 0,
            ],
            [
                'rarity_id' => 15000,
                'rarity_name' => "RARE",
                'get_item_amount' => 0,
            ],
            [
                'rarity_id' => 20000,
                'rarity_name' => "SRARE",
                'get_item_amount' => 10,
            ],
            [
                'rarity_id' => 25000,
                'rarity_name' => "SSRARE",
                'get_item_amount' => 100,
            ],
            [
                'rarity_id' => 30000,
                'rarity_name' => "URARE",
                'get_item_amount' => 600,
            ],
            [
                'rarity_id' => 40000,
                'rarity_name' => "LRARE",
                'get_item_amount' => 0,
            ],
        ];

        DB::transaction(function () use ($addWeaponRarityData) {
            foreach ($addWeaponRarityData as $data) {
                $exists = WeaponRarity::where('rarity_id', $data['rarity_id'])->exists();
                if (!$exists) {
                    WeaponRarity::create([
                        'rarity_id'       => $data['rarity_id'],
                        'rarity_name'     => $data['rarity_name'],
                        'get_item_amount' => $data['get_item_amount'],
                    ]);
                    $this->info("追加: {$data['rarity_id']}");
                } else {
                    $this->line("スキップ: {$data['rarity_id']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}
