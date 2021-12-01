<?php

/**
 * @name Стандартный набор для работы сайта
 * @version 2.65
 */
use skinmanager as sm;
class xlib{

    public static $tags;

	/**
	 * Выполнение
	 */
	function execute(){

	}

    /**
     * Устанавливает загаловок
     * title - Загаловок
     */
    public function setTitle($title=false){
        $title=trim($title);
        if(!$title){
            $title=file_get_contents($_SERVER['DOCUMENT_ROOT'].self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.'title');
            echo $title;
        }
        echo"<title>$title</title>";
    }

    /**
     * Установка utf8 кодировка
     */
    public function utf8(){echo"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";}

    /**
     * Добавление css style
     * style - стиль код css
     */
    public function style($style){echo"<style>$style</style>";}

    /**
     * Добавление js скрипта
     * js - код js
     */
    public function js($js){echo"<script defer async>$js</script>";}

    /**
     * Установка описание сайта
     */
    public function description($txt=false){
        $txt=trim($txt);
        if(!$txt){
            $txt=file_get_contents($_SERVER['DOCUMENT_ROOT'].self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.'description');
        }
        echo"<meta name=\"description\" content=\"$txt\">";
    }

    /**
     * Установка тегов сайта
     */
    public function tag($tag=false){
        if(!$tag){
            $tag=file_get_contents($_SERVER['DOCUMENT_ROOT'].self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.'tag');
        }
        if(!self::$tags){
            self::$tags=$tag;
            $tag=self::$tags;
        }else{
            self::$tags=self::$tags.','.$tag;
            $tag=self::$tags;
        }
        echo "<meta name=\"Keywords\" content=\"$tag\">";
    }

    /**
     * Установка индексация веб-сайта в общем доступе
     */
    public function ShareBot($status=true){
        if($status){
            echo "<meta name=\"robots\" content=\"index, follow\"/>";
        }else{
            echo "<meta name=\"robots\" content=\"noindex, nofollow, noarchive, nosnippet\"/>";
        }
    }

    /**
     * Выполняет js код
     */
    public function script($code){echo"<script>$code</script>";}

    /**
     * Возвращает путь к libphp
     * ----------------------------
     * @return string
     */
    public function path($file){return mb_substr(self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.self::getLibPath().DIRECTORY_SEPARATOR.$file.'.php',1);}

    /**
     * Возвращает рандомный массив
     * arr - Массив
     * ----------------------------
     * @return array
     */
    public function getrand(array $arr){return $arr[rand(0,count($arr)-1)];}

    /**
     * Возвращает z кординату
     * Возможно нужна чтобы элемент был сверху :)
     * $content - Контент
     * $value - расстояние
     * ----------------------------
     * @return string
     */
    public function z($content=null,$value=5){return "<div style='z-index: $value;position: relative;'>$content</div>";}

    /**
     * Возвращает анимацию
     * content - Контент
     * animate - Анимация название
     * ----------------------------
     * @return string
     */
    public function anim($content=null,$animate){return "<div class='animated $animate'>$content</div>";}

    /**
     * Возвращает абсалютный путь к модулю
     * ----------------------------
     * @return string
     */
    public function getPathModules($path){return self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$path;}

