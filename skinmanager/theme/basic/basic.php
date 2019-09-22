<?php

/**
 * basic
 * --------------------
 * v1.0
 * Автор Меркус
 */
use xlib as x;
class basic {

    /**
     * Возвращает кнопку (button)
     * ---------------------------
     * title		-	Загаловок
     * enabled		-	Доступность
     * type			-	Тип (button, reset, submit)
     * formaction	-	Переход на другой url (<form> необходим)
     * modal		-	Модальная форма
	 * css			-	Стиль
     * ----------------------------
     * @return string
     */
	public function btn ($opt) {
		$title		=	$opt['title'];
		$enabled	=	$opt['enabled'];
		$type		=	$opt['type'];
		$formaction	=	$opt['formaction'];
		$modal		=	$opt['modal'];
		$css		=	x::css($opt['css']);
		$tag		.=	"value=\"$title\" ";
		if ($modal) {
			$tag	.=	"type=\"submit\" ";
			$tag	.=	"formaction=\"#$modal\" ";
			$tag	.=	$enabled;
			$tag	=	trim($tag);
			return "<form><button $tag>$title</button></form>";
		} else {
			$tag	.=	"type=\"$type\" ";
			if ($formaction && $type == 'submit') {
				$tag	.=	"formaction=\"$formaction\" ";
			}
			$tag	.=	$css;
			$tag	.=	$enabled;
			$tag	=	trim($tag);
			return "<button $tag>$title</button>";
		}
	}

    /**
     * Возвращаем ссылку <a>
     * ----------------------
     * title	-	Загаловок
     * href		-	Cсылка
	 * css		-	Стиль
     * ----------------------
     * @return string
	 */
	public function a ($opt) {
		$title		=	$opt['title'];
		$href		=	$opt['href'];
		$css		=	x::css($opt['css']);
		if ($href) {
			$tag	.=	"href=\"$href\" ";
		}
		$tag .= $css;
		$tag = trim($tag);
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
		$tag .=	$css;
		$tag =	trim($tag);
		return "<form $tag>$content</form>";
	}

    /**
     * Возвращаем input (input)
     * ------------------------
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
     * css			-	Стиль
     * theme		-	Тема
     * -----------------------
     * @return string
     */
    public function input ($opt) {
    	$form		=	$opt['form'];
		$name		=	$opt['name'];
		$type		=	$opt['type'];
		$value		=	$opt['value'];
		$placeholder=	$opt['placeholder'];
		$size		=	$opt['size'];
		$width		=	$opt['width'];
		$enabled	=	$opt['enabled'];
		$class		=	$opt['class'];
		$checked	=	$opt['checked'];
		$required	=	$opt['required'];
		$min		=	$opt['min'];
		$max		=	$opt['max'];
		$theme		=	$opt['theme'];
		$css		=	x::css($opt['css']);
		$tag		.=	"type=\"$type\" ";
       	if ($form) {
        	$tag	.=	"form=\"$form\" ";
        }
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
		if ($value && $type != 'radio') {
			$tag	.=	"value=\"$value\" ";
		}
		if ($placeholder) {
			$tag	.=	"placeholder=\"$placeholder\" ";
		}
		if ($class) {
			$tag	.=	"class=\"$class\" ";
		}
		if ($size) {
			$tag	.=	"size=\"$size\" ";
		}
		if ($min && $type == 'number') {
			$tag	.=	"min=\"$min\" ";
		}
		if ($max && $type == 'number') {
			$tag	.=	"max=\"$max\" ";
		}
		if($required) {
			$tag	.=	"required ";
		}
		$tag .= $css;
		$tag .= $enabled;
		if ($type == 'radio') {
			if ($checked) {
				$tag	.=	'checked';
			}
			$tag = trim($tag);
			return		"<label><input $tag> $value</label>";
		} elseif ($type == 'checkbox') {
        	if ($checked) {
				$tag	.=	'checked';
			}
			$tag = trim($tag);
        	return		"<label><input $tag> $value</label>";
        } else {
			$tag = trim($tag);
        	return		"<input $tag>";
		}
	}

    /**
     * Возвращаем многострочное поле (textarea)
     * ----------------------------------------
     * name 		-	Имя
     * placeholder	-	Подсказка
     * value		-	Значение
     * css			-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function textarea ($opt) {
		$name		= 	$opt['name'];
		$placeholder= 	$opt['placeholder'];
		$value		= 	$opt['value'];
		$css		=	x::css($opt['css']);
    	if ($name) {
    		$tag	.=	"name=\"$name\" ";
    	}
    	if ($placeholder) {
    		$tag	.=	"placeholder=\"$placeholder\" ";
		}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<textarea $tag>$value</textarea>";
    }

    /**
     * Возвращаем текст (text)
     * -----------------------
     * text		-	Текст
     * css		-	Стиль
     * -----------------------
     * @return string
     */
	public function text ($opt) {
		$text	=	$opt['text'];
		$css	=	x::css($opt['css']);
		return	basic::p(['content' => $text, 'css' => $css]);
	}

    /**
     * Возвращаем картинку (img)
     * -------------------------
     * src	-	Изоброжение
	 * css	-	Стиль
     * -------------------------
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
     * ---------------------------
     * content	-	Контент
     * css		-	Стиль
     * ---------------------------
     * @return string
     */
	public function border ($opt) {
		$content=	$opt['content'];
		$css	=	x::css($opt['css']);
		x::addCss(['margin-bottom' => '10px']);
		$css	=	x::getCss();
		$tag	.=	$css;
		$tag	=	trim($tag);
		return "<div class=\"border\" $tag>$content</div>";
	}

