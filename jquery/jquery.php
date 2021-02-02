<?php
use xlib as x;
class jquery{
	/**
	 * Добавляет скрипт в автозагрузку
	 */
	public function addLoad($script){
		$new=self::getCountRun()+1;
		define("JQUERY_LOAD_$new",$script);
	}
	/**
	 * Добавляет скрипт в автозагрузку
	 * script-скрипт
	 * folder-Папка выполнение
	 */
	public function addLoadJs($script,$js){
		$new=self::getCountRunJs()+1;
		define("JQUERY_LOAD_SCRIPT_$new",$script);
		define("JQUERY_LOAD_SCRIPT_PATH_$new",$js);
	}
	/**
	 * Возвращает загрузку в виде массива
	 */
	public function getLoadArray(){
		$arr=[];
		for($i=1;$i<=self::getCountRun();$i++){
			if(defined("JQUERY_LOAD_$i")==false){
				return$arr;
			}else{
				array_push($arr,constant("JQUERY_LOAD_$i"));
			}
		}
	}
	function footerExecute(){
		$countLoadScript=self::getCountRunJs();
		if($countLoadScript!=0){
			for($i=1;$i<=$countLoadScript;$i++){
				$script.=constant("JQUERY_LOAD_SCRIPT_$i")."\n";
				$folder=constant("JQUERY_LOAD_SCRIPT_PATH_$i");
				x::add_js([$script],$folder);
			}
		}
		$countLoad=self::getCountRun();
		if($countLoad!=0){
			for($i=1;$i<=$countLoad;$i++){
				$execute.=constant("JQUERY_LOAD_$i")."\n";
			}
			x::js('$(document).ready(function(){ ' . $execute . ' });');
		}

	}
	/**
	 * Возвращает колво в jquery run переменных
	 */ 
	function getCountRun(){
		$i=0;
		while(1){
			$i++;
			if(defined("JQUERY_LOAD_$i")==false){
				return$i-1;
			}
		}
	}
	/**
	 * Возвращает колво в jquery run script переменных
	 */ 
	function getCountRunJs(){
		$i=0;
		while(1){
			$i++;
			if(defined("JQUERY_LOAD_SCRIPT_$i")==false){
				return$i-1;
			}
		}
	}
}
