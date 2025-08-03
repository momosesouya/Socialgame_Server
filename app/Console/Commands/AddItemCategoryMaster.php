<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\DB;


class AddItemCategoryMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:ItemCategoryMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addItemCategoryData = [
            [
                'item_category' => 1,
                'category_name' => 'STAMINA_RECOVERY_ITEM', // スタミナ回復アイテム
            ],
            [
                'item_category' => 2,
                'category_name' => 'REINFORCE_ITEM', // 強化アイテム
            ],
            [
                'item_category' => 3,
                'category_name' => 'EXCHANGE_ITEM', // 交換アイテム
            ],
        ];

        DB::transaction(function () use ($addItemCategoryData) {
            foreach ($addItemCategoryData as $data) {
                $exists = ItemCategory::where('item_category', $data['item_category'])->exists();
                if (!$exists) {
                    ItemCategory::create([
                        'item_category'     => $data['item_category'],
                        'category_name'     => $data['category_name'],
                    ]);
                    $this->info("追加: {$data['category_name']}");
                } else {
                    $this->line("スキップ: {$data['item_category']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}

