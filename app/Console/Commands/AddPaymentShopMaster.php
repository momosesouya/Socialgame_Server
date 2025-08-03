<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentShop;
use Illuminate\Support\Facades\DB;


class AddPaymentShopMaster extends Command
{
    // このコマンドでマスタデータ追加
    protected $signature = 'add:PaymentShopMaster';
    protected $description = 'マスタデータをデータベースに追加します';

    public function handle()
    {
        $addPaymentShopData = [
            [
                'product_id' => 5001,
                'product_name' => "通貨60個",
                'price' => 120,
                'paid_currency' => 60,
                'bonus_currency' => 0,
            ],
            [
                'product_id' => 5002,
                'product_name' => "通貨300個",
                'price' => 610,
                'paid_currency' => 270,
                'bonus_currency' => 30,
            ],
            [
                'product_id' => 5003,
                'product_name' => "通貨980個",
                'price' => 1840,
                'paid_currency' => 870,
                'bonus_currency' => 110,
            ],
            [
                'product_id' => 5004,
                'product_name' => "通貨1980個",
                'price' => 3680,
                'paid_currency' => 1720,
                'bonus_currency' => 260,
            ],
            [
                'product_id' => 5005,
                'product_name' => "通貨3280個",
                'price' => 6100,
                'paid_currency' => 2680,
                'bonus_currency' => 600,
            ],
            [
                'product_id' => 5006,
                'product_name' => "通貨6480個",
                'price' => 12000,
                'paid_currency' => 4880,
                'bonus_currency' => 1600,
            ],
        ];

        DB::transaction(function () use ($addPaymentShopData) {
            foreach ($addPaymentShopData as $data) {
                $exists = PaymentShop::where('product_id', $data['product_id'])->exists();
                if (!$exists) {
                    PaymentShop::create([
                        'product_id'     => $data['product_id'],
                        'product_name'   => $data['product_name'],
                        'price'          => $data['price'],
                        'paid_currency'  => $data['paid_currency'],
                        'bonus_currency' => $data['bonus_currency'],
                    ]);
                    $this->info("追加: {$data['product_name']}");
                } else {
                    $this->line("スキップ: {$data['product_name']}（既に存在）");
                }
            }
        });

        $this->info('マスタデータの追加処理が完了しました');
    }
}
