<?php
function getSkins () {
	$ls = scandir('theme');
	array_shift($ls);
	array_shift($ls);
	return $ls;
}
if ($_GET['skin']) {
	$skin	=	$_GET['skin'];
	$url	=	$_GET['redirect'];
} else {
	$skin	=	$_POST['skin'];
	$url	=	$_POST['redirectSkin'];
}
if (isset($skin)) {
	foreach(getSkins() as $theme) {
		if ($skin == $theme) {
			setcookie("__SKINMANAGER_SKIN", $skin, time() + (86400 * 30), '/');
			echo "Скин успешно сменился ;) На $skin";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
			die();
		}
	}
	setcookie("__SKINMANAGER_SKIN", 'basic', time() + (86400 * 30), '/');
	echo "Скин успешно сменился ;) На basic";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
}

/**
 * Скин менеджер
 * --------------
 * ver beta 1.37
 */
class skinmanager extends xlib {

	/**
	 * Возвращаем версию модуля
	 * ------------------------
	 * @return string
	 */
	public function getVersion () {
		return ' (' . __CLASS__ . ' ' . skinmanager::badge('beta 1.37') . ')';
	}

	/**
	 * Выполнить
	 * ----------
	 */
	function __construct($skin = false) {
		if ($_COOKIE['__SKINMANAGER_SKIN'] == false) {
			//$GLOBALS['__SKINMANAGER_SKIN'] = 'basic';
		} else {
			if (!$skin) {
				foreach($this->getSkins() as $theme) {
					if($theme == $_COOKIE['__SKINMANAGER_SKIN']) {
						$GLOBALS['__SKINMANAGER_SKIN'] = $_COOKIE['__SKINMANAGER_SKIN'];
					}
				}
				if (!$GLOBALS['__SKINMANAGER_SKIN']) {
					$GLOBALS['__SKINMANAGER_SKIN'] = 'basic';
				}
			} else {
				if ($_COOKIE['__SKINMANAGER_SKIN'] != $skin) {
					setcookie("__SKINMANAGER_SKIN", $skin, time() + (86400 * 30), '/');
				}
				$GLOBALS['__SKINMANAGER_SKIN'] = $skin;
			}
		}
	}

