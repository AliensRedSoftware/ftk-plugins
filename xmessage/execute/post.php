<?php
session_start();
$key=$_SESSION[$_POST['session_key']];
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
use xmotion as moja;
class post{

	/**
	 * Добавить новый пост
	 * --------------------
	 */
	protected function add($data,$name,$txt,$youtube,$img,$mojas){
		$id=$_POST['__XMESSAGE_THREAD'];
		$sql=xm::getmysql();
		$milliseconds=round(microtime(true) * 1000);
		$time=date('Y-m-d').'/'.date('H:i:s',time()-date('Z'))."($milliseconds)";
		//Данные
		$__xprivate_auth=$data['id'];
		mysqli_query($sql,"INSERT INTO `$id` (`id`, `text`, `name`, `time`, `__xprivate_auth`, `youtube`, `img`, `view`, `mojas`, `type`) VALUES (NULL, '$txt', '$name', '$time', '$__xprivate_auth', '$youtube', '$img', '0', '$mojas', '$type');");
		mysqli_close($sql);
		//BALANCE BONUS
		if(x::isModule('rialto',false)){
			$wallet=rialto::getWallets()['HTR'];
			if(!empty($wallet)){
				$HTR=$data['rialto']['HTR']['value'];
				$wallet=$wallet['step'];
				$HTR="$HTR+$wallet";
				xp::setData(['rialto'=>['HTR'=>['value'=>eval('return '.$HTR.';')]]]);
			}
		}
		//unset Active
		unset($_POST['text']);
		x::LoadWebUrl();//Загрузка локальной страницы прошлой
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
	    $id=xm::getSelectedThread();
	    $txt=$_POST['text'];
		require_once'syntax.php';
		$syntax=new syntax();
		//key
		$session_key=$_POST['session_key'];
		if($key!=$session_key){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo."Секретный ключ неверный '<b>$session_key</b>' :("]);
			die();
		}
		//permission
		if(!$data['xmessage']['threads'][$id]['comment']&&!$data['xmessage']['threads'][$id]['root']&&!$data['root']){
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Создание нити','content'=>$logo.'В доступе отказано :(']);
			die();
		}
	    //--->Валидация нити
		if(!xm::is_thread($id)){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo.'Ошибка нить не найдена :(</br>Пожалуйста, создайте '.sm::a(['title'=>'Новая нить','href'=>'#thread','modal'=>'thread'])]);
			die();
		}
		//--->xmotion (Умное поведение)
		$mojas=moja::getActiveMojas();
		foreach($mojas as $moja){
			foreach($data['xmotion']['img'] as $name=>$val){//img
				foreach($data['xmotion']['img'][$name]['load'] as $lmoja){
					if($moja==$lmoja){
						break 3;
					}
				}
			}
			//fail
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo.'moja неверна выбрана! :(']);
			die();
		}
		$mojas=serialize($mojas);
		//Очистка не докаченных файлов
		xm::ClearFileThreadBAD($id);
	    //--->Отправка файлов (fileUpload)
		$uri=$syntax->getUrl($txt);
		require_once 'fileUploads.php';
		$fu=new fileUploads();
		$files=$fu->execute($id);
		//--->Описание
		$maxDesc=constant('__XMESSAGE_THREAD_DESC_MAX');
		if(mb_strlen($txt)>$maxDesc&&$maxDesc!=0){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>"Нужно ввести описание не более $maxDesc символов. :("]);
			die();
		}
	    $Text=x::isCharArray(x::getENGLongToArray(x::getRUSLongToArray(x::getNumberToArray(x::getRUSToArray(x::getENGToArray(['@','$','_','-','=','/',' ',':','.','?',')','(','+','-','=',',','#']))))),$txt,true);
	    if(!is_null($Text)&&!$Text&&!is_numeric($Text)){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo.'Некоторые символы нельзя использовать в сообщение :(']);
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
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo.'Упс изоброжение меньше 8x8 нельзя отправить к нам на сервер :(</br>Или</br>наш сервер не может возвратить это изоброжение ->'.implode(',',$decode['ERROR'])]);
			die();
	    }
	    if($decode['BAD']){
	    	x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>'Повторные файлы нельзя отправить по md5-хэш! </br>уже имеется такой файл ->'.implode(',',$decode['BAD'])]);
			die();
	    }

		if(mb_strlen(trim($txt))<1&&empty(unserialize($youtube))&&empty(unserialize($img))&&empty(unserialize($url))&&empty(unserialize($mojas))){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo.'Пустое сообщение не отправить :)']);
			die();
		}
		self::add($data,$name,$txt,$youtube,$img,$mojas);//Отправка поста
	}
}
if($_SERVER['REQUEST_METHOD']=='POST'){
	//request
	$e=new post();
	$e->execute($key);
}
