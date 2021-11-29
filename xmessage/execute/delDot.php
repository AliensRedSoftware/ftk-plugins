<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use xprivate as xp;
class delDot{
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
			sm::modal(['open'=>1,'title'=>'Удаление точки '.sm::badge(['txt'=>$dot]),'content'=>$logo."Точка не найдена '<b>$dot</b>' :(</br>Пожалуйста повторите попытку снова"]);
			die();
		}
		//Проверка владелец ли
		if($data['xmessage']['dots'][$dot]['del']||$data['xmessage']['dots'][$dot]['root']||$data['root']){
			//sql connect
			$sql=xm::getmysql();
			//DELETE THREADS ALL
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator("../../../../uri$dot", RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
				if(pathinfo($path,PATHINFO_EXTENSION)=='php'){
					$name=explode('.',basename($path))[0];
					if(x::is_uuidv4($name)){
						//selected path thread
						$path=$dot.DIRECTORY_SEPARATOR.$name;
						//delete file all
						array_map('unlink',array_filter((array)array_merge(glob($_SERVER['DOCUMENT_ROOT']."/tmp$path/*"))));
						rmdir($_SERVER['DOCUMENT_ROOT']."/tmp$path");
						//delete thread
						unlink("../../../../uri$path.php");
						mysqli_query($sql,"DROP TABLE `$name`");
					}
				}
			}
			//DELETE DOTS
			//FILE
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator("../../../../uri$dot", RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
				if(pathinfo($path,PATHINFO_EXTENSION)=='php'){
					$path=substr(trim(str_replace('|',NULL,$path)),2);
					if(!is_file($path)){
						$path='.'.$path;
					}
					unlink($path);
				}
			}
			//DOTS
			$DOTS=[];
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator("../../../../uri$dot", RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
				$path=substr(trim(str_replace('|',NULL,$path)),2);
				if(!is_dir($path)){
					$path='.'.$path;
				}
				array_push($DOTS,$path);
			}
			foreach(array_reverse($DOTS) as $DOT){
				rmdir($DOT);
			}
			//DOT selected delete
			rmdir('../../../../uri'.$dot);
			unlink("../../../../uri$dot.php");
			//del permission
			foreach(xp::getIdsToArray() as $id){
				$data=xp::getDataId($id);
				unset($data['xmessage']['dots'][$dot]);
				xp::setDataId($data,$id,true);
			}
			//load web site!
			x::LoadWebUrl();
			sm::modal(['open'=>1,'title'=>'Удаление точки '.sm::badge(['txt'=>$dot]),'content'=>"Успешно удалена точка '<b>$dot</b>' :)"]);
		}else{
			x::LoadWebUrl();
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>1,'title'=>'Удаление точки '.sm::badge(['txt'=>$dot]),'content'=>$logo.'Отказано в доступе :(']);
		}
	}
}
//request
$e=new delDot();
$e->execute();
