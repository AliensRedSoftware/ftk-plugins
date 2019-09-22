<?php

/**
 * Либа для упрощенной работы bootstrap 3.3.7
 * v2.0
 */
class bootstrap {

    /**
     * Возвращает оповещение
     * $text - Текст
     * $type - Тип оповещение
     * $align - Положение
     * @return string
     */
  public function alert ($text = "Текст уведомление" , $type = 'default', $align = 'left') {
    return "<div class=\"alert alert-$type alert-dismissible show\" role=\"alert\" align=\"$align\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>$text</div>";
  }

    /**
     * Возвращает панель
     * $content - Контент
     * $title - Название
     * $type - Тип
     * $stretch - Растягивание
     * $align - Распаложение контент
     * $alignTitle - Расположение загаловка
     */
    public function panel (array $options = ['content', 'title', 'theme', 'stretch', 'align', 'alignTitle', 'style']) {
        $optionsOLD = [
            'content' => "Контент пустой ;)",
            'title' => "Название формы",
            'theme' => "default",
            'stretch' => false,
            'align' => 'center',
            'alignTitle' => 'left',
            'style' => null
        ];
        $content = $options['content'];
        $title = $options['title'];
        $theme = $options['theme'];
        $stretch = $options['stretch'];
        $align = $options['align'];
        $style = $options['style'];
        $alignTitle = $options['alignTitle'];
        if ($content == null) {
            $content = $optionsOLD['content'];
        }
        if ($title == null) {
            $title = $optionsOLD['title'];
        }
        if ($theme == null) {
            $theme = $optionsOLD['theme'];
        }
        if ($stretch == null) {
            $stretch = $optionsOLD['stretch'];
        }
        if ($align == null) {
            $align = $optionsOLD['align'];
        }
        if ($alignTitle == null) {
            $alignTitle = $optionsOLD['alignTitle'];
        }
        if ($stretch == true) {
			if ($style != null) {
				return "<div class='panel panel-$theme' style=\"width: 100%;$style\"><div class='panel-heading' align='$alignTitle'>$title</div><div class='panel-body' align='$align'>$content</div></div>";
			} else {
				return "<div class='panel panel-$theme' style=\"width: 100%;\"><div class='panel-heading' align='$alignTitle'>$title</div><div class='panel-body' align='$align'>$content</div></div>";
			}
        } else {
			if ($style != null) {
				return "<div class='panel panel-$theme' style=\"$style\"><div class='panel-heading' align='$alignTitle'>$title</div><div class='panel-body' align='$align'>$content</div></div>";
			} else {
				return "<div class='panel panel-$theme' style=\"$style\"><div class='panel-heading' align='$alignTitle'>$title</div><div class='panel-body' align='$align'>$content</div></div>";
			}
        }
    }

    /**
     * Возвращает рамка
     * $content - Контент
     * $stretch - Растягивание
     * $type - тип
     * $align - Распаложение
     */
    public function border (array $options = ['content', 'stretch', 'theme', 'align']) {
        $optionsOLD = [
            'content' => "Контент пустой ;)",
            'stretch' => false,
            'theme' => 'default', 
            'align' => 'center'
        ];
        $content = $options['content'];
        $stretch = $options['stretch'];
        $theme = $options['theme'];
        $align = $options['align'];
        if ($content == null) {
            $content = $optionsOLD['content'];
        }
        if ($stretch == null) {
            $stretch = $optionsOLD['stretch'];
        }
        if ($theme == null) {
            $theme = 'default';
        }
        if ($align == null) {
            $align = $optionsOLD['align'];
        }
        if ($stretch == true) {
            return "<div class='panel panel-$theme' style='width: 100%;'><div class='panel-body' align='$align'>$content</div></div>";
        } else {
            return "<div class='panel panel-$theme'><div class='panel-body' align='$align'>$content</div></div>";
        }
    }

