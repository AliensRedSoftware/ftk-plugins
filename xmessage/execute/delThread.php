<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';

use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use xprivate as xp;
class delThread{
    public function execute(){
    	$id=$_GET['id'];
    	$data=xp::$data;
    	$path=xm::is_thread($id);//is_thread
		if($path){
		    $sql=xm::getmysql();
    		$result=mysqli_query($sql,"SELECT * FROM `$id` LIMIT 1");
		    $xp=sm::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личном кабинете']);
			while($R=mysqli_fetch_array($result)){
				$__xprivate_auth=$R['__xprivate_auth'];
			}
			//dot selected
			$dot=substr($dot,0,-1);
			if (!$data['xmessage']['dots'][$dot]['del'] &&
				!$data['xmessage']['dots'][$dot]['cls'] &&
				!$data['xmessage']['dots'][$dot]['root'] &&
				$data['id'] != $__xprivate_auth &&
				!$data['root']){
				x::LoadWebUrl();//Загрузка локальной страницы прошлой
				$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
				sm::modal(['open'=>1,'title'=>'Удаление нити '.sm::badge(['txt'=>$id]),'content'=>"$logoУ вас недостаточно прав :(</br>Пожалуйста, измените в $xp"]);
				die();
			}
			//FILES
			array_map('unlink',array_filter((array)array_merge(glob($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'tmp'.$path.DIRECTORY_SEPARATOR.'*'))));
			//DIR
			rmdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'tmp'.$path);
			unlink("../../../../uri$path.php");
			mysqli_query($sql, "DROP TABLE `$id`");
			//del permission
			foreach(xp::getIdsToArray() as $user){
				$data=xp::getDataId($user);
				unset($data['xmessage']['threads'][$id]);
				xp::setDataId($data,$user,true);
			}
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>1,'title'=>'Удаление нити '.sm::badge(['txt'=>$id]),'content'=>'Успешно удалена нить :)']);
		}else{
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			sm::modal(['open'=>1,'title'=>'Удаление нити '.sm::badge(['txt'=>$id]),'content'=>$logo.'Ошибка нить не найдена :(</br>Пожалуйста, создайте '.sm::a(['title'=>'Новая нить','href'=>'#thread','modal'=>'thread'])]);
		}
    }
}
//request
$e=new delThread();
$e->execute();
