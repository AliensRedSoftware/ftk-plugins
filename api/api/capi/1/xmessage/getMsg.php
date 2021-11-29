<?php

/**
 * Возвращаем сообщение
 */
use xlib as x;
use xmessage as xm;
class getMsg {

	/**
	 * Выполнение
	 */
	function execute () {
		$dot		=	$_REQUEST['dot'];
		$space		=	$_REQUEST['space'];
		$selected	=	$_REQUEST['selected'];
		$count		=	$_REQUEST['count'];
		$capi		=	new capi();
		$msg		=	self::Msg($dot, $space, $selected, $count);
		if ($msg) {
			$capi->setStatus(200);
			$capi->setResponse($msg);
		} else {
			$capi->setStatus(404);
		}
		die($capi->getResponse());
	}

	/**
	 * Возвращаем сообщение
	 * ---------------------------
	 * space	-	Пространство
	 * dot		-	Точка (Все)
	 * selected	-	Выбранная точка
	 * count 	-	Кол-во (все)
	 */
	public function Msg($dot, $space = false, $selected = false, $count = 5){
		$r			=	[];
		if ($dot && $space) {
			foreach(xm::getIdToArray($space, $dot) as $id) {
				if ($selected == $id || empty($selected)) {
					$sql		=	x::getmysql();
					$result		=	mysqli_query($sql, "SELECT * FROM `$id` ORDER BY `id` DESC LIMIT $count");
					while ($row = mysqli_fetch_array($result)) {
						if ($selected) {
							$r['count'] = $result->num_rows;
						} else {
							$r[$id]['count'] = $result->num_rows;
						}
						$i = 0;
						foreach ($row as $val) {
							$i++;
							if($row['vidos'] != 'N;') {
								if (is_array(unserialize($row['vidos']))) {
									$youtube = ['youtube' => unserialize($row['vidos'])];
								} else {
									$youtube = ['youtube' => trim($row['vidos'])];
								}
							}
							if ($row['img'] != 'N;') {
								if (is_array(unserialize($row['img']))) {
									$img = ['photo' => unserialize($row['img'])];
								} else {
									$img = ['photo' => trim($row['img'])];
								}
							}

							if ($row['text']) {
								$txt = iconv(mb_detect_encoding($row['text']), 'utf-8', $row['text']);
							}

							if ($youtube['youtube']) {
								$file = $youtube;
							}

							if ($img['photo']) {
								$file['photo'] = $img['photo'];
							}

							if ($txt) {
								$out['txt'] = $txt;
							}

							if ($file) {
								$out['file'] = $file;

							}
							if ($selected) {
								$r['msg'][$row['time']] = $out;
							} else {
								$r[$id]['msg'][$row['time']] = $out;
							}
						}
					}
				}
			}
			return $r;
		} else {
			$r['count'] = count(xm::getSpace($dot));
			foreach(xm::getSpace($dot) as $space) {
				$r[$space]['opt']['count'] = count(xm::getIdToArray($space, $dot));
				foreach(xm::getIdToArray($space, $dot) as $id) {
					if ($selected == $id || empty($selected)) {
						$sql		=	$xlib->getmysql();
						$result		=	mysqli_query($sql, "SELECT * FROM `$id` ORDER BY `id` DESC LIMIT $count");
						while ($row = mysqli_fetch_array($result)) {
							if ($selected) {
								$r[$space]['count'] = $result->num_rows;
							} else {
								$r[$space][$id]['count'] = $result->num_rows;
							}
							$i = 0;
							foreach ($row as $val) {
								$i++;
								if($row['vidos'] != 'N;') {
									if (is_array(unserialize($row['vidos']))) {
										$youtube = ['youtube' => unserialize($row['vidos'])];
									} else {
										$youtube = ['youtube' => trim($row['vidos'])];
									}
								}
								if ($row['img'] != 'N;') {
									if (is_array(unserialize($row['img']))) {
										$img = ['photo' => unserialize($row['img'])];
									} else {
										$img = ['photo' => trim($row['img'])];
									}
								}

								if ($row['text']) {
									$txt = iconv(mb_detect_encoding($row['text']), 'utf-8', $row['text']);
								}

								if ($youtube['youtube']) {
									$file = $youtube;
								}

								if ($img['photo']) {
									$file['photo'] = $img['photo'];
								}

								if ($txt) {
									$out['txt'] = $txt;
								}

								if ($file) {
									$out['file'] = $file;

								}
								if ($selected) {
									$r[$space]['msg'][$row['time']] = $out;
								} else {
									$r[$space][$id]['msg'][$row['time']] = $out;
								}
							}
						}
					}
				}
			}
			return $r;
		}
	}
}
