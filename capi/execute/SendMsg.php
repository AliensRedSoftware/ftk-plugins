<?php

/**
 * Отправка сообщение в нить
 * -------------------------
 */
class SendMsg {

	/**
	 * Выполнение
	 * ----------
	 * 0 	-	Ошибка ида или что-то не так
	 * 1	-	Текст пустой или превысил значение 8096
	 * 2	-	Ошибка в имени
	 * 3	-	Ошибка в символов
	 * 200	-	ОК
	 */
	function execute () {
    	$capi	=	new capi();
    	$xlib	=	new xlib();
    	$txt	=	iconv(mb_detect_encoding($_REQUEST['txt']), 'utf-8', $_REQUEST['txt']);
    	$threads=	$_REQUEST['threads'];
    	$name	=	$_REQUEST['name'];
    	$postName = $xlib->isCharArray(['>', '<', '"', "\\"], $name);
    	$postDesc = $xlib->isCharArray(['>', '<', '"', "\\"], $txt);
    	if (empty(trim($name))) {
        	$name = "Нейзвестный";
        } elseif(mb_strlen($name) < 2) {
        	$capi->setStatus(2);
        	die($capi->getResponse());
        } elseif (mb_strlen($name) > 32) {
        	$capi->setStatus(2);
        	die($capi->getResponse());
        } elseif ($postName) {
        	$capi->setStatus(3);
        	die($capi->getResponse());
        }
        if (empty(trim($txt))) {
        	$capi->setStatus(1);
        	die($capi->getResponse());
        } elseif(mb_strlen($txt) > 8096) {
        	$capi->setStatus(1);
        	die($capi->getResponse());
        } elseif ($postDesc) {
       		$capi->setStatus(3);
        	die($capi->getResponse());
        }
		require_once '.' . $xlib->getPathModules('xmessage/execute/syntax.php');
		$syn			=	new syntax();
		$txt			=	$syn->getText($txt);
		require_once	    $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . 'mysql.php';
		$mysql		    =	new mysql();
    	$sql		    =	mysqli_connect($mysql->ip, $mysql->user, $mysql->password, $mysql->database);
    	$result		    =	mysqli_query($sql, "SELECT * FROM `$threads` ORDER BY `id` DESC");
    	if ($result) {
        	$milliseconds = round(microtime(true) * 1000);
			$time = date('Y-m-d') . '=>' . date('H:i:s', time() - date('Z')) . "($milliseconds)";
        	mysqli_query($sql , "INSERT INTO `$threads` (`id` , `text` , `name` , `vidos` , `img` , `time`) VALUES (NULL , '$txt' , '$name' , '$vidos' , '$img' , '$time');");
			mysqli_close($sql);
        	$capi->setStatus(200);
        } else {
        	$capi->setStatus(0);
        }
    	die($capi->getResponse());
    }
}