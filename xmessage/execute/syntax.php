<?php
error_reporting(0);

/**
 * Упрощенный текстовый редактор
 * @ver 1.0
 */
class syntax extends xlib {

	public $index;

	/**
	 * Возвращаем в виде html текста
	 * -----------------------------
	 * text	-	Текст
	 */
	public function getHtml ($text = 'Привет /s Как дела ммм ? / /b Привет / dsf /b test /') {
		$split = explode("\n", $text);
		foreach ($split as $spl) {
			$arruuidUrls = [];
			$arruuidYoutube = [];
			$urls = $this->getUrl($spl);
			foreach ($urls['other'] as $url) {
				if ($url != $prevurl) {
					$uuid = $this->uuidv4();
				}
				$spl = str_replace($url, "[$uuid]", $spl);
				array_push ($arruuidUrls, "[$uuid]");
				$prevurl = $url;
			}
			foreach ($urls['youtube'] as $url) {
				if ($url != $prevurl) {
					$uuid = $this->uuidv4();
				}
				$spl = str_replace($url, "[$uuid]", $spl);
				array_push ($arruuidYoutube, "[$uuid]");
				$prevurl = $url;
			}

			$i = -1;
			$syn = $this->mb_str_split($spl);
			foreach ($syn as $key) {
				$i++;
				if (!$ignore) {
					if ($key == '/') {
						switch ($index) {
							case 'b':
								$val .= "</$index>";
								unset($startxIndex);
								unset($checkval);
								unset($this->index);//Глобальный индекс
								unset($index);//Локальный индекс
							break;
							case 'i':
								$val .= "</$index>";
								unset($startxIndex);
								unset($checkval);
								unset($this->index);//Глобальный индекс
								unset($index);//Локальный индекс
							break;
							case 's':
								$val .= "</$index>";
								unset($startxIndex);
								unset($checkval);
								unset($this->index);//Глобальный индекс
								unset($index);//Локальный индекс
							break;
							case 'c':
								$exp	=	explode('=', $val);
								$name	=	$exp[1]; //Имя стикера
								$uuid	=	$exp[2]; //Ид пака
								if (file_exists("../sticker/$uuid/$name")) {
									$img	=	$this->getPathModules("xmessage/sticker/$uuid/$name");
									$val	=	$this->str_replace("<c>=$name=$uuid", "<img src=\"$img\">", $val, 1);
									$val	.=	'</img>';
								}
								unset($startxIndex);
								unset($checkval);
								unset($this->index);//Глобальный индекс
								unset($index);//Локальный индекс
							break;
							default:
								switch ($this->index) {
									case 'b':
										$index = $this->index;
										$val = str_replace($val, "<$index>$val</$index>", $val);
										unset($startxIndex);
										unset($checkval);
										unset($this->index);//Глобальный индекс
										unset($index);//Локальный индекс
									break;
									case 'i':
										$index = $this->index;
										$val = str_replace($val, "<$index>$val</$index>", $val);
										unset($startxIndex);
										unset($checkval);
										unset($this->index);//Глобальный индекс
										unset($index);//Локальный индекс
									break;
									case 's':
										$index = $this->index;
										$val = str_replace($val, "<$index>$val</$index>", $val);
										unset($startxIndex);
										unset($checkval);
										unset($this->index);//Глобальный индекс
										unset($index);//Локальный индекс
									break;
									default:
										$startxIndex = true;
									break;
								}
							break;
						}
					} else {
						$checkval = true;
					}
					if ($startxIndex) {
						switch ($syn[$i + 1]) {
							case 'b':
								if (trim($syn[$i + 2])) {
									$val .= '<b>';
									$this->index = 'b';
									$checkval = true;
									$ignore = true;
								} else {
									$val .= $key;
								}
							break;
							case 'i':
								if (trim($syn[$i + 2])) {
									$val .= '<i>';
									$this->index = 'i';
									$checkval = true;
									$ignore = true;
								} else {
									$val .= $key;
								}
							break;
							case 's':
								if (trim($syn[$i + 2])) {
									$val .= '<s>';
									$this->index = 's';
									$checkval = true;
									$ignore = true;
								} else {
									$val .= $key;
								}
							break;
							case 'c':
								if (trim($syn[$i + 2])) {
									$val .= '<c>';
									$this->index = 'c';
									$checkval = true;
									$ignore = true;
								} else {
									$val .= $key;
								}
							break;
							default:
								$checkval = true;
								$val .= $key;
							break;
						}
						$index = $this->index;
						unset($startxIndex);
					} else {
						if ($checkval) {
							$val .= $key;
						}
					}
				} else {
					$ignore = false;
				}
			}
			if ($this->index && !$index) {
				$index = $this->index;
				$val = "<$index>$val</$index>";
			}
			$ch = -1;
			foreach ($arruuidUrls as $uuid) {
				$ch++;
				$val = str_replace($uuid, $urls['other'][$ch], $val);
			}
			$chs = -1;
			foreach ($arruuidYoutube as $uuid) {
				$chs++;
				$val = str_replace($uuid, $urls['youtube'][$chs], $val);
			}
			$val = trim($val);
		}
		if ($checkval == true) {
			$index = $this->index;
			$val .= "</$index>";
		}
		return trim($val);
	}

