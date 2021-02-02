<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use logger as lg;
use xlib as x;
class js{
    function __construct(){
        $ip=$_GET['ip'];
        $nameProxy=$_GET['nameProxy'];
        $proxy=$_GET['proxy'];
        $screenX=$_GET["screenX"];
        $screenY=$_GET["screenY"];
        $path=$_GET['path'];
        $platform=$_GET['platform'];
        $core=$_GET['core'];
        $memory=$_GET['memory'];
        if($memory=='undefined'){
            unset($memory);
        }else{
            $memory="\n[memory] => $memory GB";
        }
        if(x::isCookie()){
            $cookieEnabled='Да';
        }else{
            $cookieEnabled='Нет';
        }
        if($nameProxy){
            $nameProxy="\n[nameProxy] => $nameProxy";
        }
        if($proxy){
            $proxy="\n[proxy] => $proxy";
        }
        file_put_contents(__DIR__.'/log',file_get_contents(__DIR__.'/log').'---------->'.lg::getTimeSession()."\n[ip] => $ip$proxy$nameProxy\n[REQUEST_URI] => $path\n".lg::getUserAgent()."\n[CookieEnabled] => $cookieEnabled\n[platform] => $platform\n[core] => $core$memory\n[screenX] => $screenX\n[screenY] => $screenY\n");
    }
}
new js();
