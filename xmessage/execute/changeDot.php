<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use xprivate as xp;
class changeDot{
    public function execute(){
    	$dot=$_POST['dot'];
    	$data=xp::$data;
    	//Проверка валидаций
		$valid=explode(DIRECTORY_SEPARATOR,$dot);
		if($valid[1]!=__XMESSAGE_DOT_NAME_ROOT||!is_dir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.DIRECTORY_SEPARATOR.$dot)){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			if(!$dot){
				$dot='undefined';
			}
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>1,'title'=>'Удаление точки '.sm::badge(['txt'=>$dot]),'content'=>$logo.'Точка не найдена :(</br>Пожалуйста повторите попытку снова']);
			die();
		}
		//Проверка владелец ли
		if($data['xmessage']['dots'][$dot]['edit']||$data['xmessage']['dots'][$dot]['root']||$data['root']){
			self::effect($dot);//Принять эффект PERMS
			//load web site!
			x::LoadWebUrl();
			sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>'Успешно изменилась точка :)']);
		}else{
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>$logo.'Отказано в доступе :(']);
		}
	}
	/**
	 * Принять эффект
	 * ----------------------------------
	 * dot-Точка
	 * ----------------------------------
	 * @return bool
	 */
	public function effect($dot){
	    //newThread ACCEPTED
		$newThread=trim($_POST['newThread']);
		if($newThread=='*'){//all
			//all
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['newThread'=>true]]]],$id);
			}
			xp::setData(['xmessage'=>['dots'=>[$dot=>['newThread'=>'*']]]]);
		}elseif(!empty($newThread)){
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['newThread'=>false]]]],$id);
			}
			//mark
			$marks=explode("\n",$newThread);
			if(xp::checkMark($marks)){
				foreach($marks as $mark){
					xp::setDataId(['xmessage'=>['dots'=>[$dot=>['newThread'=>true]]]],xp::getIdMarker($mark));
				}
			}else{
				x::LoadWebUrl();
				$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>$logo.'Не найдены маркеры :(']);
				die();
			}
		}else{
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['newThread'=>false]]]],$id);
			}
			$newThread=false;
		}
		//new ACCEPTED
		$new=trim($_POST['new']);
		if($new=='*'){//all
			//all
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['new'=>true]]]],$id);
			}
			xp::setData(['xmessage'=>['dots'=>[$dot=>['new'=>'*']]]]);
		}elseif(!empty($new)){
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['new'=>false]]]],$id);
			}
			//mark
			$marks=explode("\n",$new);
			if(xp::checkMark($marks)){
				foreach($marks as $mark){
					xp::setDataId(['xmessage'=>['dots'=>[$dot=>['new'=>true]]]],xp::getIdMarker($mark));
				}
			}else{
				x::LoadWebUrl();
				$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>$logo.'Не найдены маркеры :(']);
				die();
			}
		}else{
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['new'=>false]]]],$id);
			}
			$new=false;
		}		
		//del ACCEPTED
		$del=trim($_POST['del']);
		if($del=='*'){//all
			//all
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['del'=>true]]]],$id);
			}
			xp::setData(['xmessage'=>['dots'=>[$dot=>['del'=>'*']]]]);
		}elseif(!empty($del)){
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['del'=>false]]]],$id);
			}
			//mark
			$marks=explode("\n",$del);
			if(xp::checkMark($marks)){
				foreach($marks as $mark){
					xp::setDataId(['xmessage'=>['dots'=>[$dot=>['del'=>true]]]],xp::getIdMarker($mark));
				}
			}else{
				x::LoadWebUrl();
				$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>$logo.'Не найдены маркеры :(']);
				die();
			}
		}else{
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['del'=>false]]]],$id);
			}
			$del=false;
		}
		//cls ACCEPTED
		$cls=trim($_POST['cls']);
		if($cls=='*'){//all
			//all
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['cls'=>true]]]],$id);
			}
			xp::setData(['xmessage'=>['dots'=>[$dot=>['cls'=>'*']]]]);
		}elseif(!empty($cls)){
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['cls'=>false]]]],$id);
			}
			//mark
			$marks=explode("\n",$cls);
			if(xp::checkMark($marks)){
				foreach($marks as $mark){
					xp::setDataId(['xmessage'=>['dots'=>[$dot=>['cls'=>true]]]],xp::getIdMarker($mark));
				}
			}else{
				x::LoadWebUrl();
				$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>$logo.'Не найдены маркеры :(']);
				die();
			}
		}else{
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['dots'=>[$dot=>['cls'=>false]]]],$id);
			}
			$cls=false;
		}
		return true;
	}
}
//request
$e=new changeDot();
$e->execute();
