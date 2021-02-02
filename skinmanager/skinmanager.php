<?php
if($_GET['skin']){
	$skin=$_GET['skin'];
	$url=$_GET['redirect'];
}else{
	$skin=$_POST['skin'];
	$url=$_POST['redirect'];
}
function getSkins(){
	$ls=scandir(__DIR__.DIRECTORY_SEPARATOR.'theme');
	array_shift($ls);
	array_shift($ls);
	return $ls;
}
if($skin){
	foreach(getSkins() as $theme){
		if($skin==$theme){
			$_COOKIE['__SKINMANAGER_SKIN']=$skin;
			setcookie('__SKINMANAGER_SKIN',$skin,time()+(86400*30),'/');
			if($_COOKIE['__SKINMANAGER_SKIN']=='basic'){
				$_COOKIE['__SKINMANAGER-basic-THEME']=$_POST['__THEME_BASIC'];
				setcookie('__SKINMANAGER-basic-THEME',$_POST['__THEME_BASIC'],time()+(86400*30),'/');
			}
			echo "Скин успешно сменился ;) На $skin";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
			die();
		}
	}
}
//noscripts
if($_COOKIE['__SKINMANAGER_SKIN']!='basic'){
	$url=$_SERVER['REQUEST_URI'];
	$href=xlib::getPathModules('skinmanager'.DIRECTORY_SEPARATOR."./skinmanager.php?skin=basic&redirect=$url");
	echo "<noscript><meta http-equiv=\"refresh\" content=\"0;url=$href\"></noscript>";
}
	
/**
 * Скин менеджер
 * --------------
 * ver beta 1.45
 */
use jquery as jq;
use xlib as x;
class skinmanager{
	/**
	 * Выполнение после погрузки страницы
	 * --------------------------------------
	 */
	function footerExecute(){
		echo self::getSuperBox();
	}
	/**
	 * Возвращаем версию модуля
	 * ------------------------
	 * @return string
	 */
	public function getVersion(){
		return' ('.__CLASS__.' '.self::badge(['txt'=>'beta 2.00']).')';
	}

