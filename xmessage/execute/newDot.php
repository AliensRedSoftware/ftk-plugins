<?php
session_start();//start session
$key=$_SESSION[$_POST['session_key']];//get key
session_destroy();//session_destroy
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use xprivate as xp;
class newDot{
	/**
	 * Создать новой точки
	 * -------------------
	 * dot	-	Название точки
	 */
	public function create($dot){
		$path=str_replace('?'.x::getData(),NULL,$_POST['path']);
        foreach(x::scandir('../../../../uri'.$path) as $val){
            if($dot==$val){
            	x::LoadWebUrl();
            	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>true,'title'=>'Создание точки','content'=>$logo."Не удается создать '<b>$dot</b>' потому что такая уже есть :("]);
				die();
            }
        }
		mkdir('../../../../uri'.$path.DIRECTORY_SEPARATOR.$dot,0777,true);
		$_POST['redirect']=$path.$dot;
		foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator('../../../../uri'.xm::getROOT(), RecursiveDirectoryIterator::SKIP_DOTS)) as $path=>$key){
			if(!pathinfo($path,PATHINFO_EXTENSION)){
				file_put_contents("$path.php", '<?php
class ftk{
	function __construct(){
		xlib::req([\'head\',\'body\',\'footer\']);
		$H=new head();
		$B=new body();
		$F=new footer();
		$H->execute(xlib::geturi());
		$B->execute();
		$F->execute();
	}
}
');
			}
		}
		//Выдача прав всех рут
		if(x::isModule('xprivate',false)){
			xp::setData(['xmessage'=>['dots'=>[$_POST['redirect']=>['root'=>true]]]]);
		}
		x::LoadWebUrl();
		$logo=sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'thread.webp'),'css'=>['width'=>'240px','pointer-events'=>'none']]);
		sm::modal(['open'=>true,'title'=>'Успешно создание точки :)','content'=>$logo.'</br>Пожалуйста вы можете полностью '.sm::a(['href'=>$_POST['redirect'],'title'=>'Залитеть в точку']).'</br></br>Удачи в использование своей точки ;)']);
	}

	/**
	 * Выполнить
	 * ----------
	 */
    public function execute($key){
		//Переменные
		$dot=$_POST['dot'];
		//xprivate модуль
		if(x::isModule('xprivate')){
    		$data=xp::$data;
		}
		//key
		$session_key=$_POST['session_key'];
		if($key!=$session_key){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание точки','content'=>"$logoКлюч неверный '<b>$session_key</b>' :("]);
			die();
		}
		//-------------------------
		if(empty($dot)){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание точки','content'=>$logo.'Название точки не должно быть пустое :(']);
			die();
		}
		//Ммаксимум символов
		$max=__XMESSAGE_DOT_TITLE_MAX;
		if(mb_strlen($dot)>$max){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание точки','content'=>$logo."Символов в название не более чем $max :("]);
			die();
		}
		//Проверка на бажность символьность
		$arr=x::isCharArray(x::getENGLongToArray(x::getRUSLongToArray(x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['@','$','_','-','=']))))),$dot,true);
		if(!is_null($arr)&&!$arr&&!is_numeric($arr)){
        	x::LoadWebUrl();
        	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание точки','content'=>$logo.'Некоторые символы нельзя использовать в название! :(']);
			die();
        }
        //uuidv4 check
		if(x::is_uuidv4($dot)){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
		    sm::modal(['open'=>true,'title'=>'Создание точки','content'=>$logo."Содержит uuidv4 '<b>$dot</b>' :("]);
			die();
		}
        //permission
        $path=xm::getPathSelected();
		if(!$data['xmessage']['dots'][$path]['new']&&!$data['xmessage']['dots'][$path]['root']&&!$data['root']){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание точки','content'=>$logo.'В доступе отказано :(']);
			die();
		}
		//processing create dot
		$this->create($dot);
	}
}
if($_SERVER['REQUEST_METHOD']=='POST'){
	//request
	$e = new newDot();
	$e->execute($key);
}
