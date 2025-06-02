<?php
namespace App\Libs;

class GameUtilService
{
    /**
     *  最終更新時間と現在時間を計算したスタミナの処理
     */
    public static function getCurrentStamina($lastStamina, $maxStamina, $updated) : int
    {
        $currentStamina = 0;

        // 現在のスタミナが最大値を超えている場合はそのまま返す
        if ($lastStamina >= $maxStamina)
        {
            return $lastStamina;
        }

        // 最後のスタミナ回復からの経過秒数を取得し、回復量を算出
        $diffSecond = strtotime('now') - strtotime($updated);
        $recoveryStamina = floor($diffSecond / config('constants.STAMINA_RECOVERY_SECOND')) * config('constants.STAMINA_RECOVERY_VALUE');

        // 時間経過で最大値を超える場合は最大値まで
        $currentStamina = $lastStamina + $recoveryStamina;
        if ($currentStamina > $maxStamina)
        {
            $currentStamina = $maxStamina;
        }
        return $currentStamina;
    }
}