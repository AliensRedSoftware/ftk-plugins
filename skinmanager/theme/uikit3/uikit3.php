<?php

/**
 * uikit
 * ----------------
 * v3.6.5
 */
use xlib as x;
use jquery as jq;
class uikit3{

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
		$id=$opt['id'];
		$title=$opt['title'];
		$enabled=$opt['enabled'];
		$type=$opt['type'];
		$formaction=$opt['formaction'];
		$modal=$opt['modal'];
		$css=x::css($opt['css']);
		$theme=self::getTheme($opt['theme']);
		$tag.="id=\"$id\" ";
		$tag.="value=\"$title\" ";
		$tag.="type=\"$type\" ";
		$tag.="class=\"uk-button uk-button-$theme\" ";
		if($formaction&&$type=='submit'){
			$tag.="formaction=\"$formaction\" ";
		}
		if($modal){
			$tag.="uk-toggle=\"target: #$modal\" ";
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
		$title	=	$opt['title'];
		$href	=	$opt['href'];
		$modal	=	$opt['modal'];
		$css	=	x::css($opt['css']);
		$theme	=	self::getThemeA($opt['theme']);
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
		if($name){
			$tag.="name=\"$name\" ";
		}
		if($method=='get'||$method=='post') {
			$tag.="method=\"$method\" ";
		}
		if($action){
			$tag.="action=\"$action\" ";
		}
		if($enctype){
			$tag.="enctype=\"$enctype\" ";
		}
		$tag.=$css;
		$tag =trim($tag);
		return	"<form $tag>$content</form>";
	}

