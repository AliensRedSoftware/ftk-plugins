<?php
/**
 * Упрощенный текстовый редактор
 * @ver 1.0
 */
use xlib as x;
use skinmanager as sm;
use youtube as yt;
use xprivate as xp;
class syntax{
	public $index;
	/**
	 * Возвращаем в виде html текста
	 * -----------------------------
	 * text	-	Текст
	 */
	public function getHtml($text = 'Привет /s Как дела ммм ? / /b Привет / dsf /b test /'){
		$split = explode("\n", $text);
		foreach ($split as $spl) {
			$arruuidUrls = [];
			$arruuidYoutube = [];
			$urls=self::getUrl($spl);
			/*foreach ($urls['other'] as $url) {
				if ($url != $prevurl) {
					$uuid = $this->uuidv4();
				}
				$spl = str_replace($url, "[$uuid]", $spl);
				array_push($arruuidUrls,"[$uuid]");
				$prevurl = $url;
			}*/
			foreach ($urls['img'] as $url) {
				if ($url != $prevurl) {
					$uuid = x::uuidv4();
				}
				$spl = str_replace($url, "[$uuid]", $spl);
				array_push($arruuidUrls,"[$uuid]");
				$prevurl = $url;
			}
			foreach ($urls['youtube'] as $url){
				if ($url!=$prevurl){
					$uuid=x::uuidv4();
				}
				$spl=str_replace($url,"[$uuid]",$spl);
				array_push($arruuidYoutube,"[$uuid]");
				$prevurl=$url;
			}
			$i = -1;
			$syn = x::mb_str_split($spl);
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
									$src	=	x::getPathModules("xmessage/sticker/$uuid/$name");
									$img = "<img src=\"$src\" style=\"pointer-events:none;\">";
									$val	=	x::str_replace("<c>=$name=$uuid", $img, $val, 1);
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
			if($this->index&&!$index){
				$index = $this->index;
				$val = "<$index>$val</$index>";
			}
			$ch = -1;
			foreach($arruuidUrls as $uuid){
				$val=str_replace($uuid,NULL, $val);
			}
			foreach($arruuidYoutube as $uuid){$val=str_replace($uuid,NULL,$val);}
			$val=trim($val);
		}
		if ($checkval == true) {
			$index=$this->index;
			if($index){
				$val.="</$index>";
			}
		}
		return trim($val);
	}
	/**
	 * Возвращает отформатированный текст
	 */
	public function getText($text = 'text'){
		$descriptionArray=explode("\n",trim($text));
		foreach ($descriptionArray as $text){
			$output.="\n";
			$output.=self::getHtml($text);
			if (trim($output)!=null) {
				$output.='</br>';
			}
		}
		return $output;
	}
	/**
	 * Возвращает отформатированный текст и получает только ссылки
	 */
	public function getUrl($text='text'){
		$skinmanager=new skinmanager();
    	$outputs=[];
		$xlib=new xlib();
		$ls=explode("\n",$text);
		foreach($ls as $ass){
			$descriptionArray=explode(" ", $ass);
			foreach($descriptionArray as $value){
				preg_match('#(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12})#x', $value, $str);
				if(!$str[1]){
					preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',$value,$url);
					$url=strip_tags(trim($url[0]));
					if($url){
						switch($xlib->getExtension($url)){
							case 'jpg':
								$outputs['img'][].=$url;
							break;
							case 'jpeg':
								$outputs['img'][].=$url;
							break;
							case 'png':
								$outputs['img'][].=$url;
							break;
							default:
								foreach($outputs['other'] as $k){
									if($k==$url){
										break 2;
									}
								}
								$outputs['other'][].=$url;
							break;
						}
					}
				}else{
					$outputs['youtube'][].=trim($str[0]);
				}
			}
		}
		return $outputs;
	}
	/**
	 * Возвращаем готовую форму в виде массива
	 * -------------------------
	 */
	 public function getForm($index=0,$time='2019-07-12=>14:00:46',$idChat='@1562940046419
',$name='Неизвестный',$text,$src_youtube,$src_img,$theme){
	    $skinmanager=new skinmanager();
     	$youtube=new youtube();
     	if($_SERVER['REQUEST_METHOD']=='POST'&&$this->geturi(7)=='saveSettings.php'){
     		if($_REQUEST['number']){
        		$title.="[$index]->";
        	}
            if($_REQUEST['date']){
        		$title.="[$time]->";
        	}
            if($_REQUEST['idMessage']){
        		$title.="[$idChat]->";
        	}
        }else{
        	if($_COOKIE['__XMSG_NUMBER']){
            	$title.="[$index]->";
            }
        	if($_COOKIE['__XMSG_DATE']){
            	$title.="[$time]->";
            }
            if($_COOKIE['__XMSG_ID']){
        		$title.="[$idChat]->";
        	}
        }
		$title.="$name";
     	foreach(unserialize($src_img) as $img){
			$srcimg.=$skinmanager->border([
				'css'=>['margin'=>'5px','width'=>'35%','height'=>'0%'],
				'content'=>$skinmanager->lightbox(['src'=>$img,'stretch'=>true])
			]);
		}
		//--->youtube (ютуб видео)
     	foreach(unserialize($src_youtube) as $val){
     		if($this->isJs()){
        		$other.=$youtube->getIframe($val);
        	}else{
        		$other.=$youtube->video($val);
        	}
        }
        //--->Текста
		if(trim($text)){
        	//$text = $this->padding(['bottom' => '5px', 'content' => $text]);
			if(trim($other)!=null||$srcimg){
				$content=$text."<div style=\"display:flex;flex-wrap:wrap;\">$other $srcimg</div>";
			}else{
				$content=$text;
			}
		}else{
			$content = "<div style=\"display:flex;flex-wrap:wrap;\">$other $srcimg</div>";
		}
		return $skinmanager->panel([
			'title' => $title,
			'theme' => $theme,
			'content' => $content
		]);
	 }
	/**
	 * Возвращаем готовую форму в виде массива
	 * -------------------------
	 */
	 public function getFormToArray($result,$theme){
	 	$arr=[];
	 	$DATA=[];
	 	while($R=mysqli_fetch_array($result)){
	 		$i++;
	 		unset($title);
	 		unset($content);
	 		$ava=sm::img(['src'=>xp::getCacheSmallAva($R['__xprivate_auth']),'css'=>['width'=>'50px','border-radius'=>'100px','pointer-events'=>'none']]);
	 		$account=xp::getViewAccount($R['__xprivate_auth']);
	 		$name=sm::a(['title'=>xp::getDataId($R['__xprivate_auth'])['name'],'href'=>"#$account",'modal'=>$account]);
	 		$tweak=x::div(['content'=>$name]).$ava;
			$txt=$R['text'];
			$src_img=$R['img'];
			$src_youtube=$R['youtube'];
			$time=explode('(',$R['time']);
			$idMsg=substr(($time[1]),0,-1);
			//$srcCount=-1;
			$time=$time[0];
			$tweak=x::div(['content'=>x::div(['content'=>$time]).$name]).$ava;
			unset($srcimg);
			unset($other);
	    	if(!empty($_COOKIE['__XMSG_NUMBER'])){
	        	$title.="[$i]->";
	        }
	    	if(!empty($_COOKIE['__XMSG_DATE'])){
	        	$title.="[$time]->";
	        }
	        if(!empty($_COOKIE['__XMSG_ID'])){
	    		$title.="[$idMsg]->";
	    	}
			$title.=x::div(['content'=>$tweak,'id'=>$idMsg]);
			//--->Картинки (gif,jpg,jpeg,png)
			$src_img=unserialize($src_img);
			$srcCount+=count($src_img);
		 	foreach($src_img as $img){
		 		if($i==mysqli_num_rows($result)){
		 			$src_count=$srcCount;
		 		}
				$srcimg.=sm::border([
					'css'=>['margin'=>'5px','height'=>'0%','min-width'=>'64px','max-width'=>'max-content','max-width'=>'-moz-max-content'],
					'content'=>sm::lightbox(['src'=>$img,'stretch'=>true,'max'=>$src_count])
				]);
			}
			//--->youtube (ютуб видео)
		 	foreach(unserialize($src_youtube) as $val){
		    	$other.=yt::video($val);
		    }
		    //--->Текста
		    foreach(self::getUrl($txt)['other'] as $url){
	    		$url=strip_tags($url);
	    		$txt=str_replace($url,sm::a(['href'=>$url,'title'=>$url]),$txt);
		    }
			$content.=$txt;
			//--->Контент
			if(trim($other)!=null||$srcimg){
				$content.=xlib::div(['css'=>['display'=>'table'],'content'=>$other.$srcimg]);
			}
			$DATA+=[$title=>$content];
		}
		return sm::panelToArray(['data'=>$DATA]);
	 }
}
