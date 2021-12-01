<?php

/**
 * @name Логирование
 * @version 1.0
 */
use xlib as x;
class logger {

    /**
     * Выполнение
     */
    function execute(){
        if(!x::isJs()){
            $nameProxy=self::getNameProxy();
            $ProxyIp=self::getProxyIp();
            if($nameProxy){
                $nameProxy="\n[nameProxy] => ".self::getNameProxy();
            }
            if($ProxyIp){
                $ProxyIp="\n[proxy] => ".self::getProxyIp();
            }
            //php
            file_put_contents(__DIR__.'/log',file_get_contents(__DIR__.'/log') . '---------->' . self::getTimeSession() . "\n" . "[ip] => " . self::getIp() . $nameProxy . $ProxyIp . "\n" . self::getRequest_uri() . "\n" . self::getUserAgent() . "\n");
        }else{
            //php
            $execute=x::getPathModules('logger/js.php');
            $ip=self::getIp();
            $nameProxy=self::getNameProxy();
            $ProxyIp=self::getProxyIp();
            $path=urldecode($_SERVER['REQUEST_URI']);
            //js HACK ATTACK...
            x::js("
var r = new XMLHttpRequest();
var screenX=window.screen.width;
var screenY=window.screen.height;
var platform=navigator.platform;
var core=navigator.hardwareConcurrency;
var memory=navigator.deviceMemory;
r.open('GET','$execute?'+'screenX='+screenX+'&'+'screenY='+screenY+'&ip=$ip&nameProxy=$nameProxy&proxy=$proxy&path=$path&platform='+platform+'&core='+core+'&memory='+memory);
r.send();
");
        }
    }

    /**
     * Возвращает имя прокси подключенного ;)
     * @return string
     */
    function getNameProxy(){
        return $_SERVER['HTTP_VIA'];
    }

    /**
     * Возвращает ip прокси подключенного ;)
     * @return string
     */
    function getProxyIp($proxy=true){
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    /**
     * Возвращает ip подключенного ;)
     * @return string
     */
    function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Возвращает путь выполнение скрипта ;)
     * @return string
     */
    function getRequest_uri(){
        return "[REQUEST_URI] => " . urldecode($_SERVER['REQUEST_URI']);
    }

    /**
     * Возвращает user-agent браузера
     * @return string
     */
    function getUserAgent(){
        return "[HTTP_USER_AGENT] => " . $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Возвращает Время и дату
     * @return int
     */
    function getTimeSession(){
        return date('Y-m-d') . ':' . date('H:i:s', time() - date('Z'));
    }

    /**
     * Возвращает Время
     * @return int
     */
    function getTime(){
        return date('H:i:s', time() - date('Z'));
    }

    /**
     * Возвращает Минуты
     * @return int
     */
    function getTimeMinutes(){
        return date('i', time() - date('Z'));
    }
}