	/**
	 * Возвращаем input (input)
	 * -------------------------
	 * id           -   Индентификатор
	 * form			-	Индентификатор
	 * name			-	Имя
	 * type			-	Тип (button, checkbox, color, date, datetime-local, email, file, hidden, image, month, number, password, radio, range, reset, search, submit, tel, text, time, url, week)
	 * value		-	Значение
	 * placeholder	-	Подсказка
	 * size			-	Ширина объекта
	 * width		-	Ширина
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
	 * pattern		-	Выражение
	 * theme		-	Тема
	 * css			-	Стиль
	 * -------------------------
	 * @return string
	 */
	public function input($opt){
		$id=$opt['id'];
    	$form=$opt['form'];
		$name=$opt['name'];
		$type=$opt['type'];
		$value=$opt['value'];
		$placeholder=$opt['placeholder'];
		$size=$opt['size'];
		$width=$opt['width'];
		$enabled=$opt['enabled'];
		$readonly=$opt['readonly'];
		$class=$opt['class'];
		$checked=$opt['checked'];
		$required=$opt['required'];
		$min=$opt['min'];
		$max=$opt['max'];
		$accept=$opt['accept'];
		$multiple=$opt['multiple'];
		$step=$opt['step'];
		$pattern=$opt['pattern'];
		$theme=self::getTheme($opt['theme']);
		$css=x::css($opt['css']);
		$tag.="type=\"$type\" ";
       	if($form){
        	$tag.="form=\"$form\" ";
        }
		if($name) {
			$tag.="name=\"$name\" ";
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
		if($placeholder){
			$tag.="placeholder=\"$placeholder\" ";
		}
		switch($type){
			case 'button':
				$class.="uk-button uk-button-$theme";
			break;
			case 'checkbox':
				$class.='uk-checkbox';
			break;
			case 'radio':
				$class.='uk-radio';
			break;
			case 'submit':
				$class.="uk-button uk-button-$theme";
			break;
			case 'range':
				$class.='uk-range';
			break;
			case 'reset':
				$class.="uk-button uk-button-$theme";
			break;
			case 'file':
			break;
        	default:
        		$class.='uk-input';
        	break;
		}
		if($class){
			$tag.="class=\"$class\" ";
		}
		if($size){
			$tag.="size=\"$size\" ";
		}
		if(!is_null($min)&&$type=='number'){
			$tag.="min=\"$min\" ";
		}elseif(!is_null($min)){
			$tag.="minlength=\"$min\" ";
		}
		if(!is_null($max)&&$type=='number'){
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
		if($id){
			$tag.="id=\"$id\" ";
		}
		if($multiple){
			$tag.="multiple ";
		}
		if($required) {
			$tag.='required ';
		}
		if($readonly){
			$tag.='readonly ';
		}
		$tag.=$enabled;
		$tag=trim($tag);
		if($type=='radio'){
		    $tag.="value=\"$value\" ";
		    if($checked){
		    	$tag.='checked';
		    }
			return"<label><input $tag>$value</label>";
		}elseif($type=='checkbox'){
			$tag.=$css;
        	$tag=trim($tag);
        	if($checked) {
				$tag.='checked';
			}
        	return"<label><input $tag>$value</label>";
        }else{
			$tag.=$css;
			return"<input $tag>";
		}
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
	 * css			-	Стиль
	 * -----------------------------------------
	 * @return string
	 */
	public function textarea($opt){
		$enabled=$opt['enabled'];
		$readonly=$opt['readonly'];
		$name=$opt['name'];
		$placeholder=$opt['placeholder'];
		$value= $opt['value'];
		$rows=$opt['rows'];
		$required=$opt['required'];
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
		$tag=trim($tag);
		return	"<textarea class=\"uk-textarea\" $tag>$value</textarea>";
	}
	/**
	 * Возвращаем лист в виде массива
	 * ------------------------------
	 * txt		-	Массив текста
	 * theme	-	Тема
	 * css		-	Стиль
	 * ------------------------------
	 */
	public function ListText($opt){
		$txtArr=$opt['txt'];
		$theme=self::getThemeListText($opt['theme']);
		$css=x::css($opt['css']);
		foreach($txtArr as $text){
			$txt.="<li>$text</li>";
		}
		$tag.=$css;
		$tag=trim($tag);
		return "<ul class=\"uk-list uk-list-disc uk-list-$theme\" $tag>$txt</ul>";
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
		return	self::p(['content'=>$txt,'css'=>$css,'theme'=>$theme]);
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
		$src=$opt['src'];
		$css=x::css($opt['css']);
		if($src){
			$tag.="src=\"$src\" ";
		}
		$tag.=$css;
		$tag=trim($tag);
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
		$body		=	$opt['body'];
		$size		=	$opt['size'];
		$content	=	$opt['content'];
		$last	=	$opt['last'];
		$theme		=	self::getThemeCard($opt['theme']);
		$css		=	x::css($opt['css']);
		if(!$last){
			x::addCss(['margin-bottom' => '5px']);
		}
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
		}else{
			x::addCss(['display' => 'table']);
		}
		$css = x::getCss();
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
	 * onChange -	Действие при выборе
	 * id		-	Ид
     * theme	-	Тема
	 * css		-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function combobox ($opt) {
		$name=$opt['name'];
		$selected=$opt['selected'];
		$onChange=$opt['onChange'];
		$id=$opt['id'];
		$theme=self::getTheme($opt['theme']);
		$css=x::css($opt['css']);
		if($name){
			$tag.="name=\"$name\" ";
		}
		$tag.="class=\"uk-button uk-button-$theme\" ";
		foreach (array_keys($opt[0]) as $title) {
			if($selected == $title){
				$createSelected=true;
				$selected="<option value=\"$title\">$title</option>";
			} else {
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
			$css=x::css($item['css']);
			$theme=self::getTheme($item['content']);
			$slim=$item['content'];
			unset($items);
			unset($content);
			foreach ($item['item'] as $item => $val) {
				$href=$val['href'];
				$modal=$val['modal'];
				$items.=self::item($item, $href, $modal);
			}
			foreach($slim as $con){
				$content.=$con;
			}
			if(!$content){
				$output.="<div class=\"uk-inline\" $css><button class=\"uk-button uk-button-$theme\" type=\"button\" $css>$title</button><div uk-dropdown class=\"uk-dropdown-default\">$items</div></div>";
			}else{
                $output.="$content<div class=\"uk-inline\" $css><button class=\"uk-button uk-button-$theme\" type=\"button\" $css>$title</button><div uk-dropdown class=\"uk-dropdown-default\">$items</div></div>";
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
		if($modal){
			$tag.="href=\"$href\" uk-toggle";
		} else {
			$tag.="href=\"$href\" ";
		}
		$tag=trim($tag);
		return"<ul class=\"uk-nav uk-dropdown-nav\"><li><a $tag>$title</a></li></ul>";
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
		$title=x::div(['class'=>'uk-card-title','css'=>['padding'=>'10px'],'content'=>$opt['title']]);
    	$theme=self::getTheme($opt['theme']);
		$content=$opt['content'];
		$css=$opt['css'];
		if(!$title){
			$title='Пустое название ;)';
		}
		if(!$content){
			$content='Пустой контент ;)';
		}
        $content=self::border(['css'=>$css,'theme'=>$theme,'body'=>true,'content'=>$content]);
        unset($css['height']);
        unset($css['display']);
    	$title=self::border(['css'=>$css,'theme'=>$theme,'body'=>false,'content'=>$title]);
    	$panel=x::div(['content'=>$title.$content]);
		$tag.=$css;
		$tag=trim($tag);
    	return $panel;
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
				$css['display']='table';
				//$title=$xlib->div(['class' => 'uk-card-title', 'css' => ['padding' => '10px'], 'content' => $opt['title']]);
				$title=self::border(['css'=>$css,'content'=>x::div(['class'=>'uk-card-title','css'=>['padding'=>'10px'],'content'=>$title])]);
				unset($css['display']);
				$content=self::border(['css'=>$css,'body' => true,'content'=>$content,'last'=>true]);
				$panel.=$title.$content;
			}else{
				$panel.=self::panel(['title'=>$title,'content'=>$content,'css'=>$css]);
			}
			$panel.="\n";
		}
		$tag.=$css;
		$tag=trim($tag);
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
		$name=$opt['name'];
		$size=$opt['size'];
		$required=$opt['required'];
		$form=$opt['form'];
		$css=x::css($opt['css']);
		x::addCss(['height'=>'unset','padding'=>'unset']);
		$css=x::getCss();
		if($name){
			$tag.="name=\"$name\" ";
		}
		$tag.="size=\"$size\" ";
		foreach($opt[0] as $title){
			$item.="<option value=\"$title\">$title</option>";
		}
		if($form){
			$tag.="form=\"$form\" ";
		}
    	$tag.="class=\"uk-input\" ";
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
	 * css	-	Стиль
	 * ------------------------
	 * @return string
	 */
	public function badge($opt){
		$id=$opt['id'];
		$txt=$opt['txt'];
		$css=x::css($opt['css']);
		if($id){
			$tag.="id='$id' ";
		}
		$tag.="class='uk-badge' ";
		$tag.=$css;
		$tag=trim($tag);
		return"<span $tag>$txt</span>";
	}
	/**
	 * Возвращаем сеппаратор (Разделитель)
	 * size - Размер
	 */
	public function sep($opt){
		$size=$opt['size'];
		$css=x::css($opt['css']);
		switch($size){
			case 'large':
				$class.='-large';
			break;
		}
		$tag.="class=\"uk-margin$class\" ";
		$tag.=$css;
		$tag.=trim($tag);
		return "<hr $tag>";
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
		$tag.=$css;
		$tag.=$class;
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
	public function ico($ico='start',$opt=[]){
		$css=$opt['css'];
    	switch($ico){
        	case 'adjust':
        		$ico='paint-bucket';
        	break;
        	case 'eye-open':
        		$ico='folder';
        	break;
        }
		$tag.=$css;
		$tag=trim($tag);
    	return"<span uk-icon='$ico' $tag></span>";
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
			$exit="<button class=\"uk-modal-close-default\" type=\"button\" uk-close></button>";
		}
		if($exit||$title){
			if($title){
				$title="<h2 class=\"uk-modal-title\">$title</h2>";
			} else {
				$title=false;
			}
			$head="<div class=\"uk-modal-header\">$title</div>";
		}
		echo"<div id=\"$id\" uk-modal><div class=\"uk-modal-dialog\">$exit$head<div class=\"uk-modal-body uk-card uk-card-default\" $css>$content</div></div></div>";
		if($open){
			x::js("UIkit.modal(\"#$id\").show();");
			jq::addLoad("UIkit.modal(\"#$id\").show();");
		}
		return$id;
	}
	/**
	 * Возвращаем открытую модальную форму (modal)
	 * -----------------------------------
	 * id		-	Название
	 * -----------------------------------
	 * @return string
	 */
	public function OpenModal($id){
		x::js("UIkit.modal(\"#$id\").show();");
		jq::addLoad("UIkit.modal(\"#$id\").show();");
		return true;
	}
	/**
	 * Возвращаем меню пагинацию
	 * --------------------------
	 * max		-	Максимальное число элементов на 1 странице (5)
	 * indent	-	Отступы (5)
	 * align	-	Расположение элемента пагинаций
	 * content	-	Массив с содержимом
	 */
	public function pagination($opt){
    	$max=$opt['max'];
    	$indent=$opt['indent'];
    	$align=$opt['align'];
		$data=$opt['data'];
    	$list=[];
    	$countContent=count($data);
    	$step=0;
    	switch($align){
        	case 'right':
        		$align='text-align:right;';
        	break;
        	case 'center':
        		$align='text-align:center;';
        	break;
        }
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
    	$uri=str_replace("?".xlib::getData(),NULL,$uri);
    	$html="<ul class=\"uk-pagination\">";
    	if(x::getDataToArray()['page']>0){
        	$prev=x::getDataToArray()['page'] - 1;
        	$as="<li><a href=\"$uri?page=$prev\" style=\"display: content;\"><span uk-pagination-previous></span></a></li>";
        }
 		foreach(array_keys($list) as $val){
        		if($val>$prev&&$val>0&&$val!=0&&$created<$indent){
                	if($val!=x::getDataToArray()['page']){
                    	$created++;
                    	$list['pagination'].=" <li><a href=\"$uri?page=$val\">$val</a></li>";
                    }
            	}elseif($created>=$indent){
                	$ns="<li><a href=\"$uri?page=$val\" style=\"display: contents;\"><span uk-pagination-next></span></a></li>";
                	break;
                }
        }
    	if($step==1){
    		$list['pagination']=$list['pagination'];
        }else{
        	$list['pagination']=x::div(['style'=>"$align",'content'=>$html.$as.$list['pagination'].$ns."</ul>"]);
        }
    	return $list;
    }

	/**
	 * Возвращаем lightbox (lightbox)
	 * -------------------------------
	 * src		-	Изоброжение
	 * stretch	-	Растягивание
	 * css		-	Стиль
	 * -------------------------------
	 * @return string
	 */
	public function lightbox($opt){
		$src=$opt['src'];
       	$stretch=$opt['stretch'];
       	$css=$opt['css'];
    	if(gettype($stretch)=='boolean'){
        	$width='100%';
        	$style_img='style="width:inherit;border-radius:inherit;"';
        }elseif($stretch){
        	$width=$stretch;
        	$style_img='style="width:inherit;border-radius:inherit;"';
        }else{
        	$width='50%';
        	$style_img='style="width:inherit;border-radius:inherit;"';
        }
        $css['width']=$width;
		$css=x::css($css);
        $share.="<div><a class=\"uk-inline\" href=\"$src\" $css><img $style_img src=\"$src\" width=\"50%\" uk-img></a></div>";
		return	"<div uk-lightbox=\"animation: fade\">$share</div>";
	}
	/**
	 * Возвращаем видеоплеер
	 * -------------------------
	 * src-Путь к файлу
	 * width-Ширина
	 * height-Высота
	 * controls-Панель управление (false/true)
	 * preload-Используется для загрузки видео вместе с загрузкой веб-страницы. (none,metadata,auto)
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
		if(!empty($src)){
			$tag.="src=\"$src\" ";
		}
		if(!empty($width)){
			$tag.="width=\"$width\" ";
		}
		if(!empty($height)){
			$tag.="height=\"$height\" ";
		}
		if(!empty($preload)){
			switch($preload){
				case 'none':
					$tag.="preload=\"none\" ";
				break;
				case 'metadata':
					$tag.="preload=\"metadata\" ";
				break;
				case 'auto':
					$tag.="preload=\"auto\" ";
				break;
			}
		}
		if(is_null($controls)){
			$tag.='controls ';
		}elseif($controls){
			$tag.='controls ';
		}
		if($autoplay){
			$tag.='autoplay ';
		}
		if($muted){
			$tag.='muted ';
		}
		if($loop){
			$tag.='loop ';
		}
		$css=$opt['css'];
		$tag.=$css;
		$tag=trim($tag);
    	return"<video $tag></video>";
    }
	
	/**
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
	/**
	 * Возвращает тему
	 * theme - (default, primary, secondary)
	 * @return String
	 */ 
	function getThemeCard ($theme) {
		switch($theme){
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
	/**
	 * Возвращает тему
	 * theme - (default, muted, emphasis, primary, secondary)
	 * @return String
	 */ 
	function getThemeListText($theme){
		switch($theme){
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
