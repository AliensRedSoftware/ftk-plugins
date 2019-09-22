<?php
error_reporting(0);
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
class newDot extends xlib {

	/**
	 * Создать новой точки
	 * -------------------
	 * dot	-	Название точки
	 * head	-	Загаловок страницы
	 * body	-	Тело страницы
	 */
	public function create ($dot, $head, $body) {
		require_once "../../../../../options.php";
		$options	=	new options();
		$ini		=	new ini("options");
        $list		=	$ini->getSections();
        foreach ($list as $val) {
            if ($dot == $val) {
				echo $head->execute('[Создание точки] -> ошибка!');
				$body->layout_post('[Создание точки] -> ошибка!', "Не удается создать [<b>$dot</b>] потому что такая уже есть :(");
            }
        }
        if (count($ini->getKeys($list[count($list) - 1])) < 1 && count($list) != 0) {
			$get = $list[count($list) - 1];
			echo $head->execute('[Создание точки] -> ошибка!');
			$body->layout_post('[Создание точки] -> ошибка!', "Не удается создать точку <b>[$dot]</b> потому что в последней точки <b>[$get]</b> ничего нету зачем создавать еще пустую точку ?)!");
        } else {
			$ini->addSection($dot);
			$this->isDir("../../../../uri/о/$dot");
file_put_contents("../../../../uri/о/$dot.php" , '<?php
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
        $body->layout_Dot();
        $footer->execute();
    }
}
');
			echo $head->execute('[Создание точки] -> Успешно :)');
			$body->layout_post('[Создание точки] -> Успешно :)', "Точка успешно создалась <b>[$dot]</b> :)");
		}
	}

	/**
	 * Выполнить
	 * ----------
	 */
    public function execute () {
		//Переменные
			$dot	=	$_POST["dot"];
			require_once "../../../page/head.php";
			$head	=	new head();
			require_once "../../../page/body.php";
			$body	=	new body();
		//
        $charlower	=	$this->islowupper($dot);
        if ($charlower) {
			echo $head->execute('[Создание точки] -> ошибка!');
			$body->layout_post('[Создание точки] -> ошибка!', 'Большие буквы нельзя использовать :(');
        }
		if(!$dot) {
			echo $head->execute('[Создание точки] -> ошибка!');
			$body->layout_post('[Создание точки] -> ошибка!', 'Название точки не должно быть пустое :(');
		}
		if(strlen($name) >= 16) {
			echo $head->execute('[Создание точки] -> ошибка!');
			$body->layout_post('[Создание точки] -> ошибка!', 'Символов в название не более чем 15 :(');
		}
		$char		=	$this->getCharToArray();
		$number		=	$this->getNumberToArray();
		$badName	=	$this->isCharArray($char, $dot);
		if ($badName) {
			echo $head->execute('[Создание точки] -> ошибка!');
			$body->layout_post('[Создание точки] -> ошибка!', "такой символ <b>[$badName]</b> нельзя использовать :(");
		}
		$badName	=	$this->isCharArray($number, $dot);
		if ($badName) {
			echo $head->execute('[Создание точки] -> ошибка!');
			$body->layout_post('[Создание точки] -> ошибка!', "такую цифру <b>[$badName]</b> нельзя использовать :(");
		}
		$this->create($dot, $head, $body);
	}
}
$xlib = new xlib();
if($_SERVER["REQUEST_METHOD"] == 'POST' && constant() == NULL) {
	$event = new newDot();
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
ini_set("session.save_path", "/");
session_start();
if (constant() == NULL) {
	echo 'test';
}
if ($_SESSION[$execute] == $token && isset($_SESSION[$execute]) == true && isset($token) == true) {
	if($_SERVER["REQUEST_METHOD"] == 'POST') {
		$event = new newDot();
		$event->newDot();
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
