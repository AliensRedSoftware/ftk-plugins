<?php
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use skinmanager as sm;
use xmessage as xm;
use xprivate as xp;
class cfg{
	/**
	 * Выполнить
	 * ------------
	 */
	function execute(){
    	$name=$_REQUEST['NAME'];
    	$gender=$_REQUEST['gender'];
    	$desc=$_REQUEST['DESC'];
    	$dateIn=$_REQUEST['dateIn'];
    	$data=xp::$data;
    	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
    	if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    	    x::LoadWebUrl();
    	    sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'В доступе отказано :(</br>Пожалуйста введите пароль доступа в '.sm::a(['title'=>sm::ico('heart').' '.'Личный кабинет','href'=>"#xprivate",'modal'=>'xprivate'])]);
    	    die();
    	}
    	if($_FILES['upload']['type']){
        	//-->Размер
        	$max=ini_get('upload_max_filesize');
        	$size=$_FILES['upload']['size'];
		    if($size>$max*1024*1024){//MAX
		        $size=$size / 1024 / 1024 . 'M';
		        x::LoadWebUrl();
			    sm::modal(['open'=>true,'title'=>'Личная информация','content'=>"$logoНевозможно загрузить с размером больше <b>$size</b>"]);
			    die();
		    }elseif($size<=0){
		        x::LoadWebUrl();
			    sm::modal(['open'=>true,'title'=>'Личная информация','content'=>"$logoНевозможно загрузить с размером меньше 0кб или больше '<b>$max</b>'"]);
			    die();
		    }
		    if($_FILES['upload']['type']=='image/jpg'||$_FILES['upload']['type']=='image/jpeg'||$_FILES['upload']['type']=='image/png'||$_FILES['upload']['type']=='image/gif'||$_FILES['upload']['type']=='image/webp'){
		        $path='..'.DIRECTORY_SEPARATOR.'.'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$data['id'].DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'ava';
			    move_uploaded_file($_FILES["upload"]["tmp_name"],$path);
			    chmod($path,0777);
			    //Кэширование аккаунта
			    xp::cacheAva();//AVA
		    }else{
		        x::LoadWebUrl();
			    sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Невозможно загрузить расширение с '.$_FILES['upload']['type']]);
			    die();
		    }
		}
    	//-->Установка имени
    	$isName=self::setName($name);//Установить имя
        if(!empty($isName)){
		    $oldName=$data['private']['name'];
        	if(empty($oldName)){
            	$oldName='Нейзвестный';
            }
        	$change.="</br>[Имя] - \"$oldName\" изменилось на \"$name\"";
        }
        //-->Установка имени
    	$isGender=self::setGender($gender);//Установить Гендер
        if($isGender){
		    $oldGender=$data['private']['gender'];
        	if(!$oldGender){
            	$oldGender='Не определено';
            }
        	$change.="</br>[Гендер] - \"$oldGender\" изменилось на \"$gender\"";
        }
        //-->Установка описание
    	$isDesc=self::setDesc($desc);//Установить описание
        if($isDesc){
            $change.="</br>[Описание] - Установлен";
        }
    	//-->Установка дата начало
    	$isDateIn=self::setDateIn($dateIn);//Установить описание
        if($isDateIn){
            $change.="</br>[Дата начало] - Установлен $dateIn";
        }
        $xprivate=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Личный кабинет','href'=>"#xprivate",'modal'=>'xprivate'])]);
        //off cache
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        //Load web url
        x::LoadWebUrl();
    	sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Изменение успешно изменились :)'.$change.$xprivate]);
    }
	/**
	 * Установить имя
	 * ---------------
	 * @return bool
	 */
	function setName($name){
		$arr=x::getENGLongToArray(x::getRUSLongToArray(x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['@','$','_','-','=',' '])))));
	    $access=x::isCharArray($arr,$name,true);
	    if(!is_null($access)&&!$access&&!is_numeric($access)){
	        x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Некоторые символы нельзя использовать в имени :(']);
	        die();
        }
		if(mb_strlen($name)>=32){
		    x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Нужно ввести имя не более 32 символов. :(']);
            die();
        }elseif(mb_strlen($name)<2){
            x::LoadWebUrl();
		    sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Нужно ввести имя более 2 символов. :(']);
		    die();
		}elseif(xp::$data['private']['name']!=$name){
        	xp::setData(['private'=>['name'=>$name]]);
        	return true;
        }
        return false;
    }
    /**
	 * Установить Дата начало
	 * ----------------------------------
	 */
	function setGender($val){
	    if($val){
		    if($val!='Мальчик'&&$val!='Девачка'){
		        x::LoadWebUrl();
                sm::modal(['open'=>1,'title'=>'Личная информация','content'=>"Гендер не может быть установлен $val :("]);
                die();
            }elseif(xp::$data['private']['gender']!=$val){
            	xp::setData(['private'=>['gender'=>$val]]);
            	return true;
            }
        }
        return false;
    }
    /**
	 * Установить описание
	 * ---------------
	 * @return bool
	 */
	function setDesc($desc){
		$arr=x::getENGLongToArray(x::getRUSLongToArray(x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['?','@','$','_','-','=',' '])))));
	    $access=x::isCharArray($arr,$desc,true);
	    if(!is_null($access)&&!$access&&!is_numeric($access)){
	        x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Некоторые символы нельзя использовать в описание :(']);
	        die();
        }
		if(mb_strlen($desc)>2048){
		    x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Требуется ввести описание не более 2048 символов. :(']);
            die();
        }elseif(xp::$data['private']['desc']!=$desc){
        	xp::setData(['private'=>['desc'=>$desc]]);
        	return true;
        }
        return false;
    }
	/**
	 * Установить Дата начало
	 * ----------------------------------
	 */
	function setDateIn($val){
	    $date=date('Y');
	    $min=$date-100;
		if($val>$date){
		    x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>"Требуется ввести дата начало не более $date :("]);
            die();
        }elseif($val<$min){
		    x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>"Требуется ввести дата начало более $min :("]);
            die();
        }elseif(xp::$data['private']['dateIn']!=$val){
        	xp::setData(['private'=>['dateIn'=>$val]]);
        	return true;
        }
        return false;
    }
}
if($_SERVER['REQUEST_METHOD']=='POST'){
	$e=new cfg();
	$e->execute();
}else{echo xlib::alert();}
