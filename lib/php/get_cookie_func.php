<?php

// require_once dirname(__FILE__).'/config.php';
// require_once dirname(__FILE__).'/functions.php';

// if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
//     && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $result = true; // 初回アクセス
    if(isset($_COOKIE['is_first'])) {
        // 2回目以降のアクセス
        $result = false;
    }
    setCookieData('minute_index', $result, DOMAIN);
    // setcookie('is_first', 'test', time()+60*60*24*30, '/');
    header('content-type: application/json; charset=utf-8');
    // echo json_encode($_COOKIE);
    echo json_encode($result);
// } else {
//     header('Location: /coffee-nap/');
//     exit;
// }
