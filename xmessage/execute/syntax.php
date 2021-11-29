<?php
/**
 * Упрощенный текстовый редактор
 * @ver 1.435
 */
use xlib as x;
use skinmanager as sm;
use youtube as yt;
use xprivate as xp;
use xmotion as moja;
class syntax{
	public $index;
	/**
	 * Возвращаем в виде html текста
	 * -----------------------------
	 * text	-	Текст
	 */
	public function getHtml($txt = 'Привет /s Как дела ммм ? / /b Привет / dsf /b test /'){
		$arruuidUrls = [];
		$arruuidYoutube = [];
		$urls=self::getUrl($txt);
		//img URL
		foreach($urls['img'] as $url) {
			if($url != $prevurl){
				$uuid = x::uuidv4();
			}
			$txt=str_replace($url,"[$uuid]",$txt);
			array_push($arruuidUrls,"[$uuid]");
			$prevurl=$url;
		}
		//youtube URL
		foreach($urls['youtube'] as $url){
			if($url!=$prevurl){
				$uuid=x::uuidv4();
			}
			$txt=str_replace($url,"[$uuid]",$txt);
			array_push($arruuidYoutube,"[$uuid]");
			$prevurl=$url;
		}
		//other URL
		foreach($urls['other'] as $url){
			if($url!=$prevurl){
				$uuid=x::uuidv4();
			}
			$txt=str_replace($url,"[$uuid]",$txt);
			$arruuidUrls[$uuid]=$url;
			$prevurl=$url;
		}
		//SYNTAX
		foreach(explode("\n",$txt) as $spl){
			$i = -1;
			$syn = mb_str_split($spl);
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
			foreach($arruuidUrls as $uuid=>$url){
				$val=str_replace("[$uuid]",$url,$val);
			}
			foreach($arruuidYoutube as $uuid){
				$val=str_replace($uuid,NULL,$val);
			}
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
		$i=0;
		foreach($descriptionArray as $txt){
			$i++;
			$output.="\n";
			$output.=self::getHtml($txt);
			if(count($descriptionArray)!=$i){
				if (!empty(trim($output))) {
					$output.='</br>';
				}
			}
		}
		return $output;
	}
	/**
	 * Возвращает отформатированный текст и получает только ссылки
	 */
	public function getUrl($text='text'){
    	$outputs=[];
		foreach(explode("\n",$text) as $ls){
			$descriptionArray=explode(" ", $ls);
			foreach($descriptionArray as $value){
				$url=parse_url($value);
				if(!empty($url['host'])){
					//parse host
					if($url['query']){
						$url['path'].='?'.$url['query'];
					}
					switch($url['host']){
						//Ютуб
						case 'youtu.be':
							$outputs['youtube'][].=$url['scheme'].'://'.$url['host'].$url['path'];
						break;
						case 'www.youtube.com':
							$outputs['youtube'][].=$url['scheme'].'://'.$url['host'].$url['path'];
						break;
						//Прочие
						default:
							//parse ext
							switch(x::getExtension($url['path'])){
								case 'jpg':
									$outputs['img'][].=$url['scheme'].'://'.$url['host'].$url['path'];
								break;
								case 'jpeg':
									$outputs['img'][].=$url['scheme'].'://'.$url['host'].$url['path'];
								break;
								case 'png':
									$outputs['img'][].=$url['scheme'].'://'.$url['host'].$url['path'];
								break;
								case 'gif':
									$outputs['img'][].=$url['scheme'].'://'.$url['host'].$url['path'];
								break;
								case 'webp':
									$outputs['img'][].=$url['scheme'].'://'.$url['host'].$url['path'];
								break;
								case 'html_':
									$outputs['other'][].=$url['scheme'].'://'.$url['host'].substr($url['path'],0,-1);
								break;
							}
							$outputs['other'][].=$url['scheme'].'://'.$url['host'].$url['path'];
						break;
					}
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
	 	$l='';
	 	while($R=mysqli_fetch_array($result)){
	 		$i++;
	 		unset($title);
	 		unset($content);
	 		$time=explode('(',$R['time']);
	 		$idMsg=substr(($time[1]),0,-1);
	 		$time=$time[0];
	 		//Дополнительные модули
	 		if(x::isModule('xprivate',false)){
	 			unset($SS);
				$ava=sm::img(['src'=>xp::getCacheSmallAva($R['__xprivate_auth']),'css'=>['width'=>'50px','border-radius'=>'100px','pointer-events'=>'none']]);
				$account=xp::getViewAccount($R['__xprivate_auth']);
				//data...
				$data=xp::getDataId($R['__xprivate_auth']);
				//Метка SS
				if($data['root']){
					$SS=' '.sm::badge(['txt'=>'SS']);
				}
				//Метка времени
				$time=' '.sm::badge(['txt'=>$time]);
				//item
				$item['item']=[];
				$item['item']+=['Посмотреть'=>['href'=>"#$account",'modal'=>$account]];
				$name=sm::dropdown([$data['private']['name'].$SS.$time=>$item]);
			}else{
				$name='Неизвестный';
			}
			$txt=$R['text'];
			$src_img=$R['img'];
			$mojas=$R['mojas'];
			$src_youtube=$R['youtube'];
			//$srcCount=-1;
			$tweak=$ava.' | '.$name;
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
		    foreach(self::getUrl($txt) as $type=>$urls){
		    	foreach($urls as $url){
					switch($type){
						case 'youtube':
							$url=strip_tags($url);
							$txt=str_replace($url,sm::a(['href'=>$url,'title'=>$url]),$txt);
						break;
						default:
							$url=strip_tags($url);
							$txt=str_replace($url,sm::a(['href'=>$url,'title'=>$url]),$txt);
						break;
					}
					break;
				}
		    }
			$content.=$txt;
			//--->Контент
			if(trim($other)!=null||$srcimg){
				$content.=x::div(['css'=>['display'=>'table'],'content'=>$other.$srcimg]);
			}
			//--->xmotion (Умные эмоций)
			$mojas=unserialize($mojas);
			foreach($mojas as $moja){
				$content.=moja::getShowBox($moja);
			}
			$DATA+=[$title=>$content];
		}
		return sm::panelToArray(['stretch'=>false,'data'=>$DATA]);
	 }
}
