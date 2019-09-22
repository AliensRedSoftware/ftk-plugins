<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
error_reporting(0);
/**
 * Работа с пост
 * --------------
 */
class post extends xlib {

	/**
	 * Добавить новый пост
	 * --------------------
	 */
	function add ($name, $text, $vidos, $img, $head, $body) {
		$redirect	=	$_POST['redirect'];
		$_GET['redirectSkin'] = $redirect;
		$id			=	$_POST['id'];
		$skinmanager=	new skinmanager();
		$xmessage	=	new	xmessage();
		$sql		=	$this->getmysql();
		//ДОП+++++++++
		$result = mysqli_query($sql , "SELECT * FROM `view` ORDER BY `selected` DESC");
		while ($row = mysqli_fetch_array($result)) {
			if ($row['uuid'] == $id) {
				$space = $row['selected'];
			}
		}
		$milliseconds = round(microtime(true) * 1000);
		$time = date('Y-m-d') . '=>' . date('H:i:s', time() - date('Z')) . "($milliseconds)";
		mysqli_query($sql , "INSERT INTO `$id` (`id` , `text` , `name` , `vidos` , `img` , `time`) VALUES (NULL , '$text' , '$name' , '$vidos' , '$img' , '$time');");
		mysqli_close($sql);
		echo $head->execute('[Создание поста] -> YES!!!');
		echo $body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id));
		echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">";
	}

    /**
     * Выполнить
     * ----------
     */
    function execute () {
		$name		=	$_POST['name'];
	    $id			=	$_POST['id'];
	    $text		=	$_POST['text'];
		$skinmanager=	new	skinmanager();
		$youtube	=	new	youtube();
		$xmessage	=	new	xmessage();
		require_once	'../../../page/head.php';
		$head 		=	new head();
		require_once	'../../../page/body.php';
		$body		=	new body();
		require_once	'syntax.php';
		$syntax 	= new syntax();
		if($name == null){$name = 'Неизвестный';}
		if (mb_strlen($id) != 13) {
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> 'id должен содержать 13 символов ти шото задумал ?)'
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			echo $head->execute('[Создание поста] -> ошибка!');
			echo $body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id));
			die();
		}
		if(mb_strlen($__text) >= 8096) {
			echo $head->execute('[Создание поста] -> ошибка!');
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> 'Нужно ввести описание не более 8096 символов. :('
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			die($body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id)));
		}
		if(mb_strlen($name) < 2) { //Проверка на кол-во имя
			echo $head->execute('[Создание поста] -> ошибка!');
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> 'Нужно ввести имя более 2 символов. :('
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			die($body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id)));
		}
		if(mb_strlen($name) > 32) { //Проверка на кол-во имя
			echo $head->execute('[Создание поста] -> ошибка!');
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> 'Нужно ввести имя не более 32 символов. :('
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			die($body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id)));
		}
		$postname = $this->isCharArray(['>', '<', '"'], $name);//Имя
	    if ($postname) {
			echo $head->execute('[Создание поста] -> ошибка!');
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> "такой символ <b>$postname</b> нельзя использовать в имени!"
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			die($body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id)));
	    }
	    $postdescription = $this->isCharArray(['>', '<', '"', "\\"], $text);//Описание
	    if ($postdescription) {
			echo $head->execute('[Создание поста] -> ошибка!');
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> "такой символ <b>$postdescription</b> нельзя использовать в описание!"
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			die($body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id)));
	    }
    	$uri		=	$syntax->getUrl($text);
		$text		=	$syntax->getText($text);
    	$src_video	=	serialize($youtube->getVideoArray($uri['youtube']));
		$src_img	=	serialize($this->getCheckMd5Array($uri['other']));
		if (empty(trim($text)) && empty(unserialize($src_video)) && empty(unserialize($src_img))) {
			echo $head->execute('[Создание поста] -> ошибка!');
			$form = $skinmanager->modal([
						'title' 	=> '[Создание поста] -> ошибка!',
						'content'	=> 'Пустой пост не отправить :)'
					]);
			if ($skinmanager->getSkin() == 'bootstrap337') {
				$this->js("$('#$form').modal('toggle')");
			} elseif ($skinmanager->getSkin() == 'uikit') {
				$this->js("UIkit.modal(\"#$form\").show();");
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
			}
			die($body->layout_multiForm($id));
		}
	    $this->add($name, $text, $src_video, $src_img, $head, $body);//Отправка поста
	}
}
$xlib	=	new xlib();
if($_SERVER["REQUEST_METHOD"] == 'POST' && constant() == NULL) {
	$event = new post();
	$event->execute();
} else {
	echo $xlib->alert();
}

/*
require_once '../../xlib/xlib.php';
$xlib = new xlib();
$execute = $_POST['execute'] . '4';
$token = $_POST['token'];
if ($xlib->is_uuidv4($_POST['execute']) == false) {
	echo $xlib->alert();
	die();
}
session_start();
if ($_SESSION[$execute] == $token && isset($_SESSION[$execute]) == true && isset($token) == true) {
	if($_SERVER["REQUEST_METHOD"] == 'POST') {
		$event = new post();
		$event->execute();
		session_regenerate_id(true);
		//session_reset();
	} else {
		echo $xlib->alert();
		die();
	}
} else {
	echo $xlib->alert();
	die();
}
*/
