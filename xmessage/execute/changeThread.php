<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use xprivate as xp;
class changeThread{
    public function execute(){
    	$id=$_POST['id'];
    	$data=xp::$data;
    	//Проверка валидаций
    	$thread=xm::is_thread($id);
		$valid=explode(DIRECTORY_SEPARATOR,$thread);
		if($valid[1]!=__XMESSAGE_DOT_NAME_ROOT||!$thread){
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>1,'title'=>'Изменение точки'.sm::badge(['txt'=>$dot]),'content'=>$logo.'Нить не найдена :(</br>Пожалуйста повторите попытку снова']);
			die();
		}
		//Проверка владелец ли
		if($data['xmessage']['threads'][$id]['edit']||$data['xmessage']['threads'][$id]['root']||$data['root']){
			self::effect($id);//Принять эффект PERMS
			//load web site!
			x::LoadWebUrl();
			sm::modal(['open'=>1,'title'=>'Изменение нити','content'=>'Успешно изменилась нить :)']);
		}else{
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>1,'title'=>'Изменение нити','content'=>$logo.'Отказано в доступе :(']);
		}
	}
	/**
	 * Принять эффект
	 * ----------------------------------
	 * thread-Нить
	 * ----------------------------------
	 * @return bool
	 */
	public function effect($thread){
		//comment ACCEPTED
		$comment=trim($_POST['comment']);
		if($comment=='*'){//all
			//all (INSTALL)
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['threads'=>[$thread=>['comment'=>true]]]],$id);
			}
			xp::setData(['xmessage'=>['threads'=>[$thread=>['comment'=>'*']]]]);
		}elseif(!empty($comment)){
			//off all
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['threads'=>[$thread=>['comment'=>false]]]],$id);
			}
			//mark
			$marks=explode("\n",$comment);
			if(xp::checkMark($marks)){
				foreach($marks as $mark){
					xp::setDataId(['xmessage'=>['threads'=>[$thread=>['comment'=>true]]]],xp::getIdMarker($mark));
				}
			}else{
				x::LoadWebUrl();
				$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>1,'title'=>'Изменение точки','content'=>$logo.'Не найдены маркеры :(']);
				die();
			}
		}else{
			foreach(xp::getIdsToArray() as $id){
				xp::setDataId(['xmessage'=>['threads'=>[$thread=>['comment'=>false]]]],$id);
			}
			$comment=false;
		}
		//Exts (Расширение)
		//all (INSTALL)
		foreach(xp::getIdsToArray() as $id){
			xp::setDataId(['xmessage'=>['threads'=>[$thread=>['exts'=>xm::getExtensionsCompile()]]]],$id);
		}
		xm::UnsetExtensionsCompile();
		return true;
	}
}
//request
$name=basename($_SERVER['PHP_SELF']);
if($name=='changeThread.php'){
	$e=new changeThread();
	$e->execute();
}
