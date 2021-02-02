<?php

/**
 * Кнопка поднятие вверх
 * ---------------------
 * @ver 0.5
 */
class backToTop {

	protected	$align			=	'bottom: 0px;',
				$color			=	'#374760',
				$colorHover		=	'#3cc091',
				$title			=	'Подняться наверх!',
				$width			=	40,
				$height			=	40,
				$margin_left	=	'margin-left: 40px;',
				$margin_bottom	=	'margin-bottom: 40px;',
				$animation		=	'bounce',
				$image 			=	false;

	/**
	 * Создание кнопки наверх
	 */
	public function getHtml () {
		$color			=	$this->color;
		$colorHover		=	$this->colorHover;
		$title			=	$this->title;
		$align			=	$this->align;
		$width			=	$this->width;
		$height			=	$this->height;
		$margin_left	=	$this->margin_left;
		$margin_bottom	=	$this->margin_bottom;
		$animation		=	$this->animation;
		$animShow		=	$animation . 'Out';
		$animExit		=	$animation . 'In';
		$image 			=	$this->image;
		if ($image) {
			$style = "<style>.back-to-top{visibility: visible;position: fixed;width: $width;height: $height;$align cursor: pointer;opacity: 0;$margin_left$margin_bottom$margin_right$margin_top}.back-to-top.show {visibility: visible;position: fixed;$align z-index: 1;opacity: 1;transition: all .6s;}</style>";
			return $style . "<a href='#' class='back-to-top animated show $animExit' id='back-to-top' title='$title'><img class='back-to-top-image' src=\"$image\" style=\"width: $width;height: $height;\"></img></a>";
		} else {
			$style = "<style>.back-to-top{visibility: visible;position: fixed;background-color: $color;width: $width;height: $height;$align cursor: pointer;opacity: 0;$margin_left$margin_bottom$margin_right$margin_top}.back-to-top:hover{background-color: $colorHover;opacity: 1}.back-to-top.show {visibility: visible;position: fixed;$align z-index: 1;opacity: 1;transition: all .6s;}</style>";
			return $style . "<a href='#' class='back-to-top animated show $animExit' id='back-to-top' title='$title'></a>";
		}
	}

	/**
	 * Устанавливает расположение
	 * top-left		-	Сверху-слева
	 * left			-	Слева
	 * bottom-left	-	Снизу-слева
	 * bottom		-	Низ
	 * bottom-right	-	Снизу-справо
	 * right		-	Справо
	 * top-right	-	Сверху-справо
	 * center		-	Середина
	 * --------------------------
	 * align	-	Значение (left)
	 */
	public function setAlign ($align = 'left') {
		if ($align == 'left') {
			$align = 'bottom: 50%;';
		}
		if ($align == 'bottom-left') {
			$align = 'bottom: 0px;';
		}
		if ($align == 'bottom') {
			$align = "bottom: 0%;";
			$align .= "left: 50%;";
		}
		if ($align == 'bottom-right') {
			$procentwidth = 100 - $this->width / 10;
			$align = "bottom: 0;";
			$align .= "left: $procentwidth%;";
		}
		if ($align == 'right') {
			$procent = 100 - $this->width / 10;
			$align = "bottom: 50%;";
			$align .= "left: $procent%;";
		}
		if ($align == 'top-right') {
			$procentwidth = 100 - $this->width / 10;
			$align = "left: $procentwidth%;";
		}
		if ($align == 'top') {
			$procent = 100 - $this->height / 10;
			$align = "left: 50%;";
		}
		if ($align == 'center') {
			$align = 'bottom: 50%;';
			$align .= 'left: 50%;';
		}
		$this->align = $align;
	}

	/**
	 * Установить картинку
	 * --------------------
	 * img	-	Картинка (green.png)
	 */
	public function setImage ($img = 'green.png') {
		$xlib = new xlib();
		$img = $xlib->getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $img);
		$this->image = $img;
	}

	/**
	 * Установить подсказку
	 * --------------------
	 * title	-	Значение (Подняться наверх!)
	 */
	public function setTitle ($title = 'Подняться наверх!') {
		$this->title = $title;
	}

	/**
	 * Установить Цвет
	 * ---------------
	 * color	-	Цвет (#374760)
	 */
	public function setColor ($color = '#374760') {
		$this->color = $color;
	}

	/**
	 * Установить Ширину
	 * -----------------
	 * width	-	Значение (40px)
	 */
	public function setWidth ($width = '40px') {
		$this->width = $width;
	}

	/**
	 * Установить Высоту
	 * -----------------
	 * height	-	Значение (40px)
	 */
	public function setHeight ($height = '40px') {
		$this->height = $height;
	}

	/**
	 * Установить отступ слева
	 * -----------------------
	 * value	-	Значение (40px)
	 */
	public function setPaddingLeft ($value = '40px') {
		$this->margin_left = "margin-left: $value;";
	}

	/**
	 * Установить отступ от низа
	 * -------------------------
	 * value	-	Значение (40px)
	 */
	public function setPaddingBottom ($value = '40px') {
		$this->margin_bottom = "margin-bottom: $value;";
	}

	/**
	 * Установить анимацию (animate)
	 * -----------------------------
	 * name	-	Имя анимаций (bounce)
	 */
	public function setAnimation ($name = 'bounce') {
		$this->animation = $name;
	}

	/**
	 * Установить цвет выделение
	 * -------------------------
	 * color	-	Цвет (#3cc091)
	 */
	public function setColorHover ($color = '#3cc091') {
		$this->colorHover = $color;
	}
}
