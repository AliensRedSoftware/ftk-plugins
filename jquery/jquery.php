<?php
class jquery {
	
	/**
	 * Добавляет скрипт в автозагрузку
	 */
	public function addLoad ($script) {
		$count = $this->getCountRun();	
		$new = $count + 1;
		define("JQUERY_LOAD_$new", $script);
	}
	
	/**
	 * Добавляет скрипт в автозагрузку
	 */
	public function addLoadJs ($script) {
		$count = $this->getCountRunJs();	
		$new = $count + 1;
		define("JQUERY_LOAD_SCRIPT_$new", $script);
	}
	
	function footerExecute () {
		$jquery = new jquery();
		$xlib = new xlib();
		$countLoad = $jquery->getCountRun();
		if ($countLoad != 0) {
			for ($i = 1; $i <= $countLoad; $i++) {
				$execute .= constant("JQUERY_LOAD_$i");
			}
			$xlib->js('$(document).ready(function(){ ' . $execute . ' });');
		}
		$countLoadScript = $jquery->getCountRunJs();
		if ($countLoadScript != 0) {
			for ($i = 1; $i <= $countLoadScript; $i++) {
				$script .= constant("JQUERY_LOAD_SCRIPT_$i");
				$xlib->add_js([$script], null);
			}
	
		}
		
	}
	
	/**
	 * Возвращает колво в jquery run переменных
	 */ 
	function getCountRun () {
		$count = 0;
		for ($i = 1; $i <= 100; $i++) {
			if (defined("JQUERY_LOAD_$i") == false) {
				return $count;
			} else {
				$count++;
			}
		}
	}
	
	/**
	 * Возвращает колво в jquery run script переменных
	 */ 
	function getCountRunJs () {
		$count = 0;
		for ($i = 1; $i <= 100; $i++) {
			if (defined("JQUERY_LOAD_SCRIPT_$i") == false) {
				return $count;
			} else {
				$count++;
			}
		}
	}
}