	/**
	 * Возвращает отформатированный текст
	 */
	public function getText ($text = 'text') {
		$xlib = new xlib();
		$descriptionArray = explode("\n", trim($text));
		foreach ($descriptionArray as $text) {
			$output .= "\n";
			$output .= $this->getHtml($text);
			if (trim($output) != null) {
				$output .= '</br>';
			}
		}
		return $output;
	}

	/**
	 * Возвращает отформатированный текст и получает только ссылки
	 */
	public function getUrl ($text = 'text') {
    	$outputs	=	[];
		$xlib = new xlib();
		$ls = explode("\n", $text);
		foreach ($ls as $ass) {
			$descriptionArray = explode(" ", $ass);
			foreach ($descriptionArray as $value) {
				preg_match('#(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12})#x', $value, $str);
				if (!$str[1]) {
					preg_match('/(https?:\/\/|ftp:\/\/|www\.)((?![.,?!;:()]*(\s|$))[^\s]){2,}/', $value, $url);
					if ($url[0]) {
						$outputs['other'][] .= trim($url[0]);
					}
				} else {
					$outputs['youtube'][] .= trim($str[0]);
				}
			}
		}
		return $outputs;
	}

	/**
	 * Возвращаем готовую форму
	 * -------------------------
	 */
	 public function getForm ($index = 0, $time = '2019-07-12=>14:00:46', $idChat = '@1562940046419
', $name = 'Неизвестный', $text, $src_youtube, $src_img, $theme) {
	    $skinmanager    =   new skinmanager();
     	$youtube		=	new youtube();
     	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->geturi(7) == 'saveSettings.php') {
     		if ($_REQUEST['number']) {
        		$title	.=	"[$index]->";
        	}
            if ($_REQUEST['date']) {
        		$title	.=	"[$time]->";
        	}
            if ($_REQUEST['idMessage']) {
        		$title	.=	"[$idChat]->";
        	}
        } else {
        	if ($_COOKIE['__xmessage_number']) {
            	$title	.=	"[$index]->";
            }
        	if ($_COOKIE['__xmessage_date']) {
            	$title	.=	"[$time]->";
            }
            if ($_COOKIE['__xmessage_IdMessage']) {
        		$title	.=	"[$idChat]->";
        	}
        }
		$title			.=	"$name";
     	foreach (unserialize($src_img) as $img) {
			$srcimg .= $skinmanager->border([
				'style' => "margin:5px;width:35%;height: 0%;",
				'content' => $skinmanager->lightbox(['src' => $img, 'stretch' => true])
			]);
		}
     	foreach (unserialize($src_youtube) as $val) {
        	$other	.=	$youtube->getIframe($val);
        }
		if (trim($text)) {
        	$text = $this->margin(['left' => '5px', 'content' => $text]);
			if (trim($other) != null || $srcimg) {
				$content = $text . "<div style=\"display:flex;flex-wrap:wrap;\">$other $srcimg</div>";
			} else {
				$content = $text;
			}
		} else {
			$content = "<div style=\"display:flex;flex-wrap:wrap;\">$other $srcimg</div>";
		}
		$html = $skinmanager->panel([
			'title' => $title,
			'theme' => $theme,
			'content' => $content
		]);
		return $html;
	 }
}
