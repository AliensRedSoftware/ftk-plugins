<?php
class xcatalog extends xlib {

	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице
	 * indent	-	Отступы (5)
	 * content	-	Массив с содержимом
	 */
	public function getPagination(array $opt) {
    	$skinmanager	=	new skinmanager();
    	$max			=	$opt['max'];
    	$indent			=	$opt['indent'];
    	$content		=	$opt['content'];
    	$page			=	$_REQUEST['page'];
    	$pagination 	=	$skinmanager->pagination(['max' => $max,'indent' => $indent, 'content' => $content])['pagination'];
    	if ($pagination) {
        	$pagination = "</br>" . $pagination;
        }
    	if (!$page) {
        	return $skinmanager->pagination(['max' => $max,'content' => $content])[0] . $pagination;
        } elseif($page >= 0 && $page < count(array_keys($skinmanager->pagination(['max' => $max,'content' => $content]))) - 1 && $page != '-0') {
        	return $skinmanager->pagination(['max' => $max, 'content' => $content])[$page] . $pagination;
        } else {
        	$page = 0;
        	return $skinmanager->pagination(['max' => $max,'content' => $content])[$page] . $pagination;
        }
    }
}