    /**
     * Возвращает кнопку
     * title - имя
     * id - индентификатор
     * type - тип
     * modal - модальность
     * style - css стиль
     * menu - Меню
     * modalType - меню тип
     * theme - стиль темы
     */
    public function btn (array $options = ['title', 'id', 'type', 'modal', 'style', 'collaps' , 'menu'
 ,'modalType', 'href', 'theme']) {
        $optionsOLD = [
            'title' => 'Пустая кнопка',
            'id' => null,
            'type' => 'button',
            'modal' => false,
            'style' => "/* style */",
            'menu' => null,
            'modalType' => null,
            'href' => '',
            'collaps' => null,
            'theme' => 'default'
        ];
        $title = $options['title'];
        $id = $options['id'];
        $type = $options['type'];
        $modal = $options['modal'];
        $style = $options['style'];
        $menu = $options['menu'];
        $modalType = $options['modalType'];
        $collaps = $options['collaps'];
        $href = $options['href'];
        $theme = $options['theme'];
        //onclick=\"location.href = '$href'\"
        if ($title == null) {
            $title = $optionsOLD['title'];
        }
        if ($id == null) {
            $id = $optionsOLD['id'];
        }
        if ($type == null) {
            $type = $optionsOLD['type'];
        }
        if ($modal == null) {
            $modal = $optionsOLD['modal'];
        }
        if ($style == null) {
            $style = $optionsOLD['style'];
        }
        if ($menu == null) {
            $menu = $options['menu'];
        }
        if ($modalType == null) {
            $modalType = $optionsOLD['modalType'];
        }
        if ($collaps == null) {
            $collaps = $optionsOLD['collaps'];
        }
        if ($href == null) {
            $href = $optionsOLD['href'];
        }
        if ($theme == null) {
            $theme = $optionsOLD['theme'];
        }
        if ($menu != null && $collaps != null) {
            $menu = null;
        }
        if ($collaps != null) {
            return "<button class='btn btn-$theme' type='button' data-toggle='collapse' data-target='#$id' aria-expanded='false' style='$style'>$title</button>" . $collaps;
        }
        if($menu != null && $modal == true) {
            return "<div class='btn-group' style='$style'><button type='button' data-dismiss='modal' class='btn btn-$theme dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='$style'>$title<span class='caret'></span><a href=\"$href\"></a></button>$menu</div>";
        } else if ($menu != null && $modal == false) {
            if ($href != null) {
              return "<div class='btn-group' style='$style'><button type='button' onclick='location.href = \"$href\"' class='btn btn-$theme dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='$style'>$title<span class='caret'></span></button>$menu</div>";
            } else {
              return "<div class='btn-group' style='$style'><button type='button' class='btn btn-$theme dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='$style'>$title<span class='caret'></span></button>$menu</div>";
            }
        } else {
            if ($modal == true) {
                if ($modalType == 'exit') {
                    if ($href != null) {
                      return "<button type='$type' onclick='location.href = \"$href\"' data-dismiss='modal' id='$id' class='btn btn-$theme' data-toggle='modal' data-target='#$id' style='$style'>$title</button>";
                    } else {
                      return "<button type='$type' data-dismiss='modal' id='$id' class='btn btn-$theme' data-toggle='modal' data-target='#$id' style='$style'>$title</button>";
                    }
                } else {
                    if ($href != null) {
                      return "<button onclick='location.href = \"$href\"' type='$type' id='$id' class='btn btn-$theme' data-toggle='modal' data-target='#$id' style='$style'>$title</button>";
                    } else {
                       return "<button type='$type' id='$id' class='btn btn-$theme' data-toggle='modal' data-target='#$id' style='$style'>$title</button>";
                    }
                }
            } else {
                if ($href != null) {
                  return "<button onclick='location.href = \"$href\"' type='$type' id='$id' class='btn btn-$theme' style='$style'>$title</button>";
                } else {
                  return "<button type='$type' id='$id' class='btn btn-$theme' style='$style'>$title</button>";
                }
            }
        }
    }

