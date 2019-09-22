
<?php
error_reporting(0);
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
class refresh extends xlib {

	/*
	 * Возвращаем нить
	 * ----------------
	 * id		-	Адрес нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * ----------------
	 * @return object
	 */
	function get ($id, $count, $title = 'Сообщение') {
		require_once		'syntax.php';
		$syntax	        =	new syntax();
		$skinmanager    =	new skinmanager();
		if (!$id) {
			$id = $this->geturi(4);
		}
		$sql			=	$this->getmysql();
		$result		    =	mysqli_query($sql, "SELECT * FROM `$id` ORDER BY `id` DESC");
    	$xcatalog		=	new xcatalog();
    	$form = [];
		while ($row = mysqli_fetch_array($result)) {
			$index++;
			if ($index <= $count || $count == 0) {
				$name		=	$row['name'];
				$text		=	$row['text'];
				$src_img	=	$row['img'];
        		$src_video	=	$row['vidos'];
				$time	=	explode('(', $row['time']);
				$idChat	=	substr(($time[1]), 0, -1);
        		array_push($form, $syntax->getForm($index, $time[0], $idChat, $name, $text, $src_video, $src_img, $theme));
			} else {
				break;
			}
		}
		if ($index > 0) {
			$title .= ' ' . $skinmanager->badge($result->num_rows);
		}
		return $skinmanager->panel([
					'title'		=> $title,
					'content'	=> $xcatalog->getPagination(['max' => 100, 'indent' => '10', 'content' => $form])
				]);
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
		if (strlen($id) != 13) {
			echo $bootstrap->alert($bootstrap->ico('exclamation-sign') . "id должен содержать 13 символов ти шото задумал ?)", 'danger');
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
