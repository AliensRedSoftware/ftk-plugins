<?php
session_start();//start session
$key=$_SESSION[$_POST['session_key']];//get key
session_destroy();//session_destroy
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';//Load Module

use xlib as x;
use youtube as yt;
use skinmanager as sm;
use xmessage as xm;
use xprivate as xp;
class newThread{

	/**
	 * Создание нити
	 * title	-	Загаловок
	 * name		-	Имя пользователя (undefined)
	 * txt		-	Текста
	 * dot		-	Название точки
	 * img		-	Картинки
	 * youtube	-	ютуб видео
	 * id		-	Нить
	 * data		-	данные пользователя
	 * --------------
	 */
	function create($title,$name,$txt,$dot,$img,$youtube,$id,$data){
		require_once'syntax.php';
		$syntax=new syntax();
		$sql=xm::getmysql();
		//ДОП+++++++++
		$milliseconds=round(microtime(true)*1000);
		$time=date('Y-m-d').'/'.date('H:i:s',time()-date('Z'))."($milliseconds)";
		//Создание инфы об нити
		//hThread
		file_put_contents("../../../../uri$dot$id.php",'<?php
class ftk{
    function __construct(){
        xlib::req([\'head\',\'body\',\'footer\']);
        $H=new head();
        $B=new body();
        $F=new footer();
        $H->execute('."'$title'".');
        $B->layout_multiForm();
        $F->execute();
    }
}');
        //HIDDEN
		if($_POST['hThread']){
			$key=md5_file("../../../../uri$dot$id.php");
			rename("../../../../uri$dot$id.php","../../../../uri$dot$key.php");
			$id=$key;
		}
		chmod("../../../../uri$dot$id.php",0777);
		if(!x::is_uuidv4($title)){
			mysqli_query($sql, "INSERT INTO `view` (`id`, `name`, `desc`, `title`, `uuid`) VALUES (NULL, '$name', '$txt', '$title', '$id');");
		}
		//Создание нити
		$__xprivate_auth=$data['id'];
		$type=$_POST['__XMESSAGE_FORMAT_THREAD'];
		mysqli_multi_query($sql,
"CREATE TABLE `$id` (`id` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`), `text` TEXT NOT NULL , `name` VARCHAR(32) NOT NULL , `time` VARCHAR(256) NOT NULL , `__xprivate_auth` VARCHAR(128) NOT NULL , `youtube` TEXT NOT NULL , `img` TEXT NOT NULL , `view` TEXT NOT NULL , `mojas` TEXT NOT NULL , `type` TEXT NOT NULL) ENGINE = MyISAM CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;".
"INSERT INTO `$id` (`id`, `text`, `name`, `__xprivate_auth`, `youtube`, `img`, `time`, `view`, `mojas`, `type`) VALUES (NULL, '$txt', '$name', '$__xprivate_auth', '$youtube', '$img', '$time', 0, '$mojas', '$type');");
		mysqli_close($sql);
		//BALANCE BONUS
		if(x::isModule('rialto',false)){
			$wallet=rialto::getWallets()['HTR'];
			if(!empty($wallet)){
				$HTR=$data['rialto']['HTR']['value'];
				$wallet=$wallet['step']+$wallet['step'];
				$HTR="$HTR+$wallet";
				xp::setData(['rialto'=>['HTR'=>['value'=>eval('return '.$HTR.';')]]]);
			}
		}
		//Выдача прав всех рут
		if(x::isModule('xprivate',false)){
			xp::setData(['xmessage'=>['threads'=>[$id=>['root'=>true]]]]);
			//effect
			require_once './changeThread.php';
			$E=new changeThread();
			$E->effect($id);
		}
		$_POST['redirect']="$dot$id";
		xm::$id=$id;
		//unset Active
		unset($_POST['text']);
		x::LoadWebUrl("$dot$id");//Загрузка локальной страницы прошлой
		$logo=sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'thread.webp'),'css'=>['width'=>'240px','pointer-events'=>'none']]);
		sm::modal(['open'=>true,'title'=>'Успешно создание нити :)','content'=>$logo.'</br>Ваш номер комнаты '.sm::badge(['txt'=>$id]).'</br>Пожалуйста вы можете полностью '.sm::a(['href'=>"$dot$id",'title'=>'Залитеть в комнату']).'</br></br>Удачи в использование своей комнаты ;)']);
	}
	/**
	 * Выполнить
	 * key-Секретный ключ
	 * ----------
	 */
    function execute($key){
    	if(x::isModule('xprivate',false)){
			$data=xp::$data;
			$name=$data['private']['name'];
		}
		if(!$name){
			$name='Неизвестный';
		}
		$title=$_POST['title'];
		$txt=$_POST['text'];
		$dot=$_POST['dot'];
		require_once'syntax.php';
		$syntax=new syntax();
		//key
		$session_key=$_POST['session_key'];
		if($key!=$session_key){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo."Секретный ключ неверный '<b>$session_key</b>' :("]);
			die();
		}
		//permission
		$path=substr(xm::getPathSelected(),0,-1);
		if(!$data['xmessage']['dots'][$path]['newThread']&&!$data['xmessage']['dots'][$path]['root']&&!$data['root']){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo.'В доступе отказано :(']);
			die();
		}
		//Тип
		switch($_POST['__XMESSAGE_FORMAT_THREAD']){
			case 'Студия':
				$_POST['__XMESSAGE_FORMAT_THREAD']='Студия';
			break;
			default:
				$_POST['__XMESSAGE_FORMAT_THREAD']='Сообщение';
			break;
		}
		//-------------------------
		$id=x::uuidv4(); //Сгенерировать уникальный ID
		//--->Отправка файлов (fileUpload)
		$uri=$syntax->getUrl($txt);
		require_once'fileUploads.php';
		$fu=new fileUploads();
		$fu::$newThread=true;
		$files=$fu->execute($id);
		//Проверка на бажность символьность
		$arr=x::getENGLongToArray(x::getRUSLongToArray(x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['@','$','_','-','=',' '])))));
		$T=x::isCharArray($arr,$title,true);
        if(!is_null($T)&&!$T){
        	x::LoadWebUrl();
        	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo.'Некоторые символы нельзя использовать в название! :(']);
			die();
        }
        //Название
        $maxTitle=constant('__XMESSAGE_THREAD_TITLE_MAX');
        $minTitle=constant('__XMESSAGE_THREAD_TITLE_MIN');
        if(!trim($title)){ //Проверка на кол-во название
			$title=$id;
		}elseif(mb_strlen($title)<$minTitle&&$minTitle!=0){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo."Нужно ввести название более '$minTitle' символов. :("]);
			die();
		}elseif(mb_strlen($title)>$maxTitle&&$maxTitle!=0){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo."Нужно ввести название менее '$maxTitle' символов. :("]);
			die();
		}
		//Описание
	    $maxDesc=constant('__XMESSAGE_THREAD_DESC_MAX');
		if(mb_strlen($txt)>$maxDesc&&$maxDesc!=0) { //Проверка на кол-во описание
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo."Нужно ввести описание не более '<b>$maxDesc</b>' символов. :("]);
			die();
		}
        $Text=x::isCharArray(x::getENGLongToArray(x::getRUSLongToArray(x::getNumberToArray(x::getRUSToArray(x::getENGToArray(['@','$','_','-','=','/',' ',':','.','?',')','(','+','-','=',',','#']))))),$txt,true);
        if(!is_null($Text)&&!$Text&&!is_numeric($Text)){
        	x::LoadWebUrl();
        	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo.'Некоторые символы нельзя использовать в сообщение :(']);
			die();
        }
        //md5
	    $fileSQL=[];
     	foreach($files as $f){
     		$uri['img'][].=$f;
     	}
     	foreach($uri['img'] as $f){
     		array_push($fileSQL,$f);
     	}
        //-->other
        $txt=$syntax->gettext($txt);
    	$youtube=serialize(yt::getVideoArray($uri['youtube']));
    	$decode=x::getCheckMd5Array($fileSQL);
		$img=serialize($uri['img']);
		$url=serialize($uri['other']);
		//filebadMD5
		if($decode['ERROR']){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo.'Упс изоброжение меньше 8x8 нельзя отправить к нам на сервер :(</br>Или</br>наш сервер не может возвратить это изоброжение ->'.implode(',',$decode['ERROR'])]);
			die();
	    }
		if(mb_strlen(trim($txt))<1&&empty(unserialize($youtube))&&empty(unserialize($img)&&empty(unserialize($url)))){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo.'Пустое сообщение не отправить :(']);
			die();
		}
        self::create($title,$name,$txt,$dot,$img,$youtube,$id,$data);
    }
}
if($_SERVER['REQUEST_METHOD']=='POST'){
	//request
	$e=new newThread();
	$e->execute($key);
}
