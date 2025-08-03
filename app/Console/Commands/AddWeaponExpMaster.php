<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WeaponExp;
use Illuminate\Support\Facades\DB;


class AddWeaponExpMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:WeaponExpMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addWeaponExpData = [
            [
                'rarity_id' => 10000,
                'need_exp' => 50,
            ],
            [
                'rarity_id' => 15000,
                'need_exp' => 100,
            ],
            [
                'rarity_id' => 20000,
                'need_exp' => 150,
            ],
            [
                'rarity_id' => 25000,
                'need_exp' => 200,
            ],
            [
                'rarity_id' => 30000,
                'need_exp' => 250,
            ],
            [
                'rarity_id' => 40000,
                'need_exp' => 0,
            ],
        ];

        DB::transaction(function () use ($addWeaponExpData) {
            foreach ($addWeaponExpData as $data) {
                $exists = WeaponExp::where('rarity_id', $data['rarity_id'])->exists();
                if (!$exists) {
                    WeaponExp::create([
                        'rarity_id'       => $data['rarity_id'],
                        'need_exp'        => $data['need_exp']
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
