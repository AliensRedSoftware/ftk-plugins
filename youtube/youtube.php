<?php
/**
 * youtube дополнительные функций
 * v1.1
 * Форма настроек-(youtube)
 */
use xlib as x;
use skinmanager as sm;
class youtube{
    /**
     * Настройка формы
     */
    public function getSettings(){
        switch($_COOKIE['__youtube_type']){
            case 'Встроенный':
                $type=sm::p(['content'=>sm::input(['name'=>'type','type'=>'radio','value'=>'Встроенный','checked'=>1])]).
                sm::p(['content'=>sm::input(['name'=>'type','type'=>'radio','value'=>'Стандартный'])]);
            break;
            case 'Стандартный':
                $type=sm::p(['content'=>sm::input(['name'=>'type','type'=>'radio','value'=>'Встроенный'])]).
                sm::p(['content'=>sm::input(['name'=>'type','type'=>'radio','value'=>'Стандартный','checked'=>1])]);
            break;
            default:
                $type=sm::p(['content'=>sm::input(['name'=>'type','type'=>'radio','value'=>'Встроенный'])]).
                sm::p(['content'=>sm::input(['name'=>'type','type'=>'radio','value'=>'Стандартный','checked'=>1])]);
            break;
        }
        switch($_COOKIE['__youtube_quality']){
            case 720:
                $quality=sm::p(['content'=>sm::input(['name'=>'quality','type'=>'radio','value'=>'720','checked'=>1])]).
                sm::p(['content'=>sm::input(['name'=>'quality','type'=>'radio','value'=>'360'])]);
            break;
            case 360:
                $quality=sm::p(['content'=>sm::input(['name'=>'quality','type'=>'radio','value'=>'720'])]).
                sm::p(['content'=>sm::input(['name'=>'quality','type'=>'radio','value'=>'360','checked'=>1])]);
            break;
            default:                                                    $quality=sm::p(['content'=>sm::input(['name'=>'quality','type'=>'radio','value'=>'720','checked'=>1])]).
                sm::p(['content'=>sm::input(['name'=>'quality','type'=>'radio','value'=>'360'])]);
            break;
        }
        $type=sm::p(['content'=>'Плеер (Доступное):']).$type;
        $quality=sm::p(['content'=>'Качество по дефолту (Доступное)']).$quality;
        $submit=sm::input(['type'=>'submit']);
        $form=sm::form(['method'=>'post','id'=>x::RedirectUpdate(),'action'=>x::getPathModules('youtube/cfg.php'),'content'=>$type.$quality.$submit]);
        return sm::modal(['id'=>'youtube','title'=>sm::ico('adjust').' '.'Конфигурация youtube','content'=>$form]);
    }
	/**
	 * Возвращает видео с ютуба
     * data_embed-Ид видео
	 * width-Ширина
	 * height-Высота
	 */
	public function video($data_embed='BcZ8oZAJnhk',$width=400,$height=240){
	    if(x::isJs()){
	        switch($_COOKIE['__youtube_type']){
	            case 'Стандартный':
	                return self::getIframe($data_embed,$width,$height);
	            break;
	            case 'Встроенный':
	                return sm::a(['theme'=>'link','title'=>sm::video(['src'=>self::getURLE($data_embed),'width'=>$width,'height'=>"100%",'preload'=>'none']),'href'=>"https://youtu.be/$data_embed"]);
	            break;
	            default:
		            return self::getIframe($data_embed,$width,$height);
		        break;
		    }
	    }else{
	        return sm::a(['title'=>sm::video(['src'=>self::getURLE($data_embed),'width'=>$width,'height'=>"100%",'preload'=>'none']),'href'=>"https://youtu.be/$data_embed"]);
	    }
	}
	/**
	 * Возвращаем видео в iframe
	 * --------------------------
	 * data_embed	-	Ид видео
	 * width		-	Ширина
	 * height		-	Высота
	 */
	public function getIframe($data_embed='2SXp7RiF9Bs', $width = 400, $height = 240) {
		if(!empty($data_embed)){
		    $height+=20;
    	    $youtube=x::getPathModules('youtube'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'youtube.css');
    		return"<iframe width=\"$width\" height=\"$height\" srcdoc=\"<link rel='stylesheet' text='type/css' href='$youtube'><div class='youtube'><a href='https://www.youtube.com/embed/$data_embed?autoplay=1'><div class='play-button'></div><img src='https://img.youtube.com/vi/$data_embed/default.jpg'></a></div>\" frameborder='0'></iframe>";
    	}else{
    	    return false;
    	}
    }
    /**
     * Возвращаем качество видео
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
            foreach(scandir(__dir__."/other/$q") as $foo){
                if($foo==$data_embed){
                    return file_get_contents(__dir__."/other/$q/$data_embed");
                }
            }
        }
        foreach(scandir(__dir__.'/other') as $quality){
            foreach(scandir(__dir__."/other/$quality") as $foo){
                if($foo==$data_embed){
                    return file_get_contents(__dir__."/other/$quality/$data_embed");
                }
            }
        }
        $CH=curl_init("https://invidious.snopyta.org/api/v1/videos/$data_embed?fields=formatStreams");
        curl_setopt($CH,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($CH,CURLOPT_HTTPHEADER,array(
            'User-Agent: Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)'
        ));
        $b=json_decode(curl_exec($CH));
        $medium=$b->formatStreams[0]->url;
        $hight=$b->formatStreams[1]->url;
        if(!empty($hight)){
            file_put_contents(__dir__.'/other/720/'.$data_embed,$hight);
        }if(!empty($medium)){
            file_put_contents(__dir__.'/other/360/'.$data_embed,$medium);
        }
    }
	/**
	 * Возвращаем embeded код
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
            		if (count($list) >= 1) {
                		foreach ($list as $val) {
                    		if ($val==$embeded) {
                        		$err=true;
                        		break;
                        	} else {
                        		$err=false;
                        	}
                    	}
                		if(!$err){
                			array_push($list, $embeded);
                    	}
                	}else{
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
	public function getEmbeded($embeded){
		preg_match('#(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12})#x',$embeded,$str);
    	return$str[1];
    }
	/**
	 * Возвращаем видео с ютуба
	 * -------------------------
	 */
	public function getVideo($data_embed){
    	$list=['default.jpg','hqdefault.jpg','mqdefault.jpg','sddefault.jpg','maxresdefault.jpg'];
    	if($data_embed){
        	foreach($list as $type){
            	$width=getimagesize("https://img.youtube.com/vi/$data_embed/$type")[0];
            	if($width!=0){
                	return$data_embed;
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
    	if(count($item)>0){
        	$item=self::getEmbededArray($item);
        	$lsd=[];
    		$list=['default.jpg','hqdefault.jpg','mqdefault.jpg','sddefault.jpg','maxresdefault.jpg'];
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
}
