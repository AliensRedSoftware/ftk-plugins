<?php

/**
 * Api для всего сайта
 * --------------------
 */
class capi {
	
	/**
	 * Выполняется при подключение сайта
	 * ---------------------------------
	 */
	function execute () {
    	$xlib = new xlib();
    	if (capi::getSuccess()) {
        	if (capi::getType()) {
            	$func = capi::getFunc();
            	if(file_exists($func)) {
                	require_once $func;
					if(is_callable(array($xlib->geturi(3), 'execute'))) {
						$func = array($xlib->geturi(3), 'execute');
						$func();
					}
                }
            }
        }
  
    }
	
	/**
	 * Возвращаем набор api
	 * --------------------
	 */
	protected function getSuccess() {
    	$xlib = new xlib();
    	if($xlib->geturi(1) == 'capi') {
        	return true;
        } else {
    		return false;
        }
    }

	/**
	 * Возвращаем тип
	 * ---------------
	 * @system - Высокие привилегий
	 */
	protected function getType() {
    	$xlib = new xlib();
    	switch ($xlib->geturi(2)) {
        	case 'system': //Высокие привилегий выполнение
        		return 'system';
        	break;
       	 	default:
        		return false;
        	break;
        }
    }
	
	/**
 	 * Указывает статус отправки
     * --------------------------
 	 */
	public function setStatus($code) {
    	global $data;
    	$data['status'] .= $code;
    }
	
	/**
	 * Указывает response отправки
	 * ---------------------------
	 */
	public function setResponse($res) {
    	global $data;
    	if (is_array($res)){
    		$data['response'] = $res;
        } else {
        	$data['response'] .= $res;
        }
    }

	/**
	 * Возвращаем response
	 * --------------------
	 */
	public function getResponse() {
    	global $data;
    	header('Content-Type: application/json');
    	return json_encode($data);
    }

	/**
	 * Возвращаем выполняемую функцию
	 * ------------------------------
	 */
	protected function getFunc() {
    	$xlib = new xlib();
    	return '.' . $xlib->getPathModules('capi' . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . $xlib->geturi(3) . '.php');
    }
}