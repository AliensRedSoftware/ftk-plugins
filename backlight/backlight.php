<?php

/**
 * @name фон подсветки
 * @version 1.00
 */
use xlib as x;
class backlight {

    /**
     * Цветовая линия
     * s - Начало
     * e - Конец
     */
    public function line($s='white',$e='black'){
        return x::div(['css'=>['height'=>'100%','width'=>'100%','top'=>0,'left'=>0,'position'=>'absolute','background-image'=>"linear-gradient(100deg, $s 0%, $e 100%)"]]);
    }
}
