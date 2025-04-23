<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->autoIncrement()->comment('ユーザー管理ID');
            $table->ulid('user_id')->charset('utf8')->comment('ユーザーID');
            $table->string('user_name',16)->charset('utf8')->comment('表示名');
            $table->unsignedTinyInteger('max_stamina')->default(100)->comment('最大スタミナ');
            $table->unsignedTinyInteger('last_stamina')->useCurrent()->comment('最終更新時スタミナ');
            $table->dateTime('stamina_updated')->useCurrent()->comment('スタミナ更新日時');
            $table->dateTime('last_login')->useCurrent()->comment('最終ログイン日時');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->unique(['user_id']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
