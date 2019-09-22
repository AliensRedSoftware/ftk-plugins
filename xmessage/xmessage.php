<?php

/*
 * Отправка сообщение (Создание своего форума)
 * ---------------------------------------------
 * ver beta 1.37
 */
class xmessage extends xlib {

	/**
     * Возвращаем версию
	 * -----------------
	 * @return string
	 */
	public function getVersion () {
		$skinmanager = new skinmanager();
		return ' (' . __CLASS__ . ' ' . $skinmanager->badge('beta 1.37') . ')';
	}

	/**
     * Возвращает кол-во тредов в доске
	 * $id - название доски
     */
	public function getCountDoska ($id) {
		$path = "../../../../uri/о/$id";
		if (is_dir($path) == false) {
			return -1;
		}
		$iteam = scandir($path);
		$i = 0;
		foreach ($iteam as $value) {
			if ($value != '.' && $value != '..') {
				$i++;
			}
		}
		return $i;
	}

	/**
	 * Возвращает сообщение об ошибки запроса
	 */
	protected function getAlertError() {
		$bootstrap = new bootstrap();
		$xlib = new xlib();
		return $xlib->margin([
			'top' => 15,
			'content' => $bootstrap->alert($bootstrap->ico('exclamation-sign') . "Ошибка в запросе пожалуйста обновите страницу или выйдите из браузера =(", 'danger')
		]);
	}

	protected function getProgressBar () {
		$bootstrap = new bootstrap();
		$xlib = new xlib();
		return $xlib->margin([
			'top' => 15,
			'content' => $bootstrap->progressbar(100, 'striped')
		]);
	}

	/**
	 * Возвращает postType отправка выполение Создание категорий
	 */
	protected function postType ($pathini) {
return '<?php
class type {
	
	/**
	 * Создать новую категорию
	 */
	function newtype ($name) {
		require_once "../../ini/ini.php";
		$ini = new ini("options");
        require_once "../../bootstrap/bootstrap.php";
		$bootstrap = new bootstrap();
		require_once "../../xlib/xlib.php";
		$xlib = new xlib();
		require_once "../../../../../options.php";
		$options = new options();
        $list = $ini->getSections();
        foreach ($list as $val) {
            if ($name == $val) {
				echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Не удается создать категорию потому что такая уже есть!", "danger")
				]);
				die();
            }
        }
        if (count($ini->getKeys($list[count($list) - 1])) < 1 && count($list) != 0) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Не удается создать категорию потому что в последней категорий ничего нету зачем создавать еще пустую категорию ?)!", "danger")
				]);
        } else {
			$ini->addSection($name);
			echo $xlib->margin([
					"top" => 15,
					"content" => $bootstrap->alert($bootstrap->ico("info-sign") . "Категория успешно создалась ;)", "success")
				]);
			$xlib->js("$(\'#refreshType\').click();");
			return true;
		}
	}

	/**
	 * Создание категорий
	 */
    function execute () {
        $name = trim($_POST["xmessage_typeName"]);
        require_once "../../bootstrap/bootstrap.php";
		$bootstrap = new bootstrap();
        require_once "../../xlib/xlib.php";
		$xlib = new xlib();
        $charlower = $xlib->islowupper($name);
        if ($charlower == true) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Большие буквы нельзя использовать!", "danger")
			]);
			die();
        }
		if($name == null) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Название категорий не должно быть пустое!", "danger")
			]);
			$xlib->js("$(\'#xmessage_typeName\').val(null);");
			die();
		}
		if(strlen($name) >= 16) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Символов в название не более чем 15", "danger")
			]);
			die();
		}
		$char = $xlib->getCharToArray();
		$number = $xlib->getNumberToArray();
		$badName = $xlib->isCharArray($char, $name);
		if ($badName == true) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "такой символ <b>[$badName]</b> нельзя использовать", "danger")
			]);
			die();
		}
		$badName = $xlib->isCharArray($number, $name);
		if ($badName == true) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "такую цифру <b>[$badName]</b> нельзя использовать", "danger")
			]);
			die();
		}
        return $this->newtype($name);
	}
}
require_once "../../xlib/xlib.php";
$xlib = new xlib();
require_once "../../ini/ini.php";
$ini = new ini(' . "'$pathini'" . ');
$event = new type();
$response = $event->execute();
if ($response == true) {
	$uuid = $xlib->uuidv4();
	$ini->set("settings", "type", $uuid);
	rename($_SERVER["SCRIPT_FILENAME"], $uuid);
}

