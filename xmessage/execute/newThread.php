<?php
error_reporting(0);
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
class newThread extends xlib {

	/**
	 * Создание нити
	 * --------------
	 */
	function create ($title, $name, $text, $space, $head, $body) {
		$dot		=	$_REQUEST['dot'];
		require_once '../../../../../mysql.php';
		$mysql			=	new mysql();
		require_once	'syntax.php';
		$syntax			=	new syntax();
		$sql			=	$this->getmysql();
		$youtube		=	new youtube();
		$uri			=	$syntax->getUrl($text);
		$text			=	$syntax->getText($text);
		$id				=	uniqid(); //Сгенерировать уникальный ID
		//ДОП+++++++++
		$milliseconds	=	round(microtime(true) * 1000);
		$time 			=	date('Y-m-d') . '=>' . date('H:i:s', time() - date('Z')) . "($milliseconds)";
		//ДОП+++++++++
		//Картинки
		$src_video		=	serialize($youtube->getVideoArray($uri['youtube']));
		$src_img		=	serialize($this->getCheckMd5Array($uri['other']));
		mysqli_multi_query($sql,
"CREATE TABLE `$mysql->database` . `$id` (`id` INT NOT NULL AUTO_INCREMENT , `text` TEXT NOT NULL , `name` VARCHAR(32) NOT NULL , `time` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`) , `vidos` TEXT NOT NULL , `img` TEXT NOT NULL) ENGINE = MyISAM CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;" .
"INSERT INTO `$id` (`id` , `text` , `name` , `vidos` , `img` , `time`) VALUES (NULL , '$text', '$name' , '$src_video' , '$src_img', '$time');" . 
"INSERT INTO `view` (`id`, `name`, `description`, `title`, `selected`, `uuid`) VALUES (NULL, '$name', '$text', '$title' , '$space' , '$id');"
);
		$this->isDir("../../../../uri/о/$dot/$space");
		file_put_contents("../../../../uri/о/$dot/$space/$id.php" , '<?php
class ftk extends xlib {
    function __construct() {
        $this->req(["head", "body", "footer"]);
        $head = new head();
        $body = new body();
        $footer = new footer();
        $this->execute($head, $body, $footer);
    }

    function execute($head, $body, $footer) {
        $head->execute(' . "'$title'" . ');
        $body->layout_multiForm();
        $footer->execute();
    }
}');
		chmod("../../../../uri/о/$dot/$space/$id.php", 0777);
		mysqli_close($sql);
		echo "<meta http-equiv=\"refresh\" content=\"0;url=/о/$dot/$space/$id\">";
	}

	/**
	 * Пред просмотра треда
	 */
	function previwgen ($title, $name, $desc, $selected, $src_video, $theme) {
        require_once '../../bootstrap/bootstrap.php';
		$bootstrap = new bootstrap();
        require_once '../../xlib/xlib.php';
		$xlib = new xlib();
		require_once '../../lightbox/lightbox.php';
		$lightbox = new lightbox();
		require_once '../../youtube/youtube.php';
		$youtube = new youtube();
		$idChat = round(microtime(true) * 1000);
		$time = date('Y-m-d') . '=>' . date('H:i:s', time() - date('Z'));
		require_once 'syntax.php';
		$syntax = new syntax();
		$text = $syntax->getText($desc);
		$src_img = $syntax->getUrl($desc);
		//Картинки
		if($src_img != false) {
			$imghref = explode(" " , $src_img);
			unset($src_img);
			foreach ($imghref as $value_img) {
				$img = $lightbox->img($value_img, '100%');
				$src_img .= $xlib->padding([
					'all' => 5,
					'content' => "<div class='panel panel-$theme' style='margin-bottom:0px;'><div class='panel-body' align='center' style='padding:5px;'>$img</div></div>"
				]);
			}
		}
		if ($src_video != false) {
			$vidoshref = $youtube->getCheckMd5Array(explode(' ' , $src_video));
			unset($src_video);
			foreach ($vidoshref as $value_vidos) {
				$src_video .= $xlib->padding([
					'all' => 5,
					'content' => $youtube->video($value_vidos, 420, 240)
				]);
			}
		}
		echo $syntax->getForm(0, $time, $idChat, $name, $text, $src_video, $src_img, $theme);
	}

	/**
	 * Выполнить
	 * ----------
	 */
    function execute () {
		//-->Переменные $_POST
			$title	= 	$_POST[	'title'	];
			$name	= 	$_POST[	'name'	];
			$space	= 	$_POST[	'space'	];
			$text	=	$_POST[	'text'	];
        //--
		require_once "../../../page/head.php";
		$head = new head();
		require_once "../../../page/body.php";
		$body = new body();
		if($name == null){$name = 'Неизвестный';} //Проверка на имя
		if(mb_strlen($text) >= 8096) { //Проверка на кол-во описание
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', 'Нужно ввести описание не более 8096 символов. :(');
		}
		if(mb_strlen($text) == 0) { //Проверка на кол-во описание
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', 'Нужно ввести описание более 0 символов. :(');
		}
		if(mb_strlen($name) < 2) { //Проверка на кол-во имя
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', 'Нужно ввести имя более 2 символов. :(');
		}
		if(mb_strlen($name) >= 32) { //Проверка на кол-во имя
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', 'Нужно ввести имя не более 32 символов. :(');
		}
		if(mb_strlen($title) <= 6) { //Проверка на кол-во название
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', 'Нужно ввести название более 6 символов. :(');
		}
		//Проверка на бажность символьность
		$posttitle = $this->isCharArray(['>', '<', '"'], $title);
        if ($posttitle) {
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', "Такой символ <b>$posttitle</b> нельзя использовать в название! :(");
        }
        $postname = $this->isCharArray(['>', '<', '"'], $name);
        if ($postname) {
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', "Такой символ <b>$postname</b> нельзя использовать в имени! :(");
        }
        $postdescription = $this->isCharArray(['>', '<', '"', "\\"], $text);
        if ($postdescription) {
			echo $head->execute('[Создание нити] -> ошибка!');
			$body->layout_post('[Создание нити] -> ошибка!', "Такой символ <b>$postdescription</b> нельзя использовать в описание! :(");
        }
        $this->create($title, $name, $text, $space, $head, $body);
    }
}
$xlib   =	new xlib();
if($_SERVER["REQUEST_METHOD"] == 'POST' && constant() == NULL) {
	$event  =   new newThread();
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
		$event = new tred();
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
}*/
