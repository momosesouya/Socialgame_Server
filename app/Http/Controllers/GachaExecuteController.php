<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\GachaWeapon;
use App\Models\GachaPeriod;
use App\Models\Weapon;
use App\Models\WeaponInstance;

use App\Http\Controllers\Gacha\GachaService;
use App\Http\Controllers\Gacha\NormalGachaService;
use App\Http\Controllers\Gacha\RareGachaService;

class GachaExecuteController extends Controller
{
    public function __invoke(Request $request)
    {
        $gacha_id = $request->input('gacha_id');

        $now = Carbon::now();
        
        $period = GachaPeriod::where('period_start', '<=', $now)
            ->where('period_end', '>=', $now)
            ->first();

        if (!$period) {
            return response()->json(['errcode' => 'GACHA_NOT_AVAILABLE'], 400);
        }


        $gachaService = new GachaService($gacha_id);

        if (!$gachaService) {
            return response()->json(['errcode' => 'INVALID_GACHA_TYPE'], 400);
        }

        $result = $gachaService->execute($request);
        return response()->json($result);
    }
}
