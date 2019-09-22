<?php
class hpost {
	
	public $var;

	/**
	 * Устанавливает глобальную переменную для post
	 */
	public function setVar ($a) {
		$this->var = $a;
	}

	/**
	 * Возвращает глобальную переменную
	 */
	public function getVar () {
		return $this->var;
	}
}