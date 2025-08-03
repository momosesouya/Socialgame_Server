<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gacha_weapons', function (Blueprint $table) {
            $table->unsignedSmallInteger('gacha_id')->default(0)->comment('ガチャID');
            $table->unsignedMediumInteger('weapon_id')->default(0)->comment('武器ID');
            $table->unsignedMediumInteger('weight')->default(0)->comment('重み');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary(['gacha_id', 'weapon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gacha_weapons');
    }
};