	/**
	 * Принять (css)
	 * -------------------
	 */
	function ApplySkin () {
		$skin	=	skinmanager::getSkin();
		$module	=	'plugins' . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR .  $skin . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;
		$css = scandir($_SERVER['DOCUMENT_ROOT'] . $this->getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR .  $skin . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR));
		array_shift ($css);
		array_shift ($css);
		foreach ($css as $val) {
			$this->add_css([$module . $val], null);
		}
	}

	/**
	 * Принять (js)
	 * ------------------
	 */
	public function ApplyJs () {
		$skin	=	skinmanager::getSkin();
    	$xlib	=	new xlib();
		$jquery	=	new jquery();
		$module	=	'plugins' . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR .  $skin . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;
		$js = scandir($_SERVER['DOCUMENT_ROOT'] . $xlib->getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR));
		array_shift ($js);
		array_shift ($js);
    	$icoarr = [];
		foreach ($js as $val) {
   			$str = explode('-', $val);
        	foreach ($str as $sw) {
            	switch ($sw) {
                	case 'icons.min.js':
                		array_push($icoarr, $val);
                		array_shift($js);
                	break;
                }
            }
		}
    	foreach ($js as $jsd) {
        	$xlib->add_js([$module . $jsd], null);
        }
    	foreach ($icoarr as $ico) {
        	$xlib->add_js([$module . $ico], null);
        }
	}

	/**
	 * Выполнение после погрузки страницы
	 * --------------------------------------
	 */
	function footerExecute () {
		$xlib = new xlib();
		if ($_GET['redirectSkin'] && !$_GET['redirect']) {
			$redirect = $_GET['redirectSkin'];
		} elseif ($_GET['redirect']) {
			$redirect = $_GET['redirect'];
		} else {
			$redirect = $_SERVER['REQUEST_URI'];
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			require_once $_SERVER['DOCUMENT_ROOT'] . $xlib->getTheme() . $xlib->getPlatform() . DIRECTORY_SEPARATOR . $xlib->getLibPath() . DIRECTORY_SEPARATOR . 'footer.php';
			$footer = new footer();
			$footer->execute();
			skinmanager::ApplyJs();
		} else {
			skinmanager::ApplyJs();
		}
		if ($_COOKIE['__SKINMANAGER_SKIN'] != 'basic' && $_SERVER['REQUEST_METHOD'] != 'POST' && $GLOBALS['__SKINMANAGER_SKIN'] != 'basic') {
			setcookie("__SKINMANAGER_SKIN", 'basic', time() + (86400 * 30), '/');
			$href = $xlib->getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . "skinmanager.php?skin=basic&redirect=$redirect");
			echo "<noscript><meta http-equiv=\"refresh\" content=\"0;url=$href\"></noscript>";
		}
		echo skinmanager::getSuperBox();
	}

	/**
	 * Возвращаем имя выбранного скина
	 * --------------------------
	 * @return string
	 */
	public function getSkin() {
		if (!$GLOBALS['__SKINMANAGER_SKIN']) {
			if (!$_COOKIE['__SKINMANAGER_SKIN']) {
				return 'basic';
			} else {
				return $_COOKIE['__SKINMANAGER_SKIN'];
			}
		} else {
			return $GLOBALS['__SKINMANAGER_SKIN'];
		}
	}

	/**
	 * Возвращаем скины
	 * -------------------------------
	 * @return Array
	 */
	public function getSkins () {
		$script = explode(DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF']);
		if($script[1] == 'theme') {
			$theme = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $script[1] . DIRECTORY_SEPARATOR . $script[2] . DIRECTORY_SEPARATOR . $script[3] . DIRECTORY_SEPARATOR . $script[4] . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'theme';
		} else {
			$xlib = new xlib();
			$theme = '.' . $xlib->getPathModules('skinmanager' . DIRECTORY_SEPARATOR . 'theme');
		}
		$ls = scandir($theme);
		array_shift($ls);
		array_shift($ls);
		return $ls;
	}

	/**
	 * Возвращаем значение виджета
	 * ----------------------------
	 * tag	-	Виджет
	 * arr	-	Опций
	 * ----------------------------
	 * @return string
	 */
	public function import ($tag, $arr) {
		foreach ($this->getSkins() as $skin) {
			if ($skin == $this->getSkin()) {
				require_once 'theme' . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR . "$skin.php";
				if(is_callable(array($skin, $tag))) {
					$func = array($skin, $tag);
					return $func($arr);
				}
			}
		}
		require_once 'theme' . DIRECTORY_SEPARATOR . 'basic' . DIRECTORY_SEPARATOR . 'basic.php';
		if(is_callable(array('basic', $tag))) {
			$func = array('basic', $tag);
			return $func($arr);
		}
	}

	/**
	 * Установить в суперкоробку
	 * --------------------------
	 * content - контент
	 * --------------------------
	 * @return string
	 */
	public function setSuperBox ($content) {
		if(!skinmanager::getSuperBox()) {
			$GLOBALS['__SUPER_BOX'] .= $content;
		} else {
			$GLOBALS['__SUPER_BOX'] .= $content;
		}
	}

	/**
	 * Возвращаем суперкоробку
	 * ------------------------
	 * @return string
	 */
	public function getSuperBox () {
		return	$GLOBALS['__SUPER_BOX'];
	}

	/**
	 * Устанавливает стандартный скин
	 */
	public function setDefault ($skin = 'bootstrap337') {
    	if (!$_COOKIE['__SKINMANAGER_SKIN']) {
        	setcookie('__SKINMANAGER_SKIN', $skin, time() + (86400 * 30), '/');
        	$xlib	=	new xlib();
			if ($_GET['redirectSkin'] && !$_GET['redirect']) {
				$redirect = $_GET['redirectSkin'];
			} elseif ($_GET['redirect']) {
            	$redirect = $_GET['redirect'];
			} else {
				$redirect = $_SERVER['REQUEST_URI'];
			}
        	$href	=	$xlib->getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . "skinmanager.php?skin=$skin&redirect=$redirect");
        	echo "<meta http-equiv=\"refresh\" content=\"0;url=$href\">";
        }
    }

	/**
	 * Возвращаем настройки
	 * --------------------
	 * @return string
	 */
	public function getSettings() {
		$xlib		=	new xlib();
		$item		=	[];
		$action		=	$xlib->getPathModules('skinmanager' . DIRECTORY_SEPARATOR . "skinmanager.php");
		//-->redirect
		if ($_POST['redirectSkin'] && !$_POST['redirect']) {
			$redirect = $_POST['redirectSkin'];
		} elseif ($_POST['redirect']) {
			$redirect = $_POST['redirect'];
		} else {
			$redirect = $_SERVER['REQUEST_URI'];
		}
		$redirect	=	skinmanager::input([
						'name'	=>	'redirectSkin',
						'type'	=>	'hidden',
						'value'	=>	$redirect
					]);
		foreach (skinmanager::getSkins() as $skin) {
			if ($skin != skinmanager::getSkin()) {
				$item += [$skin => ['href' => $action]];
			}
		}
    	$change	=	skinmanager::p([
						'content' =>skinmanager::combobox(['name' => 'skin', $item])
					]);
    	//-->Картинка
        $img	=	skinmanager::img([
						'src' => $xlib->getPathModules('skinmanager' . DIRECTORY_SEPARATOR . 'sakuraBlue.jpg')
					]);
    	//-->Выбранный скин
    	$selected	=	skinmanager::text([
							'text'		=> 'Выбран:' . skinmanager::badge(skinmanager::getSkin()), 
							'css' 		=> ['margin' => '0px']
    					]);
		//-->Выполнить
		$submit		=	skinmanager::p([
        					'content' => skinmanager::input([
                				'type'	=>	'submit',
                    			'value'	=>	'Изменить :)'
                    		])
						]);
		return skinmanager::modal([
			'id'		=>	'skinmanager',
			'title'		=>	skinmanager::ico('adjust') . ' ' . 'Скин менеджер' . skinmanager::getVersion(),
			'content'	=>	$xlib->div([
            		'css'		=>	['display' => 'flex'],
            		'content'	=> $img	.	skinmanager::form([
                    	'action'	=>	$action,
                  		'method'	=>	'post',
                    	'content'	=>	$selected	.	$change	.	$submit	.	$redirect
               	 	])
            	])
			]);
	}
