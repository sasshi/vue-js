<?php
mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Tokyo');
ini_set('display_errors', 0);// エラー非表示(nestle.jpのみ)

// 環境変数
$env = explode('.', $_SERVER['SERVER_NAME']);
switch($env[0]) {
    case 'staging':
        define('DOMAIN', 'staging.nestle.jp');
        // エラー全表示
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        break;
        
    case 'authoring':
        define('DOMAIN', 'authoring.m.nestle.jp');
        // エラー全表示
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        break;
        
    default:
        if(strpos($env[0], 'nestle')) {
            define('DOMAIN', 'nestle.jp');
        }
        if(strpos($env[0], 'localhost') === 0) {
            define('DOMAIN', 'localhost');
            // エラー全表示
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
        } else {
            define('DOMAIN', 'nagasawa.sitetasting.com');
            // エラー全表示
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
        }
        break;
}

// 睡眠時間
$minutes = [
    1 => 15,
    2 => 20,
    3 => 25,
    4 => 30
];

// 音声ガイダンス有無
$guides = [
    10 => 'off',
    20 => 'on'
];

// 曲名
$titles = [
    100 => '海辺の朝',
    200 => '森林でのひととき',
    300 => '雨の午後',
    400 => '街の音',
    500 => '小川のせせらぎ',
    600 => '静寂の時間'
];

// デフォルト値
define('PATH_CONTENT_ROOT', '/coffee-nap/');           // コンテンツルートディレクトリ
define('PATH_MUSIC_FILE',   '/coffee-nap/lib/music/'); // 音楽ファイルディレクトリ
define('DEFAULT_MINUTES_INDEX', 1);                    // 睡眠時間の初期INDEX
define('DEFAULT_GUIDES_INDEX',  10);                   // 音声ガイダンス有無の初期値INDEX
define('DEFAULT_TITLES_INDEX',  100);                  // 曲名の初期値INDEX
define('DEFAULT_MUSIC_NO',      111);                  // 音楽ファイル名の初期値(DEFAULT_MINUTES_INDEX + DEFAULT_GUIDES_INDEX + DEFAULT_TITLES_INDEX)
