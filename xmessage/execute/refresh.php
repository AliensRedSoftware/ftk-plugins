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
	 * @return string
	 */
	function get($id,$count,$title=NULL){
		require_once'syntax.php';
		$syntax=new syntax();
		$id=explode('?',$id)[0];
		if($count>0){
			$count="LIMIT $count";
			$sql=mysqli_query(x::getmysql(),"SELECT * FROM `$id` ORDER BY `id` DESC $count");
			$DATA=$syntax->getFormToArray($sql,$theme);
		}elseif($count<0){
			$count=substr($count,-1);
			$count="LIMIT $count";
			$sql=mysqli_query(x::getmysql(),"SELECT * FROM `$id` ORDER BY `id` ASC $count");
			$DATA=$syntax->getFormToArray($sql,$theme);
		}else{
			$sql=mysqli_query(x::getmysql(),"SELECT * FROM `$id` ORDER BY `id` DESC $count");
			$DATA=$syntax->getFormToArray($sql,$theme);
		}
		
		return sm::panel(['title'=>$title,'content'=>xc::getPagination(['max'=>100,'indent'=>'10','data'=>$DATA])]);
	}

    /**
     * Выполнить
     * ----------
     */
	function execute () {
		require_once '../../../../../mysql.php';
		$mysql		=	new mysql();
		$sql		=	mysqli_connect($mysql->ip, $mysql->user , $mysql->password, $mysql->database);
		$youtube	=	new youtube();
		if (!$this->is_uuidv4($id)) {
			echo $bootstrap->alert($bootstrap->ico('exclamation-sign') . "id должен содержать uuidv4 ти шото задумал ?)", 'danger');
			die();
		}
		$result = mysqli_query($sql , "SELECT * FROM `$id` ORDER BY `id` DESC");
		$content = '';
		while ($row = mysqli_fetch_array($result)) {
			$name = $row['name'];
			$text = trim($row['text']);
			$src_video = trim($row['vidos']);
			$src_img = trim($row['img']);
			$time = explode('(', $row['time']);
			$idmsg = substr(($time[1]), 0, -1);
			$xlib->js("
				$('#$idmsg').click(function () {
					$('#posttext').val('@' + $idmsg + '\\x0A' + $('#posttext').val());
	    	});");
			$idChat = "<button type='button' class='$idmsg btn-link' id='$idmsg' style='padding: 0;'>" . '@' . $idmsg . '</button>';
			$time = $time[0];
			$index++;
			if($src_img != null) {
				$imghref = explode(" " , $src_img);
				unset($src_img);
				foreach ($imghref as $value_img) {
					$img = $lightbox->img($value_img, '100%');
					$src_img .= $xlib->padding([
						'all' => 5,
						'content' => "<div class='panel panel-$theme' style='margin-bottom:0px;'><div class='panel-body' align='center' style='padding:5px;'>$img</div></div>"
					]);
				}
			}
			if ($src_video != null) {
				$vidoshref = explode(' ' , $src_video);
				unset($src_video);
				foreach ($vidoshref as $value_vidos) {
					$src_video .= $xlib->padding([
						'all' => 5,
						'content' => $youtube->video($value_vidos, 420, 240)
					]);
				}
			}
			require_once 'syntax.php';
			$syntax = new syntax();
			$content .= $syntax->getForm($index, $time, $idChat, $name, $text, $src_video, $src_img, $theme);
		}
		echo $bootstrap->panel([
			'align' => 'left',
			'theme' => $theme,
			'title' => $id,
			'content' => $content
		]);

	}
}
