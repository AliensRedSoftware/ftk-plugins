<?php
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
use xlib as x;
use skinmanager as sm;
use xmessage as xm;
use xcatalog as xc;
class refresh{
	/**
	 * Возвращаем нить
	 * ----------------
	 * id		-	Идентификатор нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * ----------------
	 * @return object
	 */
	function get($id,$count,$title=NULL){
		//mysqli settings...
		$sql=xm::getmysql();
		
		require_once'syntax.php';
		$syntax=new syntax();
		$id=explode('?',$id)[0];
		if($count>0){
			$count="LIMIT $count";
			$data=mysqli_query($sql,"SELECT * FROM `$id` ORDER BY `id` DESC $count");
			$DATA=$syntax->getFormToArray($data,$theme);
		}elseif($count<0){
			$count=substr($count,-1);
			$count="LIMIT $count";
			//data...
			$data=mysqli_query($sql,"SELECT * FROM `$id` ORDER BY `id` ASC $count");
			$DATA=$syntax->getFormToArray($data,$theme);
		}else{
			$data=mysqli_query($sql,"SELECT * FROM `$id` ORDER BY `id` DESC $count");
			$DATA=$syntax->getFormToArray($data,$theme);
		}
		mysqli_close($sql);
		return sm::panel(['title'=>$title,'stretch'=>false,'content'=>xc::getPagination(['max'=>100,'indent'=>'10','data'=>$DATA])]);
	}
}
