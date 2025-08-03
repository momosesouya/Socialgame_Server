<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use Illuminate\Support\Facades\DB;


class AddItemMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:ItemMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addItemData = [
            [
                'item_id' => 1001,
                'item_category' => 1,
                'item_name' => 'スタミナ回復アイテム',
            ],
            [
                'item_id' => 1002,
                'item_category' => 2,
                'item_name' => '武器強化アイテム',
            ],
            [
                'item_id' => 1003,
                'item_category' => 3,
                'item_name' => '武器交換アイテム',
            ],
        ];

        DB::transaction(function () use ($addItemData) {
            foreach ($addItemData as $data) {
                $exists = Item::where('item_id', $data['item_id'])->exists();
                if (!$exists) {
                    Item::create([
                        'item_id'       => $data['item_id'],
                        'item_category' => $data['item_category'],
                        'item_name'     => $data['item_name'],
                    ]);
                    $this->info("追加: {$data['item_category']}");
                } else {
                    $this->line("スキップ: {$data['item_id']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}

