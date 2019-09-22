<?php

/**
 * uikit
 * ----------------
 * v3.1.7
 * Автор Меркус
 */
use xlib as x;
class uikit {

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
		$css		=	x::css($opt['css']);
		$theme		=	uikit::getTheme($opt['theme']);
		$tag		.=	"value=\"$title\" ";
		$tag		.=	"type=\"$type\" ";
		$tag		.=	"class=\"uk-button uk-button-$theme\" ";
		if ($formaction && $type == 'submit') {
			$tag	.=	"formaction=\"$formaction\" ";
		}
		if ($modal) {
			$tag	.=	"uk-toggle=\"target: #$modal\" ";
		}
		$tag	.=	$css;
		$tag	.=	$enabled;
		$tag	=	trim($tag);
		return "<button $tag>$title</button>";
	}

	/**
	 * Возвращаем ссылку <a>
	 * ----------------------
	 * title	-	Загаловок
	 * href		-	Cсылка
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
		$css	=	x::css($opt['css']);
		$theme	=	uikit::getThemeA($opt['theme']);
		if ($href && !$modal) {
			$tag	.=	"href=\"$href\" ";
		}
		if ($modal) {
			$tag	.=	"uk-toggle=\"target: #$modal\" ";
		}
		$tag	.=	"class=\"uk-button uk-button-$theme\" ";
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<a $tag>$title</a>";
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
		$id			=	$opt['id'];
		$name		=	$opt['name'];
		$method		=	$opt['method'];
		$action		=	$opt['action'];
		$content	=	$opt['content'];
		$css		=	x::css($opt['css']);
		if ($id) {
			$tag	.=	"id=\"$id\" ";
		}
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
		if ($method == 'get' || $method == 'post') {
			$tag	.=	"method=\"$method\" ";
		}
		if ($action) {
			$tag	.=	"action=\"$action\" ";
		}
		$tag	.=	$css;
		$tag 	=	trim($tag);
		return	"<form $tag>$content</form>";
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
	 * theme		-	Тема
	 * css			-	Стиль
	 * -------------------------
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
		$css			=	x::css($opt['css']);
		$tag			.=	"type=\"$type\" ";
       	if ($form) {
        	$tag		.=	"form=\"$form\" ";
        }
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
		if ($value) {
			$tag	.=	"value=\"$value\" ";
		}
		if ($placeholder) {
			$tag	.=	"placeholder=\"$placeholder\" ";
		}
		switch ($type) {
			case 'button':
				$class	.=	"uk-button uk-button-$theme";
			break;
			case 'checkbox':
				$class	.=	'uk-checkbox';
			break;
			case 'radio':
				$class	.=	'uk-radio';
			break;
			case 'submit':
				$class	.=	"uk-button uk-button-$theme";
			break;
			case 'range':
				$class	.=	'uk-range';
			break;
			case 'reset':
				$class	.=	"uk-button uk-button-$theme";
			break;
        	default:
        		$class	.=	'uk-input';
        	break;
		}
		if ($class) {
			$tag	.=	"class=\"$class\" ";
		}
		if ($size) {
			$tag	.=	"size=\"$size\" ";
		}
		if ($checked && $type == 'radio') {
			$tag	.=	'checked ';
		}
		if ($min && $type == 'number') {
			$tag	.=	"min=\"$min\" ";
		}
		if ($max && $type == 'number') {
			$tag	.=	"max=\"$max\" ";
		}
		if($required) {
			$tag	.=	'required ';
		}
		$tag	.=	$enabled;
		$tag	=	trim($tag);
		if ($type == 'radio') {
			return	"<label><input $tag>$value</label>";
		} elseif ($type == 'checkbox') {
			$tag	.=	$css;
        	$tag	=	trim($tag);
        	if ($checked) {
				$tag	.=	'checked';
			}
        	return	"<label><input $tag>$value</label>";
        } else {
			$tag	.=	$css;
			return	"<input $tag>";
		}
	}

	/**
	 * Возвращаем многострочное поле (textarea)
	 * -----------------------------------------
	 * name 		-	Имя
	 * placeholder	-	Подсказка
	 * value		-	Значение
	 * css			-	Стиль
	 * -----------------------------------------
	 * @return string
	 */
	public function textarea ($opt) {
		$name			= 	$opt['name'];
		$placeholder	= 	$opt['placeholder'];
		$value			= 	$opt['value'];
		$css			=	x::css($opt['css']);
		if ($name) {
			$tag		.=	"name=\"$name\" ";
		}
		if ($placeholder) {
			$tag		.=	"placeholder=\"$placeholder\" ";
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<textarea class=\"uk-textarea\" $tag>$value</textarea>";
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
		$theme	=	uikit::getThemeText($opt['theme']);
		$css	=	x::css($opt['css']);
		return	uikit::p(['content' => $text, 'css' => $css, 'theme' => $theme]);
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
		$css	=	x::css($opt['css']);
		if ($src) {
			$tag	.=	"src=\"$src\" ";
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return "<img $tag/>";
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
		$xlib		=	new xlib();
		$body		=	$opt['body'];
		$size		=	$opt['size'];
		$content	=	$opt['content'];
		$theme		=	uikit::getThemeCard($opt['theme']);
		$css		=	x::css($opt['css']);
		x::addCss(['margin-bottom' => '10px']);
		$css = x::getCss();
		switch ($size) {
			case 'small':
				$size	=	"uk-card-$size";
			break;
			case 'small':
				$size	=	"uk-card-$size";
			break;
			default:
				$size	=	"uk-card-small";
			break;
		}
		if ($body) {
			$body	=	'uk-card-body';
		}
		$class	=	"uk-card uk-card-$theme $size $body";
		$class	=	$class;
		if ($class) {
			$tag	.=	"class=\"$class\" ";
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
        return "<div $tag>$content</div>";
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
		$name	=	$opt['name'];
		$selected=	$opt['selected'];
		$theme	=	uikit::getTheme($opt['theme']);
		$css	=	x::css($opt['css']);
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
		$tag	.=	"class=\"uk-button uk-button-$theme\" ";
		foreach (array_keys($opt[0]) as $title) {
			if ($selected == $title) {
				$createSelected = true;
				$selected = "<option value=\"$title\">$title</option>";
			} else {
				$item	.=	"<option value=\"$title\">$title</option>";
			}
		}
		if ($createSelected) {
			$selected .= $item;
			$item = $selected;
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<select $tag>$item</select>";
	}

	/**
	 * Возвращаем выпадающий список (dropdown)
	 * ----------------------------------------
	 * 1.title		-	Создание нового списка
	 *  2.css		-	Стиль
	 * 	2.content	-	контент который перед списком
	 * 	2.item		-	ячейка
	 *		3.title	-	название ячейки
	 * 			4.href	-	ссылка перехода
	 *			4.modal	-	модальный режим
     * ----------------------------------------
     * @return string
	 */
	public function dropdown (array $opt = [string => ['css', 'content' => [], 'item' => [string => ['href', 'modal']]]]) {
		foreach ($opt[0] as $title => $item) {
			$css		=	x::css($item['css']);
			$theme		=	uikit::getTheme($item['content']);
			$slim		=	$item['content'];
			unset($items);
			unset($content);
			foreach ($item['item'] as $item => $val) {
				$href	=	$val['href'];
				$modal	=	$val['modal'];
				$items	.=	uikit::item($item, $href, $modal);
			}
			foreach ($slim as $con) {
				$content	.=	$con;
			}
			if (!$content) {
				$output .= "<div class=\"uk-inline\" $css><button class=\"uk-button uk-button-$theme\" type=\"button\" $css>$title</button><div uk-dropdown>$items</div></div>";
			} else {
                $output .= "$content<div class=\"uk-inline\" $css><button class=\"uk-button uk-button-$theme\" type=\"button\" $css>$title</button><div uk-dropdown>$items</div></div>";
			}
		}
		return $output;
	}

	/**
	 * Возвращаем item (dropdown)
	 * --------------------------
	 * title	-	Название
	 * href		-	Ссылки
	 * modal	-	Модальность
	 * --------------------------
	 * @return string
	 */
	public function item ($title, $href, $modal) {
		if ($modal) {
			$tag .= "href=\"$href\" uk-toggle";
		} else {
			$tag .= "href=\"$href\" ";
		}
		$tag = trim($tag);
		return "<ul class=\"uk-nav uk-dropdown-nav\"><li><a $tag>$title</a></li></ul>";
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
		$xlib		=	new xlib();
		$title		=	$xlib->div(['class' => 'uk-card-title', 'css' => ['padding' => '10px'], 'content' => $opt['title']]);
    	$theme		=	uikit::getTheme($opt['theme']);
		$content	=	$opt['content'];
		$css		=	$opt['css'];
		if (!$title) {
			$title		=	'Пустое название ;)';
		}
		if (!$content) {
			$content	=	'Пустой контент ;)';
		}
   	    if ($GLOBALS['__PANEL_BORDER_0']) {
			$panel	=	uikit::border(['css' => $css, 'theme' => $theme, 'body' => false, 'content' => $title]) . uikit::border(['css' => $css, 'theme' => $theme, 'body' => true, 'content' => $content]);
        	$GLOBALS['__PANEL_BORDER_0'] = false;
        } else {
        	$panel	=	uikit::border(['theme' => $theme, 'body' => false, 'content' => $title]) . uikit::border(['css' => $css, 'theme' => $theme, 'body' => true, 'content' => $content]);
        }
		$tag	.=	$css;
		$tag	=	trim($tag);
    	return $panel;
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
		$css		=	x::css($opt['css']);
		x::addCss(['height' => 'unset', 'padding' => 'unset']);
		$css = x::getCss();
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
		$tag		.=	"size=\"$size\" ";
		foreach (array_keys($opt[0]) as $title) {
			$item	.=	"<option value=\"$title\">$title</option>";
		}
		if ($form) {
			$tag	.=	"form=\"$form\" ";
		}
    	$tag 	.=	"class=\"uk-input\" ";
    	if ($required) {
			$tag	.=	'required ';
		}
		$tag	.=	$css;
    	$tag	=	trim($tag);
		return	"<select $tag>$item</select>";
	}

	/**
	 * Возвращаем метку (badge)
	 * ------------------------
	 * text	-	Текст
	 * css	-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function badge ($text = '18', $opt = []) {
		$css	=	x::css($opt['css']);
		$tag	.=	$css;
    	$tag	=	trim($tag);
		return	"<span class=\"uk-badge\" $tag>$text</span>";
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
    	$theme		=	$opt['theme'];
		$content	=	$opt['content'];
		$css		=	x::css($opt['css']);
		if ($theme) {
			$class	=	"class=\"text-$theme\" ";
		}
		$tag		.=	$css;
		$tag		.=	$class;
		$tag		=	trim($tag);
		return "<p $tag>$content</p>";
	}

	/**
	 * Возвращаем иконку (ico)
	 * -------------------------
	 * ico	-	Иконка
	 * css	-	Стиль
	 * -------------------------
	 * @return string
	 */
	public function ico ($ico = 'start', $opt = []) {
		$css		=	$opt['css'];
    	switch ($ico) {
        	case 'adjust':
        		$ico = 'paint-bucket';
        	break;
        	case 'eye-open':
        		$ico = 'folder';
        	break;
        }
		$tag		.=	$css;
		$tag		=	trim($tag);
    	return	"<span uk-icon='$ico' $tag></span>";
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
	public function modal ($opt) {
		$xlib		=	new xlib();
		$id			=	$opt['id'];
		$title		=	$opt['title'];
		$exit		=	$opt['exit'];
		$content	=	$opt['content'];
		$css		=	x::css($opt['css']);
    	if(is_numeric($xlib->mb_str_split($id)[0])) {
			$id		=	"a$id";
		}
		if ($exit) {
			$exit	=	"<button class=\"uk-modal-close-default\" type=\"button\" uk-close></button>";
		}
		if ($exit || $title) {
			if ($title) {
				$title	=	"<h2 class=\"uk-modal-title\">$title</h2>";
			} else {
				$title	=	false;
			}
			$head	=	"<div class=\"uk-modal-header\">$title</div>";
		}
		echo	"<div id=\"$id\" uk-modal><div class=\"uk-modal-dialog\">$exit$head<div class=\"uk-modal-body\" $css>$content</div></div></div>";
		return	$id;
	}

	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице (5)
	 * indent	-	Отступы (5)
	 * align	-	Расположение элемента пагинаций
	 * content	-	Массив с содержимом
	 */
	public function pagination ($opt) {
    	$max	=	$opt['max'];
    	$indent	=	$opt['indent'];
    	$align	=	$opt['align'];
		$content=	$opt['content'];
    	$list	=	[];
    	$countContent = count($content);
    	$step = 0;
    	$xlib	=	new xlib();
    	switch ($align) {
        	case 'right':
        		$align = 'text-align:right;';
        	break;
        	case 'center':
        		$align = 'text-align:center;';
        	break;
        }
    	foreach ($content as $val) {
        	$list[$step]	.= $val;
        	$i++;
        	if ($i == $max) {
            	array_shift($content);
            	unset($i);
            	$step++;
            	$a .= "Шаг - $step";	
            }
        }
    	if ($i) {
        	$step++;
        	$a .= "Шаг - $step";
        	unset($i);
        }
    	$uri = $xlib->geturi(1);
        if (!$uri) {
            $uri = 'index';
        } else {
            $uri = false;
        }
    	$html = "<ul class=\"uk-pagination\" style=\"display:inline-flex;\" uk-margin>";
    	if ($_REQUEST['page'] > 0) {
        	$prev = $_REQUEST['page'] - 1;
        	$as = "<li><a href=\"$uri?page=$prev\"><span uk-pagination-previous></span></a></li>";
        }
 		foreach (array_keys($list) as $val) {
        		if ($val > $prev && $val > 0 && $val != 0 && $created < $indent) {
                	if ($val != $_REQUEST['page']) {
                    	$created++;
                    	$list['pagination'] .= " <li><a href=\"$uri?page=$val\">$val</a></li>";
                    }
            	} elseif($created >= $indent) {
                	$ns = "<li><a href=\"$uri?page=$val\"><span uk-pagination-next></span></a></li>";
                	break;
                }
            
        }
    	if ($step == 1) {
    		$list['pagination'] = $list['pagination'];
        } else {
        	$list['pagination'] = $xlib->div(['style' => "$align", 'content' => $html . $as . $list['pagination'] . $ns . "</ul>"]);
        }
    	return $list;
    }

	/**
	 * Возвращаем lightbox (lightbox)
	 * -------------------------------
	 * src		-	Изоброжение
	 * stretch	-	Растягивание
	 * -------------------------------
	 * @return string
	 */
	public function lightbox ($opt) {
		$src		=	$opt['src'];
       	$stretch	=	$opt['stretch'];
    	if ($stretch) {
        	$style_lightbox	=	'style="width:100%;"';
        	$style_img		=	'style="width:inherit;"';
        } else {
        	$style_lightbox	=	'style="width:50%;"';
        	$style_img		=	'style="width:inherit;"';
        }
		return	"<div uk-lightbox=\"animation: fade\"><a $style_lightbox class=\"uk-inline\" href=\"$src\"><img $style_img src=\"$src\" width=\"50%\" uk-img></a></div>";
	}

	/*
	 * Возвращает тему кнопок
	 */ 
	function getTheme ($theme) {
		switch ($theme) {
			case 'default':
				return $theme;
			break;
			case 'primary':
				return $theme;
			break;
			case 'secondary':
				return $theme;
			break;
			case 'danger':
				return $theme;
			break;
			default:
				return 'default';
			break;
		}
	}

	/*
	 * Возвращает тему
	 * theme - (default, primary, secondary)
	 * @return String
	 */ 
	function getThemeCard ($theme) {
		switch ($theme) {
			case 'default':
				return $theme;
			break;
			case 'primary':
				return $theme;
			break;
			case 'secondary':
				return $theme;
			break;
			default:
				return 'default';
			break;
		}
	}

	/*
	 * Возвращает тему текста
	 * theme - (default, muted, emphasis, primary, secondary, success, warning, danger)
	 * @return String
	 */ 
	function getThemeText ($theme) {
		switch ($theme) {
			case 'default':
				return $theme;
			break;
			case 'muted':
				return $theme;
			break;
			case 'emphasis':
				return $theme;
			break;
			case 'primary':
				return $theme;
			break;
			case 'secondary':
				return $theme;
			break;
			case 'success':
				return $theme;
			break;
			case 'warning':
				return $theme;
			break;
			case 'danger':
				return $theme;
			break;
			default:
				return 'default';
			break;
		}
	}
	
	/*
	 * Возвращает тему ссылок
	 * theme - (default, primary, success, info, warning, danger, text, link)
	 * @return String
	 */ 
	function getThemeA ($theme) {
		switch ($theme) {
			case 'default':
				return $theme;
			break;
			case 'primary':
				return $theme;
			break;
			case 'secondary':
				return $theme;
			break;
			case 'danger':
				return $theme;
			break;
			case 'text':
				return $theme;
			break;
			case 'link':
				return $theme;
			break;
			default:
				return 'default';
			break;
		}
	}
	
}
