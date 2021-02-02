<?php

/**
 * Возвращение имя нити
 * -------------------------
 */
class getNameThread {

	/**
	 * Выполнение
	 * ----------
	 * 200	-	ОК
	 * 404	-	Ничего не найдено
	 */
	function execute () {
    	$id		=	urldecode($_REQUEST['id']);
    	$space	=	urldecode($_REQUEST['space']);
    	$capi	=	new capi();
    	$name	=	getNameThread::getName($space, $id);
    	if ($name) {
    		$capi->setStatus(200);
    		$capi->setResponse($name);
    	} else {
            $capi->setStatus(404);
        }
    	die($capi->getResponse());
    }


    /**
     * Возвращаем имя нити
     * -------------------
     * @return string
     */
    public function getName ($id) {
        $xlib = new xlib();
    	require_once $_SERVER['DOCUMENT_ROOT'] . $xlib->getPathModules('capi/execute/getThreads.php');
    	$getThreads	=	new getThreads();
    	$arr 	=	$getThreads->getToArray($id);
        
    	foreach ($arr as $key => $value) {
    		if ($id == $arr[$key]['opt']['id']) {
    			return $key;
    		}
    	}
    }
}
