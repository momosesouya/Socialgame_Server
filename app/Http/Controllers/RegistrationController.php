<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserWallet;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;   

class RegistrationController extends Controller
{
    public function __invoke(Request $request)
    {
        //デバッグ
        // \Log::debug('Register Request: ', $request->all());
        // dd($request->all());
        $result = 0;
        $response = 0;

        // ユーザー管理ID
        $manage_id = 0;

        // ユーザーデータ
        $user_Data = 0;
        // ウォレットデータ
        $walletsData = [];

        // ユーザーIDの決定
        $user_id = Str::ulid();

        //ユーザー名0文字以下、13文字以上かつ指定文字以外を使用していたらエラー
        $validator = Validator::make($request->all(), [
            'un' => ['required','max:12','regex:/^[\pL\pN\s]+$/u'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $validator->validated();
        $user_name = $data['un'];

        // 引数を取得できない場合、登録ができない
        $response = [];
        // DB::transaction(function () use (&$result, $user_id, $user_name, $manage_id, &$response,)
        DB::transaction(function () use (&$result, $user_id, $user_name, $manage_id, &$user_Data, &$walletsData){
    
            //ユーザーデータ登録
            // $response = User::create([
            //     'user_id' => $user_id,
            //     'user_name' => $user_name,
            //     'max_stamina' => config('constants.MAX_STAMINA'),
            //     'last_stamina' => config('constants.LAST_STAMINA'),
            //     'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
            //     'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
            // ]);
            $user_Data = User::create([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'max_stamina' => config('constants.MAX_STAMINA'),
                'last_stamina' => config('constants.LAST_STAMINA'),
                'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
                'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $manage_id = $user_Data->manage_id;
    
            //ウォレット登録
            // $response = UserWallet::create([
            //     'manage_id' => $manage_id,
            //     'free_amount' => config('constants.FREE_AMOUNT'),
            //     'paid_amount' => config('constants.PAID_AMOUNT'),
            //     'max_amount' => config('constants.MAX_AMOUNT'),
            // ]);
            $walletsData = UserWallet::create([
                'manage_id' => $manage_id,
                'free_amount' => config('constants.FREE_AMOUNT'),
                'paid_amount' => config('constants.PAID_AMOUNT'),
                'max_amount' => config('constants.MAX_AMOUNT'),
            ]);

            $walletsData = UserWallet::where('manage_id', $manage_id)->first();
    
            $result = 1;
        });

        if ($result === 0) {
            return response()->json([
                'result' => config('constants.ERRCODE_CANT_REGISTRATION'),
                'message' => config('constants.CANT_REGISTRATION'),
            ], 500);
        }
        // $response['result'] = $result;
        // return json_encode($response);
        return response()->json([
        'users' => $user_Data,
        'wallets' => $walletsData,
        'result' => $result,
        ]);
    }
}