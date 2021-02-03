<?php
/**
 * basic
 * --------------------
 * v1.2
 */
use xlib as x;
use skinmanager as sm;
class basic{
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
	public function btn($opt){
		$title=$opt['title'];
		$enabled=$opt['enabled'];
		$type=$opt['type'];
		$formaction=$opt['formaction'];
		$modal=$opt['modal'];
		$css=x::css($opt['css']);
		$tag.="value=\"$title\" ";
		if($modal){
			$tag.="type=\"submit\" ";
			$tag.="formaction=\"#$modal\" ";
			$tag.=$enabled;
			$tag=trim($tag);
			return"<form><button $tag>$title</button></form>";
		}else{
			$tag.="type=\"$type\" ";
			if($formaction&&$type=='submit'){
				$tag.="formaction=\"$formaction\" ";
			}
			$tag.=$css;
			$tag.=$enabled;
			$tag=trim($tag);
			return"<button $tag>$title</button>";
		}
	}
    /**
     * Возвращаем ссылку <a>
     * ----------------------
     * title	-	Загаловок
     * href		-	Cсылка
	 * css		-	Стиль
	 * class    -   Класс
     * ----------------------
     * @return string
	 */
	public function a($opt){
		$title=$opt['title'];
		$href=x::BURL($opt['href']);
		$css=x::css($opt['css']);
		$class=$opt['class'];
		if($href){
			$tag.="href=\"$href\" ";
		}
		if($class){
			$tag.="class=\"$class\" ";
		}
		$tag.=$css;
		$tag=trim($tag);
		return"<a $tag>$title</a>";
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
		$action=x::BURL($opt['action']);
		$content=$opt['content'];
		$enctype=	$opt['enctype'];
		$css=x::css($opt['css']);
		if($id){
			$tag.="id=\"$id\" ";
		}
		if($name){
			$tag.="name=\"$name\" ";
		}
		if($method=='get'||$method=='post'){
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
		return"<form $tag>$content</form>";
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
     * readonly		-	Чтивость
     * class 		-	Класс
     * checked		-	Выбранный компонент (radio)
     * required		-	Проверка
     * min			-	Минимальный размер (number)
     * max			-	Максимальный размер (number)
     * accept		-	Расширение для загрузки (file) (video/*,image/*,audio/*)
	 * multiple		-	Отправлять файлы сразу несколько (file)
	 * step         -   Шаг числа (number)
     * css			-	Стиль
     * theme		-	Тема
     * -----------------------
     * @return string
     */
    public function input($opt){
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
		$theme=$opt['theme'];
		$css=x::css($opt['css']);
		$tag.="type=\"$type\" ";
       	if($form){
        	$tag.="form=\"$form\" ";
        }
		if($name){
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
		if($multiple){
			$tag.='multiple ';
		}
		if($required) {
			$tag.='required ';
		}
		if($readonly){
			$tag.='readonly ';
		}
		if($type=='radio'){
			$tag.="value=\"$value\" ";
			if($checked){
				$tag.='checked ';
			}
			$tag.=$css;
			$tag.=$enabled;
			$tag=trim($tag);
			return self::label(['text'=>"<input $tag> $value"]);
		}elseif($type=='checkbox'){
        	if($checked){
				$tag.='checked ';
			}
			$tag.=$css;
			$tag.=$enabled;
			$tag=trim($tag);
        	return self::label(['text'=>"<input $tag> $value"]);
        }else{
        	$tag.=$css;
			$tag.=$enabled;
			$tag=trim($tag);
        	return"<input $tag>";
		}
	}
    /**
     * Возвращаем многострочное поле (textarea)
     * ----------------------------------------
     * enabled		-	Доступность
     * readonly     -   Чтивость
     * name 		-	Имя
     * placeholder	-	Подсказка
     * value		-	Значение
     * rows			-	Наборы или возврат значения атрибута строк области текста
     * required		-	Проверка
     * css			-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function textarea($opt){
		$enabled=$opt['enabled'];
		$readonly=$opt['readonly'];
		$name=$opt['name'];
		$placeholder= $opt['placeholder'];
		$value=$opt['value'];
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
		$tag.=$css;
		$tag.=$enabled;
		if($readonly){
		    $tag.=' readonly';
		}
		$tag=trim($tag);
		return"<textarea $tag>$value</textarea>";
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
		$css=x::css($opt['css']);
		if($for){
			$tag.="for=\"$for\" ";
		}
		$tag.=$css;
		$tag=trim($tag);
		return	"<label $tag>$text</label>";
	}
    /**
     * Возвращаем текст многорастянутый (text)
     * -----------------------
     * text		-	Текст
     * css		-	Стиль
     * -----------------------
     * @return string
     */
	public function text($opt){
		$text=$opt['text'];
		$css=x::css($opt['css']);
		return self::p(['content'=>$text,'css'=>$css]);
	}
    /**
     * Возвращаем картинку (img)
     * -------------------------
     * src	-	Изоброжение
	 * css	-	Стиль
     * -------------------------
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
     * ---------------------------
     * content	-	Контент
     * css		-	Стиль
     * ---------------------------
     * @return string
     */
	public function border($opt){
		$content=$opt['content'];
		$last=$opt['last'];
		$css=x::css($opt['css']);
		if(!$last){
			x::addCss(['margin-bottom' => '5px']);
		}
		$css=x::getCss();
		$tag.=$css;
		$tag=trim($tag);
		return"<div class=\"border\" $tag>$content</div>";
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
	public function panel($opt){
		$title=$opt['title'];
		$content=$opt['content'];
		$css=$opt['css'];
		$css['display']='table';
    	$title=self::border(['css'=>$css,'content'=>$title]);
    	unset($css['display']);
    	$content=self::border(['css'=>$css,'content'=>$content]);
    	$panel=$title.$content;
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
				$title = self::border(['css'=>$css,'content'=>$title]);
				unset($css['display']);
				$content=self::border(['css'=>$css,'content'=>$content,'last'=>true]);
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
     * Возвращаем выпадающий список (combobox)
     * ----------------------------------------
     * name		-	Имя
	 * selected	-	Выбранный элемент
     * css		-	Стиль
     * ----------------------------------------
     * @return string
     */
	public function combobox($opt){
		$name=$opt['name'];
		$selected=$opt['selected'];
		$css=x::css($opt['css']);
		if($name){
			$tag.="name=\"$name\" ";
		}
		foreach(array_keys($opt[0]) as $title){
			if($selected == $title){
				$createSelected=true;
				$selected="<option value=\"$title\">$title</option>";
			}else{
				$item.="<option value=\"$title\">$title</option>";
			}
		}
		if($createSelected){
			$selected .= $item;
			$item = $selected;
		}
		$tag.=$css;
		$tag=trim($tag);
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
	public function dropdown($opt = [string => ['css', 'content' => [], 'item' => [string => ['href', 'modal']]]]){
		foreach($opt[0] as $title=>$item){
			$css=xlib::css($item['css']);
			$slim=$item['content'];
			unset($items);
			unset($content);
			foreach($item['item'] as $item => $val){
				$href=$val['href'];
				$items.=self::item($item, $href);
			}
			foreach($slim as $con){
				$content.=$con;
			}
			if (!$content){
				$output.="<li $css><a>$title</a><ul class=\"border\" style=\"position:absolute;\">$items</ul></li>";
			} else {
				$output.="$content<li $css><a>$title</a><ul class=\"border\" style=\"position:absolute;\">$items</ul></li>";
			}
		}
		return"<ul style=\"display: contents;\">$output</ul>";
	}
	/**
	 * Возвращаем item (dropdown)
	 * ---------------------------
	 * title	-	Загаловок
	 * href		-	Ссылка
	 * ---------------------------
	 * @return string
	 */
	public function item($title, $href){
		$href=x::BURL($href);
		$tag.="href=\"$href\" ";
		$tag=trim($tag);
		return	"<li><a $tag>$title</a></li>";
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
		foreach($opt[0] as $title){
			$item.="<option value=\"$title\">$title</option>";
		}
		if($form){
			$tag.="form=\"$form\" ";
		}
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
		$tag.=$css;
		$tag=trim($tag);
		return"<span>($txt)</span>";
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
		$content=$opt['content'];
		$css=$opt['css'];
		$tag.=$css;
		$tag=trim($tag);
		return "<p $tag>$content</p>";
	}
	/**
	 * Возвращаем видеоплеер
	 * -------------------------
	 * src-Путь к файлу
	 * width-Ширина
	 * height-Высота
	 * controls-Панель управление (false/true)
	 * preload-Используется для загрузки видео вместе с загрузкой веб-страницы. (none,metadata,auto)
	 * -------------------------
	 * @return string
	 */
	public function video($opt){
		$src=$opt['src'];
		$width=$opt['width'];
		$height=$opt['height'];
		$controls=$opt['controls'];
		$preload=$opt['preload'];
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
		$css=$opt['css'];
		$tag.=$css;
		$tag=trim($tag);
    	return"<video $tag></video>";
    }
	/**
	 * Возвращаем модальная форма (modal)
	 * -----------------------------------
	 * id		-	Индентификатор
	 * title	-	Название
	 * exit		-	Кнопка выход (false/true)
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
		//Запрет на создание повторных форм
		if(!defined($id)){
			define($id,$id);
		}else{
			return $id;
		}
		if($exit){
			$exit="<a class=\"close\" title=\"Закрыть\" href=\"#close\"></a>";
		}
		if($exit||$title){
			$head="<div class=\"modal-header\"><h2>$title</h2>$exit</div>";
		}
		$o=x::strRand();
		//overlays
		x::style(".$o{top:0px;right:0px;bottom:0px;left:0px;z-index:10;display:none;background-color:rgba(0, 0, 0, 0.65);position:fixed;cursor:default;overflow-y:auto;}");
		//target
		x::style(".$o:target{display:block;}");
		echo "<div href=\"#x\" class=\"$o\" id=\"$id\"><div id=\"$id\" class=\"popup\">$head<div class=\"modal-body\" $css>$content</div></div></div>";
		if($open){
			echo "<meta http-equiv=\"refresh\" content=\"0;url=#$id\">";
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
	    echo "<meta http-equiv=\"refresh\" content=\"0;url=#$id\">";
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
	public function pagination(array $opt){
    	$max=$opt['max'];
    	$indent=$opt['indent'];
    	$align=$opt['align'];
		$content=$opt['data'];
    	$list=[];
    	$step=0;
    	switch($align){
        	case 'right':
        		$align='text-align:right;';
        	break;
        	case 'center':
        		$align='text-align:center;';
        	break;
        }
    	foreach ($content as $val) {
        	$list[$step].=$val;
        	$i++;
        	if ($i==$max) {
            	array_shift($content);
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
    	if(x::getDataToArray()['page']>0){
        	$prev=x::getDataToArray()['page'] - 1;
        	$as=self::a(['title'=>'Назад','href'=>"$uri?page=$prev"]).'|';
        }
 		foreach(array_keys($list) as $val){
        		if($val>$prev&&$val>0&&$val!=0&&$created<$indent){
                	if($val!=x::getDataToArray()['page']){
                    	$created++;
        				$list['pagination'].=self::a(['title'=>$val,'href'=>"$uri?page=$val"]).'|';
                    }
            	}elseif($created>=$indent){
                	$ns=self::a(['title'=>'Дальше','href'=>"$uri?page=$val"]);
                	break;
                }
        }
    	if($step==1){
        	$list['pagination']=$list['pagination'];
        }else{
    		$list['pagination']=x::div(['style'=>"$align",'content'=>$as.$list['pagination'].$ns]);
        }
    	return $list;
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
    	$stretch=$opt['stretch'];
    	$max=$opt['max'];
		$id+=$_REQUEST['SKINMANAGER_BASIC_LB']+1;
		$_REQUEST['SKINMANAGER_BASIC_LB']=$id;
		$form="img$id";
		$id++;
		$srcN="img$id";
		$id-=2;
		$srcB="img$id";
    	if($stretch){
        	$style_lightbox	=	'style="width:100%;"';
        	$style_img		=	"style=\"width:inherit;content:url($src);\"";
        }else{
        	$style_lightbox	=	'style="width:50%;"';
        	$style_img		=	"style=\"width:inherit;content:url($src);\"";
        }
        $next=sm::a(['class'=>'lightboxBtn lightbox-next','href'=>"#$srcN"]);
        $back=sm::a(['class'=>'lightboxBtn lightbox-back','href'=>"#$srcB"]);
        $close=sm::a(['class'=>'lightboxBtn lightbox-close','href'=>"#x"]);
$img=x::div(['css'=>['background-image'=>"url('$src')",'position'=>'absolute','top'=>0,'right'=>0,'bottom'=>0,'left'=>0,'margin'=>'auto','height'=>'calc(100%/2)','background-repeat'=>'no-repeat','background-position-x'=>'center','background-size'=>'contain']]);
		$box=sm::a(['href'=>$src,'title'=>$img]);
		//preloading
		$o=x::strRand();
		//styles
		x::style(".$o{top:0;height:100%;position:fixed;background:rgba(0,0,0,.7);width:100%;z-index:10;display:none;}");
		//target
		x::style(".$o:target{display:block;}");
        if($_REQUEST['SKINMANAGER_BASIC_LB']!=1){
			sm::addSuperBox(x::div(['class'=>$o,'id'=>$form,'id'=>$form,'content'=>$box.$next.$back.$close]));
		}else{
			sm::addSuperBox(x::div(['class'=>$o,'id'=>$form,'id'=>$form,'content'=>$box.$next.$close]));
		}
		//target
		$control=x::strRand();
		$ico=x::getPathModules('skinmanager/theme/basic/img/preloading.png');
		x::style("#$control{}#$control:checked + label > a{background-image:url(\"$src\");background-repeat:no-repeat;background-size:100%;height:320px;padding-left:320px;display:table;}");
		$selected=sm::input(['css'=>['width'=>'100%','margin-bottom'=>'5px']]);
		if(!$_COOKIE['__LIGHTBOX_VIEW']){
			return"<input type='checkbox' id='$control' style=\"width: 100%;margin-bottom: 5px;\"/><label style='width:100%;' for='$control'><a href='#$form'><div class=\"lightbox\" style='min-height:64px;background-image:url($ico);'></div></a></label>";
		}else{
			return"<input checked type='checkbox' id='$control' style=\"width: 100%;margin-bottom: 5px;\"/><label style='width:100%;' for='$control'><a href='#$form'><div class=\"lightbox\" style='min-height:64px;background-image:url($ico);'></div></a></label>";
		}
	}
}
