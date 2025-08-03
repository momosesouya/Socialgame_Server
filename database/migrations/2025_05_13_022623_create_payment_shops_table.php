<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_shops', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->default(0)->comment('商品ID');
            $table->string('product_name')->default('no name')->charset('utf8')->comment('商品名');
            $table->unsignedSmallInteger('paid_currency')->default(0)->comment('有償通貨数');
            $table->unsignedSmallInteger('bonus_currency')->default(0)->comment('おまけ無償通貨数');
            $table->unsignedSmallInteger('price')->default(0)->comment('商品価格');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_shops');
    }
};
