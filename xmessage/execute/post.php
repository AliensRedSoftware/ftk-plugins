<?php
session_start();
$key=$_SESSION[$_POST['session_key']];
session_gc();
session_destroy();
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
/**
 * Работа с пост
 * --------------
 */
use xlib as x;
use youtube as yt;
use skinmanager as sm;
use xmessage as xm;
use xprivate as xp;
class post{
	/**
	 * Добавить новый пост
	 * --------------------
	 */
	function add($data,$txt,$youtube,$img){
		$redirect=$_POST['redirect'];
		$id=$_POST['id'];
		$sql=x::getmysql();
		$milliseconds=round(microtime(true)*1000);
		$time=date('Y-m-d').'=>'.date('H:i:s',time()-date('Z'))."($milliseconds)";
		//Данные
		$__xprivate_auth=$data['id'];
		$name=$data['name'];
		mysqli_query($sql,"INSERT INTO `$id` (`id` , `text` , `name` , `__xprivate_auth` , `youtube` , `img` , `time`) VALUES (NULL , '$txt' , '$name' , '$__xprivate_auth' , '$youtube' , '$img' , '$time');");
		mysqli_close($sql);
		//BALANCE BONUS
		$HTR=xp::getData()['HTR'];
		$HTR="$HTR+0.001";
		xp::setData(['HTR'=>eval('return '.$HTR.';')]);
		echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">";
		x::LoadWebUrl();//Загрузка локальной страницы прошлой
	}
    /**
     * Выполнить
     * ----------
     */
    function execute($key){
        $data=xp::getData();
		$name=$data['name'];
		if(!$_POST['id']){
	    	$id=str_replace('?'.x::getData(),NULL,x::geturi());
	    	$_POST['id']=$id;
	    }
	    $id=$_POST['id'];
	    $txt=$_POST['text'];
		require_once'syntax.php';
		$syntax=new syntax();
		//key
		$session_key=$_POST['session_key'];
		if($key!=$session_key){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>"Секретный ключ неверный $session_key :("]);
			die();
		}
	    //--->Отправка текста
		if(!x::is_uuidv4($id)){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>'id должен содержать uuidv4']);
			die();
		}
		//Очистка не докаченных файлов
		xm::ClearFileThreadBAD($id);
	    //--->Отправка файлов (fileUpload)
		$uri=$syntax->getUrl($txt);
		require_once'./fileUpload.php';
		$fileUpload=new fileUpload();
		$files=$fileUpload->execute($id);
		if(mb_strlen($txt)>=8096) {
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>'Нужно ввести описание не более 8096 символов. :(']);
			die();
		}
	    $Text=x::isCharArray(x::getENGLongToArray(x::getRUSLongToArray(x::getNumberToArray(x::getRUSToArray(x::getENGToArray(['@','$','_','-','=','/',' ',':','.','?',')','(','+','-','=',',','#']))))),$txt,true);
	    if(!is_null($Text)&&!$Text){
	    	x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>"Некоторые символы нельзя использовать в сообщение :("]);
			die();
	    }
	    //md5
	    $fileSQL=[];
	    $result=mysqli_query(x::getmysql(),"SELECT * FROM `$id` ORDER BY `id`");
	    while($R=mysqli_fetch_array($result)){
     		foreach(unserialize($R['img']) as $img){
     			array_push($fileSQL,$img);
     		}
     	}
     	foreach($files as $f){
     		$uri['img'][].=$f;
     	}
     	foreach($uri['img'] as $f){
     		array_push($fileSQL,$f);
     	}
		//--->other
		$txt=$syntax->getText($txt);
    	$youtube=serialize(yt::getVideoArray($uri['youtube']));
    	$decode=x::getCheckMd5Array($fileSQL);
		$img=serialize($uri['img']);
		$url=serialize($uri['other']);
		//filebadMD5
		if($decode['ERROR']){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>'Упс изоброжение меньше 8x8 нельзя отправить к нам на сервер :(</br>Или</br>наш сервер не может возвратить это изоброжение ->'.implode(',',$decode['ERROR'])]);
			die();
	    }
	    if($decode['BAD']){
	    	x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>'Повторные файлы нельзя отправить по md5-хэш! </br>уже имеется такой файл ->'.implode(',',$decode['BAD'])]);
			die();
	    }
		if(empty(trim($txt))&&empty(unserialize($youtube))&&empty(unserialize($img)&&empty(unserialize($url)))){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>'Пустое сообщение не отправить :)']);
			die();
		}
		self::add($data,$txt,$youtube,$img);//Отправка поста
	}
}
if($_SERVER["REQUEST_METHOD"]=='POST'){
	$e=new post();
	$e->execute($key);
}else{
	echo x::alert();
}
