<?php

return [
    // ユーザーデータ
    'MAX_STAMINA' => 200,
    'LAST_STAMINA' => 200,
    // ウォレットテーブル
    'FREE_AMOUNT' => 0,
    'PAID_AMOUNT' => 0,
    'MAX_AMOUNT' => 99999,
    // アイテム
    'WEAPON_ENHANCE_ITEM' => 1002,
    'ENHANCE_ITEM_ADD_NUM' => 5,
    // ガチャ
    'GACHA_COST' => 160,

    // マスターバージョン
    'MASTER_DATA_VERSION' => '1',

    // スタミナ関連
    'STAMINA_RECOVERY_SECOND' => 60,  // スタミナ回復にかかる時間
    'STAMINA_RECOVERY_VALUE' => 1,     // 1回のスタミナ回復量

    /*レスポンス*/
    /* 400番台...リダイレクト*/
    /* 500番台...サーバーエラー
    */

    /* リダイレクト */
    'ERRCODE_LOGIN_SESSION' => 400,
    'ERRCODE_USER_NOT_FOUND' => 401, // ユーザー認証エラー
    'ERRCODE_LOGIN_SESSION' => 403, // ユーザー認証エラー

    /*サーバーエラー*/
    'ERRCODE_VALIDATION' => 500,
    'ERRCODE_MASTER_VERSION' => 501,

    // エラーコード
    'ERRCODE_NOT_LOGGED_IN' => 503,        // ログインできなかった
    'ERRCODE_LOST_CONNECT' => 504,         // 通信が切断された

    'ERRCODE_CANT_REGISTRATION' => 505,              // 登録ができなかった
    'ERRCODE_CANT_LOGIN' => 506,                     // ログインできなかった
    'ERRCODE_CANT_UPDATE_HOME' => 507,               // ホーム情報の更新に失敗した
    'ERRCODE_CANT_STAMINA_RECOVERY' => 508,          // スタミナ回復ができなかった
    'ERRCODE_CANT_STAMINA_CONSUMPTION' => 509,       // スタミナ消費ができなかった
    'ERRCODE_CANT_RECOVERY_ANY_MORE_STAMINA' => 510, // これ以上スタミナが回復できない
    'ERRCODE_CANT_BUY_CURRENCY' => 511,              // 通貨の購入に失敗した
    'ERRCODE_NOT_ENOUGH_CURRENCY' => 512,            // 通貨が足りない
    'ERRCODE_NOT_GACHA_PERIOD' => 513,               // ガチャが期間内ではない
    'ERRCODE_NOT_GACHA_FOUND' => 513,                // ガチャが見つからない


    /*エラーメッセージ*/
    
    // ログイン
    'LOGIN_USER_NOT_FOUND' => 'ログインしているユーザーは見つかりませんでした',
    'USER_IS_NOT_LOGGED_IN' => 'ユーザーはログインしていません',
    'LOST_CONNECT' => '接続が切れました',
    
    // ホーム
    'CANT_UPDATE_HOME' => 'ホーム情報を更新できませんでした',
    
    // 登録
    'USERNAME_REQUIRED' => 'ユーザー名は必須です',
    'USERNAME_MAX' => 'ユーザー名は12文字以内で入力してください',
    'USERNAME_REGEX' => 'ユーザー名はひらがな、カタカナ、ローマ字のみ使用できます',
    'CANT_REGISTRATION' => '登録に失敗しました。',

    // ログイン
    'CANT_LOGIN' => 'ログインができませんでした',

    // ショップ
    'NOT_ENOUGH_CURRENCY' => '通貨が足りません',

    // ガチャ
    'NOT_GACHA_PERIOD' => 'ガチャが期間内ではありません',
    'NOT_GACHA_FOUND' => 'ガチャが見つかりません',

    // マスタデータ
    'MASTER_DATA_UPDATE' => 'マスターデータ更新が必要です',
    'LATEST_MASTER_DATA' => '最新のバージョンです',
];