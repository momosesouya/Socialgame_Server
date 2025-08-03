<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapon_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(0)->comment('ユーザー管理ID');
            $table->unsignedMediumInteger('weapon_id')->default(0)->comment('武器ID');
            $table->unsignedSmallInteger('rarity_id')->default(0)->comment('武器レアリティID');
            $table->unsignedTinyInteger('level')->default(1)->comment('武器レベル');
            $table->unsignedTinyInteger('level_max')->default(50)->comment('武器レベル上限');
            $table->unsignedInteger('current_exp')->default(0)->comment('現在の経験値');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary(['manage_id','weapon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_instances');
    }
};