    /**
     * Возвращаем выпадающий список (combobox)
     * ----------------------------------------
     * name		-	Имя
	 * selected	-	Выбранный элемент
     * css		-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function combobox ($opt) {
		$name		=	$opt['name'];
		$selected	=	$opt['selected'];
		$css		=	x::css($opt['css']);
		if ($name) {
			$tag	.=	"name=\"$name\" ";
		}
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
	 *	2.content	-	контент который перед списком
	 *	2.item		-	ячейка
	 *		3.title	-	название ячейки
	 * 			4.href	-	ссылка перехода
	 *			4.modal	-	модальный режим
     * ----------------------------------------
     * @return string
	 */
	public function dropdown ($opt = [string => ['css', 'content' => [], 'item' => [string => ['href', 'modal']]]]) {
		foreach ($opt[0] as $title => $item) {
			$css		=	x::css($item['css']);
			$slim		=	$item['content'];
			unset($items);
			unset($content);
			foreach ($item['item'] as $item => $val) {
				$href	=	$val['href'];
				$items	.=	basic::item($item, $href);
			}
			foreach ($slim as $con) {
				$content	.=	$con;
			}
			if (!$content) {
				$output	.=	"<li $css><a>$title</a><ul class=\"border\" style=\"position:absolute;\">$items</ul></li>";
			} else {
				$output	.=	"$content<li $css><a>$title</a><ul class=\"border\" style=\"position:absolute;\">$items</ul></li>";
			}
		}
		return	"<ul>$output</ul>";
	}

	/**
	 * Возвращаем item (dropdown)
	 * ---------------------------
	 * title	-	Загаловок
	 * href		-	Ссылка
	 * ---------------------------
	 * @return string
	 */
	public function item ($title, $href) {
		$tag	.=	"href=\"$href\" ";
		$tag	=	trim($tag);
		return	"<li><a $tag>$title</a></li>";
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
	public function panel ($opt) {
		$title		=	$opt['title'];
		$content	=	$opt['content'];
		$css		=	$opt['css'];
		//$css		.=	'margin-top:10px;margin-bottom:10px;';
    	if ($GLOBALS['__PANEL_BORDER_0']) {
        	$panel = basic::border(['css' => $css, 'content' => $title]) . basic::border(['css' => $css, 'content' => $content]);
        	$GLOBALS['__PANEL_BORDER_0'] = false;
        } else {
        	$panel = basic::border(['content' => $title]) . basic::border(['css' => $css, 'content' => $content]);
    	}
		$tag	.=	$css;
		$tag	=	trim($tag);
		return $panel;
	}

	/**
	 * Возвращаем лист (listview)
	 * ---------------------------
	 * form		-	форм
	 * name		-	Имя
	 * size		-	размер (не меньше чем 2)
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
		$css	=	$opt['css'];
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<span>($text)</span>";
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
		$content=	$opt['content'];
		$css	=	$opt['css'];
		$tag	.=	$css;
		$tag	=	trim($tag);
		return	"<p $tag>$content</p>";
	}

	/**
	 * Возвращаем модальная форма (modal)
	 * -----------------------------------
	 * id		-	Индентификатор
	 * title	-	Название
	 * exit		-	Кнопка выход
	 * content	-	Контент
	 * -----------------------------------
	 * @return string
	 */
	public function modal($opt) {
		$id			=	$opt['id'];
		$title		=	$opt['title'];
		$exit		=	$opt['exit'];
		$content	=	$opt['content'];
		$css		=	x::css($opt['css']);
		if ($exit) {
			$exit	=	"<a class=\"close\" title=\"Закрыть\" href=\"#close\"></a>";
		}
		if ($exit || $title) {
			$head	=	"<div class=\"modal-header\"><h2>$title</h2>$exit</div>";
		}
		echo "<a href=\"#x\" class=\"overlay\" id=\"$id\"></a><div class=\"popup\">$head<div class=\"modal-body\" $css>$content</div></div>";
		return $id;
	}

	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице (5)
	 * indent	-	Отступы (5)
	 * align	-	Расположение элемента пагинаций
	 * content	-	Массив с содержимом
	 */
	public function pagination (array $opt) {
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
    	if ($_REQUEST['page'] > 0) {
        	$prev = $_REQUEST['page'] - 1;
        	$as = basic::a(['title' => 'Назад', 'href' => "$uri?page=$prev"]) . '|';
        }
 		foreach (array_keys($list) as $val) {
        		if ($val > $prev && $val > 0 && $val != 0 && $created < $indent) {
                	if ($val != $_REQUEST['page']) {
                    	$created++;
        				$list['pagination'] .= basic::a(['title' => $val, 'href' => "$uri?page=$val"]) . '|';
                    }
            	} elseif($created >= $indent) {
                	$ns = basic::a(['title' => 'Дальше', 'href' => "$uri?page=$val"]);
                	break;
                }
        }
    	if ($step == 1) {
        	$list['pagination'] = $as . $list['pagination'] . $ns;
        } else {
    		$list['pagination'] = $xlib->div(['style' => "$align", 'content' => $as . $list['pagination'] . $ns]);
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
		$skinmanager->setSuperBox("<div class=\"lightbox-target\" id=\"$uuid\"><img src=\"$src\"/><a class=\"lightbox-close\" href=\"#x\"></a></div>");
		return	"<a $style_lightbox class=\"lightbox\" href=\"#$uuid\"><img $style_img src=\"$src\"/></a>";
	}

}