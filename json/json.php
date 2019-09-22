<?php

class json {

	protected $file;

    function __construct($file) {
        $this->file = "$file.json";
    }

    function __toString() {
        return $this->file;
    }

	/**
	 * Записать
	 */
	public function put ($array = ['Name' => 'admin']) {
		$file = file_get_contents($this->file); // Открыть файл
		$taskList = json_decode($file,TRUE);    // Декодировать в массив           
		unset($file);                           // Очистить переменную $file
		$taskList[] = $array;        			// Представить новую переменную как элемент массива, в формате 'ключ'=>'имя переменной' 
		file_put_contents($this->file,json_encode($taskList));  // Перекодировать в формат и записать в файл.
	}

	/**
	 * Установить
	 */
	public function set ($array = ['Name' => 'admin']) {
		$newjson = json_encode($array);
		$file = file_get_contents($this->file);
		$taskList = json_decode($file, TRUE);	// Декодировать в массив 
    	foreach ($taskList as $key => $value) {    // Найти в массиве  
     
          
      		
   		}
   	
   		file_put_contents($this->file,json_encode($taskList));  // Перекодировать в формат и записать в файл.
	}
}