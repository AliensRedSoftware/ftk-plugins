<?php

/**
 * @name Работа с играми
 * @ver 1.0
 */
class games {

    /**
     * Возврщаем игру flash
     * $url-Ссылка
     */
    public function get($url){
        return "<embed wmode='transparent' src='$url' type='application/x-shockwave-flash' height='320px' width='100%'>";
    }
}