';
	}

	/**
	 * Возвращает postTred отправка выполение Создание категорий
	 */
	protected function postTred () {
		//return ''
	}

    /**
     * Возвращаем все пространство в виде массива
	 * ------------------------------------------
	 * dot - Точка
	 * ------------------------------------------
	 * @return Array
     */
    public function getSpace ($dot) {
		$ini	=	new ini('options');
		return $ini->getKeys($dot);
    }

	/**
     * Возвращаем все точки в виде массива
	 * -----------------------------------
	 * @return Array
     */
    public function getDotToArray () {
		$ini	=	new ini('options');
		return $ini->getSections();
    }

	/**
	 * Возвращаем id в пространство
	 * ----------------------------
	 * space	-	Имя пространство
	 * dot		-	Имя точки
	 * ----------------------------
	 * @return array
	 */
	public function getIdToArray($space, $dot = false) {
		if ($dot) {
			$path = '.' . $this->getTheme() . "uri/о/$dot/$space";
			$dir = scandir($path);
			$out	=	[];
			array_shift($dir);
			array_shift($dir);
			foreach ($dir as $dr) {
				array_push($out, explode('.', $dr)[0]);
			}
			return $out;
		} else {
			foreach ($this->getDotToArray() as $dot) {
				$path = '.' . $this->getTheme() . "uri/о/$dot/$space";
				$dir = scandir($path);
				$out	=	[];
				array_shift($dir);
				array_shift($dir);
				foreach ($dir as $dr) {
					array_push($out, explode('.', $dr)[0]);
				}
			}
			return $out;
		}
	}

	/**
     * Возвращаем форму (Создание нить)
	 * -------------------------------
	 * @return string
     */
	public function getCreateThread() {
		$skinmanager	=	new skinmanager();
		$jquery			=	new jquery();
        $action	    	=	$this->getPathModules("xmessage/execute/newThread.php");
		$dot			=	$_REQUEST['dot'];
		//-->Название нити
		$title	=	$skinmanager->p([
							'content'	=>	$skinmanager->input([
								'name'			=>	'title',
								'placeholder'	=>	'Название нити:'
							])
						]);
		//-->Имя создателя
		$name	=	$skinmanager->p([
							'content' => $skinmanager->input([
								'value'			=>	$_COOKIE['__xmessage_name'],
								'name'			=>	'name',
								'placeholder'	=>	'Имя создателя (Неизвестный)'
							])
						]);
		//-->Точка выбранная
		$dot	=	$skinmanager->p([
							'content' => $skinmanager->input([
								'value'	=>	$dot,
								'name'	=>	'dot',
								'type'	=>	'hidden'
							])
						]);
		//-->Пространство
		$space	=	$skinmanager->p([
							'content' => $skinmanager->input([
								'value'	=>	$this->geturi(3),
								'type'	=>	'hidden',
								'name'	=>	'space'
							])
						]);
		//-->Описание пространство
		$text	=	$skinmanager->p([
							'content' => $skinmanager->textarea([
								'name'			=>	'text',
								'css'			=>	['width'		=>	'100%',
														'resize'	=>	'vertical'
													],
								'placeholder'	=>	'Сообщение...'
							])
						]);
		//-->Выполнить
		$submit	=	$skinmanager->input([
							'type'	=>	'submit',
							'value'	=>	'Выполнить'
						]);
		return	$skinmanager->form([
					'action'	=>	$action,
					'method'	=>	'post',
					'content'	=>	$title	.	$name	.	$dot	.	$space	.	$text	.	$submit
				]);
        /*
        $bootstrap = new bootstrap();
        $id = $xlib->uuidv4();
		$progress = $this->getProgressBar();
		$alert = $this->getAlertError();
		$execute = $this->generateSession($xlib->uuidv4());//Создание сессий против cURL ;)
        $tred = $xlib->getPathModules("xmessage/execute/tred.php");
        //Создать тред
        $js = "$('#$id').submit(function(){ $('#getTred').html('$progress');var arr=$(this).serializeArray();var y = youtube.getEmbedStr(arr[6]['value']);arr.push({name: 'youtube', value:y});arr.push({name: 'post_index', value:$('#post_index').val()});arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$tred', arr, function(data){ $('#getTred').html(data);youtube.Update();}).fail(function(){ $('#getTred').html('$alert');});return false;});";
        $jquery->addLoad($js);
        return $xlib->evnform([
                'id' => $id,
			    'content' =>
					$bootstrap->input("Как называется тред ?)", "title") .
					$bootstrap->input("Имя создателя (Неизвестный)", "name") .
					$bootstrap->combobox([
						'id' => 'selected',
						'option' => $this->getOptions()
					]) .
					$bootstrap->combobox([
						'id' => 'event',
						'option' => $bootstrap->opt('Создать тред') . $bootstrap->opt('Просмотр')
					]) . $xlib->inputhidden($_SESSION[$execute . '4'], 'token') . $xlib->inputhidden($execute, 'execute') .
					$bootstrap->textarea("Описание (текст) (используйте знак => \"Пробел\" чтобы добавить более одного файла)", "tredtext") .
					    $bootstrap->sep([
							'modal' => true,
							'content' => $xlib->padding([
							'top' => 15,
								'content' => $bootstrap->btn([
									'modal' => true,
									'type' => 'submit',
									'title' => 'Выполнить'
								])
						])
					]) .
					$xlib->div([
						'id' => 'getTred'
					])
	    ]);
	    */
    }

	/**
     * Возвращаем форму создание пространство
	 * --------------------------------------
	 * dot	-	Название точки
	 * --------------------------------------
	 * @return string
     */
	public function getCreateSpace ($dot = false) {
		$jquery			=	new jquery();
		$skinmanager	=	new skinmanager();
		$action			=	$this->getPathModules("xmessage/execute/newSpace.php");
		//$execute			=	$this->generateSession($xlib->uuidv4());
		$dots			= 	[];
		foreach ($this->getDotToArray() as $val) {
			$dots += [$val => []];
		}
		//-->Точка
		$dot	=	$skinmanager->p([
							'content' => $skinmanager->combobox([
								'name'	=>	'dot',
								'selected' => $dot,
								$dots
							])
				    	]);
		//-->Пространство
		$space	=	$skinmanager->p([
							'content' => $skinmanager->input([
								'name' 			=>	'space',
								'placeholder'	=>	'Название пространство:'
							])
						]);
		//-->Описание пространство
		$desc	=	$skinmanager->p([
							'content' => $skinmanager->input([
								'name'			=>	'description',
								'placeholder'	=>	'Описание пространство:'
							])
						]);
		//-->Выполнить
		$submit	=	$skinmanager->input([
							'type'	=>	'submit',
							'value'	=>	'Выполнить'
						]);
		return	$skinmanager->form([
					'action'	=>	$action,
					'method'	=>	'post',
					'content'	=>	$dot	.	$space	.	$desc	.	$submit
				]);
		/*
        $bootstrap = new bootstrap();
        $id = $xlib->uuidv4();
		$progress = $this->getProgressBar();
		$alert = $this->getAlertError();
		$execute = $this->generateSession($xlib->uuidv4());//Создание сессий против cURL ;)
        $tred = $xlib->getPathModules("xmessage/execute/doska.php");
        $js = "$('#$id').submit(function(){ $('#getDoska').html('$progress');var arr = $(this).serializeArray();$.post('$tred', $(this).serialize(), function(data){ $('#getDoska').html(data);}).fail(function(){ $('#getDoska').html('$alert');});return false;});";
        $jquery->addLoad($js);
        return $xlib->evnform([
		    'id' => $id,
		    'content' =>
				$bootstrap->combobox([
					'id' => 'type',
					'option' => $this->getOptionsType()
				]) .
			    $bootstrap->input("Название", "title") .
			    $bootstrap->input("Описание краткое", "description") .
			    $bootstrap->sep([
				    'modal' => true,
				    'content' => $xlib->padding([
					    'top' => 15,
					    'content' => $bootstrap->btn([
						    'modal' => true,
						    'type' => 'submit',
						    'title' => 'Выполнить'
					    ]) . $xlib->inputhidden($_SESSION[$execute . '4'], 'token') . $xlib->inputhidden($execute, 'execute')
				    ])
			    ]) .
		    $xlib->div([
			    'id' => 'getDoska'
		    ])
	    ]);
	    * */
    }
	/**
     * Возвращает меню создание точки в виде элемента
     * $skinmanager->input([
			'type' => 'hidden',
			'value' => $_SESSION[$execute . '4'],
			'name' => 'token'
		]) .
		$skinmanager->input([
			'type' => 'hidden',
			'value' => $execute,
			'name' => 'execute'
		])
     */
	public function getCreateDot() {
		$skinmanager	=	new skinmanager();
		$action			=	$this->getPathModules("xmessage/execute/newDot.php");
		//$execute		=	$this->generateSession($xlib->uuidv4());
		//-->Имя точки
		$dot	=	$skinmanager->p([
							'content'	=>	$skinmanager->input([
								'name'			=>	'dot',
								'placeholder'	=>	'Название точки:'
							])
						]);
		//-->Выполнить
		$submit	=	$skinmanager->input([
							'type'	=>	'submit',
							'value'	=>	'Выполнить'
						]);
		return	$skinmanager->form([
					'action'	=>	$action,
					'method'	=>	'post',
					'content'	=>	$dot	.	$submit
				]);
	}

	/**
	 * Создать уникальный пост файл с запросам
	 * $name - имя запроса
	 */
	protected function post ($name = 'type', $postText = null) {
		$xlib = new xlib();
		$idScript = $xlib->uuidv4();
		$ip = $_SERVER['REMOTE_ADDR'];
		$ini = new ini($xlib->getPathModules('xmessage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $ip));
		if ($ini->get('settings', 'type') != null) {
			unlink('.' . $xlib->getPathModules('xmessage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $ini->get('settings', $name) . '.php'));
		}
		$ini->set('settings', $name, $idScript);
		$type = $xlib->getPathModules('xmessage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . "$idScript.php");
		file_put_contents('.' . $type, $postText);
		chmod('.' . $type, 0777);
		return $type;
	}

	/**
	 * Создает уникальную ссесию для post запроса
	 * $name - имя сессий
	 */
	protected function generateSession ($name = 'token') {
		session_start();
		$length = 32;
		$_SESSION[$name . '4'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
		return $name;
	}

	/**
     * Возвращаем точки в виде элемента
	 * ---------------------------------
	 * @return string
     */
	public function getDot() {
		$skinmanager	=	new skinmanager();
		$jquery			=	new jquery();
		$ini			=	new ini('options');
		$idNew			=	$this->uuidv4();
		$idCollaps		=	$this->uuidv4();
		$Update			=	$this->uuidv4();
		$gospace		=	$this->getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'goSpace.php');
		$item			=	[];
		foreach ($this->getDotToArray() as $dot) {
			$newSpace		=	$this->uuidv4();
			$btnSpace		=	$skinmanager->input(['type' => 'hidden','name' => 'dot', 'form' => $newSpace, 'value' => $dot]) .
									$skinmanager->form([
														'action'	=>	$this->getPathModules("xmessage/form/space.php"),
														'id'		=>	$newSpace,
														'content' => $skinmanager->input([
																		'value' => '+',
																		'type'	=>	'submit'
														])
												   ]);
			$index++;
			$listspace	=	[];
			foreach ($this->getSpace($dot) as $space) {
				$listspace	+=	[$space	=>	[]];
			}
			$count		=	' ' . $skinmanager->badge(count($listspace));
			$listview	=	$this->uuidv4();
			$id			=	$skinmanager->modal([
									'title'		=>	"$dot$count",
									'css'		=>	['text-align' => 'right'],
									'content'	=>	$btnSpace . $skinmanager->p([
										'content'	=>	$skinmanager->listView([
										'form'		=>	$listview,
										'name'		=>	'space',
										'required'	=>	true,
										'css'		=>	['width' => '100%'],
										$listspace
									])
								]) . $skinmanager->input(['type' => 'hidden','name' => 'dot', 'form' => $listview, 'value' => $dot])
									. $skinmanager->form([
										'action'	=>	$gospace,
										'id'		=>	$listview,
										'content'	=>	$skinmanager->input([
														'value'	=>	'Выбрать пространство',
														'type'	=>	'submit'
													])
										])
							]);
			if (count($this->getDotToArray()) == $index) {
				$list	.=	$skinmanager->dropdown([
								$dot . $count => [
									'item'	=>	[
										'Перейти'	=> ['href' => "/о/$dot"],
										'Создать'	=> ['href' => $this->getPathModules("xmessage/form/space.php?dot=$dot")],
										$skinmanager->ico('eye-open') . ' ' . 'Посмотреть'	=>	['href'	=>	"#$id",	'modal'	=>	true]
									]
								]
							]);
			} else {
				$list	.=	$skinmanager->dropdown([
								$dot . $count => [
									'item'	=>	[
										'Перейти'	=> ['href' => "/о/$dot"],
										'Создать'	=> ['href' => $this->getPathModules("xmessage/form/space.php?dot=$dot")],
										$skinmanager->ico('eye-open') . ' ' . 'Посмотреть'	=>	['href'	=>	"#$id",	'modal'	=>	true]
									]
								]
							]);
			}
		}
	    return $skinmanager->panel([
					'title'		=>	"Точки " . $skinmanager->badge($ini->getCountSections()),
					'css'		=>	['text-align' => 'center'],
					'content'	=>	$list
				]);
		/*
		$progress = $this->getProgressBar();
		$alert = $this->getAlertError();
		$type = $xlib->getPathModules("xmessage/execute/type.php");
		$refresh = $xlib->getPathModules("xmessage/execute/refreshType.php");
		$execute = $this->generateSession($xlib->uuidv4());//Создание сессий против cURL ;)
		$timerHtml = $xlib->uuidv4();
		$timer = $xlib->uuidv4();
		//Новый тип
		$js1 = "$('#$idNew').submit(function(){ $('#getType').html('$progress');$.post('$type',$(this).serialize(),function(data){ $('#getType').html(data);$('#$Update').click();}).fail(function(){ $('#getType').html('$alert');});return false;});";
		$jquery->addLoad($js1);
		//Получение типов
		$js2 = "$('#$Update').click(function(){ $('#get').html('$progress');var arr=$(this).serializeArray();arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$refresh', arr, function(data){ $('#get').html(data);}).fail(function() { $('#get').html('$alert');});return false;});$('#$Update').click();";
		$jquery->addLoad($js2);
        $content = $bootstrap->border([
			'content' => $xlib->evnform([
				'id' => $idNew,
				'content' =>
					$bootstrap->input("Название", "xmessage_typeName") .
					$bootstrap->sep([
						'modal' => true,
						'content' => $xlib->padding([
							'top' => 15,
							'content' => $bootstrap->btn([
								'modal' => true,
								'type' => 'submit',
								'title' => $bootstrap->ico('ok')
							]) . $xlib->inputhidden($_SESSION[$execute . '4'], 'token') . $xlib->inputhidden($execute, 'execute')
						])
					]) .
				$xlib->div([
					'id' => 'getType'
				])
			])
	    ]);

		$xmessageSettingsType = $bootstrap->form([
			'title' => 'Настройки xmessageType v1.0',
			'id' => $xmessageSettingsType,
			'content' => "<input type='number' id='$timer' placeholder='10' value='10' required style='width:100%;'>"
		]);
		$refresh = $bootstrap->btn([
			'title' => $bootstrap->ico('refresh') . $xlib->div(['id' => $timerHtml]),
			'id' => $Update
		]);
		$xmessageopen = $bootstrap->btn([
			'id' => $xmessageSettingsType,
			'modal' => true,
			'title' => $bootstrap->ico('cog')
		]);
		//Таймер
		$timer = "timer.Update('xmessageUpdateType', '$timer', '$Update', '$timerHtml');";
		$jquery->addLoad($timer);
		return $bootstrap->border([
			'align' => 'right',
			'stretch' => true,
			'content' => $bootstrap->btn([
				'title' => $bootstrap->ico('option-horizontal'),
				'id' => $idCollaps,
				'collaps' => '-' . $refresh . '-' . $xmessageopen . $bootstrap->collaps($xlib->margin([
					'top' => 15,
					'content' => $content
				]), $idCollaps)
			])
		]);*/	
    }

	/**
	 * Возвращаем нить
	 * ----------------
	 * id		-	Адрес нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * ----------------
	 * @return string
	 */
	public function getThread ($id = false, $count = 0, $title = 'Сообщение') {
		require_once	$_SERVER['DOCUMENT_ROOT'] . $this->getPathModules('xmessage' . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'refresh.php');
		$refresh	=	new refresh();
		return $refresh->get($id, $count, $title);
	}

	/**
	 * Возвращаем все нити по названию пространство
	 * --------------------------------------------
	 * space	-	Адрес пространство
	 * dot		-	Точка
	 * count	-	Кол-во постов (5)
	 * title	-	Загаловок
	 * --------------------------------------------
	 * @return string
	 */
	public function getThreadToSpace ($space, $dot, $count = 5, $title = 'Сообщение') {
		$arr = [];
		$ids = $this->getIdToArray($space, $dot);
		foreach ($ids as $id) {
			array_push($arr,  $this->getThread($id, $count, $title));
		}
		return $arr;
	}

	/**
     * Возвращаем форму с отправки постов
	 * ----------------------------------
	 * id		-	Адрес нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * ----------------------------------
	 * @return string
     */
	public function multiForm ($id, $count = 0, $title = 'Сообщение') {
		if ($_POST['id']) {
			$id = $_POST['id'];
		} elseif (!$id) {
			$id = $this->geturi(4);
		}
		return $this->getSendBox($id)	.	$this->getThread($id, $count, $title);
	}

	/**
	 * Возвращаем (Изменение профиля)
	 * ---------------------------
	 */
	public function getPanelSettingsSb($form, $id) {
		$skinmanager	=	new skinmanager();
		$jquery			=	new jquery();
		$post			=	$this->getPathModules("xmessage/execute/saveSettings.php");
    	//-->Имя
   		if ($_REQUEST['name']) {
			$name	=	$_REQUEST['name'];
		} elseif ($_COOKIE['__xmessage_name']) {
        	$name	=	$_COOKIE['__xmessage_name'];
		} else {
			$name	=	'Нейзвестный';
        }
		//-->NumberPost
		if ($_REQUEST['number'] || $_SERVER['REQUEST_METHOD'] == 'POST') {
			$number	=	$_REQUEST['number'];
		} elseif ($_COOKIE['__xmessage_number']) {
			$number	=	$_COOKIE['__xmessage_number'];
		} else {
			$number	=	false;
        }
		//-->DatePost
		if ($_REQUEST['date'] || $_SERVER['REQUEST_METHOD'] == 'POST') {
			$date	=	$_REQUEST['date'];
		} elseif ($_COOKIE['__xmessage_date']) {
        	$date	=	$_COOKIE['__xmessage_date'];
		} else {
			$date	=	false;
		}
		//-->IdMessage
		if ($_REQUEST['idMessage'] || $_SERVER['REQUEST_METHOD'] == 'POST') {
			$idMessage	=	$_REQUEST['idMessage'];
		} elseif ($_COOKIE['__xmessage_IdMessage']) {
			$idMessage	=	$_COOKIE['__xmessage_IdMessage'];
		} else {
			$idMessage	=	false;
        }
		//-->redirect
		if ($_POST['redirect']) {
			$redirect	=	$_POST['redirect'];
		} else {
			$redirect	=	$_SERVER['REQUEST_URI'];
		}
		$redirect		=	$skinmanager->input([
									'name'	=>	'redirect',
									'type'	=>	'hidden',
									'value'	=>	$redirect
								]);
		//-->ид нити
		$uniqid			=	$skinmanager->input([
									'type'			=>	'hidden',
									'name'			=>	'id',
									'value'			=>	$id,
									'placeholder'	=>	"ид отправителя нити ($id)"
								]) . $skinmanager->input([
									'form'			=>	$form,
									'type'			=>	'hidden',
									'name'			=>	'id',
									'value'			=>	$id,
									'placeholder'	=>	"ид отправителя нити ($id)"
								]);
		//-->Имя создателя поста
		$name			=	'Имя или ник' . $skinmanager->p([
										'content'	=>	$skinmanager->input([
										'name'			=>	'name',
										'value'			=>	$name,
										'placeholder'	=>	'Имя создателя поста (Неизвестный)'
									]) . $skinmanager->input([
										'form'			=>	$form,
										'name'			=>	'name',
										'value'			=>	$name,
										'type'			=>	'hidden',
										'placeholder'	=>	'Имя создателя поста (Неизвестный)'
									])
								]);
		//-->Номер поста
		$NumberPost		=	$skinmanager->p([
									'content' => $skinmanager->input([
										'type'		=>	'checkbox',
										'name'		=>	'number',
										'value'		=>	'Получение номера поста',
										'checked'	=>	$number
									])
								]);
        //-->Дата отправки
		$date		=	$skinmanager->p([
								'content' => $skinmanager->input([
									'type'		=>	'checkbox',
									'name'		=>	'date',
									'value'		=>	'Дата отправки',
									'checked'	=>	$date
								])
							]);
		//-->Ид сообщение
		$idMessage	=	$skinmanager->p([
								'content' => $skinmanager->input([
									'type'		=>	'checkbox',
									'name'		=>	'idMessage',
									'value'		=>	'Ид сообщение',
									'checked'	=>	$idMessage
								])
							]);
		//-->Выполнить
		$submit		=	$skinmanager->btn([
								'type'	=>	'submit',
								'title'	=>	$skinmanager->ico("check") . 'Изменить'
							]);
    	$Panel		=	$skinmanager->modal([
								'title' 	=>	'Изменение профиля',
								'content'	=>	$skinmanager->form([
									'method'	=>	'post',
									'action'	=>	$post,
									'content'	=>  $redirect	.	$uniqid	.	$name . $NumberPost	.	$date	.	$idMessage	.	$submit
								])
							]);
		$change		=	$skinmanager->a([
								'title'	=>	$skinmanager->ico('cog') . 'Личного профиля',
								'href'	=>	"#$Panel",
								'modal' => $Panel
							]);
		$type		=	$skinmanager->modal([
								'title'		=>	'Настройки',
								'content'	=>	$skinmanager->p([
									'content'	=>	$change
								])
							]);
		if($skinmanager->getSkin() == 'bootstrap337'){
			$jquery->addLoad("
				$('#$Panel').on('show.bs.modal', function (e) {
					$('#$type').modal('hide');
				})");
		}
		return	$skinmanager->p([
					'content' => $skinmanager->a([
						'title'	=>	$skinmanager->ico('cog') . 'Настройки',
						'href'	=>	"#$type",
						'modal'	=>	$type
					])
				]);
    }

    /**
     * Возвращаем мульти-форму отправки постов
	 * ----------------------------------------
	 * id	-	ид отправки в нить
	 * @return string
     */
	public function getSendBox ($id) {
		$skinmanager	=	new skinmanager();
		$jquery			=	new jquery();
    	$form			=	$this->uuidv4();
		$post			=	$this->getPathModules("xmessage/execute/post.php");
		//-->redirect
		if ($_POST['redirect']) {
			$redirect	=	$_POST['redirect'];
		} else {
			$redirect	=	$_SERVER['REQUEST_URI'];
		}
		$redirect		=	$skinmanager->input([
									'name'	=>	'redirect',
									'type'	=>	'hidden',
									'value'	=>	$redirect
								]);
		//-->ид нити
		$uniqid			=	$skinmanager->p([
									'content'	=>	$skinmanager->input([
										'name'			=>	'id',
                            			'type'			=>	'hidden',
										'value'			=>	$id,
										'placeholder'	=>	"ид отправителя нити ($id)"
									])
								]);
		//-->Описание поста
		$desc			=	$skinmanager->p([
									'content'	=>	$skinmanager->textarea([
										'name'			=>	'text',
										'css'			=>	[
																'width'		=>	'100%',
																'resize'	=>	'vertical'
															],
										'placeholder'	=>	"Сообщение..."
									])
								]);
		//-->Выполнить (Отправить)
		$submit			=	$skinmanager->btn([
									'type'	=>	'submit',
									'title'	=>	'Отправить'
								]);
		//-->Модальная форма (#donate)
		$skinmanager->modal([
			'id'		=>	'syntax',
			'title'		=>	'Помощь ;)',
			'content'	=>	'Привет это страница помощь написание своей первой статьи :)<br>-------------------------------------------------------------------------------------------------------------<br>Об синтаксисе испольнозвание<br>-------------------------------------------------------------------------------------------------------------<br>/bЖирность/ - <b>Жирность</b><br>/sЗачеркнутый текст/ - <s>Зачеркнутый текст</s><br>/iНаклоненные буквы/ - <i>Наклоненные буквы</i><br>-------------------------------------------------------------------------------------------------------------<br>Ссылки использование их<br>-------------------------------------------------------------------------------------------------------------<br>Ютубище - https://youtu.be/mo6APOpfS3U -> Отоброжается как видео<br>Расширение картинок - .jpeg, .jpg, .png, .gif -> Отоброжается как картинки'
		]);
		//-->Открытие формы о Syntax (#syntax)
		$syntax = $skinmanager->a([
					'title'	=>	'Помощь',
					'href'	=>	'#syntax',
					'modal'	=>	'syntax'
				]);
		//-->Syntax (Возможности упрощенного)
		//$b				=	$skinmanager->btn(['type' => 'submit', 'title' => 'Жирный']);
		//$s				=	$skinmanager->btn(['type' => 'submit', 'title' => 'Зачеркнутый']);
		//$i				=	$skinmanager->btn(['type' => 'submit', 'title' => 'Курсив']);

    	$GLOBALS['__PANEL_BORDER_0'] = true;
		return	$skinmanager->panel([
        			'title'		=>	'Ответить на сообщение',
					'content'	=>	$this->getPanelSettingsSb($form, $id) . $syntax . $skinmanager->form([
                    	'id'		=>	$form,
						'method'	=>	'post',
						'action'	=>	$post,
						'content'	=>	$uniqid . $redirect	.	$name	.	$desc	.	$submit . $b . $s . $i
					])
				]);
		/*
		
        $bootstrap = new bootstrap();
		$progress = $this->getProgressBar();
		$alert = $this->getAlertError();
		$execute = $this->generateSession($xlib->uuidv4());//Создание сессий против cURL ;)
		$refresh = $xlib->getPathModules("xmessage/execute/refresh.php");
		$post = $xlib->getPathModules("xmessage/execute/post.php");
		$Update = $xlib->uuidv4();
		$timerHtml = $xlib->uuidv4();
		//Форма отправки
		$js = "$('#$Update').submit(function(event){event.preventDefault();var arr=$(this).serializeArray();$('#loadingcontent').html('$progress');arr.push({name: 'post_index', value:$('#post_index').val()});arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$refresh', arr, function(data){ $('#response').html(data);youtube.Update();$('#loadingcontent').empty();}).fail(function(){ $('#response').html('$alert');});});$('#$Update').submit();$('#post').submit(function(event){ event.preventDefault();$('#loadingcontent').html('$progress');var arr = $(this).serializeArray();var y = youtube.getEmbedStr(arr[2]['value']);arr.push({name: 'vs', value:y});arr.push({name: 'theme', value:getThemeBootstrap()});$('#response').empty();$.post('$post', arr, function(data){ $('#response').html(data);$('#loadingcontent').empty();youtube.Update();$(\"#posttext\").val(null);}).fail(function(){ $('#response').html('$alert');});});";
		$jquery->addLoad($js);
		//$xlib->js("$(document).ready(function(){ $('#$Update').submit(function(event){event.preventDefault();var arr=$(this).serializeArray();$('#loadingcontent').html('$progress');arr.push({name: 'post_index', value:$('#post_index').val()});arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$refresh', arr, function(data){ $('#response').html(data);youtube.Update();$('#loadingcontent').empty();}).fail(function(){ $('#response').html('$alert');});});$('#$Update').submit();$('#post').submit(function(event){ event.preventDefault();$('#loadingcontent').html('$progress');var arr = $(this).serializeArray();var y = youtube.getEmbedStr(arr[2]['value']);arr.push({name: 'vs', value:y});arr.push({name: 'theme', value:getThemeBootstrap()});$('#response').empty();$.post('$post', arr, function(data){ $('#response').html(data);$('#loadingcontent').empty();youtube.Update();$(\"#posttext\").val(null);}).fail(function(){ $('#response').html('$alert');});});});");
        $formsendpost = $xlib->padding([
        	'top' => 15,
        	'content' => $xlib->evnform([
        		'id' => 'post',
        		'content' => $bootstrap->border([
        			'align' => 'left',
        			'content' =>
		        		$bootstrap->input('ид отправителя треда', 'post_index', $xlib->geturi(3)) .
		        		$bootstrap->input("Имя создателя поста (Неизвестный)", 'name') .
		        		$bootstrap->textarea("Описание (текст) (используйте знак => \"Пробел\" чтобы добавить более одного файла)", 'posttext') .
		        		$bootstrap->btn([
		        			'title' => $bootstrap->ico('ok'),
		        			'type' => 'submit'
	        			]) . $xlib->inputhidden($_SESSION[$execute . '4'], 'token') . $xlib->inputhidden($execute, 'execute')
        		])
        	])
        ]);
		$timer = $xlib->uuidv4();
		/**
		 * форма настройки поста ;)
		 *
		$Settings = $bootstrap->form([
			'title' => 'Настройки xmessageSend v1.0',
			'id' => $Settings,
			'content' => "<input type='number' id='$timer' placeholder='10' value='10' required style='width:100%;'>"
		]);
		//Таймер
		$timer = "timer.Update('xmessageUpdatePost', '$timer', '$Update', '$timerHtml', 'submit');";
		$jquery->addLoad($timer);
     	return $bootstrap->border([
     		'align' => 'left',
     		'content' => $xlib->div(['id' => 'loadingcontent']) . $xlib->evnform([
				'id' => $Update,
				'content' =>
			 		$bootstrap->btn([
			 			'id' => 'postform',
				 		'title' => $bootstrap->ico('option-horizontal'),
						'collaps' => '-'
			 		]) .
					$bootstrap->btn([
						'type' => 'submit', 
				 		'title' => $bootstrap->ico('refresh') . $xlib->div(['id' => $timerHtml])
			 		]) . '-' . $bootstrap->btn([
			 			'id' => $Settings,
			 			'modal' => true,
				 		'title' => $bootstrap->ico('cog')
			 		])
				]) . $bootstrap->collaps($formsendpost, 'postform')
	     	]) . $xlib->div(['id' => 'response']);
	     	*/
	}
}
