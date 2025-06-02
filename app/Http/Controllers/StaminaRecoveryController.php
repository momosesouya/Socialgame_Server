<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaminaRecoveryController extends Controller
{
    /**
     * スタミナ回復
     * remethod = 回復方法(通貨orアイテム)
     */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // --- Auth処理(ログイン確認)-----------------------------------------
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();

        // ユーザー情報
        $userBase = User::where('user_id',$request->uid);

        // ユーザー情報取得
        $userData = $userBase->first();
       
        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // ログインしているユーザーが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }
        // -----------------------------------------------------------------

        // ウォレット情報取得
        $walletBase = UserWallet::where('manage_id',$manage_id);
        $walletData = $walletBase->first();

        // 回復方法を取得
        $recoveryMethod = $request->remethod;
        // 現在のスタミナが最大スタミナを超えていたらエラー
        if ($userData->last_stamina >= $userData->max_stamina) {
            $errcode = config('constants.ERRCODE_CANT_RECOVERY_ANY_MORE_STAMINA');
            $response = $errcode;
            return json_encode($response);
        }

        DB::transaction(function () use (&$result, $userData, $manage_id, $walletBase,$walletData, $recoveryMethod) {
            // 現在のスタミナが最大スタミナではないときに最大まで回復
            if($userData->last_stamina < $userData->max_stamina)
            {
                $result = User::where('user_id', $userData->user_id)->update([
                    'last_stamina' => $userData->max_stamina,
                ]);

                $consumptionCurrency = 5; // 消費する通貨
                $totalCurrency = $walletData->free_amount + $walletData->paid_amount; // 通貨の合計
                switch($recoveryMethod)
                {
                    case 'currency':
                        // 通貨が足りないとき
                        if ($totalCurrency < $consumptionCurrency)
                        {
                            $errcode = config('constants.ERRCODE_NOT_ENOUGH_CURRENCY');
                            $response = $errcode;
                            return json_encode($response);
                        }
                        
                        // 無償分の通貨だけで足りるとき
                        if($walletData->free_amount > 0)
                        {
                            $result = $walletBase->update([
                                'free_amount' => $walletData->free_amount - $consumptionCurrency,
                            ]);
                        }
                        // 無償分の通貨はあるが足りないとき 無償+有償
                        else if($walletData->free_amount < $consumptionCurrency && $walletData->free_amount > 0)
                        {
                            $useFree = $walletData->free_amount;
                            $usePaid = $consumptionCurrency - $useFree;

                            $result = $walletBase->update([
                                'free_amount' => 0,
                                'paid_amount' => $walletData->paid_amount - $usepaid,
                            ]);
                        }
                        else 
                        {
                            $result = $walletBase->update([
                                'paid_amount' => $walletData->paid_amount - $consumptionCurrency,
                            ]);
                        }
                        break;
                    default:
                        break;
                }
                $result = 1;
            }
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_STAMINA_RECOVERY');
                $result = $errcode;
                break;
            case 1:
                $response = [
                    'users' => User::where('user_id', $request->uid)->first(),
                    'wallet' => UserWallet::where('manage_id', $manage_id),
                ];
                break;
        }

        return json_encode($request);
    }
}
