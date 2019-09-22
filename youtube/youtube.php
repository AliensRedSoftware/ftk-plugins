<?php

/**
 * youtube дополнительные функций
 * v1.0
 */
class youtube extends xlib {

	/**
	 * Возвращает видео с ютуба
	 */
	public function video ($data_embed = 'BcZ8oZAJnhk', $width = 240, $height = 240) {
		return "<div class='youtube' data-embed='$data_embed' style='width:$width;height:$height;'><div class='play-button'></div></div>";
	}

	/**
	 * Возвращаем видео в iframe
	 * --------------------------
	 * data_embed	-	Ид видео
	 * width		-	Ширина
	 * height		-	Высота
	 */
	public function getIframe ($data_embed = '2SXp7RiF9Bs', $width = 240, $height = 240) {
		if ($data_embed) {
			$height		+=	20;
    		$youtube	=	$this->getPathModules('youtube' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'youtube.css');
    		return	"<iframe width=\"$width\" height=\"$height\" srcdoc=\"<link rel='stylesheet' text='type/css' href='$youtube'><div class='youtube'><a href='https://www.youtube.com/embed/$data_embed?autoplay=1'><div class='play-button'></div><img src='https://img.youtube.com/vi/$data_embed/default.jpg'></a></div>\" frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
    	}
    }

	/**
	 * Возвращаем embeded код
	 * ----------------------
	 * url	-	Видео с ютуба
	 * ----------------------
	 * @return Array
	 */
	public function getEmbededArray ($item) {
    	if($item){
    		$list = [];
    		foreach ($item as $embeded) {
        		$embeded = $this->getEmbeded($embeded);
        		if ($embeded) {
            		if (count($list) >= 1) {
                		foreach ($list as $val) {
                    		if ($val == $embeded) {
                        		$err = true;
                        		break;
                        	} else {
                        		$err = false;
                        	}
                    	}
                		if (!$err) {
                			array_push($list, $embeded);
                    	}
                	} else {
                		array_push($list, $embeded);
                	}
            	}
        	}
    		return $list;
    	} else {
        	return false;
        }
    }

	/**
	 * Возвращаем embeded код
	 * ----------------------
	 * url	-	Видео с ютуба
	 * ----------------------
	 * @return string
	 */
	public function getEmbeded ($embeded) {
		preg_match('#(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12})#x', $embeded, $str);
    	return $str[1];
    }

	/**
	 * Возвращаем видео с ютуба
	 * -------------------------
	 */
	public function getVideo($data_embed) {
    	$list		=	['default.jpg', 'hqdefault.jpg', 'mqdefault.jpg', 'sddefault.jpg', 'maxresdefault.jpg'];
    	if ($data_embed) {
        	foreach ($list as $type) {
            	$width	= getimagesize("https://img.youtube.com/vi/$data_embed/$type")[0];
            	if ($width != 0) {
                	return $data_embed;
                }
            }
        	return false;
        } else {
        	return false;
        }
    	
    }

	/**
	 * Возвращаем видео с ютуба
	 * -------------------------
	 */
	public function getVideoArray($item) {
    	if (count($item) >= 1) {
        	$item		=	$this->getEmbededArray($item);
        	$lsd		=	[];
    		$list		=	['default.jpg', 'hqdefault.jpg', 'mqdefault.jpg', 'sddefault.jpg', 'maxresdefault.jpg'];
        	foreach ($item as $data_embed) {
        		foreach ($list as $type) {
            		$width	= getimagesize("https://img.youtube.com/vi/$data_embed/$type")[0];
            		if ($width != 0) {
                        array_push($lsd, $data_embed);
                		break;
                	}
            	}
        	}
        	return $lsd;
    	}
    	
    }

}