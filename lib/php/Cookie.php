<?php
class Cookie {
    private $__path;
    private $__domain;
    private $__secure;
    private $__httponly;
    
    public function __construct() {
        $this->__path     = PATH_CONTENT_ROOT;
        $this->__domain   = DOMAIN;
        $this->__secure   = true;
        $this->__httponly = true;
    }
    
    public function setCookies($datas, $expire) {
        foreach($datas as $name => $value) {
            setcookie($name, $value, $expire, $this->path, $this->domain, $this->secure, $this->httponly);
        }
//         // TODO:Cookieの保存期限をいつまでにするのか？？一応10年くらいにした方がよいのでは？？
//         setcookie($key, $value, time()+60*60*24*30, PATH_CONTENT_ROOT, $domain, TRUE, TRUE);
    }
}