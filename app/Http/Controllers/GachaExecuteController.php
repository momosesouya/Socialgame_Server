<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $gachaId = $request->input('gacha_id');

        $now = Carbon::now();
        
        $period = GachaPeriod::where('period_start', '<=', $now)
            ->where('period_end', '>=', $now)
            ->first();

        if (!$period) {
            return response()->json(['errcode' => 'constants.NOT_GACHA_PERIOD'], 'constants.ERRCODE_NOT_GACHA_PERIOD');
        }

        $gachaService = new GachaService($gachaId);

        if (!$gachaService) {
            return response()->json(['errcode' => 'constants.NOT_GACHA_FOUND'], 'constants.ERRCODE_NOT_GACHA_FOUND');
        }

        $result = $gachaService->execute($request);
        return response()->json($result);
    }
}
