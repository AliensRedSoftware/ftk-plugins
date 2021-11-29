<?php
/**
 * youtube дополнительные функций
 * v1.2
 * Форма настроек-(youtube)
 */
use xlib as x;
use skinmanager as sm;
class youtube{

    /**
     * Настройка формы
     */
    public function getSettings(){
        $tList=[];
		$tList+=['Встроенный'=>['href'=>$action]];
		$tList+=['Стандартный'=>['href'=>$action]];
        switch($_COOKIE['__youtube_type']){
            case 'Встроенный':
                $type='Встроенный';
            break;
            case 'Стандартный':
                $type='Стандартный';
            break;
            default:
                $type='Встроенный';
            break;
        }
        $type=sm::p(['content'=>sm::combobox(['id'=>'type','name'=>'type','selected'=>$type,$tList])]);
        $qList=[];
        $qList+=[144=>['href'=>$action]];
		$qList+=[360=>['href'=>$action]];
		$qList+=[720=>['href'=>$action]];
        switch($_COOKIE['__youtube_quality']){
            case 720:
                $quality=720;
            break;
            case 360:
                $quality=360;
            break;
            case 144:
                $quality=144;
            break;
            default:
                $quality=720;
            break;
        }
        $quality=sm::p(['content'=>sm::combobox(['id'=>'quality','name'=>'quality','selected'=>$quality,$qList])]);
        $type=sm::p(['content'=>'Плеер (Доступное)']).$type;
        $quality=sm::p(['content'=>'Качество по дефолту (Доступное)']).$quality;
        $submit=sm::input(['type'=>'submit']);
        $form=sm::form(['method'=>'post','id'=>x::RedirectUpdate(),'action'=>x::getPathModules('youtube'.DIRECTORY_SEPARATOR.'cfg.php'),'content'=>$type.$quality.$submit]);
        return sm::modal(['id'=>'youtube','title'=>sm::ico('adjust').' '.'Конфигурация youtube','content'=>$form]);
    }