    /**
     * Возвращает меню
     * item - Пункт
     * theme - Тема
     */
    public function menu(array $options = [
        'align' => 'center',
        'width' => null,
        'item' => null,
        'theme' => 'default'
    ]) {
        $align = $options['align'];
        $optionsOLD = [
            'align' => 'center',
            'width' => null,
            'item' => $this->item(['align' => $align]),
            'theme' => 'default'
        ];
        $item = $options['item'];
        $theme = $options['theme'];
        $width = $options['width'];
        if ($item == null) {
            $item = $optionsOLD['item'];
        }
        if ($theme == null) {
            $theme = $optionsOLD['theme'];
        }
        if ($align == null) {
            $align = $optionsOLD['align'];
        }
        if ($width == null) {
            $width = $optionsOLD['width'];
        }
        $item = str_replace('$align', $align, $item);
        if ($width != null) {
            return "<ul class='dropdown-menu panel-$theme' style='width:$width;'>$item</ul>";
        } else {
            return "<ul class='dropdown-menu panel-$theme'>$item</ul>";
        }
    }

    /**
     * Добавить пункт url кнопки к menu
     * title - Имя
     * href - Ссылка
     * align - Выравнивание
     * modal - Модальность
     */
    public function item(array $options = [
            'title' => 'Тестовая ссылка',
            'href' => 'https://ya.ru',
            'align' => '$align',
            'modal' => false,
            'id' => 'id'
        ]) {
        $optionsOLD = [
            'title' => 'Тестовая ссылка',
            'href' => 'https://ya.ru',
            'align' => '$align',
            'modal' => false,
            'id' => 'id'
        ];
        $title = $options['title'];
        $href = $options['href'];
        $align = $options['align'];
        $modal = $options['modal'];
        $id = $options['id'];
        if ($title == null) {
            $title = $optionsOLD['title'];
        }
        if ($href == null) {
            $href = $optionsOLD['href'];
        }
        if ($align == null) {
            $align = $optionsOLD['align'];
        }
        if ($modal == null) {
            $modal = $optionsOLD['modal'];
        }
        if ($id == null) {
            $id = $optionsOLD['id'];
        }
        if ($modal == true) {
            return "<li><a href='' style=\"text-align:$align;\" data-toggle='modal' data-target='#$id'>$title</a></li>";
        } else {
            return "<li><a href='$href' style=\"text-align:$align;\">$title</a></li>";
        }
    }

    /**
     * Возвращаем кнопку разворачивает контекст
     * $text - Имя
     * $id - индентификатор
     * $type - тип
     */
    public function btn_collaps($text = 'Кнопка разварачивание', $id = 'myidCollaps', $type = 'default') {
        return "<button class='btn btn-$type' type='button' data-toggle='collapse' data-target='#$id' aria-expanded='false'>$text</button>";
    }

    /**
     * Возвращаем разварачиванный список
     * $content - контент
     * $id - индентификатор
     */
    public function collaps($content = 'Кнопка разварачивание', $id = 'myidCollaps') {
        return "<div class='collapse' id='$id'>$content</div>";
    }

    /**
     * Сеппаратор для пункта
     * modal - модальный сеппаратор
     */
    public function sep (array $options = ['content', 'align', 'modal']) {
        $optionsOLD = [
            'content' => null,
            'align' => 'right',
            'modal' => false
        ];
        $content = $options['content'];
        $align = $options['align'];
        $modal = $options['modal'];
        if ($content == null) {
            $content = $optionsOLD['content'];
        }
        if ($align == null) {
            $align = $optionsOLD['align'];
        }    
        if ($modal == null) {
            $modal = $optionsOLD['modal'];
        }
        if ($modal == true) {
            return "<div class='modal-footer' style=\"text-align: $align;\">$content</div>";
        } else {
            return '<li role="separator" class="divider"></li>';
        }
    }

