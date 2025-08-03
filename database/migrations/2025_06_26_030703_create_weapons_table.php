<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapons', function (Blueprint $table) {
            $table->unsignedBigInteger('weapon_id')->default(0)->comment('武器ID');
            $table->string('weapon_name')->charset('utf8')->default('no name')->comment('武器名');
            $table->unsignedSmallInteger('rarity_id')->default(0)->comment('武器レアリティID');
            $table->unsignedTinyInteger('weapon_category')->default(0)->comment('武器カテゴリー');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('weapon_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
