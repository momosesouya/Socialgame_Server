<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WeaponCategory;
use Illuminate\Support\Facades\DB;


class AddWeaponCategoryMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:WeaponCategoryMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addWeaponCategoryData = [
            [
                'weapon_category' => 1,
                'weapon_category_name' => "SWORD",
            ],
            [
                'weapon_category' => 2,
                'weapon_category_name' => "WAND",
            ],
            [
                'weapon_category' => 3,
                'weapon_category_name' => "SHIELD",
            ],
            [
                'weapon_category' => 4,
                'weapon_category_name' => "EXCHANGEWEAPON",
            ],
        ];

        DB::transaction(function () use ($addWeaponCategoryData) {
            foreach ($addWeaponCategoryData as $data) {
                $exists = WeaponCategory::where('weapon_category', $data['weapon_category'])->exists();
                if (!$exists) {
                    WeaponCategory::create([
                        'weapon_category'       => $data['weapon_category'],
                        'weapon_category_name'     => $data['weapon_category_name'],
                    ]);
                    $this->info("追加: {$data['weapon_category_name']}");
                } else {
                    $this->line("スキップ: {$data['weapon_category']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}
