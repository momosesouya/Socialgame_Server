<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weapon_rarities', function (Blueprint $table) {
            $table->unsignedSmallInteger('rarity_id')->default(0)->comment('武器レアリティID');
            $table->string('rarity_name')->charset('utf8')->default('no name')->comment('武器レアリティ名');
            $table->unsignedSmallInteger('get_item_amount')->default(0)->comment('ガチャで手に入る交換アイテム数');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('rarity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon_rarities');
    }
};
