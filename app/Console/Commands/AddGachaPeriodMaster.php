<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GachaPeriod;
use Illuminate\Support\Facades\DB;


class AddGachaPeriodMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:GachaPeriodMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addGachaPeriodData = [
            [
                'gacha_id' => 100,
                'gacha_name' => "通常ガチャ",
                'single_cost' => 160,
                'multi_cost' => 1600,
                'period_start' => "2025/1/1  00:00:00",
                'period_end' => "9999/12/31  23:59:59",
            ],
            [
                'gacha_id' => 200,
                'gacha_name' => "ピックアップガチャ",
                'single_cost' => 160,
                'multi_cost' => 1600,
                'period_start' => "2025/4/1  10:00:00",
                'period_end' => "2026/3/31  9:59:59",
            ],
            
        ];

        DB::transaction(function () use ($addGachaPeriodData) {
            foreach ($addGachaPeriodData as $data) {
                $exists = GachaPeriod::where('gacha_id', $data['gacha_id'])->exists();
                if (!$exists) {
                    GachaPeriod::create([
                        'gacha_id'     => $data['gacha_id'],
                        'gacha_name'   => $data['gacha_name'],
                        'single_cost'  => $data['single_cost'],
                        'multi_cost'   => $data['multi_cost'],
                        'period_start' => $data['period_start'],
                        'period_end'   => $data['period_end'],
                    ]);
                    $this->info("追加: {$data['gacha_name']}");
                } else {
                    $this->line("スキップ: {$data['gacha_name']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}
