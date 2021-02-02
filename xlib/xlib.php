<?php

/**
 * Стандартный модуль для создание сайта
 * v2.35
 */
class xlib{
    /**
     * Устанавливает загаловок
     * $title - Загаловок
     * @return string
     */
    public function setTitle($title){echo"<title>$title</title>";}
    /**
     * Добавление css style
     * $style - стиль код css
     */
    public function style($style){echo"<style>$style</style>";}
    /**
     * Добавление js скрипта
     * $js - код js
     */
    public function js($js){echo"<script defer async>$js</script>";}
    /**
     * Установка utf8 кодировка
     */
    public function utf8(){echo"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";}
    /**
     * Установка описание сайта
     */
    public function description($text){echo"<meta name=\"description\" content=\"$text\">";}
    /**
     * Установка тегов сайта
     */
    public function tag($tag){echo"<meta name=\"Keywords\" content=\"$tag\">";}
    /**
     * Выполняет js код
     */
    public function script($code){echo"<script>$code</script>";}
    /**
     * Возвращает путь к libphp
     */
    public function path($file){return mb_substr(self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.self::getLibPath().DIRECTORY_SEPARATOR.$file.'.php',1);}
    /**
     * Возвращает рандомный массив
     * $iteam - Массив
     * @return string
     */
    public function getrand(array $iteam){return $iteam[rand(0,count($iteam)-1)];}
    /**
     * Возвращает z кординату
     * Возможно нужна чтобы элемент был сверху :)
     * $content - Контент
     * $value - расстояние
     * @return string
     */
    public function z($content=null,$value=5){return "<div style='z-index: $value;position: relative;'>$content</div>";}
    /**
     * Возвращает анимацию
     * $content - Контент
     * $animate - Анимация название
     * @return string
     */
    public function anim($content=null,$animate){return "<div class='animated $animate'>$content</div>";}
    /**
     * Возвращает абсалютный путь к модулю
     */
    public function getPathModules($path){return self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$path;}
    /**
     * Возвращаем ссылка это или нет
     * url-Ссылка
     */
    public function isUrl($url){return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i',$url);}
    /**
     * Возвращает массив с модулями
     */
    public function getModules(){
		$script=explode(DIRECTORY_SEPARATOR,$_SERVER['PHP_SELF']);
		if($script[1]=='theme'){
			$theme=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$script[1].DIRECTORY_SEPARATOR.$script[2].DIRECTORY_SEPARATOR.$script[3].DIRECTORY_SEPARATOR.$script[4];
		}else{
			$theme='.'.self::getPathModules(null);
		}
        $modules=scandir($theme);
        $output=[];
        foreach($modules as $value){
            if($value!='.'&&$value!='..'){
                array_push($output,$value);
            }
        }
        return $output;
    }
	public function import($class){
		$dir=constant('modules');
		foreach($dir as $value){
			if($value==$class){
				return 'yes yes';
			}
		}
	}
    /**
     * Возвращает путь выбранной темы
     * @return string
     */
    public function getTheme(){
        require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'options.php';
        $options=new options();
        return DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$options->theme.DIRECTORY_SEPARATOR;
    }
    /**
     * Возвращает платформу
     * @return string
     */
    public function getPlatform(){
        require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'options.php';
        $options=new options();
        $browser=$_SERVER['HTTP_USER_AGENT'];
        if ($options->platform=='auto'){
            if(preg_match('/android/i',$browser)){
                $platform='android';
            }else{
                $platform='linux';
            }
        }else{
            $platform=$options->platform;
        }
        return $platform;
    }
    /**
     * Автодобавление всех стилей из папки
     * $folder_css - папка с css стилей
     */
    public function loader_css($folder_css='css'){
        $path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.$folder_css.DIRECTORY_SEPARATOR;
        $cssfile=scandir('.'.$path);
        foreach($cssfile as $css){
            if ($css!='.'&&$css!='..'){
                echo "<link rel=\"stylesheet\" text=\"type/css\" href=\"$path$css\">";
            }
        }
    }
    /**
     * Автодобавление всех js из папки
     * $folder_js - папка с js скриптами
     */
    public function loader_js($folder_js='js'){
		if($_COOKIE['__SKINMANAGER_SKIN']&&$_COOKIE['__SKINMANAGER_SKIN']!='basic'){
			$path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.$folder_js.DIRECTORY_SEPARATOR;
			$jsfile=scandir('.'.$path);
			foreach($jsfile as $js){
				if ($js!='.'&&$js!='..'){
					echo "<script type=\"text/javascript\" src=\"$path$js\"></script>";
				}
			}
		}
    }
	/**
	 * Возвращает блок стиля
	 * ---------------------
	 * opt - Основные стили (Кастомный)
	 */
	public function css($opt){
		unset($GLOBALS['style']);
		foreach($opt as $val=>$key){
			$i++;
			$style.="$val:$key";
			if(count($opt)+1!=$i){
				$style.=';';
			}
		}
		if($style){
			$css="style=\"$style\"";
		}
		$GLOBALS['style']=$style;
		return $css;
	}
	/**
	 * Добавляет к основному блоку стиль
	 * ---------------------------------
	 * opt - Основые стили (Кастомный)
	 */
	public function addCss($opt){
		foreach($opt as $val=>$key){
			$i++;
			$style.="$val:$key";
			if (count($opt)+1!=$i){
				$style.=';';
			}
		}
		$GLOBALS['style'].=$style;
	}
	/**
	 * Возвращает геты данные в виде массива из uri
	 */
	public function getDataToArray(){
	    $out=[];
		$datas=explode('?',$_SERVER['REQUEST_URI']);
		foreach(explode('&',$datas[1]) as $data){
		    $v=explode('=',$data);
		    $out[$v[0]]=$v[1];
		}
		return $out;
	}
	/**
	 * Возвращает геты данные в виде строки из uri
	 */
	public function getData(){
	    $out='';
		$datas=explode('?',$_SERVER['REQUEST_URI']);
		foreach(explode('&',$datas[1]) as $data){
		    $out.=$data;
		}
		return $out;
	}
	/**
	 * Возвращаем стиль
	 * ----------------
	 * @return string
	 */
	public function getCss(){
		$sty=$GLOBALS['style'];
		$style.="style=\"$sty\" ";
		return $style;
	}
    /**
     * Добавление js из папки
     * js - Массив файлов js
     * folder_js - папка где лежат js
     */
    public function add_js(array $js,$folder_js='js'){
        $i=[];
		foreach($js as $js){
		    $js=trim($js);
		    if($folder_js=='js'){
			    $path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.$folder_js.DIRECTORY_SEPARATOR.$js;
		    }else{
		        if(self::startWith('.',$folder_js)){
		            $path=self::getPathModules($GLOBALS['endModule'].substr($folder_js,1).DIRECTORY_SEPARATOR.$js);
		        }else{
		            if(!self::endsWith(DIRECTORY_SEPARATOR,$folder_js)){
		                $folder_js.=DIRECTORY_SEPARATOR;
		            }
			        $path=$folder_js.$js;
			    }
		    }
		    if(self::getExtension($js)=='js'){
		        echo "<script type=\"text/javascript\" src=\"$path\"></script>";
		    }
		}
    }
    /**
     * Добавление css из папки
     * $css - Массив файлов css
     * $folder_css - папка где лежат css
     */
    public function add_css(array $css,$folder_css='css'){
        foreach($css as $css){
			if ($folder_css=='css'){
				$path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.$folder_css.DIRECTORY_SEPARATOR.$css;
			}else{
			    if(self::startWith('.',$folder_css)){
			        $path=self::getPathModules($GLOBALS['endModule'].substr($folder_css,1).DIRECTORY_SEPARATOR.$css);
			    }else{
				    $path=$folder_css.$css;
				}
			}
			if(self::getExtension($css)=='css'){
                echo "<link rel=\"stylesheet\" text=\"type/css\" href=\"$path\">";
            }
        }
    }
    /**
     * Возвращаем mysql подключение
	 * ----------------------------
     * @return sql
     */
    public function getmysql(){
        require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'mysql.php';
        $mysql=new mysql();
        $sql=mysqli_connect($mysql->ip,$mysql->user,$mysql->password,$mysql->database);
        if(!$sql){
			die("Ошибка подключение mysql :(");
        }else{
            return $sql;
        }
    }
    /**
     * Возвращает подключение php
     * file - массив страниц
     */
    public function req(array $file){
        foreach ($file as $val){
            require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.self::path($val);
        }
    }
    /**
     * Возвращает путь php скрипты либы
     * @return string
     */
    public function getLibPath(){
        require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'options.php';
        $options=new options();
        return $options->libphp;
    }
	/**
	 * Возвращаем svg
	 * --------------
	 */
	public function svg($svg,$width=false,$height=false){
    	if($width){
        	$style.="width=\"$width\" ";
        }
    	if($height){
        	$style.="height=\"$height\" ";
        }
    	$svg="<svg viewBox=\"-70 0 1214.4733 1081.6177\" $style>$svg</svg>";
    	return $svg;
    }
    /**
     * Установка ico сайта
     * $ico - путь к иконки
     */
    public function ico($ico){
        $path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR;
        echo "<link rel='shortcut icon' type='image/png' href=$path$ico>";
    }
    /**
     * Возвращает имя ссылки
     * $value - ступень обозночение
     * @return string
     */
    public function geturi($value=false){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if($_GET['redirect']){
			    $redirect=$_GET['redirect'];
		    }elseif($_POST['redirect']){
			    $redirect=$_POST['redirect'];
		    }else{
			    $redirect=$_SERVER['REQUEST_URI'];
		    }
		    $uri=$redirect;
        }else{
            $uri=$_SERVER['REQUEST_URI'];
        }
        if($uri=='/'){
		    $uri='/index';
		}
		return urldecode($uri);
    }
    /**
     * Обновление редиректа ссылки
     * form-форма
     * url-редирект адрес
     */
    public function RedirectUpdate($form=false,$url=false){
        if($_POST['redirect']){
		    $redirect=$_POST['redirect'];
	    }elseif($_GET['redirect']){
		    $redirect=$_GET['redirect'];
	    }else{
		    $redirect=$_SERVER['REQUEST_URI'];
	    }
	    if(!$form){
	        $form=self::uuidv4();
	    }
        echo skinmanager::input(['name'=>'redirect','type'=>'hidden','value'=>$redirect,'form'=>$form]);
        return $form;
    }
    /**
     * Загрузка страницы сайта
     * url-Ссылка загрузка страницы
     */
    public function LoadWebUrl($url=false){
        $headers=[];
        foreach(getallheaders() as $name => $value){
            $value=str_replace('PHPSESSID','session_id',$value);
            array_push($headers,"$name: $value");
        }
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            if(!self::isUrl($url)){
                $url=self::geturi();
                $execute=explode('?',$url);
                if(!empty($execute[1])){
                    $url=str_replace("?".$execute[1],NULL,$url);
                }
                $url=$_SERVER['DOCUMENT_ROOT'].self::getTheme()."uri$url.php";
                if(!is_file($url)){
                    $url=$_SERVER['DOCUMENT_ROOT'].self::getTheme()."uri/404.php";
                }
                require_once $url;
                $_SERVER['REQUEST_URI']="?".$execute[1];
            	$ftk=new ftk();
            	//SuperBox
            	echo $GLOBALS['__SUPER_BOX'];
            }elseif(self::isUrl($url)){
                $CH=curl_init($url);
                curl_setopt($CH,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($CH,CURLOPT_HTTPHEADER,$headers);
                $html=curl_exec($CH);
                curl_close($CH);
            }

        }elseif($_SERVER["REQUEST_METHOD"]=='GET'){
            if(!self::isUrl($url)){
                if(!empty($url)){
                    $url="127.0.0.1$url";
                    $CH=curl_init($url);
                    curl_setopt($CH,CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($CH,CURLOPT_HTTPHEADER,$headers);
                    $html=curl_exec($CH);
                    curl_close($CH);
                }
            }else{
                $CH=curl_init($url);
                curl_setopt($CH,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($CH,CURLOPT_HTTPHEADER,$headers);
                $html=curl_exec($CH);
                curl_close($CH);
            }
        }
        echo $html;
    }
    /**
     * Проверяет есть папка или нету если нету то создает
     */
    public function isDir($dir){
        if(is_dir($dir)==false){
            mkdir($dir,0777);
        }
    }
    /**
     * Проверка есть такой символ или нету
     * Возвращает true если есть такой символ :)
     * nochar - массив с символами
     * txt - текст
     * excp-Находить неполные символы
     */
    public function isCharArray(array $nochar,$txt,$excp=false){
        if(count(self::mb_str_split(trim($txt)))<=0){
            return NULL;
        }
        foreach(explode("\n",$txt) as $txt){
            $txt=self::mb_str_split(trim($txt));
            $foo=[];
            if(!$excp){
                foreach($nochar as $val){
                    foreach($txt as $t){
                        if($t==$val){
                            return $val;
                        }
                    }
                }
            }else{
                foreach($nochar as $val){
                    $foo[$val]=$val;
                }
                foreach($txt as $t){
                    if(!$foo[$t]){
                         return false;
                    }
                }

            }
        }
        foreach($nochar as $val){
            foreach($txt as $t){
                if($t==$val){
                    return $val;
                }
            }
        }
        return false;
    }
    /**
     * Возвращаем блок
	 * ---------------
     * content	-	Контент
     * class	-	Класс
     * id		-	Индентификатор
	 * css		-	Стиль
     * @return string
     */
    public function div($opt){
		$content=$opt['content'];
		$class=$opt['class'];
        $id=$opt['id'];
		$css=self::css($opt['css']);
        if($class){
            $tag.="class=\"$class\" ";
        }
        if($id){
            $tag.="id=\"$id\" ";
        }
		$tag.=$css;
		$tag=trim($tag);
		return "<div $tag>$content</div>";
    }
    public function luhn($number){
        // Force the value to be a string as this method uses string functions.
        // Converting to an integer may pass PHP_INT_MAX and result in an error!
        $number = (string)$number;

        if (!ctype_digit($number)) {
            // Luhn can only be used on numbers!
            return FALSE;
        }

        // Check number length
        $length = strlen($number);

        // Checksum of the card number
        $checksum = 0;

        for ($i = $length - 1; $i >= 0; $i -= 2) {
            // Add up every 2nd digit, starting from the right
            $checksum += substr($number, $i, 1);
        }

        for ($i = $length - 2; $i >= 0; $i -= 2) {
            // Add up every 2nd digit doubled, starting from the right
            $double = substr($number, $i, 1) * 2;

            // Subtract 9 from the double where value is greater than 10
            $checksum += ($double >= 10) ? ($double - 9) : $double;
        }

        // If the checksum is a multiple of 10, the number is valid
        return ($checksum % 10 === 0);
    }

    public function is_valid_credit_card($number){
        $card_array = array(
            'default' => array(
                'length' => '13,14,15,16,17,18,19',
                'prefix' => '',
                'luhn' => TRUE,
            ),
            'american express' => array(
                'length' => '15',
                'prefix' => '3[47]',
                'luhn' => TRUE,
            ),
            'diners club' => array(
                'length' => '14,16',
                'prefix' => '36|55|30[0-5]',
                'luhn' => TRUE,
            ),
            'discover' => array(
                'length' => '16',
                'prefix' => '6(?:5|011)',
                'luhn' => TRUE,
            ),
            'jcb' => array(
                'length' => '15,16',
                'prefix' => '3|1800|2131',
                'luhn' => TRUE,
            ),
            'maestro' => array(
                'length' => '16,18',
                'prefix' => '50(?:20|38)|6(?:304|759)',
                'luhn' => TRUE,
            ),
            'mastercard' => array(
                'length' => '16',
                'prefix' => '5[1-5]',
                'luhn' => TRUE,
            ),
            'visa' => array(
                'length' => '13,16',
                'prefix' => '4',
                'luhn' => TRUE,
            ),
        );

        // Remove all non-digit characters from the number
        if (($number = preg_replace('/\D+/', '', $number)) === '')
            return FALSE;

        // Use the default type
        $type = 'default';

        $cards = $card_array;

        // Check card type
        $type = strtolower($type);

        if (!isset($cards[$type]))
            return FALSE;

        // Check card number length
        $length = strlen($number);

        // Validate the card length by the card type
        if (!in_array($length, preg_split('/\D+/', $cards[$type]['length'])))
            return FALSE;

        // Check card number prefix
        if (!preg_match('/^' . $cards[$type]['prefix'] . '/', $number))
            return FALSE;

        // No Luhn check required
        if ($cards[$type]['luhn'] == FALSE)
            return TRUE;
        return self::luhn($number);
    }

    /**
     * Проверка на хеш сумму md5 массив картинок
     * Возвращает массив с проверенным md5 картинок
     * $item - Массив картинок
     * @return array
     */
    public function getCheckMd5Array($item){
        $o=[];
        $i=-1;
    	if($item){
        	$output=[];
        	foreach($item as $value_img){
        	    if(!self::isUrl($value_img)){
        	        $value_img=$_SERVER['DOCUMENT_ROOT'].$value_img;
        	    }
                $imagefile=getimagesize($value_img);
                if($imagefile[0]>=8&&$imagefile[1]>=8){
                    $currentmd5=md5_file($value_img);
                    array_push($output, $value_img);
                    array_shift($item);
                    $next = $item;
                    foreach ($next as $key) {
                        if(!self::isUrl($key)){
                            $key=$_SERVER['DOCUMENT_ROOT'].$key;
                        }
                        if ($currentmd5==md5_file($key)){
                            $o['BAD'][].=$key;
                            array_pop($output);
                    	}
					}
            	}else{
            	    $o['ERROR'][].=$value_img;
            	}
        	}
        	//LOCAL
        	foreach($output as $b){
        	    $o['success'][].=$b;
        	}
        	foreach($o['success'] as $b){
        	    $i++;
        	    if(!self::isUrl($b)){
        	        $o['success'][$i]=str_replace($_SERVER['DOCUMENT_ROOT'],NULL,$o['success'][$i]);
        	    }
        	}
        	$i=-1;
        	foreach($o['BAD'] as $b){
        	    $i++;
        	    if(!self::isUrl($b)){
        	        $o['BAD'][$i]=str_replace($_SERVER['DOCUMENT_ROOT'],NULL,$o['BAD'][$i]);
        	    }
        	}
        	$i=-1;
        	foreach($o['ERROR'] as $b){
        	    $i++;
        	    if(!self::isUrl($b)){
        	        $o['ERROR'][$i]=str_replace($_SERVER['DOCUMENT_ROOT'],NULL,$o['ERROR'][$i]);
        	    }
        	}
        	return $o;
    	}
    }
    /**
	 * Создает уникальную ссесию для post запроса
	 * name-имя сессий
	 */
	public function generateSession($name='token'){
		$_SESSION[$name]=$name;
		return skinmanager::input(['type'=>'hidden','value'=>$name,'name'=>'session_key']);
	}
    /**
     * Возвращает есть ли первый символ в тексте
     * ----------------------------
     * @return bool
     */
    public function startWith($delimater,$txt){
        $txt=preg_split('//',$txt,-1,PREG_SPLIT_NO_EMPTY);
        if($txt[0]==$delimater){return true;}else{return false;}
    }
    /**
     * Проверяет есть ли последний символ в тексте
     * ----------------------------
     * @return bool
     */
    public function endsWith($haystack,$needle){
        $length=strlen($needle);
        if(!$length){
            return true;
        }
        return substr($haystack,-$length)===$needle;
    }
    /**
     * Возвращает символы в виде массива
     * ----------------------------
     * @return array
     */
    public function getCharToArray(){return ['!','"','№',';','%',':','?','*','(',')','@','#','$','%','^','&','*','[',']','{','}',"'","|", '/', '.', ',', '-', '+', '=', '`', '~', '\\','_'];}
    /**
     * Возвращает символы цифры в виде массива
     * ----------------------------
     * @return array
     */
    public function getNumberToArray($arr=[]){
        $a=['1','2','3','4','5','6','7','8','9','0'];
        foreach($arr as $val){
            array_push($a,$val);
        }
        return $a;
    }
    /**
     * Возвращает анг символы в виде массива
     * ----------------------------
     * @return array
     */
    public function getENGToArray($arr=[]){
        $a=['z','a','q','x','s','w','c','d','e','v','f','r','b','g','t','n','h','y','m','j','u','k','i','l','o','p'];
        foreach($arr as $val){
            array_push($a,$val);
        }
        return $a;
    }
    /**
     * Возвращает большие анг символы в виде массива
     * ----------------------------
     * @return array
     */
    public function getENGLongToArray($arr=[]){
        $a=['Z','A','Q','X','S','W','C','D','E','V','F','R','B','G','T','N','H','Y','M','J','U','K','I','L','O','P'];
        foreach($arr as $val){
            array_push($a,$val);
        }
        return $a;
    }
    /**
     * Возвращает рус символы в виде массива
     * ----------------------------
     * @return array
     */
    public function getRUSToArray($arr=[]){
                                                $a=['я','ф','й','ч','ы','ц','с','в','у','м','а','к','и','п','е','т','р','н','ь','о','г','б','л','ш','ю','д','щ','ж','з','э','х','ъ','ё'];
        foreach($arr as $val){
            array_push($a,$val);
        }
        return $a;
    }
    /**
     * Возвращает большие рус символы в виде массива
     * ----------------------------
     * @return array
     */
    public function getRUSLongToArray($arr=[]){
                                                $a=['Я','Ф','Й','Ч','Ы','Ц','С','В','У','М','А','К','И','П','Е','Т','Р','Н','Ь','О','Г','Б','Л','Ш','Ю','Д','Щ','Ж','З','Э','Х','Ъ','Ё'];
        foreach($arr as $val){
            array_push($a,$val);
        }
        return $a;
    }
    /**
     * Возвращает каждый символ в виде массива utf8
     * ----------------------------
     * @return array
     */
    public function mb_str_split($str){
        preg_match_all('#.{1}#uis',$str,$out);
        return$out[0];
    }
    /**
     * Возвращает проверенную строку на подлинность ()
     * ----------------------------
     * @return bool
     */
    public function islowupper($str='java'){
        $strlower=self::mb_str_split($str);
        $strArray=self::mb_str_split($str);
        array_shift($strArray);
        foreach($strArray as $val){
            $i++;
            if($val!=mb_strtolower($strlower[$i])){return true;}
        }
        return false;
    }
    /**
     * Возвращает чекс-бокс
     * ----------------------------
     * @return string
     */
    public function checkbox($id='checkbox',$value='ЧексБокс',$selected=false){
        if($selected==true){
            $selected='checked';
        }else{$selected=null;}
        return"<input type='checkbox' name='$id' id='$id' value='$value' $selected><label>$value</label>";
    }
    /**
     * Возвращает uuidv4
     * ----------------------------
     * @return string
     */
    public function uuidv4(){
	    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        mt_rand(0,0xffff),mt_rand(0,0xffff),
			mt_rand(0,0xffff),
			mt_rand(0,0x0fff)|0x4000,
			mt_rand(0,0x3fff)|0x8000,
			mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff)
		);
    }
    /**
     * Возвращает рандомный текст
     * ----------------------------
     * char-набор символов
     * length-Кол-во символов рандом
     * ----------------------------
     * @return bool
     */
    public function strRand($pool='abcdefghijklmnopqrstuvwxyz',$length=16){
        return substr(str_shuffle(str_repeat($pool, 5)),0,$length);
    }
    /**
     * Возвращает валидный ли uuidv4
     * ----------------------------
     * @return bool
     */
    public function is_uuidv4($uuid){if(preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',$uuid)){return true;} return false;}
    /**
     * Возвращаем включен ли js
     * ----------------------------
     * @return bool
     */
    public function isJs(){
        if($_COOKIE['__SKINMANAGER_SKIN']=='basic'){
            return false;
        }elseif(!$_COOKIE['__SKINMANAGER_SKIN']){
            return false;
        }else{
            return true;
        }
    }
    /**
     * Уменьшить размер 
     */
    public function resizeImg($img,$w=300,$h=300,$to=false){
        $size=getimagesize($img);
        $imgW=$size[0];
        $imgH=$size[1];
        $type=mime_content_type($img);
        switch($type){
            case 'image/jpeg':
                $image=imagecreatefromjpeg($img);
                $effect=imagecreatetruecolor($w,$h);
                //Разрез
                imagecopyresampled($effect,$image,0,0,0,0,$w,$h,$imgW,$imgH);
                if(!$to){
                    imagejpeg($effect,$img);
                }else{
                    imagejpeg($effect,$to);
                }
            break;
            case 'image/jpg':
                $image=imagecreatefromjpeg($img);
                $effect=imagecreatetruecolor($w,$h);
                //Разрез
                imagecopyresampled($effect,$image,0,0,0,0,$w,$h,$imgW,$imgH);
                if(!$to){
                    imagejpeg($effect,$img);
                }else{
                    imagejpeg($effect,$to);
                }
            break;
            case 'image/gif':
                $image=imagecreatefromgif($img);
                $effect=imagecreatetruecolor($w,$h);
                //Разрез
                imagecopyresampled($effect,$image,0,0,0,0,$w,$h,$imgW,$imgH);
                if(!$to){
                    imagegif($effect,$img);
                }else{
                    imagegif($effect,$to);
                }
            break;
            case 'image/png':
                $image=imagecreatefrompng($img);
                $effect=imagecreatetruecolor($w,$h);
                //Прозрачность
                imagealphablending($effect, false);
                imagesavealpha($effect, true);
                //Разрез
                imagecopyresampled($effect,$image,0,0,0,0,$w,$h,$imgW,$imgH);
                if(!$to){
                    imagepng($effect,$img);
                }else{
                    imagepng($effect,$to);
                }
            break;
            default:
                return false;
            break;
        }
    }
    /**
     * Возвращаем расширение файла по имени
     * ----------------------------
     * file-Имя
     * ----------------------------
     * @return string
     */
    public function getExtension($file){
        $f=explode('.',$file);
        return $f[count($f)-1];
    }
    /**
     * Возвращаем включен ли cookie
     * ----------------------------
     * @return bool
     */
    public function isCookie(){if(getallheaders()['Cookie']==NULL){return false;}else{return true;}}
    /**
     * Возвращаем полученное
     * URL-Адрес
     */
    public function get($URL){
        $CH=curl_init();
        curl_setopt($CH, CURLOPT_URL, $URL);
        curl_setopt($CH,CURLOPT_RETURNTRANSFER, true);
        $R=curl_exec($CH);
        return $R;
    }
    /**
     * Выполнение в виде уникального файла
     * Script-Скрипт выполнение
     */
    public function curlExecute($Script){
        $file=self::uuidv4();
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/$file.php",$Script);
        $path=$_SERVER['DOCUMENT_ROOT']."/$file.php";
        $exec=include $path;
        return $path;
    }
    /**
     * Возвращаем собранную ссылку
     * ----------------------------
     * href-ссылка
     * ----------------------------
     * @return string
     */
    public function BURL($href=false){
        //echo $href."\n";
        //$href=str_replace('?'.self::getData(),NULL,$href);

        return $href;
    }
	/**
	 * Возвращает замененную строку
	 * search	-	Строка которую нужно найти
	 * replace	-	На что изменить строку
	 * str		-	В которой будет пойск строки
	 * count	-	Кол-во цикл моментов изменение ;) (все)
	 */
	public function str_replace($search='саша',$replace='Катя',$str="Шла саша по шоссе и сосала сушку\nШла саша по шоссе и сосала сушку", $count=1){
		foreach(explode("\n",$str) as $string){
			foreach(explode(" ", $string) as $foo){
				if($foo==$search){
					if($count>$i||$count==0){
						$txt.=$replace;
					    $i++;
					}else{
						$txt.=$foo;
					}
				}else{
				    $txt.=$foo;
				}
				$txt.=' ';
			}
		}
		return trim($txt);
	}
    /**
     * Возвращает сообщение стандартное об ошибки
     */
    public function alert () {
        $css = '../../../css/alert.css';
        $uuidlogin = $this->uuidv4();
        $name = $_SERVER['SERVER_NAME'];
        return "<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml' hasBrowserHandlers='true'>
    <head>
        <title>Попытка соединения не удалась</title>
        <link rel='stylesheet' href='$css' type='text/css' media='all' />
        <link rel='icon' type='image/png' id='favicon' href='https://findicons.com/files/icons/99/office/16/alert.png'/>
    </head>
    <body dir='ltr'>
        <div id='errorPageContainer' class='$uuidlogin'>
            <div id='errorTitle'>
                <h1 id='errorTitleText'>Попытка соединения не удалась</h1>
            </div>
            <div id='errorLongContent'>
                <div id='errorShortDesc'>
                    <p id='errorShortDescText'>Модем не может установить соединение с сервером c $name</p>
                </div>
                <div id='errorLongDesc'>
                    <ul>
                        <li>Возможно, сайт временно недоступен, в этом случае подождите некоторое время и попробуйте снова.</li>
                        <li>Если вам не удалось открыть другие сайты, проверьте настройки соединения компьютера с сетью.</li>
                        <li>Если ваш компьютер или локальная сеть защищены межсетевым экраном или прокси-сервером, проверьте их, так как неверные настройки могут помешать просмотру веб-сайтов.</li>
                    </ul>
                </div>
            </div>
            <button id='errorTryAgain' autocomplete='off' onclick='location.reload();' autofocus='true'>Попробовать снова!</button>
        </div>
    </body>
</html>";
    }
}

