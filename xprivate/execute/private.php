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
    	$data=xp::getData();
    	if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    	    x::LoadWebUrl();
    	    sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'В доступе отказано :(</br>Пожалуйста введите пароль доступа в '.sm::a(['title'=>sm::ico('heart').' '.'Личный кабинет','href'=>"#xprivate",'modal'=>'xprivate'])]);
    	    die();
    	}
    	if($_FILES['upload']['type']){
        	//-->Размер
		    if($_FILES['upload']['size']>2000048){
		        x::LoadWebUrl();
			    sm::modal(['open'=>true,'title'=>'[Создание поста] -> ошибка!','content'=>"Невозможно загрузить с размером больше ".$size]);
			    die();
		    }elseif($_FILES['upload']['size']==0){
		        x::LoadWebUrl();
			    sm::modal(['open'=>true,'title'=>'[Создание поста] -> ошибка!','content'=>"Невозможно загрузить с размером больше 2мб"]);
			    die();
		    }
		    if($_FILES['upload']['type']=='image/jpg'||$_FILES['upload']['type']=='image/jpeg'||$_FILES['upload']['type']=='image/png'||$_FILES['upload']['type']=='image/gif'){
			    move_uploaded_file($_FILES["upload"]["tmp_name"],'.././account/anon/'.$data['id'].'/ico/ava');
			    //Кэширование аккаунта
			    xp::cacheAva();
		    }else{
		        x::LoadWebUrl();
			    sm::modal(['open'=>1,'title'=>'[Создание поста] -> ошибка!','content'=>"Невозможно загрузить расширение с ".$_FILES['upload']['type']]);
			    die();
		    }
		}
    	//-->Установка имени
    	$isName=self::setName($name);//Установить имя
        if(!empty($isName)){
		    $oldName=$data['name'];
        	if(empty($oldName)){
            	$oldName='Нейзвестный';
            }
        	$change.="</br>[Имя] - \"$oldName\" изменилось на \"$name\"";
        }
        //-->Установка имени
    	$isGender=self::setGender($gender);//Установить Гендер
        if($isGender){
		    $oldGender=$data['gender'];
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
	    if(!is_null($access)&&!$access){
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
		}elseif(xp::getData()['name']!=$name){
        	xp::setData(['name'=>$name]);
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
            }elseif(xp::getData()['gender']!=$val){
            	xp::setData(['gender'=>$val]);
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
	$arr=x::getENGLongToArray(x::getRUSLongToArray(x::getENGToArray(x::getRUSToArray(x::getNumberToArray(['@','$','_','-','=',' '])))));
	    $access=x::isCharArray($arr,$desc,true);
	    if(!is_null($access)&&!$access){
	        x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Некоторые символы нельзя использовать в описание :(']);
	        die();
        }
		if(mb_strlen($desc)>2048){
		    x::LoadWebUrl();
            sm::modal(['open'=>1,'title'=>'Личная информация','content'=>'Требуется ввести описание не более 2048 символов. :(']);
            die();
        }elseif(xp::getData()['desc']!=$desc){
        	xp::setData(['desc'=>$desc]);
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
        }elseif(xp::getData()['dateIn']!=$val){
        	xp::setData(['dateIn'=>$val]);
        	return true;
        }
        return false;
    }
}
if($_SERVER["REQUEST_METHOD"]=='POST'){
	$e=new cfg();
	$e->execute();
}else{echo xlib::alert();}