    /**
     * Возвращаем модальную форму
     * $content - Контент
     * $title - загаловок
     * $id - индентификатор
     * $align - выравнивание
     */
    public function form (array $options = [
            'content' => null,
            'title' => 'Название формы',
            'id' => null,
            'align' => 'center'
        ]) {
	$xlib = new xlib();
        if ($options['id'] == null) {
            $id = $xlib->uuidv4();
        } else {
            $id = $options['id'];
        }
        $content = $options['content'];
        $title = $options['title'];
        $align = $options['align'];
        $optionsOLD = [
            'content' => null,
            'title' => 'Название формы',
            'id' => null,
            'align' => 'center'
        ];
        if ($content == null) {
            $content = $optionsOLD['content'];
        }
        if ($title == null) {
            $title = $optionsOLD['title'];
        }
        if ($align == null) {
            $align = $optionsOLD['align'];
        }
        echo "<div class='modal fade' id='$id' tabindex='-1' role='dialog' style='margin-block-end: 0em;'><div class='modal-dialog' role='document'><div class='modal-content'><div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button><h4 class='modal-title'>$title</h4></div><div class='modal-body' align='$align'>$content</div></div></div></div>";
        return $id;
    }

    /**
     * Возвращает компонент поля ввода
     * $description - описание
     * $id - индентификатор
     * $value - Значение
     * $type - тип
     */
    public function input ($description = 'Описание', $id = 'input', $value = null, $theme = 'default') {
        return "<p><input value='$value' id='$id' type='text' class='form-control panel-$theme' placeholder='$description' name='$id'/></p>";
    }

    /**
     * Возвращает компонент список
     * $option - Опций
     * $name - имя
     * $type - тип
     */
    public function combobox (array $options = ['option', 'id', 'theme']) {
        $optionsOLD = [
            'option' => "<option>Тестовая опция</option>",
            'id' => 'combobox',
            'theme' => 'default'
        ];
        $option = $options['option'];
        $id = $options['id'];
        $theme = $options['theme'];
        if ($option == null) {
            $option = $optionsOLD['option'];
        }
        if ($id == null) {
            $id = $optionsOLD['id'];
        }
        if ($theme == null) {
            $theme = $optionsOLD['theme'];
        }
        return "<p><select class='panel-$theme form-control' id='$id' name='$id'>$option</select></p>";
    }

    /**
     * Возвращает опцию
     * $name - имя
     */
    public function opt ($name = 'Название опции') {
        return "<option>$name</option>";
    }

    /**
     * Возвращает компонент поля для текста
     * $description - Описание
     * $id - Индентификатор
     * $type - тип
     */
    public function textarea ($description = 'Описание (текст)', $id = 'Text', $type = 'default') {
        return "<p><textarea type='text' class='panel-$type form-control' name='$id' placeholder='$description' id='$id' rows='3'></textarea></p>";
    }

    /**
     * Возвращает индикатор загрузки
     * $value - значение
     * $type - тип
     */
    public function progressbar($value = 50, $type = 'default') {
        return "<div class=\"progress\"><div class=\"progress-bar progress-bar-$type active\" role=\"progressbar\" aria-valuenow=\"$value\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: $value%\"><span class=\"sr-only\">$value% Complete</span></div></div>";

}

    /**
     * Возвращает загаловок
     * $text - Текст
     * $lvl - Уровень 1 - 6
     */
    public function h ($text = "Текст", $lvl = 1){
        return "<h$lvl>$text</h$lvl>";
    }

    /**
     * Метка
     * $text - Текст
     */
    public function span ($text = "Текст") {
        return "<span class='badge'>$text</span>";
    }

    /**
     * Иконка - http://getbootstrap.ru/docs/3.3.7/components/#glyphicons
     * $ico - иконка
     */
    public function ico ($ico = "ok") {
        return "<span class=\"glyphicon glyphicon-$ico\" aria-hidden=\"true\"></span>";
    }
}
