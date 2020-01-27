<?php
require_once dirname(__FILE__).'/config.php';

$result = false; // 2回目以降のアクセス
if(!isset($_COOKIE['is_first'])) {
    $result = true; // 初回アクセス
    setCookieData('is_first', 'true', DOMAIN);
}
// echo json_encode($_COOKIE['is_first']);
header('content-type: application/json; charset=utf-8');
echo json_encode($result);

function setCookieData($key, $value, $domain, $del = false) {
    if($del) {
        // 削除
        // setcookie($key, $value, time()-3600, PATH_CONTENT_ROOT, $domain, TRUE, TRUE);
        setcookie($key, $value, time()-3600, PATH_CONTENT_ROOT, '', TRUE, TRUE); // IEではlocalhostを受け付けなかったので、一時的にdomainを消す
    } else {
        // 保存
        // TODO:Cookieの保存期限をいつまでにするのか？？一応10年くらいにした方がよいのでは？？
        // setcookie($key, $value, time()+60*60*24*30, PATH_CONTENT_ROOT, $domain, TRUE, TRUE);
        setcookie($key, $value, time()+60*60*24*30, PATH_CONTENT_ROOT, '', TRUE, TRUE); // IEではlocalhostを受け付けなかったので、一時的にdomainを消す
    }
}