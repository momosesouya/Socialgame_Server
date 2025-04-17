<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //デバッグ
        //dd($request->un);

        // ユーザー管理ID
        $manage_id = 0;

        // ユーザーデータ
        $user_Data = 0;

        // ユーザーIDの決定
        $user_id = Str::ulid();

        //ユーザー名0文字以下、13文字以上かつ指定文字以外を使用していたらエラー
        $validator = Validator::make($request->all(), [
            'un' => 'required|max:12|regex:/^[ぁ-んァ-ンa-zA-Zー]+$/u',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $validator->validated();
        $user_name = $data['un'];

        // 引数を取得できない場合は登録ができない
        DB::transaction(function () use ($user_id, $user_name, &$user_Data, &$manage_id){
            //ユーザーデータ登録
            $user_Data = User::create([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'login_days' => config('constants.LOGIN_DAYS'),
                'max_stamina' => config('constants.MAX_STAMINA'),
                'last_stamina' => config('constants.LAST_STAMINA'),
            ]);
            $manage_id = $userData->manage_id;
        });

        return response()->json([
            'status' => 'success',
            'user_id' => $user_id,
            'user_name' => $user_name
        ], 200);
    }
}
