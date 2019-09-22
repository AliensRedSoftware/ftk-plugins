<?php

class logger {

    /**
     * Выполняется при подключение модуля
     */
    static function execute () {
        file_put_contents("log",file_get_contents('log') . '---------->' . logger::getTimeSession() . "\n" . "[ip] => " . logger::getIp() . "\n" . logger::getRequest_uri() . "\n" . logger::getUserAgent() . "\n");
    }

    /**
     * Возвращает ip подключенного ;)
     */
    static function getIp () {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
        else $ip = $remote;
        return $ip;
    }

    /**
     * Возвращает путь выполнение скрипта ;)
     */
    static function getRequest_uri () {
        return "[REQUEST_URI] => " . urldecode($_SERVER['REQUEST_URI']);
    }

    /**
     * Возвращает user-agent браузера
     */
    static function getUserAgent () {
        return "[HTTP_USER_AGENT] => " . $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Возвращает Время и дату
     */
    static function getTimeSession () {
        return date('Y-m-d') . ':' . date('H:i:s', time() - date('Z'));
    }

    /**
     * Возвращает Время
     */
    static function getTime () {
        return date('H:i:s', time() - date('Z'));
    }

    /**
     * Возвращает Минуты
     */
    static function getTimeMinutes () {
        return date('i', time() - date('Z'));
    }
}
