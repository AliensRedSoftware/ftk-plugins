<?php

/**
 * Работа api для всего сайта
 * ver 1.10
 * --------------------
 */
use xlib as x;
class api{

    static $data;

	/**
	 * Выполняется при подключение сайта
	 * ---------------------------------
	 */
	function connect(){
	    $api=x::geturi(0);
	    if($api == 'api'){
	        if(self::setApi(x::geturi(1), x::geturi(2))){
	            $func=self::getFunc();
	            if($func != 'undefined'){
                    require_once $func;
                    if(is_callable(array('manual', 'execute'))) {
                        $func = array('manual', 'execute');
                        $func();
                    }
                }
	        }
	    }
    }

    /**
     * Установить название api подключение
     * name - Имя
     * ver  - Версия
     * @return bool
     */
    public function setApi($name='default', $ver=1){
        if(self::isApi($name, $ver)){
            self::$data['name']=$name;
    	    self::$data['ver']=$ver;
            return true;
        }
        return false;
    }

    /**
     * Возвращаем подлинность api
     * name - Имя
     * ver  - Версия
     * @return bool
     */
    public function isApi($name, $ver){
        return '.' . x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $ver . DIRECTORY_SEPARATOR . 'manual.php');
    }

	/**
	 * Возвращаем тип выполнение
	 * ---------------
	 * @system - Высокие привилегий
	 */
	protected function getType() {
    	switch(x::geturi(2)){
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
    	self::$data['status'] .= $code;
    }

	/**
	 * Указывает response отправки
	 * ---------------------------
	 */
	public function setResponse($res) {
    	if(is_array($res)){
    		self::$data['response'] = $res;
        }else{
        	self::$data['response'] .= $res;
        }
    }

	/**
	 * Возвращаем response
	 * --------------------
	 */
	public function getResponse(){
    	header('Content-Type: application/json');
    	//code...
    	http_response_code(200);
    	return json_encode(self::$data);
    }

	/**
	 * Возвращаем выполняемую функцию
	 * @return string
	 */
	protected function getFunc(){
	    $module=x::geturi(3);
        if(!self::isModule($module)){
            return '.' . x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . self::$data['name'] . DIRECTORY_SEPARATOR . self::$data['ver'] . DIRECTORY_SEPARATOR . 'manual.php');
        }else{
            $method=x::geturi(4);
            if(self::isMethod($module, $method)){
                return '.' . x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . self::$data['name'] . DIRECTORY_SEPARATOR . self::$data['ver'] . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "$method.php");
            }
        }
        return 'undefined';
    }

    /**
     * Возвращаем есть ли модуль в api
     * @return bool
     */
    protected function isModule($module){
        return is_dir('.' . x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . self::$data['name'] . DIRECTORY_SEPARATOR . self::$data['ver']));
    }

    /**
     * Возвращаем есть ли метод в модуле
     * @return bool
     */
    protected function isMethod($module, $method){
        return is_file('.' . x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . self::$data['name'] . DIRECTORY_SEPARATOR . self::$data['ver'] . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $method));
    }
}
$api = new api();
$api->connect();