    function execute(){
        //Опция
        $skin=self::getSkin();
        //INSTALL SKIN DEFAULT
        self::setDefaultSkin(trim(file_get_contents(__DIR__.'/cfg/skin')));
        if(!self::isSkin($skin)){
    		$skin=self::getSkins()[0];
			setcookie('__SKINMANAGER_SKIN',$skin,time()+(86400*30),'/');
			$_COOKIE['__SKINMANAGER_SKIN']=$skin;
		}
		//INSTALL SKIN THEME DEFAULT
        self::setDefaultTheme(trim(file_get_contents(__DIR__.'/cfg/theme')));
        if(!self::isTheme(self::getTheme())){
        	if(!x::isJs()){
    			$theme=self::getThemes()[0];
				setcookie('__SKINMANAGER-basic-THEME',$theme,time()+(86400*30),'/');
				$_COOKIE['__SKINMANAGER-basic-THEME']=$theme;
			}
		}
        //Исправление фиксов со скинами
		switch($skin){
			case 'bootstrap3':
				jq::addLoad("$('*').on('show.bs.modal',function(){ $('*').modal('hide');})");
			break;
			case 'bootstrap4':
				jq::addLoad("$('*').on('show.bs.modal',function(){ $('*').modal('hide');})");
			break;
		}
    }
	/**
	 * Принять (css)
	 * -------------------
	 */
	function ApplySkin(){
		$skin=self::getSkin();
		switch($skin){
			case 'basic':
				$theme=$_COOKIE['__SKINMANAGER-basic-THEME'];
				if(empty($theme)){
					$theme='Стандартный';
				}
				$module='plugins'.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR;
				$css=scandir($_SERVER['DOCUMENT_ROOT'].x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR));
				array_shift($css);
				array_shift($css);
				for($i=1;$i<=count($css);$i++){
					$path=$_SERVER['DOCUMENT_ROOT'].x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$i.DIRECTORY_SEPARATOR);
					foreach(scandir($path) as $C){
						if($C!='.'&&$C!='..'){
							$path=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$i.DIRECTORY_SEPARATOR);
							x::add_css([$path.$C],null);
						}
					}
				}
			break;
			default:

				$module='plugins'.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR;
				$css=scandir($_SERVER['DOCUMENT_ROOT'].x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR));
				array_shift($css);
				array_shift($css);
				for($i=1;$i<=count($css);$i++){
					$path=$_SERVER['DOCUMENT_ROOT'].x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$i.DIRECTORY_SEPARATOR);
					foreach(scandir($path) as $C){
						if($C!='.'&&$C!='..'){
							$path=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$i.DIRECTORY_SEPARATOR);
							x::add_css([$path.$C],null);
						}
					}
				}
			break;
		}
	}

	/**
	 * Принять (js)
	 * ------------------
	 */
	public function ApplyJs () {
		$skin=self::getSkin();	$js=scandir($_SERVER['DOCUMENT_ROOT'].x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR));
		array_shift($js);
		array_shift($js);
		for($i=1;$i<=count($js);$i++){	$path=$_SERVER['DOCUMENT_ROOT'].x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$i.DIRECTORY_SEPARATOR);
			foreach(scandir($path) as $J){
				if($J!='.'&&$J!='..'){
					$path=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$i.DIRECTORY_SEPARATOR);
					x::add_js([$path.$J],null);
				}
			}
		}
		//-->Выполнение кода после загрузки страницы
		foreach(modules as $module){
			$func=array($module,'footerExecute');
			if(is_callable($func)){
				$func();
			}
		}
	}
	/**
	 * Возвращаем имя выбранного скина
	 * --------------------------
	 * @return string
	 */
	public function getSkin(){
		if(!$_COOKIE['__SKINMANAGER_SKIN']){
			return 'basic';
		}else{
			return $_COOKIE['__SKINMANAGER_SKIN'];
		}
	}
	/**
	 * Возвращаем существует ли скин
	 * --------------------------
	 * @return string
	 */
	public function isSkin($skin){
		foreach(self::getSkins() as $s){
			if($s==$skin){
				return true;
			}
		}
		return false;
	}
	/**
	 * Возвращаем скины
	 * -------------------------------
	 * @return Array
	 */
	public function getSkins(){
		$theme=$_SERVER['DOCUMENT_ROOT'].x::getPathModules('skinmanager'.DIRECTORY_SEPARATOR.'theme');
		$ls=scandir($theme);
		array_shift($ls);
		array_shift($ls);
		return $ls;
	}
	/**
	 * Возвращаем темы
	 * -------------------------------
	 * @return Array
	 */
	public function getThemes(){
		if(!x::isJs()){
			$theme=$_SERVER['DOCUMENT_ROOT'].x::getPathModules('skinmanager'.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'basic'.DIRECTORY_SEPARATOR.'css');
			$ls=scandir($theme);
			array_shift($ls);
			array_shift($ls);
			return $ls;
		}
		return false;
	}
	/**
	 * Возвращаем выбранную тему
	 * -------------------------------
	 * @return Array
	 */
	public function getTheme(){
		if(!x::isJs()){
			$theme=self::getSkin();
			if(!$_COOKIE["__SKINMANAGER-$theme-THEME"]){
				return self::getThemes[0];
			}else{
				return $_COOKIE["__SKINMANAGER-$theme-THEME"];
			}
		}
		return false;
	}
	/**
	 * Возвращаем существует ли скин
	 * --------------------------
	 * @return string
	 */
	public function isTheme($theme){
		foreach(self::getThemes() as $s){
			if($s==$theme){
				return true;
			}
		}
		return false;
	}
	/**
	 * Возвращаем значение виджета
	 * ----------------------------
	 * tag	-	Виджет
	 * opt	-	Опций
	 * ----------------------------
	 * @return string
	 */
	public function import($tag,$opt){
		foreach(self::getSkins() as $skin){
			if($skin==self::getSkin()){
				require_once'theme'.DIRECTORY_SEPARATOR.$skin.DIRECTORY_SEPARATOR."$skin.php";
				if(is_callable(array($skin,$tag))){
					$func=array($skin,$tag);
					return $func($opt);
				}
			}
		}
		require_once'theme'.DIRECTORY_SEPARATOR.'basic'.DIRECTORY_SEPARATOR.'basic.php';
		if(is_callable(array('basic',$tag))){
			$func=array('basic',$tag);
			return $func($opt);
		}
	}
	/**
	 * Установить в суперкоробку
	 * --------------------------
	 * content - контент
	 * --------------------------
	 * @return string
	 */
	public function setSuperBox($content){
		$GLOBALS['__SUPER_BOX']=$content;
	}
	/**
	 *  в суперкоробку
	 * --------------------------
	 * content - контент
	 * --------------------------
	 * @return string
	 */
	public function addSuperBox($content){
		$GLOBALS['__SUPER_BOX'].=$content;
	}
	/**
	 * Возвращаем суперкоробку
	 * ------------------------
	 * @return string
	 */
	public function getSuperBox(){
		return$GLOBALS['__SUPER_BOX'];
	}
	/**
	 * Устанавливает стандартный скин
	 */
	public function setDefaultSkin($skin='bootstrap3'){
		$skins=self::getSkins();
		foreach($skins as $selected){
			if($selected==$skin){
				if(!$_COOKIE['__SKINMANAGER_SKIN']){
					setcookie('__SKINMANAGER_SKIN',$skin,time()+(86400*30),'/');
					$_COOKIE['__SKINMANAGER_SKIN']=$skin;
					return true;
				}
				return false;
			}
		}
		self::setDefaultSkin($skins[0]);
    }
    /**
	 * Устанавливает стандартный тему
	 */
	public function setDefaultTheme($theme='lolifox'){
		if(!x::isJs()){
			$themes=self::getThemes();
			foreach($themes as $selected){
				if($selected==$theme){
					if(!$_COOKIE['__SKINMANAGER-basic-THEME']){
						setcookie('__SKINMANAGER-basic-THEME',$theme,time()+(86400*30),'/');
						$_COOKIE['__SKINMANAGER-basic-THEME']=$theme;
						return true;
					}
					return false;
				}
			}
			self::setDefaultTheme($themes[0]);
		}
    }
	/**
	 * Возвращаем настройки
	 * --------------------
	 * @return string
	 */
	public function getSettings(){
		$item=[];
		$action=x::getPathModules('skinmanager'.DIRECTORY_SEPARATOR."skinmanager.php");
		//theme
		$MySkin=self::getSkin();
		$MyTheme=self::getTheme();
		//redirect
		$redirect=x::geturi();
		foreach(self::getSkins() as $skin){
			$item+=[$skin=>['href'=>$action]];
		}
		//-->Настройка темы
    	switch($MySkin){
    		case 'uikit3':
    			$theme=[];
    			$theme+=['Светло-белая'=>['href'=>$action]];
    			$theme+=['Светло-синия'=>['href'=>$action]];
    			$theme+=['Светло-красная'=>['href'=>$action]];
    			$theme+=['Россия'=>['href'=>$action]];
    			$theme+=['Тёмная'=>['href'=>$action]];
    			$theme=self::p(['content'=>self::combobox(['id'=>'theme',$theme])]);
    		break;
    		case 'bootstrap3':
    			$theme=[];
    			$theme+=['Светло-белая'=>['href'=>$action]];
    			$theme+=['Светло-синия'=>['href'=>$action]];
    			$theme+=['Светло-зеленная'=>['href'=>$action]];
    			$theme+=['Светло-голубая'=>['href'=>$action]];
    			$theme+=['Светло-желтая'=>['href'=>$action]];
    			$theme+=['Светло-красная'=>['href'=>$action]];
    			$theme+=['gentoo'=>['href'=>$action]];
    			$theme=self::p(['content'=>self::combobox(['id'=>'theme',$theme])]);
    		break;
    		case 'bootstrap4':
    			$theme=[];
    			$theme+=['Светло-белая'=>['href'=>$action]];
    			$theme+=['Светло-синия'=>['href'=>$action]];
    			$theme+=['Светло-зеленная'=>['href'=>$action]];
    			$theme+=['Светло-голубая'=>['href'=>$action]];
    			$theme+=['Светло-желтая'=>['href'=>$action]];
    			$theme+=['Светло-красная'=>['href'=>$action]];
    			$theme+=['Тёмная'=>['href'=>$action]];
    			$theme+=['gentoo'=>['href'=>$action]];
    			$theme=self::p(['content'=>self::combobox(['id'=>'theme',$theme])]);
    		break;
    		case 'basic':
    			//lightbox
    			$lightbox.=self::p(['content'=>self::input(['type'=>'checkbox','name'=>'__LIGHTBOX_VIEW','value'=>'Подгрузка изоброжение','checked'=>$_COOKIE['__LIGHTBOX_VIEW']])]);
    			$submit=self::input(['type'=>'submit','value'=>'Изменить']);
    			self::modal([
					'id'=>'lightbox',
					'title'=>'Конфигурация lightbox',
					'content'=>self::form([
				            	'action'=>x::getPathModules('skinmanager'.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'basic'.DIRECTORY_SEPARATOR."lightbox.php"),
				          		'method'=>'post',
				          		'id'=>x::RedirectUpdate(),
				            	'content'=>$lightbox.$submit
				    	])
					]);
    			//-->Выполнить
    			$themeItem=[];
    			foreach(self::getThemes() as $theme){
    				$themeItem+=[$theme=>['href'=>$action]];
    			}
    			$theme=self::p(['content'=>self::combobox(['id'=>'theme','name'=>'__THEME_BASIC','selected'=>$MyTheme,$themeItem])]);
				$submit=self::p([
        				'content'=>self::input([
                				'type'=>'submit',
                    			'value'=>'Изменить :)'
                    		])
						]);
    		break;
    	}
		$location="$action?skin=";
		$skin=self::p(['content'=>self::combobox(['selected'=>$MySkin,'onChange'=>"window.location=\"$location\"+document.getElementById(\"skin\").options[document.getElementById(\"skin\").selectedIndex].value+\"&redirect=$redirect\"",'id'=>'skin','name'=>'skin',$item])]);
    	//-->Выбранный скин описание
    	$skin=self::text(['text'=>'Скин:'.self::badge(['txt'=>$MySkin])]).$skin;
    	$theme=self::text(['text'=>'Тема:'.self::badge(['id'=>'theme-label','txt'=>$MyTheme])]).$theme;
		return self::modal([
			'id'		=>	'skinmanager',
			'title'		=>	self::ico('adjust').' Скин менеджер'.self::getVersion(),
			'content'	=>	self::form([
                    	'action'=>$action,
                    	'id'=>x::RedirectUpdate(),
                  		'method'=>'post',
                    	'content'=>$skin.$theme.$submit
            	])
			]);
	}
