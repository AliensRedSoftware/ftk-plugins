<?php
session_start();
$key=$_SESSION[$_POST['session_key']];
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
xlib::LoadWebUrl();//Загрузка локальной страницы прошлой
use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use perms as pm;
class newDot{
	/**
	 * Создать новой точки
	 * -------------------
	 * dot	-	Название точки
	 * head	-	Загаловок страницы
	 * body	-	Тело страницы
	 */
	public function create($dot){
		$path=str_replace('?'.x::getData(),NULL,$_POST['path']);
		require_once "../../../../../options.php";
		$options=new options();
        foreach(scandir("../../../../uri".$path) as $val){
            if ($dot==$val){
				sm::modal(['open'=>true,'title'=>'[Создание точки] -> ошибка!','content'=>'[Создание точки] -> ошибка!', "Не удается создать [<b>$dot</b>] потому что такая уже есть :("]);
				die();
            }
        }
		mkdir("../../../../uri".$path.'/'.$dot,0777,true);
		$redirect="$path$dot";
		foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator("../../../../uri".xm::getROOT(), RecursiveDirectoryIterator::SKIP_DOTS)) as $path=>$key){
			if(!pathinfo($path,PATHINFO_EXTENSION)){
				file_put_contents("$path.php", '<?php
class ftk extends xlib{
	function __construct(){
		$this->req(["head","body","footer"]);
		$H = new head();
		$B = new body();
		$F = new footer();
		$this->execute($H,$B,$F);
	}
	function execute($H,$B,$F){
		$H->execute($this->geturi());
		$B->layout_Dot();
		$F->execute();
	}
}
');
			}
		}
		echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">";
	}

	/**
	 * Выполнить
	 * ----------
	 */
    public function execute($key){
		//Переменные
		$dot=$_POST['dot'];
		//key
		$session_key=$_POST['session_key'];
		if($key!=$session_key){
			sm::modal(['open'=>true,'title'=>'[Создание поста] -> ошибка!','content'=>"Ключ неверный $session_key :("]);
			die();
		}
		//-------------------------
		if(empty($dot)){
			sm::modal(['open'=>true,'title'=>'[Создание точки] -> ошибка!','content'=>'Название точки не должно быть пустое :(']);
			die();
		}
		if(mb_strlen($dot)>32){
			sm::modal(['open'=>true,'title'=>'[Создание точки] -> ошибка!','content'=>'Символов в название не более чем 32 :(']);
			die();
		}
		//Проверка на бажность символьность
		$arr=x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['@','$','_','-','='])));
        if(!x::isCharArray($arr,$dot)){
			sm::modal(['open'=>true,'title'=>'[Создание поста] -> ошибка!','content'=>'Некоторые символы нельзя использовать в название! :(']);
			die();
        }
		//PERMS
	    $isDot=xm::isDot(); //isDot
		if(!pm::isAccess()){
			sm::modal(['open'=>true,'title'=>'[Создание точки] -> ошибка!','content'=>'В доступе отказано']);
			die();
		}
		if(x::is_uuidv4($dot)){
		    sm::modal(['open'=>true,'title'=>'[Создание точки] -> ошибка!','content'=>"Содержит uuidv4 <b>[$dot]</b> :("]);
			die();
		}
		$this->create($dot);
	}
}
if($_SERVER["REQUEST_METHOD"]=='POST') {
	$e = new newDot();
	$e->execute($key);
}else{
	echo x::alert();
}
