<?php

/**
 * Возвращение пространство
 * -------------------------
 */
class getSpace {

	/**
	 * Выполнение
	 * ----------
	 * 200	-	ОК
	 * 404	-	Ничего не найдено
	 */
	function execute () {
    	$dot		=	urldecode($_REQUEST['dot']);
    	$capi		=	new capi();
    	$xmessage	=	new xmessage();
    	$arr		=	$xmessage->getSpace($dot);
    	if (count($arr) > 0) {
    		$capi->setStatus(200);
    		$capi->setResponse($arr);
    	} else {
            $capi->setStatus(404);
        }
    	die($capi->getResponse());
    }
}