//-------------------------------------------------------------------------------------------------------------
    /**
     * Возвращает кнопку (button)
     * ---------------------------
     * id			-	Индентификатор
     * title		-	Загаловок
     * enabled		-	Доступность
     * type			-	Тип (button, reset, submit)
     * formaction	-	Переход на другой url (<form> необходим)
     * modal		-	Модальная форма
     * theme		-	Тема
	 * css			-	Стиль
     * ---------------------------
     * @return string
     */
	public function btn($opt){
		$id			=	$opt['id'];
		$title		=	$opt['title'];
		$enabled	=	$opt['enabled'];
		$type		=	$opt['type'];
		$formaction	=	$opt['formaction'];
		$modal		=	$opt['modal'];
		$theme		=	$opt['theme'];
		$css		=	$opt['css'];
		//-->Загаловок
		if (!$title) {
			$title		=	'Нажми меня :)';
		}
		//-->Доступность
		if (!isset($enabled)) {
			$enabled	=	true;
		}
        if (!$enabled) {
			$enabled	=	'disabled';
		} else {
			$enabled	=	'';
		}
		//-->Тип (button, reset, submit)
		if (!$type) {
			$type		=	'button';
		}
		if (!$type) {
			$type		=	'button';
		}
		//-->Тема
		if (!$theme) {
			$theme		=	'default';
		}
		return	self::import(__FUNCTION__,['id'=>$id,'title'=>$title,'enabled'=>$enabled,'type'=>$type,'formaction'=>$formaction,'modal'=>$modal,'theme'=>$theme,'css'=>$css]);
	}
	/**
	 * Возвращаем ссылку <a>
	 * ----------------------
	 * title	-	Загаловок
	 * href		-	Ссылка
	 * modal	-	Модальная форма
	 * theme	-	Тема
	 * css		-	Стиль
	 * class	-	классы
	 * ----------------------
	 * @return string
	 */
	public function a($opt){
		$title	=	$opt['title'];
		$href	=	$opt['href'];
		$modal	=	$opt['modal'];
		$theme	=	$opt['theme'];
		$css	=	$opt['css'];
		$class	=	$opt['class'];
		return self::import(__FUNCTION__,['title'=>$title,'href'=>$href,'modal'=>$modal,'theme'=>$theme,'css'=>$css,'class'=>$class]);
	}
	/**
	 * Возвращаем форму <form>
	 * ------------------------
	 * id		-	Индентификатор
	 * name		-	Имя формы
	 * method	-	Метод отправки (get, post)
	 * action	-	Выполнение пути
	 * content	-	Контент
	 * enctype	-	Метод кодировки (application/x-www-form-urlencoded,multipart/form-data,text/plain)
	 * css		-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function form($opt){
		$id=$opt['id'];
		$name=$opt['name'];
		$method=$opt['method'];
		$action=$opt['action'];
		$content=$opt['content'];
		$enctype=$opt['enctype'];
		$css=$opt['css'];
		return self::import(__FUNCTION__,['id'=>$id,'name'=>$name,'method'=>$method,'action'=>$action,'content'=>$content,'enctype'=>$enctype,'css'=>$css]);}

	/**
	 * Возвращаем input (input)
	 * -------------------------
	 * id			-	Индентификатор
	 * form			-	Индентификатор
	 * name			-	Имя
	 * type			-	Тип (button, checkbox, color, date, datetime-local, email, file, hidden, image, month, number, password, radio, range, reset, search, submit, tel, text, time, url, week)
	 * value		-	Значение
	 * placeholder	-	Подсказка
	 * size			-	Ширина объекта
	 * width		-	Ширина
	 * enabled		-	Доступность
	 * readonly     -   Чтивость
	 * class 		-	Класс
	 * checked		-	Выбранный компонент (radio)
	 * required		-	Проверка
	 * min			-	Минимальный размер (number)
	 * max			-	Максимальный размер (number)
	 * accept		-	Расширение для загрузки (file) (video/*,image/*,audio/*)
	 * multiple		-	Отправлять файлы сразу несколько (file)
	 * step         -   Шаг числа (number)
	 * pattern      -   Выражение
	 * theme		-	Тема
	 * css			-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function input($opt){
	    $id             =   $opt['id'];
    	$form			=	$opt['form'];
		$name			=	$opt['name'];
		$type			=	$opt['type'];
		$value			=	$opt['value'];
		$placeholder	=	$opt['placeholder'];
		$size			=	$opt['size'];
		$width			=	$opt['width'];
		$enabled		=	$opt['enabled'];
		$readonly		=	$opt['readonly'];
		$class			=	$opt['class'];
		$checked		=	$opt['checked'];
		$required		=	$opt['required'];
		$min			=	$opt['min'];
		$max			=	$opt['max'];
		$accept			=	$opt['accept'];
		$multiple		=	$opt['multiple'];
		$step			=	$opt['step'];
		$pattern		=	$opt['pattern'];
		$theme			=	$opt['theme'];
		$css			=	$opt['css'];
		if(substr($width,-1)=='%'){

		}else{
			if($width){
				$width.='px';
			}else{
				$width=null;
			}
		}
		//-->Доступность
		if(!isset($enabled)){
			$enabled	=	true;
		}
        if(!$enabled){
			$enabled	=	'disabled';
		}else{
			$enabled	=	'';
		}
		if(!$theme){
			$theme		=	'default';
		}
		if(!$type){
			$type		=	'input';
		}
    	if($type=='checkbox'){
        	if($checkbox){
            	$checkbox=true;
            }
        }
		return self::import(__FUNCTION__, [
		    'id'            =>  $id,
        	'form'			=>	$form,
			'name'			=>	$name,
			'type'			=>	$type,
			'value' 		=>	$value,
			'placeholder'	=>	$placeholder,
			'size'			=>	$size,
			'width'			=>	$width,
			'enabled'		=>	$enabled,
			'readonly'		=>	$readonly,
			'class'			=>	$class,
			'checked'		=>	$checked,
			'required'		=>	$required,
			'min'			=>	$min,
			'max'			=>	$max,
			'accept'		=>	$accept,
			'multiple'		=>	$multiple,
			'step'			=>	$step,
			'pattern'		=>	$pattern,
			'theme'			=>	$theme,
			'css'			=>	$css
		]);
	}
	/**
	 * Возвращаем многострочное поле (textarea)
	 * -----------------------------------------
	 * enabled		-	Доступность
	 * readonly		-	Чтивость
	 * name 		-	Имя
	 * placeholder	-	Подсказка
	 * value		-	Значение
	 * rows			-	Наборы или возврат значения атрибута строк области текста
	 * required		-	Проверка
	 * theme		-	Тема
	 * css			-	Стиль
	 * -----------------------------------------
	 * @return string
	 */
	public function textarea($opt){
		$enabled		=	$opt['enabled'];
		$readonly		=	$opt['readonly'];
		$name			= 	$opt['name'];
		$placeholder	= 	$opt['placeholder'];
		$value			= 	$opt['value'];
		$rows			=	$opt['rows'];
		$required		=	$opt['required'];
		$theme			=	$opt['theme'];
		$css			=	$opt['css'];
		//-->Доступность
		if(!isset($enabled)){
			$enabled	=	true;
		}
        if(!$enabled){
			$enabled	=	'disabled';
		}else{
			$enabled	=	'';
		}
		return self::import(__FUNCTION__,[
			'enabled'		=>	$enabled,
			'readonly'		=>	$readonly,
			'name'			=>	$name,
			'placeholder'	=>	$placeholder,
			'value'			=>	$value,
			'rows'			=>  $rows,
			'required'		=>	$required,
			'theme'			=>	$theme,
			'css'			=>	$css
		]);
	}
	/**
     * Возвращаем текст (label)
     * -----------------------
     * text		-	Текст
     * for		-	Идентификатор элемента, с которым следует установить связь. (input - ID)
     * css		-	Стиль
     * -----------------------
     * @return string
     */
	public function label($opt){
		$text=$opt['text'];
		$for=$opt['for'];
		$css=$opt['css'];
		return self::import(__FUNCTION__, ['text'=>$text,'for'=>$for,'css'=>$css]);
	}
	/**
	 * Возвращаем текст (text)
	 * ------------------------
	 * txt		-	Текст
	 * theme	-	Тема
	 * css		-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function text($opt){
		$txt=$opt['text'];
		$theme=$opt['theme'];
		$css=$opt['css'];
		return self::import(__FUNCTION__, [
			'text'	=>	$txt,
			'theme'	=>	$theme,
			'css'	=>	$css
		]);
	}
	/**
	 * Возвращаем лист в виде массива
	 * ------------------------------
	 * txt-Массив текста
	 * theme	-	Тема
	 * css		-	Стиль
	 * ------------------------------
	 */
	public function ListText($opt){
		$txtArr=$opt['txt'];
		$theme=$opt['theme'];
		$css=$opt['css'];
		return self::import(__FUNCTION__,[
			'txt'	=>	$txtArr,
			'theme'	=>	$theme,
			'css'	=>	$css
		]);
	}
    /**
     * Возвращаем картинку (img)
     * --------------------------
     * src	-	Изоброжение
	 * css	-	Стиль
     * --------------------------
     * @return string
     */
	public function img($opt){
		$src	=	$opt['src'];
		$css	=	$opt['css'];
		return self::import(__FUNCTION__, [
			'src'	=>	$src,
			'css'	=>	$css
		]);
	}
	/**
	 * Возвращаем обводку (border)
	 * ----------------------------
	 * body		-	Тело
	 * size		-	Размер
	 * content	-	Контент
	 * theme	-	Тема
	 * css		-	Стиль
	 * ----------------------------
	 * @return string
	 */
	public function border($opt){
		$body		=	$opt['body'];
		$size		=	$opt['size'];
		$content	=	$opt['content'];
		$theme		=	$opt['theme'];
		$css		=	$opt['css'];
		if (!$content) {
			$content=	'Контент пуст пожалуйста обновите страницу или повторите попытку снова! :)';
		}
		if (!$size) {
			$size	=	'small';
		}
		if (!isset($body)) {
			$body	=	true;
		}
		return self::import(__FUNCTION__, [
			'body'		=>	$body,
			'size'		=>	$size,
			'content'	=>	$content,
			'theme'		=>	$theme,
			'css'		=>	$css
		]);
	}
	/**
	 * Возвращаем панель (panel)
	 * -------------------------
	 * title	-	Загаловок
	 * theme	-	Тема
	 * content	-	Контент
	 * css		-	Стиль
	 * -------------------------
	 * @return string
	 */
	public function panel($opt){
		$title		=	$opt['title'];
    	$theme		=	$opt['theme'];
		$content	=	$opt['content'];
		$css		=	$opt['css'];
		if (!$title) {
			$title	=	'Без название';
		}
		if (!$content){
			$content=	'Контент пуст пожалуйста обновите страницу или повторите попытку снова! :)';
		}
		return self::import(__FUNCTION__, [
        	'title'		=>	$title,
            'theme'		=>	$theme,
        	'content'	=>	$content,
			'css'		=>	$css
        ]);
	}
	/**
	 * Возвращаем панель (panel)
	 * -------------------------
	 * title	-	Загаловок
	 * content	-	Контент
	 * css		-	Стиль
	 * -------------------------
	 * @return string
	 */
	public function panelToArray($opt){
		$arr=$opt['data'];
		$theme=$opt['theme'];
		$css=$opt['css'];
		$data=[];
		foreach($arr as $title=>$content){
			if(gettype($title)=='integer'){
				$title="Без название $title";
			}
			if(!$content){
				$content='Контент пуст пожалуйста обновите страницу или повторите попытку снова! :)';
			}
			$data[$title]=$content;
		}
		return explode("\n",self::import(__FUNCTION__,['data'=>$data,'theme'=>$theme,'css'=>$css]));
	}
    /**
     * Возвращаем выпадающий список (combobox)
     * ----------------------------------------
     * name		-	Имя
	 * selected	-	Выбранный элемент
     * theme	-	Тема
	 * css		-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function combobox($opt){
		$name		=	$opt['name'];
		$selected	=	$opt['selected'];
		$onChange	=	$opt['onChange'];
		$id			=	$opt['id'];
		$theme		=	$opt['theme'];
		$css		=	$opt['css'];
		return self::import(__FUNCTION__, [
			'name'		=>	$name,
			'selected'	=>	$selected,
			'onChange'	=>	$onChange,
			'id'		=>	$id,
			'theme'		=>	$theme,
			'css'		=>	$css,$opt[0]
		]);
	}
	/**
	 * Возвращаем выпадающий список (dropdown)
	 * ----------------------------------------
	 * 1.title		-	Создание нового списка
	 * 	2.css		-	Стиль
	 *	2.content	-	контент который перед списком
	 *	2.item		-	ячейка
	 *		3.title	-	название ячейки
	 * 			4.href	-	ссылка перехода
	 *			4.modal	-	модальный режим
     * ----------------------------------------
     * @return string
	 */
	public function dropdown(array $opt=[string=>['theme'=>'default','css'=>[],'content'=>[],'item'=>[string=>['href','modal']]]]){$arr=[$opt];return self::import(__FUNCTION__,$arr);}
	/**
	 * Возвращаем лист (listview)
	 * ---------------------------
	 * form		-	Форм
	 * name		-	Имя
	 * size		-	Размер (не меньше чем 2)
	 * required	-	В это поля явно нужно что-то вести
	 * form		-	Форма
	 * css		-	Cтиль
	 * ---------------------------
	 * @return string
	 */
	public function listView($opt){
		$name		=	$opt['name'];
		$size		=	$opt['size'];
		$required	=	$opt['required'];
		$form		=	$opt['form'];
		$css		=	$opt['css'];
		if(is_numeric($size)&&$size<2){
			$size	=	8;
		}else{
			$size	=	8;
		}
		return self::import(__FUNCTION__, [
        	'name'		=>	$name,
        	'size'		=>	$size,
        	'required'	=>	$required,
        	'form'		=>	$form,
        	'css'		=>	$css,
        	$opt[0]
        ]);
	}
	/**
	 * Возвращаем метку (badge)
	 * ------------------------
	 * id	-	Индентификатор
	 * text	-	Текст
	 * css	-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function badge($opt=[]){
		$id=$opt['id'];
		$txt=$opt['txt'];
		$css=$opt['css'];
		return self::import(__FUNCTION__,[
			'id'=>$id,
			'txt'=>$txt,
			'css'=>$css
		]);
	}
	/**
	 * Возвращаем сеппаратор (Разделитель)
	 * size - 	Размер
	 * css	-	Стиль
	 */
	public function sep($opt=[]){
		$size=$opt['size'];
		$css=$opt['css'];
		return self::import(__FUNCTION__,[
			'size'	=>	$size,
			'css'	=>	$css
		]);
	}
	/**
	 * Возвращаем (p)
	 * ---------------
	 * theme	-	Тема
	 * content	-	Контент
	 * css		-	Стиль
	 * ---------------
	 * @return string
	 */
	public function p($opt){
		$content	=	$opt['content'];
		$theme		=	$opt['theme'];
		$css		=	$opt['css'];
		return self::import(__FUNCTION__,['theme'=>$theme,'content'=>$content,'css'=>$css]);
	}
	/**
	 * Возвращаем иконку (ico)
	 * -------------------------
	 * ico	-	иконка
	 * -------------------------
	 * @return string
	 */
	public function ico($ico){return self::import(__FUNCTION__,$ico);}
	/**
	 * Возвращаем видеоплеер
	 * -------------------------
	 * src-Путь к файлу
	 * width-Ширина
	 * height-Высота
	 * controls-Панель управление
	 * preload-Используется для загрузки видео вместе с загрузкой веб-страницы.
	 * autoplay-Включение видео
	 * muted-Без звука
	 * loop-Зацикленность
	 * -------------------------
	 * @return string
	 */
	public function video($opt){
		$src=$opt['src'];
		$width=$opt['width'];
		$height=$opt['height'];
		$controls=$opt['controls'];
		$preload=$opt['preload'];
		$autoplay=$opt['autoplay'];
		$muted=$opt['muted'];
		$loop=$opt['loop'];
		$css=$opt['css'];
    	return self::import(__FUNCTION__,['src'=>$src,'width'=>$width,'height'=>$height,'controls'=>$controls,'preload'=>$preload,'autoplay'=>$autoplay,'muted'=>$muted,'loop'=>$loop,'css'=>$css]);
    }
	/**
	 * Возвращаем модальная форма (modal)
	 * -----------------------------------
	 * id		-	Индентификатор
	 * title	-	Название
	 * exit		-	Кнопка выход
	 * content	-	Контент
	 * open		-	При загрузки сайта (Открытие автоматически форму)
	 * css		-	Стиль
	 * -----------------------------------
	 * @return string
	 */
	public function modal($opt){
		$id=$opt['id'];
		$title=$opt['title'];
		$exit=$opt['exit'];
		$content=$opt['content'];
		$open=$opt['open'];
		$css=$opt['css'];
		if(empty($id)){
			$id=x::uuidv4();
		}
		if(!isset($exit)){
			$exit=true;
		}
		return self::import(__FUNCTION__,[
        	'id'		=>	$id,
        	'title'		=>	$title,
        	'exit'		=>	$exit,
        	'content'	=>	$content,
        	'open'		=>	$open,
			'css'		=>	$css
        ]);
	}
	/**
	 * Возвращаем открытую модальную форму (modal)
	 * -----------------------------------
	 * id		-	Название
	 * -----------------------------------
	 * @return string
	 */
	public function OpenModal($id){
		return self::import(__FUNCTION__,$id);
	}
	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице ()
	 * indent	-	Отступы (5)
	 * content	-	Массив с содержимом
	 */
	public function pagination($opt){
    	$max=$opt['max'];
    	$indent=$opt['indent'];
    	if($max<1){
        	$max=1;
        }
    	if($indent<1){
        	$indent=1;
        }
    	return self::import(__FUNCTION__,['max'=>$max,'indent'=>$indent,'data'=>$opt['data']]);
    }
	/**
	 * Возвращаем lightbox (lightbox)
	 * -------------------------------
	 * src		-	Изоброжение
	 * stretch	-	Растягивание
	 * max		-	Максимальный контент
	 * -------------------------------
	 * @return string
	 */
	public function lightbox($opt){
		$src=$opt['src'];
		$max=$opt['max'];
    	$stretch=$opt['stretch'];
    	$css=$opt['css'];
		if(!$src){
			 $src="https://proxy.duckduckgo.com/iu/?u=https%3A%2F%2Ftse1.mm.bing.net%2Fth%3Fid%3DOIP.8glp8zu2x8_WnEUNmca7JAHaFT%26pid%3DApi&f=1";
		}
		return self::import(__FUNCTION__,['src'=>$src,'stretch'=>$stretch,'max'=>$max,'css'=>$css]);
	}
}