	/**
	 * Возвращает видео с ютуба
     * data_embed-Ид видео
	 * width-Ширина
	 * height-Высота
	 */
	public function video($data_embed='BcZ8oZAJnhk',$width=370,$height=300){
	    if(x::isJs()){
	        switch($_COOKIE['__youtube_type']){
	            case 'Стандартный':
	                return self::getIframe($data_embed,$width,$height);
	            break;
	            case 'Встроенный':
	            	$arr=self::getURLEToArray($data_embed);
	            	if(!empty($arr)){
	            		foreach($arr as $name=>$url){
	                		$list.=' '.sm::a(['theme'=>'link','title'=>'Видео '.$name.'p','href'=>$url]);
	                	}
	            		$src=self::getURLE($data_embed);
	                }else{
	                	$src=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'404.mp4');
	                }
	            	return sm::video(['src'=>$src,'width'=>$width,'height'=>"100%",'preload'=>'none']).sm::p(['content'=>sm::a(['theme'=>'link','title'=>'Прямая ссылка','href'=>"https://youtu.be/$data_embed"]).$list]);
	            break;
	            default:
		            return self::getIframe($data_embed,$width,$height);
		        break;
		    }
	    }else{
			$arr=self::getURLEToArray($data_embed);
			if(!empty($arr)){
				foreach($arr as $name=>$url){
					$list.=' '.sm::a(['theme'=>'link','title'=>'Видео '.$name.'p','href'=>$url]);
				}
				$src=self::getURLE($data_embed);
			}else{
				$src=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'404.mp4');
			}
	        return sm::video(['src'=>$src,'width'=>$width,'height'=>"100%",'preload'=>'none']).sm::p(['content'=>sm::a(['theme'=>'link','title'=>'Прямая ссылка','href'=>"https://youtu.be/$data_embed"]).$list]);
	    }
	}

	/**
	 * Возвращаем видео в iframe
	 * --------------------------
	 * data_embed	-	Ид видео
	 * width		-	Ширина
	 * hight		-	Высота
	 */
	public function getIframe($data_embed='2SXp7RiF9Bs',$width=370,$hight=300) {
		if(!empty($data_embed)){
    	    $youtube=x::getPathModules('youtube'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'youtube.css');
    		return"<iframe style=\"background-image: url('https://img.youtube.com/vi/$data_embed/default.jpg');background-size: contain;background-repeat: round;box-shadow: 0 0 5px;\" width=\"$width\" height=\"$hight\" srcdoc=\"<link rel='stylesheet' text='type/css' href='$youtube'><div class='youtube'><a href='https://www.youtube.com/embed/$data_embed?autoplay=1'><div class='play-button'></div></a></div>\" frameborder='0'></iframe>";
    	}else{
    	    return false;
    	}
    }

    /**
     * Возвращаем качество видео
     * @return string
     */
    public function getQuality(){
         if(empty($_COOKIE['__youtube_quality'])){
             return false;
         }else{
             return $_COOKIE['__youtube_quality'];
         }
    }

    /**
     * Возвращаем прямую ссылку
     * --------------------------
     * data_embed	-	Ид видео
     */
    public function getURLE($data_embed='Opj781Vdctw'){
        $q=self::getQuality();
        if($q){
            foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$q) as $foo){
                if($foo==$data_embed){
                    return file_get_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$q.DIRECTORY_SEPARATOR.$data_embed);
                }
            }
        }
        foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other') as $quality){
            foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality) as $foo){
                if($foo==$data_embed){
                    return file_get_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality.DIRECTORY_SEPARATOR.$data_embed);
                }
            }
        }
        $CH=curl_init("https://invidious.snopyta.org/api/v1/videos/$data_embed?fields=formatStreams");
        curl_setopt($CH,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($CH,CURLOPT_HTTPHEADER,array('User-Agent: Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)'));
        //proxy (tor)
		curl_setopt($CH,CURLOPT_PROXY,'127.0.0.1');
		curl_setopt($CH,CURLOPT_PROXYPORT,'9050');
		curl_setopt($CH,CURLOPT_PROXYTYPE,CURLPROXY_SOCKS5_HOSTNAME);
		//video getting
        $b=json_decode(curl_exec($CH));
        foreach($b->formatStreams as $date){
            $df=self::getQualityFormat($date->quality);
            mkdir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$df);
            file_put_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$df.DIRECTORY_SEPARATOR.$data_embed,$date->url);
        }
        //return
        if($q){
            foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$q) as $foo){
                if($foo==$data_embed){
                    return file_get_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$q.DIRECTORY_SEPARATOR.$data_embed);
                }
            }
        }
        foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other') as $quality){
            foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality) as $foo){
                if($foo==$data_embed){
                    return file_get_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality.DIRECTORY_SEPARATOR.$data_embed);
                }
            }
        }
        //404 code response...
        return false;
    }

    /**
     * Возвращаем доступные ссылки в виде массива
     * --------------------------
     * data_embed	-	Ид видео
     */
    public function getURLEToArray($data_embed='Opj781Vdctw'){
    	$arr=[];
        foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other') as $quality){
            foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality) as $foo){
                if($foo==$data_embed){
                    $arr[$quality]=file_get_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality.DIRECTORY_SEPARATOR.$data_embed);
                }
            }
        }
        if(!empty($arr)){
        	return $arr;
        }
        $CH=curl_init("https://invidious.snopyta.org/api/v1/videos/$data_embed?fields=formatStreams");
        curl_setopt($CH,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($CH,CURLOPT_HTTPHEADER,array('User-Agent: Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)'));
        //proxy (tor)
		curl_setopt($CH,CURLOPT_PROXY,'127.0.0.1');
		curl_setopt($CH,CURLOPT_PROXYPORT,'9050');
		curl_setopt($CH,CURLOPT_PROXYTYPE,CURLPROXY_SOCKS5_HOSTNAME);
		//video getting
        $b=json_decode(curl_exec($CH));
        foreach($b->formatStreams as $date){
            $df=self::getQualityFormat($date->quality);
            mkdir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$df);
            file_put_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$df.DIRECTORY_SEPARATOR.$data_embed,$date->url);
        }
        foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other') as $quality){
            foreach(x::scandir(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality) as $foo){
                if($foo==$data_embed){
                	$arr[$quality]=file_get_contents(__dir__.DIRECTORY_SEPARATOR.'other'.DIRECTORY_SEPARATOR.$quality.DIRECTORY_SEPARATOR.$data_embed);
                }
            }
        }
        if(!empty($arr)){
        	return $arr;
        }
        return false;
    }

	/**
	 * Возвращаем embeded код в виде массива
	 * ----------------------
	 * url	-	Видео с ютуба
	 * ----------------------
	 * @return Array
	 */
	public function getEmbededArray($item){
    	if($item){
    		$list=[];
    		foreach($item as $embeded){
        		$embeded=self::getEmbeded($embeded);
        		if($embeded){
            		if(count($list)>=1){
                		foreach($list as $val){
                    		if($val==$embeded){
                        		$err=true;
                        		break;
                        	}else{
                        		$err=false;
                        	}
                    	}
                		if(!$err){
                			array_push($list,$embeded);
                    	}
                	}else{
                		array_push($list,$embeded);
                	}
            	}
        	}
    		return $list;
    	}else{
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
	public function getEmbeded($embeded){
		preg_match('#(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12})#x',$embeded,$str);
    	return $str[1];
    }

	/**
	 * Возвращаем видео с ютуба
	 * -------------------------
	 */
	public function getVideo($data_embed){
    	$list=['default.jpg', 'hqdefault.jpg', 'mqdefault.jpg', 'sddefault.jpg', 'maxresdefault.jpg'];
    	if($data_embed){
        	foreach($list as $type){
            	$width=getimagesize("https://img.youtube.com/vi/$data_embed/$type")[0];
            	if($width!=0){
                	return$data_embed;
                }
            }
        	return false;
        }else{
        	return false;
        }
    }

	/**
	 * Возвращаем видео с ютуба
	 * -------------------------
	 */
	public function getVideoArray($item){
    	if(count($item)>0){
        	$item=self::getEmbededArray($item);
        	$lsd=[];
    		$list=['default.jpg', 'hqdefault.jpg', 'mqdefault.jpg', 'sddefault.jpg', 'maxresdefault.jpg'];
        	foreach($item as $data_embed){
        		foreach($list as $type){
            		$width=getimagesize("https://img.youtube.com/vi/$data_embed/$type")[0];
            		if($width!=0){
                        array_push($lsd, $data_embed);
                		break;
                	}
            	}
        	}
        	return $lsd;
    	}else{
    	    return false;
    	}
    }

    /**
     * @return string
     */
    public function getQualityFormat($q){
        switch($q){
            case 'small': // 144p
                return 144;
            break;
            case 'medium': // 360p
                return 360;
            break;
            case 'hd720': // 720p
                return 720;
            break;
        }
    }
}
