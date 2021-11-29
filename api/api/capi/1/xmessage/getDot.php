<?php

/**
 * Возвращение точек
 * -------------------------
 */
class getDot {
	
	/**
	 * Выполнение
	 * ----------
	 * 200	-	ОК
	 */
	function execute () {
    	$capi		=	new capi();
    	$xmessage	=	new xmessage();
    	$arr		=	$xmessage->getDotToArray();
    	$capi->setStatus(200);
    	$capi->setResponse($arr);
    	die($capi->getResponse());
    }
}