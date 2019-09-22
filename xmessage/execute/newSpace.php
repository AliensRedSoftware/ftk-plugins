<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
error_reporting(0);
class newSpace extends xlib {

	/**
	 * Создать новое пространство
	 * ---------------------------
	 */
	function create ($space, $desc, $dot) {
		require_once	'../../../../../options.php';
		$options	=	new options();
		$ini		=	new ini('options');
		$list		=	$ini->getKeys($type);
		if ($ini->is_key($dot, $space)) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', 'Не удается создать пространство потому что такая уже есть! :(');
		}
		foreach ($list as $key) {
			if ($ini->get($dot, $key) == $desc) {
				echo $head->execute('[Создание пространство] -> ошибка!');
				$body->layout_post('[Создание пространство] -> ошибка!', 'Не удается создать пространство потому что пространство с таким описанием уже есть! :(');
			}
		}
        $ini->set($dot, $space, $desc);
		file_put_contents("../../../../uri/о/$dot/$space.php" , '<?php
class ftk extends xlib {
    function __construct() {
        $this->req(["head", "body", "footer"]);
        $head = new head();
        $body = new body();
        $footer = new footer();
        $this->execute($head, $body, $footer);
    }
    function execute ($head, $body, $footer) {
        $selected = urldecode($this->geturi(2));
        $head->execute("/о/" . $selected);
        $body->layout_Thread();
        $footer->execute();
    }
}
');
		$this->isDir("../../../../uri/о/$dot/$space");
		echo "<meta http-equiv=\"refresh\" content=\"0;url=/о/$dot/$space\">";
	}

	/**
	 * Выполнить
	 * ----------
	 */
    function execute () {
		$dot			=	$_POST['dot'];
        $space			=	$_POST['space'];
        $description	=	$_POST['description'];
		require_once	"../../../page/head.php";
		$head			=	new head();
		require_once	"../../../page/body.php";
		$body			=	new body();
		if(!$space) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', 'Название пространство не должно быть пустое! :(');
		}
		if(!$description) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', 'Описание не должно быть пустое! :(');
		}
		if(strlen($space) >= 16) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', 'Символов в название не более чем 15 :(');
		}
		if(strlen($description) >= 30) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', 'Символов в описание краткое не более чем 30 :(');
		}
		$char		=	$this->getCharToArray();
		$number		=	$this->getNumberToArray();
		$badName	=	$this->isCharArray($char, $space);
		if ($badName) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', "такой символ <b>[$badName]</b> нельзя использовать");
		}
		$badName = $this->isCharArray($number, $space);
		if ($badName) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', "такую цифру <b>[$badName]</b> нельзя использовать");
		}
		$badDescription = $this->isCharArray($char, $description);
		if ($badDescription) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', "такой символ <b>[$badDescription]</b> нельзя использовать");
		}
		$badDescription = $this->isCharArray($number, $description);
		if ($badDescription) {
			echo $head->execute('[Создание пространство] -> ошибка!');
			$body->layout_post('[Создание пространство] -> ошибка!', "такую цифру <b>[$badDescription]</b> нельзя использовать");
		}
		$this->create($space, $description, $dot);//Создать новое пространство
	}
}
$xlib		=	new xlib();
if($_SERVER["REQUEST_METHOD"] == 'POST' && constant() == NULL) {
	$event	=	new newSpace();
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
		$event = new doska();
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
