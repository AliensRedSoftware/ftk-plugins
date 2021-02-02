<?php

/**
 * Bootstrap
 * --------------------
 * v3.3.7
 * Автор Меркус
 */
use xlib as x;
use jquery as jq;
use skinmanager as sm;
class bootstrap4{
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
	public function btn($opt){
		$title=$opt['title'];
		$enabled=$opt['enabled'];
		$type=$opt['type'];
		$formaction=$opt['formaction'];
		$modal=$opt['modal'];
		$css=x::css($opt['css']);
		$theme=self::getTheme($opt['theme']);
		$tag.="value=\"$title\" ";
		$tag.="type=\"$type\" ";
		$tag.="class=\"btn btn-$theme\" ";
		if($formaction&&$type=='submit'){
			$tag.="formaction=\"$formaction\" ";
		}
		if($modal){
			$tag.="data-toggle=\"modal\" data-target=\"#$modal\" ";
		}
		$tag.=$css;
		$tag.=$enabled;
		$tag=trim($tag);
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
	public function a($opt){
		$title		=	$opt['title'];
		$href		=	$opt['href'];
		$modal		=	$opt['modal'];
		$css		=	x::css($opt['css']);
		$theme		=	self::getThemeA($opt['theme']);
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
	 * enctype	-	Метод кодировки (application/x-www-form-urlencoded,multipart/form-data,text/plain)
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
		$enctype	=	$opt['enctype'];
		$css		=	x::css($opt['css']);
		if($id){
			$tag.="id=\"$id\" ";
		}
		if($name) {
			$tag.="name=\"$name\" ";
		}
		if($method == 'get' || $method == 'post') {
			$tag.="method=\"$method\" ";
		}
		if($action){
			$tag.="action=\"$action\" ";
		}
		if($enctype){
			$tag.="enctype=\"$enctype\" ";
		}
		$tag.=$css;
		$tag=trim($tag);
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
	 * readonly		-	Чтивость
	 * class 		-	Класс
	 * checked		-	Выбранный компонент (radio)
	 * required		-	Проверка
	 * min			-	Минимальный размер (number)
	 * max			-	Максимальный размер (number)
	 * accept		-	Расширение для загрузки (file) (video/*,image/*,audio/*)
	 * multiple		-	Отправлять файлы сразу несколько (file)
	 * step         -   Шаг числа (number)
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
		$theme			=	self::getTheme($opt['theme']);
		$css			=	x::css($opt['css']);
		$tag			.=	"type=\"$type\" ";
    	if ($form) {
        	$tag		.=	"form=\"$form\" ";
        }
		if ($name) {
			$tag		.=	"name=\"$name\" ";
		}
		switch($type){
			case 'number':
				if(empty($value)){
					$value=0;
				}
				$tag.="step=\"$step\" ";
			break;
		}
		if(isset($value)&&$type!='radio'){
			$tag.="value=\"$value\" ";
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
			case 'file':
			break;
			case 'input':
				$class	.=	'form-control panel-default';
			break;
			default:
				$class	.=	'form-control';
			break;
		}
		if($class){
			$tag.="class=\"$class\" ";
		}
		if($size){
			$tag.="size=\"$size\" ";
		}
		if(!is_null($min)&&$type=='number') {
			$tag.="min=\"$min\" ";
		}elseif(!is_null($min)){
			$tag.="minlength=\"$min\" ";
		}
		if(!is_null($max)&&$type=='number') {
			$tag.="max=\"$max\" ";
		}elseif(!is_null($max)){
		    $tag.="maxlength=\"$max\" ";
		}
		if($accept){
			$tag.="accept=\"$accept\" ";
		}
		if($pattern){
			$tag.="pattern=\"$pattern\" ";
		}
		if($multiple){
			$tag.='multiple ';
		}
		if($required) {
			$tag.="required ";
		}
		if($readonly){
			$tag.='readonly ';
		}
		$tag	.=	$enabled;
		if($type == 'radio'){
			$tag.="value=\"$value\" ";
			x::addCss(['display'=>'unset','width'=>'0px','height'=>'0px']);
			$css = x::getCss();
			$tag	.=	$css;
			if ($checked) {
				$tag	.=	'checked';
			}
        	$tag	=	trim($tag);
			return	"<label class='text-light'><input $tag> $value</label>";
		}if($type == 'checkbox'){
			x::addCss(['display'=>'unset','width'=>'0px','height'=>'0px']);
			$css = x::getCss();
			$tag	.=	$css;
			if ($checked) {
				$tag	.=	'checked';
			}
			$tag	=	trim($tag);
			return	"<label class='text-light'><input $tag> $value</label>";
        }
		$tag	.=	$css;
        $tag	=	trim($tag);
		return	"<input $tag>";
	}

	/**
	 * Возвращаем многострочное поле (textarea)
	 * -----------------------------------------
	 * enabled		-	Доступность
	 * readonly     -   Чтивость
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
		$enabled=$opt['enabled'];
		$readonly=$opt['readonly'];
		$name=$opt['name'];
		$placeholder=$opt['placeholder'];
		$value=$opt['value'];
		$rows=$opt['rows'];
		$required=$opt['required'];
		$theme=self::getTheme($opt['theme']);
		$css=x::css($opt['css']);
		if($name){
			$tag.="name=\"$name\" ";
		}
		if($placeholder){
			$tag.="placeholder=\"$placeholder\" ";
		}
		if($rows){
			$tag.="rows=\"$rows\" ";
		}
		if($required){
			$tag.='required ';
		}
		if($readonly){
		    $tag.='readonly ';
		}
		$tag.=$css;
		$tag.=$enabled;
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
	public function text($opt){
		$txt=$opt['text'];
		$theme=self::getThemeText($opt['theme']);
		$css=x::css($opt['css']);
		return self::p(['content'=>$txt,'css'=>$css,'theme'=>$theme]);
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
		$src=$opt['src'];
		$css=x::css($opt['css']);
		if($src){
			$tag.="src=\"$src\" ";
		}
		$tag.=$css;
		$tag=trim($tag);
		return"<img $tag/>";
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
    	$content=$opt['content'];
		$theme=self::getTheme($opt['theme']);
    	$css=x::css($opt['css']);
		$tag.=$css;
		$tag=trim($tag);
		return"<div class=\"card bg-$theme\" $tag><div class=\"card-body\">$content</div></div>";
	}
    /**
     * Возвращаем выпадающий список (combobox)
     * ----------------------------------------
     * name		-	Имя
	 * selected	-	Выбранный элемент
	 * onChange -	Действие при выборе
	 * id		-	Ид
     * theme	-	Тема
	 * css		-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function combobox($opt){
		$name=$opt['name'];
		$selected=$opt['selected'];
		$onChange=$opt['onChange'];
		$id=$opt['id'];
		$theme=self::getTheme($opt['theme']);
		$css=x::css($opt['css']);
		if($name){
			$tag.="name=\"$name\" ";
		}
		$tag.="class=\"btn btn-$theme\" ";
		foreach(array_keys($opt[0]) as $title){
			if($selected==$title){
				$createSelected=true;
				$selected="<option value=\"$title\">$title</option>";
			}else{
				$item.="<option value=\"$title\">$title</option>";
			}
		}
		if($createSelected){
			$selected.=$item;
			$item=$selected;
		}
		if($onChange){
			$tag.="onchange='$onChange' ";
		}
		if($id){
			$tag.="id=\"$id\" ";
		}
		$tag.=$css;
		$tag=trim($tag);
		return"<select $tag>$item</select>";
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
	public function dropdown(array $opt=[string=>['css','theme','content'=>[],'item'=>[string=>['href','modal']]]]){
		foreach($opt[0] as $title=>$item){
			$css=x::css($item['css']);
			$theme=self::getTheme($item['theme']);
			$slim=$item['content'];
			unset($items);
			unset($content);
			foreach($item['item'] as $item=>$val){
				$href=$val['href'];
				$modal=$val['modal'];
				$items.=self::item($item,$href,$modal);
			}
			foreach($slim as $con){
				$content.=$con;
			}
			if(empty($content)){
				$output.="<div class=\"btn-group\" $css><button type=\"button\" class=\"btn btn-$theme dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"width:inherit;\">$title</button><div class=\"dropdown-menu panel-default\">$items</div></div>";
			}else{
            	$output.="$content<div class=\"btn-group\" $css><button type=\"button\" class=\"btn btn-$theme dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"width:inherit;\">$title</button><div class=\"dropdown-menu panel-default\">$items</div></div>";
			}
		}
		return$output;
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
	public function item($title,$href,$modal){
		$tag=trim($tag);
		if($modal){
			$tag.="href=\"#\" ";
			if(x::startWith('#', $href)){
				$href=substr($href,1);
				$tag.="data-toggle=\"modal\" data-target=\".$href\" ";
			}
		}else{
			$tag.="href=\"$href\" ";
		}
		$tag.="class=\"dropdown-item\" ";
		return"<a $tag>$title</a>";
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
		$title=$opt['title'];
    	$theme=self::getTheme($opt['theme']);
		$content=$opt['content'];
		$css=x::css($opt['css']);
		if(empty($title)){
			$title='Пустое название ;)';
		}
		if(empty($content)){
			$content='Пустой контент ;)';
		}
		$tag.=$css;
		$tag=trim($tag);
		return"<div class=\"card bg-$theme\"><div class=\"card-header text-$theme\">$title</div><div class=\"card-body text-$theme\" $css>$content</div></div>";
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
		$css=$opt['css'];
		foreach($arr as $title =>$content){
			$i++;
			if(count($arr)==$i){
				/*$css['display']='table';
				$title = bootstrap337::border(['css'=>$css,'content'=>$title]);
				unset($css['display']);
				$content=bootstrap337::border(['css'=>$css,'content'=>$content]);
				$panel.=$title.$content;*/
				$panel.=self::panel(['title'=>$title,'content'=>$content,'css'=>$css]);
			}else{
				$panel.=self::panel(['title'=>$title,'content'=>$content,'css'=>$css]);
			}
			$panel.="\n";
		}
		$tag.=$css;
		$tag=trim($tag);
		return$panel;
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
	public function listView($opt){
		$name=$opt['name'];
		$size=$opt['size'];
		$required=$opt['required'];
		$form=$opt['form'];
		$css=x::css($opt['css']);
		if($name){
			$tag.="name=\"$name\" ";
		}
		$tag.="size=\"$size\" ";
		foreach ($opt[0] as $title) {
			$item.="<option value=\"$title\">$title</option>";
		}
		if($form){
			$tag.="form=\"$form\" ";
		}
    	$tag.="class=\"form-control panel-default\" ";
    	if($required){
			$tag.='required ';
		}
		$tag.=$css;
		$tag=trim($tag);
		return"<select $tag>$item</select>";
	}
	/**
	 * Возвращаем метку (badge)
	 * ------------------------
	 * id	-	Индентификатор
	 * txt	-	Текст
	 * theme-   Тема
	 * css	-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function badge($opt){
		$theme=self::getTheme($opt['theme']);
		if(empty($theme)){
			$theme='default';
		}
		$id=$opt['id'];
		$txt=$opt['txt'];
		$css=x::css($opt['css']);
		if($id){
			$tag.="id='$id' ";
		}
		$tag.="class='badge badge-$theme' ";
		$tag.=$css;
		$tag=trim($tag);
		return"<span $tag>$txt</span>";
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
    	$theme=$opt['theme'];
		$content=$opt['content'];
		$css=x::css($opt['css']);
		if($theme){
			$class="class=\"text-$theme\" ";
		}
		$tag.=$class;
		$tag.=$css;
		$tag=trim($tag);
		return"<p $tag>$content</p>";
	}
	/**
	 * Возвращаем иконку (ico)
	 * -------------------------
	 * ico	-	Иконка
	 * css	-	Стиль
	 * -------------------------
	 * @return string
	 */
	public function ico ($ico = 'start',$opt = []){
		$css=x::css($opt['css']);
		$tag.=$css;
		$tag=trim($tag);
    	return"<span class='glyphicon glyphicon-$ico' aria-hidden='true' $tag></span>";
    }

	/**
	 * Возвращаем модальная форма (modal)
	 * -----------------------------------
	 * id		-	Индентификатор
	 * title	-	Название
	 * exit		-	Кнопка выход
	 * content	-	Контент
	 * open		-	При загрузки сайта (Открытие автоматически форму)
	 * theme	-	Тема
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
		$theme=self::getTheme($theme);
		$css=x::css($opt['css']);
		if(is_numeric(x::mb_str_split($id)[0])){
			$id="a$id";
		}
		//Запрет на создание повторных форм
		if(!defined($id)){
			define($id,$id);
		}else{
			return $id;
		}
		if($exit){
			$exit="<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		}
		if($exit||$title){
			if($title){
				$title="<h4 class=\"modal-title text-$theme\">$title</h4>";
			}else{
				$title=false;
			}
			$head="<div class=\"modal-header bg-$theme\">$title$exit</div>";
		}
		$content=self::text(['text'=>$content]);
		echo"<div class=\"modal fade $id\" tabindex=\"-1\" id=\"$id\" role=\"dialog\"><div class=\"modal-dialog\" role=\"document\"><div class=\"modal-content\">$head<div class=\"modal-body bg-$theme\" $css>$content</div></div></div></div>";
		if($open){
		    x::js("$('#$id').modal('toggle')");
			jq::addLoad("$('#$id').modal('toggle')");
		}
		return $id;
	}
	/**
	 * Возвращаем открытую модальную форму (modal)
	 * -----------------------------------
	 * id		-	Название
	 * -----------------------------------
	 * @return string
	 */
	public function OpenModal($id){
		x::js("$('#$id').modal('toggle')");
		jq::addLoad("$('#$id').modal('toggle')");
		return true;
	}
	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max-Максимальное число элементов на 1 странице (5)
	 * indent-Отступы (5)
	 * data-Массив с содержимом
	 * css-Стиль
	 */
	public function pagination($opt){
    	$max=$opt['max'];
    	$indent=$opt['indent'];
		$data=$opt['data'];
    	$list=[];
    	$countContent=count($data);
    	$step=0;
		foreach($data as $val){
        	$list[$step].=$val;
        	$i++;
        	if($i==$max){
            	array_shift($data);
            	unset($i);
            	$step++;
            	$a.="Шаг - $step";
            }
        }
    	if($i){
        	$step++;
        	$a.="Шаг - $step";
        	unset($i);
        }
    	$uri=$http_response_header[6].x::geturi();
    	$uri=str_replace("?".x::getData(),NULL,$uri);
    	$html="<nav aria-label=\"Page navigation\"><ul class=\"pagination\">";
    	if(x::getDataToArray()['page'] > 0){
        	$prev=x::getDataToArray()['page'] - 1;
        	$as="<li class=\"page-item\"><a class=\"page-link\" href=\"$uri?page=$prev\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>";
        }
 		foreach(array_keys($list) as $val){
        		if($val>$prev&&$val>0&&$val!=0&&$created<$indent){
                	if($val!=x::getDataToArray()['page']){
                    	$created++;
        				$list['pagination'].="<li class=\"page-item\"><a class=\"page-link\" href=\"$uri?page=$val\">$val</a></li>";
                    }
            	}elseif($created>=$indent){
                	$ns = "<li class=\"page-item\"><a class=\"page-link\" href=\"$uri?page=$val\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                	break;
                }
        }
    	if($step==1){
        	$list['pagination']=$list['pagination'];
        }else{
        	$list['pagination']=x::div(['content'=>$html.$as.$list['pagination'].$ns."</ul></nav>"]);
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
	public function lightbox($opt){
		$src=$opt['src'];
       	$stretch=$opt['stretch'];
    	if($stretch){
        	$style_lightbox='style="width:100%;"';
        	$style_img='style="width:inherit;"';
        }else{
        	$style_lightbox='style="width:50%;"';
        	$style_img='style="width:inherit;"';
        }
        sm::setSuperBox('<div id="sb-container"><div id="sb-overlay"></div><div id="sb-wrapper"><div id="sb-title"><div id="sb-title-inner"></div></div><div id="sb-wrapper-inner"><div id="sb-body"><div id="sb-body-inner"></div><div id="sb-loading"><div id="sb-loading-inner"><span>loading</span></div></div></div></div><div id="sb-info"><div id="sb-info-inner"><div id="sb-counter"></div><div id="sb-nav"><a id="sb-nav-close" title="Close" onclick="Shadowbox.close()"></a><a id="sb-nav-next" title="Next" onclick="Shadowbox.next()"></a><a id="sb-nav-play" title="Play" onclick="Shadowbox.play()"></a><a id="sb-nav-pause" title="Pause" onclick="Shadowbox.pause()"></a><a id="sb-nav-previous" title="Previous" onclick="Shadowbox.previous()"></a></div></div></div></div></div>');
		return"<a $style_lightbox href=\"$src\" rel=\"shadowbox[img]\"><img $style_img src=\"$src\" data-src=\"$src\"/></img></a>";
	}
	/**
	 * Возвращает тему
	 * theme - (light, primary, success, info, warning, danger)
	 * @return String
	 */
	function getTheme($theme){
		switch($theme){
			case 'light':
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
				return 'light';
			break;
		}
	}
	/**
	 * Возвращает тему текста
	 * theme - (light,muted, primary, success, info, warning, danger)
	 * @return String
	 */ 
	function getThemeText($theme){
		switch($theme){
			case 'light':
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
				return 'light';
			break;
		}
	}
	/**
	 * Возвращает тему ссылок
	 * theme - (light, primary, success, info, warning, danger, link)
	 * @return String
	 */ 
	function getThemeA($theme){
		switch($theme){
			case 'light':
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
				return 'light';
			break;
		}
	}

}
