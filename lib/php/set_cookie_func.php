<?php
require_once dirname(__FILE__).'/config.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
    $minute_index = DEFAULT_MINUTES_INDEX;
    $guide_index  = DEFAULT_GUIDES_INDEX;
    $title_index  = DEFAULT_TITLES_INDEX;
    $music_no     = DEFAULT_MUSIC_NO;
    if((isset($_POST['minute_index']) && !empty($_POST['minute_index']) && isset($minutes[$_POST['minute_index']])) &&
        (isset($_POST['guide_index']) && !empty($_POST['guide_index']) && isset($guides[$_POST['guide_index']])) &&
        (isset($_POST['title_index']) && !empty($_POST['title_index']) && isset($titles[$_POST['title_index']]))) {
        $minute_index = $_POST['minute_index'];
        $guide_index  = $_POST['guide_index'];
        $title_index  = $_POST['title_index'];
        $music_no     = $minute_index+$guide_index+$title_index;
    }
    
    // Cookieに保存する
    setCookieData('minute_index', $minute_index, DOMAIN);
    setCookieData('guide_index', $guide_index, DOMAIN);
    setCookieData('title_index', $title_index, DOMAIN);
    
    echo json_encode([
            'minute_index' => $minute_index,
            'guide_index'  => $guide_index,
            'title_index'  => $title_index,
            'music_no'     => $music_no,
    ]);
} else {
    header('Location: /coffee-nap/');
    exit;
}