    /**
     * Возвращаем ссылка это или нет
     * url-Ссылка
     * ----------------------------
     * @return bool
     */
    public function isUrl($url){return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i',$url);}

    /**
     * Возвращает массив с модулями
     * cfg-Загрузка через конфиг
     * ----------------------------
     * @return array
     */
    public function getModules($cfg=true){
		$theme=$_SERVER['DOCUMENT_ROOT'].self::getPathModules(null);
        $output=[];
        if($cfg){
			foreach(modules as $value){
				if($value!='cfg.json'){
					array_push($output,$value);
				}
			}
		}else{
			foreach(self::scandir($theme) as $value){
				if($value != 'cfg.json'){
					array_push($output,$value);
				}
			}
		}
        return $output;
    }

    /**
     * Возращает подключен ли модуль
     * name-Имя пакета
     * cfg-Загрузка через конфиг
     * ----------------------------
     * @return bool
     */
	public function isModule($name,$cfg=true){
		foreach(self::getModules($cfg) as $module){
			if($module==$name){
				return true;
			}
		}
		return false;
	}

    /**
     * Возвращает путь выбранной темы
     * ----------------------------
     * @return string
     */
    public function getTheme(){
        require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'options.php';
        $options=new options();
        return DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$options->theme.DIRECTORY_SEPARATOR;
    }

    /**
     * Возвращает платформу
     * ----------------------------
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
     * folder_css - папка с css стилей
     */
    public function loader_css($folder_css='css'){
        $path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.$folder_css.DIRECTORY_SEPARATOR;
        $cssfile=self::scandir('.'.$path);
        foreach($cssfile as $css){
        	echo "<link rel=\"stylesheet\" text=\"type/css\" href=\"$path$css\">";
        }
    }

    /**
     * Автодобавление всех js из папки
     * folder_js - папка с js скриптами
     */
    public function loader_js($folder_js='js'){
		if($_COOKIE['__SKINMANAGER_SKIN'] && $_COOKIE['__SKINMANAGER_SKIN'] != 'basic'){
			$path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR.$folder_js.DIRECTORY_SEPARATOR;
			$jsfile=self::scandir('.'.$path);
			foreach($jsfile as $js){
				echo "<script type=\"text/javascript\" src=\"$path$js\"></script>";
			}
		}
    }

	/**
	 * Возвращает блок стиля
	 * opt - Основные стили (Кастомный)
	 * ----------------------------
	 * @return string
	 */
	public function css($opt){
		unset($GLOBALS['style']);
		foreach($opt as $val => $key){
			$i++;
			$style.="$val:$key";
			if(count($opt) + 1 != $i){
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
	 * opt - Основые стили (Кастомный)
	 */
	public function addCss($opt){
		foreach($opt as $val => $key){
			$i++;
			$style.="$val:$key";
			if (count($opt) + 1 != $i){
				$style.=';';
			}
		}
		$GLOBALS['style'].=$style;
	}

	/**
	 * Возвращает геты данные в виде массива из uri
	 * ----------------------------
	 * @return array
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
	 * ----------------------------
	 * @return string
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
	 * ----------------------------
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
        $skin=skinmanager::getSkin();
        if($skin&&$skin!='basic'){	
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
    }

    /**
     * Добавление css из папки
     * css - Массив файлов css
     * folder_css - папка где лежат css
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
	 * Инициализация database
	 * ----------------------------
     * @return sql
     */
    public function getmysql(){
        require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'mysql.php';
        $mysql=new mysql();
        $sql=mysqli_connect($mysql->ip,$mysql->user,$mysql->password);
        if(!$sql){
		    die('Ошибка подключение mysql :(');
        }else{
            $database=trim($mysql->database);
            if(!mysqli_select_db($sql, $database)){
                mysqli_query($sql, "CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
            }
        }
        mysqli_select_db($sql, $database);
        return $sql;
    }

    /**
     * Возвращаем подлинность таблицы в бд
     * database - База данных
     * table    - Таблица
     * ----------------------------
     * @return bool
     */
    public function isTable($database, $table){
        $sql=self::getmysql();
        mysqli_select_db($sql, $database);
        $result=mysqli_query($sql, "CHECK TABLE $table FAST QUICK");
        if($result->num_rows == 1){
            return true;
        }
        return false;
    }

    /**
     * Возвращает подключение php
     * file - массив страниц
     * ----------------------------
     */
    public function req(array $file){
        foreach($file as $val){
            require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.self::path($val);
        }
    }

    /**
     * Возвращает путь php скрипты либы
     * ----------------------------
     * @return string
     */
    public function getLibPath(){
        require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'options.php';
        $options=new options();
        return $options->libphp;
    }

	/**
	 * Возвращаем svg
     * ----------------------------
     * @return string
	 */
	public function svg($svg,$w=false, $h=false){
    	if($w){
        	$style.="width=\"$w\" ";
        }
    	if($h){
        	$style.="height=\"$h\" ";
        }
    	$svg="<svg viewBox=\"-70 0 1214.4733 1081.6177\" $style>$svg</svg>";
    	return $svg;
    }

    /**
     * Установка ico сайта
     * ico - путь к иконки
     */
    public function ico($ico){
        $path=self::getTheme().self::getPlatform().DIRECTORY_SEPARATOR;
        echo "<link rel='shortcut icon' type='image/png' href=$path$ico>";
    }

    /**
     * Возвращает имя ссылки
     * value - ступень обозночение
     * ----------------------------
     * @return string
     */
    public function geturi($value=-1){
	    if($_GET['redirect']){
	        $redirect=$_GET['redirect'];
        }elseif($_POST['redirect']){
	        $redirect=$_POST['redirect'];
        }else{
	        $redirect=$_SERVER['REQUEST_URI'];
        }
	    $redirect=urldecode(self::getURICompile($redirect));
	    if($value>=0){
            $redirect=explode('/', $redirect);
            return $redirect[$value + 1];
        }
        return $redirect;
    }

    /**
	 * Возвращаем компилирумый путь (Исправленный)
	 * data-геты
	 * ----------------------------
	 * @return string
	 */
	public function getURICompile($PATH,$data=false){
	    $DOTS=[];
	    if(!$data && $_SERVER['REQUEST_METHOD']!='POST'){
	    	$PATH=str_replace('?'.self::getData(),NULL,$_SERVER['REQUEST_URI']);
	    }
	    //fix path...
	    $protocol=$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
	    $PATH=str_replace($protocol.'://'.$_SERVER['SERVER_NAME'],NULL,$PATH);
		foreach(explode(DIRECTORY_SEPARATOR, $PATH.DIRECTORY_SEPARATOR) as $DOT){
			if($DOT!='.'&&$DOT!='..'&&trim($DOT)){
				array_push($DOTS,$DOT);
			}
		}
		foreach($DOTS as $D){
			$DOT.=DIRECTORY_SEPARATOR.$D;
		}
		if(empty($DOT)){
			return '/index';
		}
		return $DOT;
	}

    /**
     * Обновление редиректа ссылки
     * form-форма
     * url-редирект адрес
     * ----------------------------
     * @return string
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
	    $protocol=$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
	    $redirect=$protocol.'://'.$_SERVER['SERVER_NAME'].str_replace($protocol.'://'.$_SERVER['SERVER_NAME'],NULL,$redirect);
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
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if(!self::isUrl($url)){
            	if(!$url){
            		$url=self::geturi();
            	}
                $url=parse_url($url);
                $path=$url['path'];
                //load 	web file
                $path=$_SERVER['DOCUMENT_ROOT'].self::getTheme()."uri$path.php";
                if(!is_file($path)){
                    $path=$_SERVER['DOCUMENT_ROOT'].self::getTheme()."uri/404.php";
                }
                require_once $path;
                $_SERVER['REQUEST_URI']='?'.$url['query'];
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
        }elseif($_SERVER['REQUEST_METHOD']=='GET'){
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
     * Проверка есть такой символ или нету
     * Возвращает true если есть такой символ :)
     * nochar - массив с символами
     * txt - текст
     * excp - Находить неполные символы
     * ----------------------------
     * @return bool
     */
    public function isCharArray(array $nochar,$txt,$excp=false){
        if(count(mb_str_split(trim($txt)))<=0){
            return NULL;
        }
        foreach(explode("\n",$txt) as $txt){
            $txt=mb_str_split(trim($txt));
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
                	if(is_numeric($foo[$t]) && $foo[$t]==0){
                		break;
                	}
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
     * content	-	Контент
     * class	-	Класс
     * id		-	Индентификатор
	 * css		-	Стиль
	 * ----------------------------
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

    /**
     * Возвращает проверку на валидность кредитной карты
	 * ----------------------------
     * @return bool
     */
    public function is_valid_credit_card($number){
    	if(self::startWith(0,$number)){
    		return false;
    	}
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
     * item - Массив картинок (Локальный путь или url)
     * ----------------------------
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
                if($imagefile[0] >= 8 && $imagefile[1] >= 8){
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
	 * key-Ключ сессий
	 * ----------------------------
	 * @return object
	 */
	public function generateSession($key=false){
		if(!$key){
			$key=self::uuidv4();
		}
		$_SESSION[$key]=$key;
		return skinmanager::input(['type'=>'hidden','value'=>$key,'name'=>'session_key']);
	}

    /**
	 * Возвращение активная сессия или нет
	 * ----------------------------
	 * @return bool
	 */
	public function isActiveSession(){
		if($_COOKIE[session_name()]){
			return true;
		}
		return false;
	}

    /**
     * Возвращает есть ли первый символ в тексте
     * ----------------------------
     * @return bool
     */
    public function startWith($delimater,$txt){
        $txt=preg_split('//',trim($txt),-1,PREG_SPLIT_NO_EMPTY);
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
     * Сканировать папки без точки
     * path-Путь
     * ----------------------------
     * @return array
     */
    public function scandir($path){
    	$arr=scandir($path);
		array_shift($arr);
		array_shift($arr);
    	if(empty($arr)){
    		return false;
    	}
    	return $arr;
    }

    /**
     * Возвращает проверенную строку на подлинность
     * ----------------------------
     * @return bool
     */
    public function islowupper($str='java'){
        $strlower=mb_str_split($str);
        $strArray=mb_str_split($str);
        array_shift($strArray);
        foreach($strArray as $val){
            $i++;
            if($val!=mb_strtolower($strlower[$i])){return true;}
        }
        return false;
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
     * Возвращает валидный ли uuidv4
     * ----------------------------
     * @return bool
     */
    public function is_uuidv4($uuid){if(preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid)){return true;} return false;}

    /**
     * Возвращает рандомные символы
     * char-набор символов
     * length-Кол-во символов рандом
     * ----------------------------
     * @return string
     */
    public function strRand($pool='abcdefghijklmnopqrstuvwxyz',$length=16){
        return substr(str_shuffle(str_repeat($pool, 5)),0,$length);
    }

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
     * Подключить другие плагины
     * plugins-Массив плагинов (auto)
     * ----------------------------
     * @return array
     */
    public function oConnect($plugins=[]){
    	if(empty($plugins)){
			$path=dirname(__DIR__).DIRECTORY_SEPARATOR.$GLOBALS['endModule'];
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator($path,RecursiveDirectoryIterator::SKIP_DOTS)) as $lib){
				$lib=substr(trim(str_replace('|', NULL, $lib)), 2);
				if(!self::startWith(DIRECTORY_SEPARATOR, $lib)){
					$lib=DIRECTORY_SEPARATOR.$lib;
				}
				if(!is_dir($lib) && $path . DIRECTORY_SEPARATOR . $GLOBALS['endModule'] . '.php' != $lib){
					if(self::getExtension($lib) == 'php'){
						$interface=self::get_interface_file($lib);
						if($interface){
							$plugins[$lib]=$interface;
							break;
						}
						$class=self::get_class_file($lib);
						if($class){
							$plugins[$lib]=$class;
							break;
						}
					}
				}
			}
		}

		//connect implements
		foreach($plugins as $lib => $status){
			$implements=array_reverse(self::get_implements_file($lib, true));
			if($implements){
				foreach($implements as $file => $name){
					require_once $file;
				}
			}
		}

		//connect extends
		foreach($plugins as $lib => $status){
			$extends=array_reverse(self::get_extends_file($lib, true));
			if($extends){
				foreach($extends as $file => $name){
					require_once $file;
				}
			}
		}

		//connect plugins
		foreach($plugins as $lib => $status){
			require_once $lib;
			array_push($connected, $lib);
		}
		return $connected;
    }

    /**
     * Возвращает название класса файла
     * ----------------------------
     * @return string
     */
    public function get_class_file($file){
    	$tokens = token_get_all(file_get_contents($file));
    	if(empty($tokens)){
    		return false;
    	}
		$classStart = false;
		foreach ($tokens as $token) {
			if($token[0] === T_CLASS){
				$classStart = true;
			}
			if($classStart && $token[0] === T_STRING){
				$class = $token[1];
				break;
			}
		}
		return $class;
    }

    /**
     * Возвращает название интерфейса класса файла
     * ----------------------------
     * @return string
     */
    public function get_interface_file($file){
    	$tokens = token_get_all(file_get_contents($file));
    	if(empty($tokens)){
    		return false;
    	}
		$interfaceStart = false;
		foreach($tokens as $token){
			if($token[0] === T_INTERFACE){
				$interfaceStart = true;
			}
			if($interfaceStart && $token[0] === T_STRING){
				$interface = $token[1];
				break;
			}
		}
		return $interface;
    }

    /**
     * Возвращает класса зависимость файла
     * file-Файл с implements
     * last-Последний файл
     * ----------------------------
     * @return string
     */
    public function get_extends_file($file,$last=false,$connected=[]){
    	$tokens = token_get_all(file_get_contents($file));
    	if(empty($tokens)){
    		return false;
    	}
		$extendsStart = false;
		foreach($tokens as $token){
			if($token[0] === T_EXTENDS){
				$extendsStart = true;
			}
			if($extendsStart && $token[0] === T_STRING){
				$extends=$token[1];
				if(!is_file(dirname($file).DIRECTORY_SEPARATOR.$extends.'.php')){
					break;
				}
				if($extends=='null'){
					break;
				}
				$connected[dirname($file).DIRECTORY_SEPARATOR.$extends.'.php']=$extends;
			}
		}
		if(!$connected){
			return false;
		}
		if($last){
			foreach($connected as $lib => $class){
				$i=self::get_extends_file($lib);
				foreach($i as $b => $a){
					if(!$connected[$b]){
						$connected += [$b => $a];
						return self::get_extends_file($lib, true, $connected);
					}
				}

			}
		}
		return $connected;
    }

    /**
     * Возвращает файла implements
     * file-Файл с implements
     * last-Последний файл
     * ----------------------------
     * @return string
     */
    public function get_implements_file($file,$last=false,$connected=[]){
    	$tokens = token_get_all(file_get_contents($file));
    	if(empty($tokens)){
    		return false;
    	}
		$implementsStart = false;
		foreach($tokens as $token){
			if($token[0] === T_IMPLEMENTS) {
				$implementsStart = true;
			}
			if($implementsStart && $token[0] === T_STRING){
				$implement=$token[1];
				if(!is_file(dirname($file).DIRECTORY_SEPARATOR.$implement.'.php')){
					break;
				}
				if($implement=='null'){
					break;
				}
				$connected[dirname($file).DIRECTORY_SEPARATOR.$implement.'.php']=$implement;
			}
		}
		if(!$connected){
			return false;
		}
		if($last){
			foreach($connected as $lib => $class){
				$i=self::get_implements_file($lib);
				foreach($i as $b => $a){
					if(!$connected[$b]){
						$connected += [$b => $a];
						return self::get_implements_file($lib, true, $connected);
					}
				}

			}
		}
		return $connected;
    }

    /**
     * Разрезать изоброжение (jpeg, jpg, gif, png, webp)
     * img - путь к изоброжению
     * w - ширина
     * h - высота
     * to - Перемещение изоброжение
     */
    public function resizeImg($img, $w=300, $h=300, $to=false){
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
            	//extract
            	$gfe=new GifFrameExtractor();
            	$gfe->extract($img, true);
            	$tmp=__DIR__.DIRECTORY_SEPARATOR.self::strRand();
				mkdir($tmp);
				chmod($tmp,0777);
				$i=0;
				$frames=[];
            	foreach($gfe->getFrames() as $frame){
            		$i++;
					// The frame resource image var
					$image=$frame['image'];
					$effect=imagecreatetruecolor($w,$h);
					//Разрез
					imagecopyresampled($effect,$image,0,0,0,0,$w,$h,$imgW,$imgH);
					//extract
					imagegif($effect,$tmp.DIRECTORY_SEPARATOR.$i);
					//frames add
					array_push($frames,file_get_contents($tmp.DIRECTORY_SEPARATOR.$i));
				}
				//pack
				$gfa=new AnimGif();
				$gfa->create($frames);
                if(!$to){
                	$gfa->save($tmp.DIRECTORY_SEPARATOR.'output.gif');
                }else{
                	$gfa->save($to);
                	//cache clear
                	array_map('unlink',array_filter((array)array_merge(glob($tmp.DIRECTORY_SEPARATOR.'*'))));
                	rmdir($tmp);
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
            case 'image/webp':
                $image=imagecreatefromwebp($img);
                $effect=imagecreatetruecolor($w,$h);
                //Прозрачность
                imagealphablending($effect, false);
                imagesavealpha($effect, true);
                //Разрез
                imagecopyresampled($effect,$image,0,0,0,0,$w,$h,$imgW,$imgH);
                if(!$to){
                    imagewebp($effect,$img);
                }else{
                    imagewebp($effect,$to);
                }
            break;
            default:
                return false;
            break;
        }
        imagedestroy($image);
        return true;
    }

    /**
     * Возвращаем кол-во фреймов gif
     * file-Полный путь к файлу
     * ----------------------------
     * @return int
     */
    public function getCountFrameGif($file){
		if(file_exists($file)){
			$type=mime_content_type($file);
			switch($type){
            	case 'image/gif':
					$gif=fopen($file,'rb');
					$count=0;
					while(!feof($gif)){
						//add the last 20 characters from the previous string, to make sure the searched pattern is not split.
						$chunk=($chunk ? substr($chunk, -20) : "") . fread($gif, 1024 * 100); //read 100kb at a time
						$count+=preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
					}
					fclose($gif);
					return $count;
				break;
			}
		}
		return 0;
    }

    /**
     * Возвращаем фреймы gif в виде массива
     * file-Полный путь к файлу
     * ----------------------------
     * @return int
     */
    public function getFramesGif($file){
		if(file_exists($file)){
			$type=mime_content_type($file);
			switch($type){
            	case 'image/gif':
            		$data=[];
            		$i=0;
					$tmp=__DIR__.DIRECTORY_SEPARATOR.self::strRand();
					mkdir($tmp);
					chmod($tmp,0777);
					$frames=[];
					$images=explode("\x2C\x21\xF9\x04",file_get_contents($file));
					foreach($images as $image){
						if($images[0]!=$image){
							$i++;
							$path=$tmp.DIRECTORY_SEPARATOR.$i;
							file_put_contents($path,$images[0].$image.chr(0x3b));
							chmod($path,0777);
							array_push($data,$path);
						}
					}
					return $data;
				break;
			}
		}
		return 0;
    }
    /**
     * Возвращаем фреймы gif в виде массива
     * frames-Массив картинок для конвертирование
     * name-Название gif
     * to-Путь завершение
     * ----------------------------
     * @return int
     */
    public function PackGifImages($frames,$name,$to=false){
    	$data=false;
    	foreach($frames as $frame){
    		if($frame!='.'&&$frame!='..'){
				if(file_exists($frame)){
					$type=mime_content_type($frame);
					if(!$to){
						$to=explode(DIRECTORY_SEPARATOR,$frame);
						array_pop($to);
						foreach($to as $t){
							$path.=$t.DIRECTORY_SEPARATOR;
						}
					}
					switch($type){
						case 'image/gif':
							if(self::getCountFrameGif($frame)==0){
					
								//$frame=explode(chr(0x2c),file_get_contents($frame));
								
								var_dump($frame);
								die();
								$i=0;
								foreach($frame as $byte){
									if(self::startWith('@',$byte)){
										break;
									}
									array_shift($frame);
									$header.=$byte.chr(0x2c);
								}
								foreach($frame as $byte){
									$data.=$byte.chr(0x2c);
								}
								
								file_put_contents($path.$name,$header.chr(0x2c).$data);
								die();
								$images=explode(chr(0x2c),file_get_contents($frame));
								if(!$header){
									$header=$images[0];
								}
								$i=implode("\x00\x21\xF9\x04", $images);
								$data.=$images[1];
								var_dump($images);
								die();
							}
						break;
					}
				}else{
					return false;
				}
			}
		}
		if($data){
			var_dump($data);
			file_put_contents($path.$name,$header.$data.chr(0x3b));
			chmod($path.$name,0777);
		}else{
			return false;
		}
    }
    /**
     * Возвращаем расширение файла по имени
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
     * Возвращаем страницу
     * URL-Адрес
     * ----------------------------
     * @return string
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
     * ----------------------------
     * @return string
     */
    public function curlExecute($Script){
        $file=self::uuidv4();
        file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."$file.php",$Script);
        $path=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."$file.php";
        $exec=include $path;
        return $path;
    }

    /**
     * Возвращаем собранную ссылку
     * href-ссылка
     * ----------------------------
     * @return string
     */
    public function BURL($href=false){
        return $href;
    }

    /**
     * Возвращаем в виде формы об плагинов
     * ----------------------------
     * @return form
     */
    public function about(){
        $arr=self::getModules();
		return sm::modal(['title'=>'Об плагинах '.sm::badge(['txt'=>count($arr)]), 'content'=>self::aboutObject()]);
    }

    /**
     * Возвращаем в виде объекта об плагинов
     * ----------------------------
     * @return object
     */
    public function aboutObject(){
    	$i=0;
    	$logo=sm::p(['content'=>sm::img(['src'=>self::getPathModules('xlib'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'plugins.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
    	$modules='';
    	$arr=self::getModules();
    	foreach($arr as $module){
    		$i++;
    		$modules.="</br>[$i] $module => Load";
    	}
    	$modules.="</br> success :)";
		//-->Модули
		$modules=sm::txt(['txt'=>$modules]);
		return $logo.$modules;
    }

	/**
	 * Возвращает замененную строку
	 * search	-	Строка которую нужно найти
	 * replace	-	На что изменить строку
	 * str		-	В которой будет пойск строки
	 * count	-	Кол-во цикл моментов изменение ;) (все)
	 * ----------------------------
	 * @return string
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
	 * Возвращает текст с верхним регистром
	 * ----------------------------
	 * @return string
	 */
	function mb_ucfirst($string, $encoding='UTF-8'){
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

	/**
	 * Возвращаем в виде формат монет
	 * money - Монет
	 * ----------------------------
	 * @return string
	 */
	public function genFormatMoney($money){
		$money=number_format($money,0,' ',' ');
		$m=explode(' ',$money);
		return $money;
	}

	/**
	 * Возвращаем название счета
	 * ----------------------------
	 * @return string
     */
    public function getNumberName($money){
        $money=number_format($money,0,' ',' ');
        $m=explode(' ',$money);
        if($m[4]){
		    return 'Трилл';
	    }elseif($m[3]){
		    return 'Млрд';
	    }elseif($m[2]){
		    return 'Млн';
	    }elseif($m[1]){
		    return 'Тыс';
	    }elseif($m[0]){
		    return 'Едн';
	    }
    }

	/**
	 * Чекает все коды запроса
	 */
	public function resetResponse(){
	    //codes
		$codes=[
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => "I'm a teapot",
            419 => 'Authentication Timeout',
            420 => 'Enhance Your Calm',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            424 => 'Method Failure',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            444 => 'No Response',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            451 => 'Unavailable For Legal Reasons',
            494 => 'Request Header Too Large',
            495 => 'Cert Error',
            496 => 'No Cert',
            497 => 'HTTP to HTTPS',
            499 => 'Client Closed Request',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
            598 => 'Network read timeout error',
            599 => 'Network connect timeout error'
];
        //Apply effect
		foreach($codes as $code => $desc){
		    http_response_code($code);
		}
	}

    /**
     * Возвращает сообщение стандартное об ошибки
     * ----------------------------
     * @return string
     */
    public function alert(){
        $css = '../../../css/alert.css';
        $uuidlogin = self::uuidv4();
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

