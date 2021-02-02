<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use xmessage as xm;
use skinmanager as sm;
use xprivate as xp;
class delThread{
    public function execute(){
    	$id=$_GET['id'];
    	$data=xp::getData();
    	$path=xm::getInfoThreadToArray()[$id]['path'];
		if($path){
		    $sql=x::getmysql();
    		$result=mysqli_query($sql,"SELECT * FROM `$id` LIMIT 1");
		    $xp=sm::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личном кабинете']);
			while($R=mysqli_fetch_array($result)){
				$__xprivate_auth=$R['__xprivate_auth'];
			}
			if($data['id']!=$__xprivate_auth){
				x::LoadWebUrl();//Загрузка локальной страницы прошлой
				sm::modal(['open'=>1,'title'=>'Удаление нити '.sm::badge(['txt'=>$id]),'content'=>"У вас недостаточно прав :(</br>Пожалуйста, измените в $xp"]);
				die();
			}
			array_map('unlink',array_filter((array)array_merge(glob($_SERVER['DOCUMENT_ROOT']."/tmp$path/*"))));
			rmdir($_SERVER['DOCUMENT_ROOT']."/tmp$path");
			unlink("../../../../uri$path.php");
			mysqli_multi_query($sql,"DROP TABLE `$id`");
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>1,'title'=>"Удаление нити ".sm::badge(['txt'=>$id]),'content'=>'Успешно удалена нить :)']);
		}else{
			x::LoadWebUrl();//Загрузка локальной страницы прошлой
			sm::modal(['open'=>1,'title'=>"Удаление нити ".sm::badge(['txt'=>$id]),'content'=>'Ошибка нить не найдена :(</br>Пожалуйста, создайте '.sm::a(['title'=>'Новая нить','href'=>'#thread','modal'=>'thread'])]);
		}
    }
}
$e=new delThread();
$e->execute();
