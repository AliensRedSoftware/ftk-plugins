<?php

/**
 * @name Работа с каталогом
 * @version 1.0
 */
use xlib as x;
use skinmanager as sm;
class xcatalog {

	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max-Максимальное число элементов на 1 странице
	 * indent-Отступы (5)
	 * data-Массив в виде данных
	 */
	public function getPagination(array $opt){
    	$max=$opt['max'];
    	$indent=$opt['indent'];
    	$data=$opt['data'];
    	$page=x::getDataToArray()['page'];
    	$pagination=sm::pagination(['max'=>$max,'indent'=>$indent,'data'=>$data])['pagination'];
    	if(!$page){
        	return sm::pagination(['max'=>$max,'data'=>$data])[0].$pagination;
        }elseif($page>=0&&$page<count(array_keys(sm::pagination(['max'=>$max,'data'=>$data])))-1&&$page!='-0'){
        	return sm::pagination(['max'=>$max,'data'=>$data])[$page].$pagination;
        }else{
        	$page=0;
        	return sm::pagination(['max'=>$max,'data'=>$data])[$page].$pagination;
        }
    }
}
