<?php
session_start();
$key=$_SESSION[$_POST['session_key']];
session_gc();
session_destroy();
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use youtube as yt;
use skinmanager as sm;
use xmessage as xm;
use perms as pm;
use xprivate as xp;
class newThread{
	/**
	 * Создание нити
	 * --------------
	 */
	function create($title,$name,$txt,$dot,$src_img,$youtube,$id,$data){
		require_once'../../../../../mysql.php';
		$mysql=new mysql();
		require_once'syntax.php';
		$syntax=new syntax();
		$sql=x::getmysql();
		//ДОП+++++++++
		$milliseconds=round(microtime(true)*1000);
		$time=date('Y-m-d').'=>'.date('H:i:s',time()-date('Z'))."($milliseconds)";
		//Создание инфы об нити
		if(!x::is_uuidv4($title)){
			mysqli_query($sql,"INSERT INTO `view` (`id`, `name`, `description`, `title`, `uuid`) VALUES (NULL, '$name', '$txt', '$title', '$id');");
		}
		//Создание нити
		$__xprivate_auth=$data['id'];
		mysqli_multi_query($sql,
"CREATE TABLE `$mysql->database` . `$id` (`id` INT NOT NULL AUTO_INCREMENT , `text` TEXT NOT NULL , `name` VARCHAR(32) NOT NULL , `time` VARCHAR(256) NOT NULL , `__xprivate_auth` VARCHAR(128) NOT NULL , PRIMARY KEY (`id`) , `youtube` TEXT NOT NULL , `img` TEXT NOT NULL) ENGINE = MyISAM CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;".
"INSERT INTO `$id` (`id`, `text`, `name`, `__xprivate_auth`, `youtube`, `img`, `time`) VALUES (NULL, '$txt', '$name', '$__xprivate_auth', '$youtube', '$src_img', '$time');");
		file_put_contents("../../../../uri$dot$id.php",'<?php
class ftk extends xlib {
    function __construct() {
        $this->req([\'head\',\'body\',\'footer\']);
        $head = new head();
        $body = new body();
        $footer = new footer();
        $this->execute($head,$body,$footer);
    }
    function execute($head,$body,$footer) {
        $head->execute('."'$title'".');
        $body->layout_multiForm();
        $footer->execute();
    }
}');
		chmod("../../../../uri$dot$id.php", 0777);
		mysqli_close($sql);
		//BALANCE BONUS
		$HTR=xp::getData()['HTR'];
		$HTR="$HTR+0.002";
		xp::setData(['HTR'=>eval('return '.$HTR.';')]);
		echo "<meta http-equiv=\"refresh\" content=\"0;url=$dot$id\">";
	}
	/**
	 * Выполнить
	 * ----------
	 */
    function execute($key){
    	$data=xp::getData();
		$title=$_POST['title'];
		$name=$data['name'];
		$txt=$_POST['text'];
		$dot=$_POST['dot'];
		require_once 'syntax.php';
		$syntax=new syntax();
		//key
		$session_key=$_POST['session_key'];
		if($key!=$session_key){
			x::LoadWebUrl();
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>"Секретный ключ неверный $session_key :("]);
			die();
		}
		//-------------------------
		$id=x::uuidv4(); //Сгенерировать уникальный ID
		//--->Отправка файлов (fileUpload)
		$uri=$syntax->getUrl($txt);
		require_once'./fileUpload.php';
		$fileUpload=new fileUpload();
		$files=$fileUpload->execute($id);
		//PERMS
	    if(xm::isDot()){
	    	//-->Perms
			if(!pm::isAccess()){
				sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'В доступе отказано!']);
				die();
			}
	    }
		if(mb_strlen($txt)>=8096) { //Проверка на кол-во описание
			x::LoadWebUrl();
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Нужно ввести описание не более 8096 символов. :(']);
			die();
		}
		//Проверка на бажность символьность
		$arr=x::getENGLongToArray(x::getRUSLongToArray(x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['@','$','_','-','='])))));
		$T=x::isCharArray($arr,$title,true);
        if(!is_null($T)&&!$T){
        	x::LoadWebUrl();
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Некоторые символы нельзя использовать в название! :(']);
			die();
        }
        if(!trim($title)){ //Проверка на кол-во название
			$title=$id;
		}elseif(mb_strlen($title)<=2){
			x::LoadWebUrl();
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Нужно ввести название более 2 символов. :(']);
			die();
		}elseif(mb_strlen($title)>=64){
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Нужно ввести название менее 64 символов. :(']);
			die();
		}
        $Text=x::isCharArray(x::getENGLongToArray(x::getRUSLongToArray(x::getNumberToArray(x::getRUSToArray(x::getENGToArray(['@','$','_','-','=','/',' ',':','.','?',')','(','+','-','=',',','#']))))),$txt,true);
        if(!is_null($Text)&&!$Text){
        	x::LoadWebUrl();
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Некоторые символы нельзя использовать в сообщение :(']);
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
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Упс изоброжение меньше 8x8 нельзя отправить к нам на сервер :(</br>Или</br>наш сервер не может возвратить это изоброжение ->'.implode(',',$decode['ERROR'])]);
			die();
	    }
		if(empty(trim($txt))&&empty(unserialize($youtube))&&empty(unserialize($img)&&empty(unserialize($url)))){
			x::LoadWebUrl();
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>'Пустое сообщение не отправить :)']);
			die();
		}
        self::create($title,$name,$txt,$dot,$img,$youtube,$id,$data);
    }
}
if($_SERVER["REQUEST_METHOD"]=='POST'){
	$e=new newThread();
	$e->execute($key);
}else{echo x::alert();}
