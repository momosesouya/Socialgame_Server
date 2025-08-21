<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;

use Illuminate\Support\Facades\Log;

class MasterDataCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        // マスターデータチェック
        $clientMasterVersion = $request->mv;
        if(!MasterDataService::CheckMasterDataVersion($clientMasterVersion)) {
            return response()->json([
                'message' => config('constants.MASTER_DATA_UPDATE'),
                'serverVersion' => config('constants.MASTER_DATA_VERSION'),
                'success' => 0,
            ]);
        } else{
            // クライアントのバージョンが新しいとき
            return response()->json([
                'message' => config('constants.LATEST_MASTER_DATA'),
                'serverVersion' => config('constants.MASTER_DATA_VERSION'),
                'success' => 1,
            ]);
        }
    }
}