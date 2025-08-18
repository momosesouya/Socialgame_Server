<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;

class MasterDataCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        // マスターデータチェック
        $clientMasterVersion = $request->mv;
        if(!MasterDataService::CheckMasterDataVersion($clientMasterVersion)) {
            return config('error.ERROR_MASTER_DATA_UPDATE');
        }
        $response = ['message' => '正常終了'];
        
        return json_encode($response);
    }
}