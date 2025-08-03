<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\ItemInstance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class StaminaRecoveryController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        if (!Auth::hasUser()) {
            return json_encode([
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ]);
        }

        $authUserData = Auth::user();
        $userBase = User::where('user_id', $request->uid);
        $userData = $userBase->first();

        if (!$userData || $userData->manage_id !== $authUserData->manage_id) {
            return json_encode([
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ]);
        }

        $now = Carbon::now();
        $lastRecovery = new Carbon($userData->stamina_updated);
        $minutesPassed = $lastRecovery->diffInMinutes($now);
        // 3分で1回復
        $recoveryPoints = intdiv($minutesPassed, 3);
        
        // 最終更新時間から現在のスタミナを算出
        if ($recoveryPoints > 0 && $userData->last_stamina < $userData->max_stamina) {
            $newStamina = min($userData->last_stamina + $recoveryPoints, $userData->max_stamina);
            $userData->last_stamina = $newStamina;
            $userData->stamina_updated = $now->toDateTimeString();
            $userData->save();
        }

        // 最大スタミナに達していればエラー
        if ($userData->last_stamina >= $userData->max_stamina) {
            return json_encode(config('constants.ERRCODE_CANT_RECOVERY_ANY_MORE_STAMINA'));
        }

        $walletBase = UserWallet::where('manage_id', $userData->manage_id);
        $walletData = $walletBase->first();
        $recoveryMethod = $request->remethod;

        DB::transaction(function () use (&$result, &$response, &$errcode, $recoveryMethod, $userData, $walletBase, $walletData, $request) {
            $user_id = $userData->user_id;
            switch ($recoveryMethod) {
                case 'currency':
                    $cost = 5;
                    $totalCurrency = $walletData->free_amount + $walletData->paid_amount;
                    if ($totalCurrency < $cost) {
                        $errcode = config('constants.ERRCODE_NOT_ENOUGH_CURRENCY');
                        return;
                    }
                    if ($walletData->free_amount >= $cost) {
                        $walletBase->update(['free_amount' => $walletData->free_amount - $cost]);
                    } elseif ($walletData->free_amount > 0) {
                        $useFree = $walletData->free_amount;
                        $usePaid = $cost - $useFree;
                        $walletBase->update([
                            'free_amount' => 0,
                            'paid_amount' => $walletData->paid_amount - $usePaid,
                        ]);
                    } else {
                        $walletBase->update(['paid_amount' => $walletData->paid_amount - $cost]);
                    }
                    break;

                case 'item':
                    $itemId = $request->item_id;
                    $item = ItemInstance::where('manage_id', $userData->manage_id)->where('item_id', $itemId)->first();
                    
                    if (!$item || $item->amount <= 0) {
                        $errcode = config('constants.ERRCODE_ITEM_NOT_FOUND_OR_EMPTY');
                        return;
                    }
                    $item->amount -= 1;
                    $item->save();
                    break;

                default:
                    $errcode = config('constants.ERRCODE_INVALID_PARAM');
                    return;
            }
            // スタミナと最終更新時刻を更新
            User::where('user_id', $user_id)->update([
                'last_stamina' => $userData->max_stamina,
                'stamina_updated' => Carbon::now()->toDateTimeString(),
            ]);

            $response = [
                'users' => User::where('user_id', $user_id)->first(),
                'wallet' => UserWallet::where('manage_id', $userData->manage_id)->first(),
            ];
            $result = 1;
        });


        if ($result === 0) {
            return json_encode($errcode ?: config('constants.ERRCODE_CANT_STAMINA_RECOVERY'));
        }

        return response()->json($response);
    }
}
