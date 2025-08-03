<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Weapon;
use Illuminate\Support\Facades\DB;


class AddWeaponMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:WeaponMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addWeaponData = [
            // NORMAL
            [
                'weapon_id' => 1000001,
                'rarity_id' => 10000,
                'weapon_category' => 1,
                'weapon_name' => "はじまりのけん",
            ],
            [
                'weapon_id' => 1000002,
                'rarity_id' => 10000,
                'weapon_category' => 1,
                'weapon_name' => "ふるびたけん",
            ],
            [
                'weapon_id' => 1000003,
                'rarity_id' => 10000,
                'weapon_category' => 2,
                'weapon_name' => "ふるびたつえ",
            ],
            [
                'weapon_id' => 1000004,
                'rarity_id' => 10000,
                'weapon_category' => 3,
                'weapon_name' => "ふるびたたて",
            ],
            [
                'weapon_id' => 1000005,
                'rarity_id' => 10000,
                'weapon_category' => 1,
                'weapon_name' => "めっちゃよわいけん",
            ],
            // RARE
            [
                'weapon_id' => 1500001,
                'rarity_id' => 15000,
                'weapon_category' => 1,
                'weapon_name' => "ほのおのけん",
            ],
            [
                'weapon_id' => 1500002,
                'rarity_id' => 15000,
                'weapon_category' => 1,
                'weapon_name' => "こおりのけん",
            ],
            [
                'weapon_id' => 1500003,
                'rarity_id' => 15000,
                'weapon_category' => 1,
                'weapon_name' => "よわいけん",
            ],
            [
                'weapon_id' => 1500004,
                'rarity_id' => 15000,
                'weapon_category' => 2,
                'weapon_name' => "よわいつえ",
            ],
            [
                'weapon_id' => 1500005,
                'rarity_id' => 15000,
                'weapon_category' => 3,
                'weapon_name' => "よわいたて",
            ],
            // SRARE
            [
                'weapon_id' => 2000001,
                'rarity_id' => 20000,
                'weapon_category' => 1,
                'weapon_name' => "どらごんのけん",
            ],
            [
                'weapon_id' => 2000002,
                'rarity_id' => 20000,
                'weapon_category' => 1,
                'weapon_name' => "だいやのけん",
            ],
            [
                'weapon_id' => 2000003,
                'rarity_id' => 20000,
                'weapon_category' => 1,
                'weapon_name' => "つよそうなけん",
            ],
            [
                'weapon_id' => 2000004,
                'rarity_id' => 20000,
                'weapon_category' => 2,
                'weapon_name' => "つよそうなつえ",
            ],
            [
                'weapon_id' => 2000005,
                'rarity_id' => 20000,
                'weapon_category' => 3,
                'weapon_name' => "つよそうなたて",
            ],
            // SSRARE
            [
                'weapon_id' => 2500001,
                'rarity_id' => 25000,
                'weapon_category' => 1,
                'weapon_name' => "かみのけん",
            ],
            [
                'weapon_id' => 2500002,
                'rarity_id' => 25000,
                'weapon_category' => 1,
                'weapon_name' => "つよいけん",
            ],
            [
                'weapon_id' => 2500003,
                'rarity_id' => 25000,
                'weapon_category' => 2,
                'weapon_name' => "つよいつえ",
            ],
            [
                'weapon_id' => 2500004,
                'rarity_id' => 25000,
                'weapon_category' => 3,
                'weapon_name' => "つよいたて",
            ],
            // URARE
            [
                'weapon_id' => 3000001,
                'rarity_id' => 30000,
                'weapon_category' => 1,
                'weapon_name' => "めっちゃつよいけん",
            ],
            [
                'weapon_id' => 3000002,
                'rarity_id' => 30000,
                'weapon_category' => 2,
                'weapon_name' => "めっちゃつよいつえ",
            ],
            [
                'weapon_id' => 3000003,
                'rarity_id' => 30000,
                'weapon_category' => 3,
                'weapon_name' => "めっちゃつよいたて",
            ],
        ];

        DB::transaction(function () use ($addWeaponData) {
            foreach ($addWeaponData as $data) {
                $exists = Weapon::where('weapon_id', $data['weapon_id'])->exists();
                if (!$exists) {
                    Weapon::create([
                        'weapon_id' => $data['weapon_id'],
                        'rarity_id' => $data['rarity_id'],
                        'weapon_category' => $data['weapon_category'],
                        'weapon_name' => $data['weapon_name'],
                    ]);
                    $this->info("追加: {$data['weapon_id']}");
                } else {
                    $this->line("スキップ: {$data['weapon_id']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}