//-------------------------------------------------------------------------------------------------------------
    /**
     * Возвращает кнопку (button)
     * ---------------------------
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
	public function btn ($opt) {
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
		$arr	=	[
			'title'		=>	$title,
			'enabled'	=>	$enabled,
			'type'		=>	$type,
			'formaction'=>	$formaction,
			'modal'		=>	$modal,
			'theme'		=>	$theme,
			'css'		=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем ссылку <a>
	 * ----------------------
	 * title	-	Загаловок
	 * href		-	Ссылка
	 * modal	-	Модальная форма
	 * theme	-	Тема
	 * css		-	Стиль
	 * ----------------------
	 * @return string
	 */
	public function a ($opt) {
		$title	=	$opt['title'];
		$href	=	$opt['href'];
		$modal	=	$opt['modal'];
		$theme	=	$opt['theme'];
		$css	=	$opt['css'];
		$arr	=	[
			'title'	=>	$title,
			'href'	=>	$href,
			'modal'	=>	$modal,
			'theme'	=>	$theme,
			'css'	=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем форму <form>
	 * ------------------------
	 * id		-	Индентификатор
	 * name		-	Имя формы
	 * method	-	Метод отправки (get, post)
	 * action	-	Выполнение пути
	 * content	-	Контент
	 * css		-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function form ($opt) {
		$id		=	$opt['id'];
		$name	=	$opt['name'];
		$method	=	$opt['method'];
		$action	=	$opt['action'];
		$css	=	$opt['css'];
		$content	=	$opt['content'];
		$arr	=	[
			'id'		=>	$id,
			'name'		=>	$name,
			'method'	=>	$method,
			'action'	=>	$action,
			'content' 	=>	$content,
			'css'		=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем input (input)
	 * -------------------------
	 * form			-	Индентификатор
	 * name			-	Имя
	 * type			-	Тип (button, checkbox, color, date, datetime-local, email, file, hidden, image, month, number, password, radio, range, reset, search, submit, tel, text, time, url, week)
	 * value		-	Значение
	 * placeholder	-	Подсказка
	 * size			-	Ширина объекта
	 * width		-	Ширина
	 * enabled		-	Доступность
	 * class 		-	Класс
	 * checked		-	Выбранный компонент (radio)
	 * required		-	Проверка
	 * min			-	Минимальный размер (number)
	 * max			-	Максимальный размер (number)
	 * style		-	Стиль
	 * theme		-	Тема
	 * ------------------------
	 * @return string
	 */
	public function input ($opt) {
    	$form			=	$opt['form'];
		$name			=	$opt['name'];
		$type			=	$opt['type'];
		$value			=	$opt['value'];
		$placeholder	=	$opt['placeholder'];
		$size			=	$opt['size'];
		$width			=	$opt['width'];
		$enabled		=	$opt['enabled'];
		$class			=	$opt['class'];
		$checked		=	$opt['checked'];
		$required		=	$opt['required'];
		$min			=	$opt['min'];
		$max			=	$opt['max'];
		$theme			=	$opt['theme'];
		$css			=	$opt['css'];
		if (substr($width, -1) == '%') {

		} else {
			if ($width) {
				$width	.=	'px';
			} else {
				$width	=	null;
			}
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
		if (!$theme) {
			$theme		=	'default';
		}
		if (!$type) {
			$type		=	'input';
		}
    	if ($type == 'checkbox') {
        	if ($checkbox) {
            	$checkbox = true;
            }
        }
		$arr	=	[
        	'form'			=>	$form,
			'name'			=>	$name,
			'type'			=>	$type,
			'value' 		=>	$value,
			'placeholder'	=>	$placeholder,
			'size'			=>	$size,
			'width'			=>	$width,
			'enabled'		=>	$enabled,
			'class'			=>	$class,
			'checked'		=>	$checked,
			'required'		=>	$required,
			'min'			=>	$min,
			'max'			=>	$max,
			'theme'			=>	$theme,
			'css'			=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем многострочное поле (textarea)
	 * -----------------------------------------
	 * name 		-	Имя
	 * placeholder	-	Подсказка
	 * value		-	Значение
	 * theme		-	Тема
	 * css			-	Стиль
	 * -----------------------------------------
	 * @return string
	 */
	public function textarea ($opt) {
		$name			= 	$opt['name'];
		$placeholder	= 	$opt['placeholder'];
		$value			= 	$opt['value'];
		$theme			=	$opt['theme'];
		$css			=	$opt['css'];
		$arr 			= 	[
			'name'			=>	$name,
			'placeholder'	=>	$placeholder,
			'value'			=>	$value,
			'theme'			=>	$theme,
			'css'			=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем текст (text)
	 * ------------------------
	 * text		-	Текст
	 * theme	-	Тема
	 * css		-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function text ($opt) {
		$text	=	$opt['text'];
		$theme	=	$opt['theme'];
		$css	=	$opt['css'];
		$arr	=	[
			'text'	=>	$text,
			'theme'	=>	$theme,
			'css'	=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
	}

    /**
     * Возвращаем картинку (img)
     * --------------------------
     * src	-	Изоброжение
	 * css	-	Стиль
     * --------------------------
     * @return string
     */
	public function img ($opt) {
		$src	=	$opt['src'];
		$css	=	$opt['css'];
		$arr	=	[
			'src'	=>	$src,
			'css'	=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
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
	public function border ($opt) {
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
		$arr	=	[
			'body'		=>	$body,
			'size'		=>	$size,
			'content'	=>	$content,
			'theme'		=>	$theme,
			'css'		=>	$css
		];
		return	$this->import(__FUNCTION__, $arr);
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
	public function combobox ($opt) {
		$name		=	$opt['name'];
		$selected	=	$opt['selected'];
		$theme		=	$opt['theme'];
		$css		=	$opt['css'];
		$arr	=	[
			'name'		=>	$name,
			'selected'	=>	$selected,
			'theme'		=>	$theme,
			'css'		=>	$css,
		       	$opt[0]
		];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем выпадающий список (dropdown)
	 * ----------------------------------------
	 * 1.title		-	Создание нового списка
	 *  2.css		-	Стиль
	 *	2.content	-	контент который перед списком
	 *	2.item		-	ячейка
	 *		3.title	-	название ячейки
	 * 			4.href	-	ссылка перехода
	 *			4.modal	-	модальный режим
     * ----------------------------------------
     * @return string
	 */
	public function dropdown (array $opt = [string => ['theme' => 'default', 'css' => [], 'content' => [], 'item' => [string => ['href', 'modal']]]]) {
		$arr = [$opt];
		return $this->import(__FUNCTION__, $arr);
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
	public function panel ($opt) {
		$title		=	$opt['title'];
    	$theme		=	$opt['theme'];
		$content	=	$opt['content'];
		$css		=	$opt['css'];
		if (!$title) {
			$title	=	'Без название';
		}
		if (!$content) {
			$content=	'Контент пуст пожалуйста обновите страницу или повторите попытку снова! :)';
		}
		$arr	=	[
        	'title'		=>	$title,
            'theme'		=>	$theme,
        	'content'	=>	$content,
			'css'		=>	$css
        ];
		return	$this->import(__FUNCTION__, $arr);
	}

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
	public function listView ($opt) {
		$name		=	$opt['name'];
		$size		=	$opt['size'];
		$required	=	$opt['required'];
		$form		=	$opt['form'];
		$css		=	$opt['css'];
		if (is_numeric($size) && $size < 2) {
			$size	=	8;
		} else {
			$size	=	8;
		}
		$arr	=	[
        	'name'		=>	$name,
        	'size'		=>	$size,
        	'required'	=>	$required,
        	'form'		=>	$form,
        	'css'		=>	$css,
        	$opt[0]
        ];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем метку (badge)
	 * ------------------------
	 * text	-	Текст
	 * css	-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function badge ($text = '18') {
		return	$this->import(__FUNCTION__, $text);
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
	public function p ($opt) {
		$content	=	$opt['content'];
		$theme		=	$opt['theme'];
		$css		=	$opt['css'];
		$arr	=	[
        	'theme'		=>	$theme,
        	'content'	=>	$content,
			'css'		=>	$css
        ];
		return	$this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем иконку (ico)
	 * -------------------------
	 * ico	-	иконка
	 * -------------------------
	 * @return string
	 */
	public function ico ($ico) {
    	return	$this->import(__FUNCTION__, $ico);
    }

	/**
	 * Возвращаем модальная форма (modal)
	 * -----------------------------------
	 * id		-	Индентификатор
	 * title	-	Название
	 * exit		-	Кнопка выход
	 * content	-	Контент
	 * css		-	Стиль
	 * -----------------------------------
	 * @return string
	 */
	public function modal($opt) {
		$id			=	$opt['id'];
		$title		=	$opt['title'];
		$exit		=	$opt['exit'];
		$content	=	$opt['content'];
		$css		=	$opt['css'];
		if (!$id) {
			$id		=	$this->uuidv4();
		}
		if (!isset($exit)) {
			$exit	=	true;
		}
		$arr	=	[
        	'id'		=>	$id,
        	'title'		=>	$title,
        	'exit'		=>	$exit,
        	'content'	=>	$content,
			'css'		=>	$css
        ];
		return $this->import(__FUNCTION__, $arr);
	}

	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице ()
	 * indent	-	Отступы (5)
	 * content	-	Массив с содержимом
	 */
	public function pagination ($opt) {
    	$max	=	$opt['max'];
    	$indent	=	$opt['indent'];
    	$content=	$opt['content'];
    	if ($max < 1) {
        	$max = 1;
        }
    	if ($indent < 1) {
        	$indent = 1;
        }
    	$arr	=	['max'	=> $max, 'indent' => $indent, 'content' => $content];
    	return	$this->import(__FUNCTION__, $arr);
    }

	/**
	 * Возвращаем lightbox (lightbox)
	 * -------------------------------
	 * src		-	Изоброжение
	 * stretch	-	Растягивание
	 * -------------------------------
	 * @return string
	 */
	public function lightbox (array $opt) {
		$src	=	$opt['src'];
    	$stretch=	$opt['stretch'];
		if (!$src) {
			 $src = "https://proxy.duckduckgo.com/iu/?u=https%3A%2F%2Ftse1.mm.bing.net%2Fth%3Fid%3DOIP.8glp8zu2x8_WnEUNmca7JAHaFT%26pid%3DApi&f=1";
		}
		$arr = ['src'	=>	$src, 'stretch'	=>	$stretch];
		return	$this->import(__FUNCTION__, $arr);
	}

}
