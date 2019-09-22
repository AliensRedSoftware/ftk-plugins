<?php

/**
 * Bootstrap
 * --------------------
 * v3.3.7
 * Автор Меркус
 */
use xlib as x;
class bootstrap337 {

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
		$theme		=	bootstrap337::getTheme($opt['theme']);
		$tag		.=	"value=\"$title\" ";
		$tag		.=	"type=\"$type\" ";
		$tag		.=	"class=\"btn btn-$theme\" ";
		if ($formaction && $type == 'submit') {
			$tag	.=	"formaction=\"$formaction\" ";
		}
		if ($modal) {
			$tag	.=	"data-toggle=\"modal\" data-target=\"#$modal\" ";
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
		$title		=	$opt['title'];
		$href		=	$opt['href'];
		$modal		=	$opt['modal'];
		$css		=	x::css($opt['css']);
		$theme		=	bootstrap337::getThemeA($opt['theme']);
		if ($href && !$modal) {
			$tag	.=	"href=\"$href\" ";
		}
		if ($modal) {
			$tag	.=	"data-toggle=\"modal\" data-target=\"#$modal\" ";
		}
		$tag		.=	"class=\"btn btn-$theme\" ";
		$tag		.=	$css;
		$tag		=	trim($tag);
		return			"<a $tag>$title</a>";
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
		$tag .=	$css;
		$tag =	trim($tag);
		return "<form $tag>$content</form>";
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
			$tag		.=	"name=\"$name\" ";
		}
		if ($value) {
			$tag		.=	"value=\"$value\" ";
		}
		if ($placeholder) {
			$tag		.=	"placeholder=\"$placeholder\" ";
		}
		switch ($type) {
			case 'button':
				$class	.=	"btn btn-$theme";
			break;
			case 'submit':
				$class	.=	"btn btn-$theme";
			break;
			case 'reset':
				$class	.=	"btn btn-$theme";
			break;
			default:
				$class	.=	'form-control';
			break;
		}
		if ($class) {
			$tag		.=	"class=\"$class\" ";
		}
		if ($size) {
			$tag		.=	"size=\"$size\" ";
		}
		if ($min && $type == 'number') {
			$tag		.=	"min=\"$min\" ";
		}
		if ($max && $type == 'number') {
			$tag		.=	"max=\"$max\" ";
		}
		if($required) {
			$tag		.=	"required ";
		}
		$tag	.=	$enabled;
		if ($type == 'radio') {
			x::addCss(['display' => 'unset', 'width' => '0px', 'height' => '0px']);
			$css = x::getCss();
			$tag	.=	$css;
			if ($checked) {
				$tag	.=	'checked';
			}
        	$tag	=	trim($tag);
			return	"<label><input $tag> $value</label>";
		} if ($type == 'checkbox') {
			x::addCss(['display' => 'unset', 'width' => '0px', 'height' => '0px']);
			$css = x::getCss();
			$tag	.=	$css;
			if ($checked) {
				$tag	.=	'checked';
			}
			$tag	=	trim($tag);
			return	"<label><input $tag> $value</label>";
        }
		$tag	.=	$css;
        $tag	=	trim($tag);
		return	"<input $tag>";
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
		$theme			=	bootstrap337::getTheme($opt['theme']);
		$css			=	x::css($opt['css']);
		if ($name) {
			$tag		.=	"name=\"$name\" ";
		}
		if ($placeholder) {
			$tag		.=	"placeholder=\"$placeholder\" ";
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<textarea class=\"panel-$theme form-control\" $tag>$value</textarea>";
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
		$theme	=	bootstrap337::getThemeText($opt['theme']);
		$css	=	x::css($opt['css']);
		return	bootstrap337::p(['content' => $text, 'css' => $css, 'theme' => $theme]);
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
		return	"<img $tag/>";
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
    	$content	=	$opt['content'];
		$theme		=	bootstrap337::getTheme($opt['theme']);
    	$css		=	x::css($opt['css']);
		$tag	.=	$css;
		$tag	=	trim($tag);
		return "<div class=\"panel panel-$theme\" $tag><div class=\"panel-body\">$content</div></div>";
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
		$theme	=	bootstrap337::getTheme($opt['theme']);
		$css	=	x::css($opt['css']);
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
		$tag	.=	"class=\"btn btn-$theme\" ";
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
	 *  2.theme		-	Тема
	 * 	2.content	-	Контент который перед списком
	 * 	2.item		-	Ячейка
	 *		3.title	-	название ячейки
	 * 			4.href	-	ссылка перехода
	 *			4.modal	-	модальный режим
     * ----------------------------------------
     * @return string
	 */
	public function dropdown (array $opt = [string => ['css', 'theme', 'content' => [], 'item' => [string => ['href', 'modal']]]]) {
		foreach ($opt[0] as $title => $item) {
			$css		=	x::css($item['css']);
			$theme		=	bootstrap337::getTheme($item['content']);
			$slim		=	$item['content'];
			unset($items);
			unset($content);
			foreach ($item['item'] as $item => $val) {
				$href	=	$val['href'];
				$modal	=	$val['modal'];
				$items	.=	bootstrap337::item($item, $href, $modal);
			}
			foreach ($slim as $con) {
				$content	.=	$con;
			}
			if (!$content) {
				$output	.=	"<div class=\"btn-group\" $css><button type=\"button\" class=\"btn btn-$theme dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"width:inherit;\">$title</button><ul class=\"dropdown-menu\">$items</ul></div>";
			} else {
                $output	.=	"$content<div class=\"btn-group\" $css><button type=\"button\" class=\"btn btn-$theme dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"width:inherit;\">$title</button><ul class=\"dropdown-menu\">$items</ul></div>";
			}
		}
		return	$output;
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
		$tag = trim($tag);
		if ($modal) {
			$tag .= "href=\"#\" ";
			if (x::startWith('#', $href)) {
				$href = substr($href, 1);
				$tag .= "data-toggle=\"modal\" data-target=\".$href\" ";
			}
		} else {
			$tag .= "href=\"$href\" ";
		}
		return "<li><a $tag>$title</a></li>";
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
    	$theme		=	bootstrap337::getTheme($opt['theme']);
		$content	=	$opt['content'];
		$css		=	x::css($opt['css']);
		if (!$title) {
			$title		=	'Пустое название ;)';
		}
		if (!$content) {
			$content	=	'Пустой контент ;)';
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<div class=\"panel panel-$theme\"><div class=\"panel-heading\"><h3 class=\"panel-title\">$title</h3></div><div class=\"panel-body\" $css>$content</div></div>";
	}

	/**
	 * Возвращаем лист (listview)
	 * ---------------------------
	 * form		-	форм
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
    	$tag 	.=	"class=\"form-control\" ";
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
		return	"<span class=\"badge\" $tag>$text</span>";
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
		$tag	.=	$class;
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<p $tag>$content</p>";
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
		$css	=	x::css($opt['css']);
		$tag	.=	$css;
		$tag	=	trim($tag);
    	return	"<span class='glyphicon glyphicon-$ico' aria-hidden='true' $tag></span>";
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
		$id			=	$opt['id'];
		$title		=	$opt['title'];
		$exit		=	$opt['exit'];
		$content	=	$opt['content'];
		$css		=	x::css($opt['css']);
		if ($exit) {
			$exit	=	"<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		}
		if ($exit || $title) {
			if ($title) {
				$title	=	"<h4 class=\"modal-title\">$title</h4>";
			} else {
				$title	=	false;
			}
			$head		=	"<div class=\"modal-header\">$exit$title</div>";
		}
		echo "<div class=\"modal fade $id\" tabindex=\"-1\" id=\"$id\" role=\"dialog\"><div class=\"modal-dialog\" role=\"document\"><div class=\"modal-content\">$head<div class=\"modal-body\" $css>$content</div></div></div></div>";
		return $id;
	}

	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице (5)
	 * indent	-	Отступы (5)
	 * content	-	Массив с содержимом
	 * css		-	Стиль
	 */
	public function pagination ($opt) {
    	$max	=	$opt['max'];
    	$indent	=	$opt['indent'];
		$content=	$opt['content'];
    	$list	=	[];
    	$countContent = count($content);
    	$step = 0;
    	$xlib = new xlib();
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
    	$html = "<nav aria-label=\"Page navigation\"><ul class=\"pagination\">";
    	if ($_REQUEST['page'] > 0) {
        	$prev = $_REQUEST['page'] - 1;
        	$as = "<li><a href=\"$uri?page=$prev\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>";
        }
 		foreach (array_keys($list) as $val) {
        		if ($val > $prev && $val > 0 && $val != 0 && $created < $indent) {
                	if ($val != $_REQUEST['page']) {
                    	$created++;
        				$list['pagination'] .= "<li><a href=\"$uri?page=$val\">$val</a></li>";
                    }
            	} elseif($created >= $indent) {
                	$ns = "<li><a href=\"$uri?page=$val\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                	break;
                }
        }
    	if ($step == 1) {
        	$list['pagination'] = $list['pagination'];
        } else {
        	$list['pagination'] = $xlib->div(['content' => $html . $as . $list['pagination'] . $ns . "</ul></nav>"]);
        }
    	return $list;
    }

	/**
	 * Возвращаем lightbox (lightbox)
	 * -------------------------------
	 * src	-	Изоброжение
	 * -------------------------------
	 * @return string
	 */
	public function lightbox ($opt) {
		$skinmanager	=	new skinmanager();
		$uuid			=	x::uuidv4();
		$src			=	$opt['src'];
       	$stretch		=	$opt['stretch'];
    	if ($stretch) {
        	$style_lightbox	=	'style="width:100%;"';
        	$style_img		=	'style="width:inherit;"';
        } else {
        	$style_lightbox	=	'style="width:50%;"';
        	$style_img		=	'style="width:inherit;"';
        }
		return	"<a $style_lightbox class=\"example-image-link\" href=\"$src\" data-lightbox=\"example-1\"><img $style_img class=\"example-image\" src=\"$src\"/></a>";
	}

	/*
	 * Возвращает тему
	 * theme - (default, primary, success, info, warning, danger)
	 * @return String
	 */
	function getTheme ($theme) {
		switch ($theme) {
			case 'default':
				return $theme;
			break;
			case 'primary':
				return $theme;
			break;
			case 'success':
				return $theme;
			break;
			case 'info':
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
	 * Возвращает тему текста
	 * theme - (default,muted, primary, success, info, warning, danger)
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
			case 'primary':
				return $theme;
			break;
			case 'success':
				return $theme;
			break;
			case 'info':
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
	 * theme - (default, primary, success, info, warning, danger, link)
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
			case 'success':
				return $theme;
			break;
			case 'info':
				return $theme;
			break;
			case 'warning':
				return $theme;
			break;
			case 'danger':
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
